<?php

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not a guard
    exit;
}

// SQL query to fetch violations data
$violations_query = "SELECT * FROM violation_list";
$violations_result = mysqli_query($conn, $violations_query);

if (!$violations_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission to add a new violation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_violation'])) {
        $violation_description = $_POST['violation_description'];

        $query = "INSERT INTO violation_list (violation_description) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $violation_description);

        try {
            $stmt->execute();
            echo "<div class='alert alert-success' role='alert'>Violation added successfully.</div>";
            echo '<script>
        window.location.href = "admin-nav-violations.php"; // Replace with the desired page
    </script>';
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                echo "<div class='alert alert-danger' role='alert'>Violation already exists.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Failed to add violation: " . $e->getMessage() . "</div>";
            }
        }

        $stmt->close();
    } elseif (isset($_POST['edit_violation'])) {
        $violation_id = $_POST['violation_id'];
        $violation_description = $_POST['violation_description'];

        $query = "UPDATE violation_list SET violation_description = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $violation_description, $violation_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Violation updated successfully.</div>";
            echo '<script>
        window.location.href = "admin-nav-violations.php"; // Replace with the desired page
    </script>';
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to update violation: " . $conn->error . "</div>";
        }

        $stmt->close();
    } elseif (isset($_POST['delete_violation'])) {
        $violation_id = $_POST['violation_id'];

        $query = "DELETE FROM violation_list WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $violation_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Violation deleted successfully.</div>";
            echo '<script>
        window.location.href = "admin-nav-violations.php"; // Replace with the desired page
    </script>';
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to delete violation: " . $conn->error . "</div>";
        }

        $stmt->close();
    }
}
unset($_SESSION['edit_success']);
unset($_SESSION['add_success']);
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

        .btn-custom:focus, .btn-custom:active {
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

        table tbody td {
    text-transform: capitalize;
}

.table th, .table td {
    padding: 15px; 
    text-align: left; 
    border-bottom: 1px solid #ddd; 
}

.table-hover tbody tr:hover {
        background-color: #e2e2e2;
    }

    .table {
        border-collapse: collapse;
        background-color: #f9f9f9;
    }

    .table thead {
    position: sticky; 
    top: 0; 
    background-color: #0C2D0B; 
    z-index: 1; 
    color: white; 
}

.table-container {
            max-height: 400px; 
            overflow-y: auto; 
            border: 1px solid #ddd; 
        }
    </style>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>Violation List</strong></h3>
                </div>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a violation...">
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#addViolationModal">
                        Add
                    </button>
                </div>
            </div>
           
        </div>
    
        <div class="table-container">
            <table class="table table-hover border">
                <thead class="thead-custom">
                    <tr>
                        <th style="width: 85%;">Violation</th>
                        <th class="text-center" style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="personnelTable">
                    <?php
                    // Fetch data for both table and modals
                    mysqli_data_seek($violations_result, 0); // Reset result pointer to fetch data again
                    while ($row = mysqli_fetch_assoc($violations_result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars(ucwords($row['violation_description'])); ?></td>
                            <td class="text-center">
                                <button class="btn btn btn-secondary" data-toggle="modal" data-target="#editViolationModal<?php echo $row['id']; ?>"> <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteViolationModal<?php echo $row['id']; ?>"> <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Violation Modal -->
<div class="modal fade" id="addViolationModal" tabindex="-1" role="dialog" aria-labelledby="addViolationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #0C2D0B;">
                <h5 class="modal-title" id="addViolationModalLabel">Add Violation</h5>
                <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="violation_description">Violation Description</label>
                        <input type="text" class="form-control" id="violation_description" name="violation_description" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_violation" style="width: 100%;">Add Violation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Violation Modals -->
<?php
// Reset result pointer again for modal generation
mysqli_data_seek($violations_result, 0);
while ($row = mysqli_fetch_assoc($violations_result)) : ?>
    <div class="modal fade" id="editViolationModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #0C2D0B;">
                    <h5 class="modal-title" id="editViolationModalLabel">Edit Violation</h5>
                    <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="violation_description">Violation</label>
                            <input type="text" class="form-control" id="violation_description" name="violation_description" value="<?php echo htmlspecialchars(ucwords($row['violation_description'])); ?>" required>
                            <input type="hidden" name="violation_id" value="<?php echo $row['id']; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="edit_violation" style="width: 100%;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<!-- Delete Violation Modals -->
<?php
// Reset result pointer once more for modal generation
mysqli_data_seek($violations_result, 0);
while ($row = mysqli_fetch_assoc($violations_result)) : ?>
    <div class="modal fade" id="deleteViolationModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteViolationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #0C2D0B;">
                    <h5 class="modal-title" id="deleteViolationModalLabel">Delete Violation</h5>
                    <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this violation?</p>
                        <input type="hidden" name="violation_id" value="<?php echo $row['id']; ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" name="delete_violation">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.querySelectorAll('#personnelTable tr');

            rows.forEach(function(row) {
                var name = row.cells[0].innerText.toLowerCase();
                var position = row.cells[1].innerText.toLowerCase();

                if (name.includes(input) || position.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
