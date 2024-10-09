<?php
// Database connection
include "conn.php";
// Initialize variables for success/error messages
$addTeacherSuccess = false;
$errorMessage = '';

// Add Teacher functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['teacher_first_name']) && isset($_POST['teacher_last_name'])) {
    $teacherFirstName = $_POST['teacher_first_name'];
    $teacherLastName = $_POST['teacher_last_name'];
    $teacherUsername = $_POST['teacher_username'];
    $teacherPassword = $_POST['teacher_password'];
    $school_year = $_POST['school_year'];

    // Check if username already exists
    $checkUsernameSql = "SELECT id FROM teachers WHERE username = '$teacherUsername'";
    $result = mysqli_query($conn, $checkUsernameSql);
    if (mysqli_num_rows($result) > 0) {
        $errorMessage = 'Error adding teacher: Username already exists.';
    } else {
        $addTeacherSql = "INSERT INTO teachers (first_name, last_name, username, password, position, school_year)
                          VALUES ('$teacherFirstName', '$teacherLastName', '$teacherUsername', '$teacherPassword', 'Teacher', '$school_year')";

        if (mysqli_query($conn, $addTeacherSql)) {
            $addTeacherSuccess = true;
        } else {
            $errorMessage = 'Error adding teacher: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add Teacher</h2>

    <?php if ($addTeacherSuccess): ?>
        <div class="alert alert-success">Teacher added successfully!</div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="teacher_first_name">First Name</label>
            <input type="text" class="form-control" id="teacher_first_name" name="teacher_first_name" required>
        </div>
        <div class="form-group">
            <label for="teacher_last_name">Last Name</label>
            <input type="text" class="form-control" id="teacher_last_name" name="teacher_last_name" required>
        </div>
        <div class="form-group">
            <label for="teacher_username">Username</label>
            <input type="text" class="form-control" id="teacher_username" name="teacher_username" required>
        </div>
        <div class="form-group">
            <label for="teacher_password">Password</label>
            <input type="password" class="form-control" id="teacher_password" name="teacher_password" required>
        </div>
        <div class="form-group">
            <label for="school_year">School Year</label>
            <select class="form-control" id="school_year" name="school_year" required>
                <?php
                $current_year = date('Y');
                $school_year_start = $current_year - 1; // Start year is last year
                for ($i = $school_year_start; $i <= $school_year_start + 2; $i++) {
                    $next_year = $i + 1;
                    echo "<option value='{$i}-{$next_year}'>{$i}-{$next_year}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Teacher</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
