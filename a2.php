<?php
include 'conn.php'; // Database connection

// Handle teacher creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement for inserting a teacher
    $stmt = $conn->prepare("INSERT INTO teachers (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='success-message'>Teacher added successfully!</div>";
    } else {
        echo "<div class='error-message'>Error adding teacher: " . $stmt->error . "</div>";
    }

    $stmt->close(); // Close the statement
}

// Handle teacher update (from modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_teacher'])) {
    $update_id = $_POST['update_id'];
    $update_first_name = $_POST['update_first_name'];
    $update_last_name = $_POST['update_last_name'];
    $update_username = $_POST['update_username'];

    $update_stmt = $conn->prepare("UPDATE teachers SET first_name = ?, last_name = ?, username = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $update_first_name, $update_last_name, $update_username, $update_id);

    if ($update_stmt->execute()) {
        echo "<div class='success-message'>Teacher updated successfully!</div>";
    } else {
        echo "<div class='error-message'>Error updating teacher: " . $update_stmt->error . "</div>";
    }

    $update_stmt->close();
}

// Handle teacher deletion
if (isset($_POST['delete_teacher'])) {
    $delete_id = $_POST['teacher_id'];
    $delete_stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        echo "<div class='success-message'>Teacher deleted successfully!</div>";
    } else {
        echo "<div class='error-message'>Error deleting teacher: " . $delete_stmt->error . "</div>";
    }

    $delete_stmt->close();
}


// Fetch teachers from the database
$result = $conn->query("SELECT * FROM teachers");
$teachers = $result->fetch_all(MYSQLI_ASSOC);
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">



<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
    }

    .modal-header, .btn, .table, .success-message, .error-message, .table th, .table td {
        font-family: 'Montserrat', sans-serif;
    }

    .modal-header {
            background-color: #1F5F1E;
            color: white;
    }

    .btn-primary {
        background-color: #1F5F1E;
        border: none;
        margin-bottom: 20px;
    }

    .btn-primary:hover {
        background-color: #145214;
    }

    .table {
        border-collapse: collapse;
        background-color: #f9f9f9;
    }

    .table th, .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #e2e2e2;
    }

    .thead-custom {
        background-color: #0C2D0B;
        color: white;
    }

    .table-container {
    max-height: 400px; 
    overflow-y: auto; 
    border: 1px solid #ddd; 
}

.table {
    width: 100%; 
    border-collapse: collapse; 
}

.table thead {
    position: sticky; 
    top: 0; 
    background-color: #0C2D0B; 
    z-index: 1; 
    color: white; 
}

.table th, .table td {
    padding: 15px; 
    text-align: left; 
    border-bottom: 1px solid #ddd; 
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
</style>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <h3 style="margin-top: 15px;">Manage Teachers</h3>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTeacherModal">
            Add Teacher
        </button>

        <div class="table-container">
            <table class="table table-hove r border">
                <thead class="thead-custom">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?php echo $teacher['id']; ?></td>
                        <td><?php echo $teacher['first_name']; ?></td>
                        <td><?php echo $teacher['last_name']; ?></td>
                        <td><?php echo $teacher['username']; ?></td>
                        <td>
                            <a href="#" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $teacher['id']; ?>" data-toggle="modal" data-target="#confirmDeleteModal">Delete</a>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $teacher['id']; ?>" data-first_name="<?php echo $teacher['first_name']; ?>" data-last_name="<?php echo $teacher['last_name']; ?>" data-username="<?php echo $teacher['username']; ?>" data-toggle="modal" data-target="#editTeacherModal">Edit</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add Teacher</h5>
                <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="teacher-form">
                    <input type="hidden" name="add_teacher" value="1">
                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="First Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" placeholder="Last Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" role="dialog" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
                <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="teacher-form">
                    <input type="hidden" name="update_teacher" value="1">
                    <input type="hidden" name="update_id" id="update_teacher_id">
                    <div class="form-group">
                        <input type="text" name="update_first_name" id="update_first_name" placeholder="First Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="update_last_name" id="update_last_name" placeholder="Last Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="update_username" id="update_username" placeholder="Username" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Update Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-danger btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this teacher?
            </div>
            <div class="modal-footer">
                <!-- Form for deletion -->
                <form id="deleteTeacherForm" method="POST">
                    <input type="hidden" name="delete_teacher" value="1">
                    <input type="hidden" id="delete_teacher_id" name="teacher_id">
                    <button type="submit" class="btn btn-danger">Yes, delete</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Script to populate the edit modal with current teacher data
    document.querySelectorAll('.edit-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('update_teacher_id').value = this.getAttribute('data-id');
            document.getElementById('update_first_name').value = this.getAttribute('data-first_name');
            document.getElementById('update_last_name').value = this.getAttribute('data-last_name');
            document.getElementById('update_username').value = this.getAttribute('data-username');
        });
    });

    // Script for delete confirmation
document.querySelectorAll('.delete-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var teacherId = this.getAttribute('data-id');
        document.getElementById('delete_teacher_id').value = teacherId;
    });
});

</script>
