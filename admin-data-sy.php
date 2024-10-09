<?php

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not an admin
    exit;
}

// SQL query to fetch school years data
$school_year_query = "SELECT * FROM school_year";
$school_year_result = mysqli_query($conn, $school_year_query);

if (!$school_year_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission to add, edit or delete a school year
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_sy'])) {
        $school_year = $_POST['school_year'];

        $query = "INSERT INTO school_year (school_year) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $school_year);

        try {
            $stmt->execute();
            $success_message = "School year added successfully.";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                $error_message = "School year already exists.";
            } else {
                $error_message = "Failed to add school year: " . $e->getMessage();
            }
        }

        $stmt->close();
    } elseif (isset($_POST['edit_sy'])) {
        $sy_id = $_POST['sy_id'];
        $school_year = $_POST['school_year'];

        $query = "UPDATE school_year SET school_year = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $school_year, $sy_id);

        if ($stmt->execute()) {
            $success_message = "School year updated successfully.";
        } else {
            $error_message = "Failed to update school year: " . $conn->error;
        }

        $stmt->close();
    } elseif (isset($_POST['delete_sy'])) {
        $sy_id = $_POST['sy_id'];

        $query = "DELETE FROM school_year WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $sy_id);

        if ($stmt->execute()) {
            $success_message = "School year deleted successfully.";
        } else {
            $error_message = "Failed to delete school year: " . $conn->error;
        }

        $stmt->close();
    }
}
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
    </style>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-12">
                <!-- Display Success or Error Message -->
                <?php if (!empty($success_message)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php elseif (!empty($error_message)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>School Year List</strong></h3>
                </div>
            </div>
            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a school year...">
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#addSchoolYearModal">
                        Add
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <strong>Warning :</strong> Deleting a school year will remove all associated data, including students, teachers, and records.
                </div>
            </div>
        </div>

        <table class="table table-hover mt-2 border">
            <thead class="thead-custom">
                <tr>
                    <th style="width: 85%;">School Year</th>
                    <th class="text-center" style="width: 15%;">Actions</th>
                </tr>
            </thead>
            <tbody id="schoolYearTable">
                <?php
                // Fetch data for both table and modals
                mysqli_data_seek($school_year_result, 0); // Reset result pointer to fetch data again
                while ($row = mysqli_fetch_assoc($school_year_result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars(ucwords($row['year_start'] . " - " . $row['year_end'])); ?></td>
                        <td class="text-center">

                            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteSchoolYearModal<?php echo $row['id']; ?>"> <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add School Year Modal -->
<div class="modal fade" id="addSchoolYearModal" tabindex="-1" role="dialog" aria-labelledby="addSchoolYearModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #0C2D0B;">
                <h5 class="modal-title" id="addSchoolYearModalLabel">Add School Year</h5>
                <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="school_year">School Year</label>
                        <input type="text" class="form-control" id="school_year" name="school_year" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_sy" style="width: 100%;">Add School Year</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete School Year Modals -->
<?php
mysqli_data_seek($school_year_result, 0); // Reset result pointer once more for modal generation
while ($row = mysqli_fetch_assoc($school_year_result)) : ?>
    <div class="modal fade" id="deleteSchoolYearModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteSchoolYearModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #0C2D0B;">
                    <h5 class="modal-title" id="deleteSchoolYearModalLabel">Delete School Year</h5>
                    <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the school year "<strong><?php echo htmlspecialchars(ucwords($row['school_year'])); ?></strong>"?
                </div>
                <div class="modal-footer">
                    <form action="" method="POST">
                        <input type="hidden" name="sy_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger" name="delete_sy" style="width: 100%;">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

<script>
    // Filter school years based on search input
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        const rows = document.getElementById('schoolYearTable').getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const td = rows[i].getElementsByTagName('td')[0];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                rows[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
            }
        }
    });
</script>