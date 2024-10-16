<?php

include "conn.php";
include "head.php";

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit;
}

// Function to fetch all personnel data
function getAllPersonnelData($conn)
{
    $sql = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, 'Guard' AS position FROM guards";
    $result = mysqli_query($conn, $sql);
    $personnelData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $personnelData[] = $row;
    }
    return $personnelData;
}

$deleteSuccess = false; // Flag to indicate success
$addGuardSuccess = isset($_SESSION['add_guard_success']) ? $_SESSION['add_guard_success'] : false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_personnel'])) {
    $personnelId = intval($_POST['delete_personnel']);
    
    $deleteSql = "DELETE FROM guards WHERE id = ?";

    $stmt = mysqli_prepare($conn, $deleteSql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $personnelId);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            $deleteSuccess = true;
        } else {
            $errorMessage = 'Error deleting personnel record: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $errorMessage = 'Error preparing delete statement: ' . mysqli_error($conn);
    }
}

// Add Guard functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guard_first_name']) && isset($_POST['guard_last_name'])) {
    $guardFirstName = $_POST['guard_first_name'];
    $guardLastName = $_POST['guard_last_name'];
    $guardUsername = $_POST['guard_username'];
    $guardPassword = $_POST['guard_password'];

    // Check if username already exists
    $checkUsernameSql = "SELECT id FROM guards WHERE username = '$guardUsername'";
    $result = mysqli_query($conn, $checkUsernameSql);
    if (mysqli_num_rows($result) > 0) {
        $errorMessage = 'Error adding guard: Username already exists.';
    } else {
        $addGuardSql = "INSERT INTO guards (first_name, last_name, username, position, password)
                        VALUES ('$guardFirstName', '$guardLastName', '$guardUsername', 'Guard', '$guardPassword')";

        if (mysqli_query($conn, $addGuardSql)) {
            $_SESSION['add_guard_success'] = true;
            $addGuardSuccess = true;
        } else {
            $errorMessage = 'Error adding guard: ' . mysqli_error($conn);
        }
    }
}

// Clear session variables after displaying messages
unset($_SESSION['add_guard_success']);

// Fetching data
$personnelData = getAllPersonnelData($conn);
?>

<style>
    .btn-custom {
        background-color: #1F5F1E;
        color: white;
        border: none;
    }

    .btn-custom:hover {
        background-color: #389434;
        color: white;
    }

    .btn-custom:focus,
    .btn-custom:active {
        box-shadow: none;
        outline: none;
    }

    .thead-custom {
        background-color: #0C2D0B;
        color: white;
    }

    .btn-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
    }
</style>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>School Guards</strong></h3>
                </div>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" data-bs-toggle="dropdown" aria-expanded="false">
                        Add
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-toggle="modal" data-target="#addGuardModal">Add a Security Guard</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?php if ($deleteSuccess || $addGuardSuccess || !empty($errorMessage)) : ?>
            <div class="alert <?php echo $deleteSuccess || $addGuardSuccess ? 'alert-success' : 'alert-danger'; ?> mt-4" role="alert">
                <?php
                if ($deleteSuccess) {
                    echo 'Record deleted successfully';
                } elseif ($addGuardSuccess) {
                    echo 'Guard added successfully';
                } elseif (!empty($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </div>
        <?php endif; ?>

        <table class="table table-hover mt-4 border">
            <thead class="thead-custom">
                <tr>
                    <th style="width:35%;">Full Name</th>
                    <th style="width:10%;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="personnelTable">
                <?php foreach ($personnelData as $person) : ?>
                    <tr>
                        <td><?php echo ucwords(strtolower($person['full_name'])); ?></td>

                        <td class="text-center">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $person['id']; ?>" data-id="<?php echo $person['id']; ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $person['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $person['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-custom" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-guideco text-white">
                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $person['id']; ?>">Delete Record</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this record?
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="">
                                                <input type="hidden" name="delete_personnel" value="<?php echo $person['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Guard Modal -->
        <div class="modal fade" id="addGuardModal" tabindex="-1" role="dialog" aria-labelledby="addGuardModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-guideco text-white">
                        <h5 class="modal-title" id="addGuardModalLabel">Add Security Guard</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="guardFirstName">First Name:</label>
                                <input type="text" id="guardFirstName" name="guard_first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="guardLastName">Last Name:</label>
                                <input type="text" id="guardLastName" name="guard_last_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="guardUsername">Username:</label>
                                <input type="text" id="guardUsername" name="guard_username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="guardPassword">Password:</label>
                                <input type="password" id="guardPassword" name="guard_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Guard</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
