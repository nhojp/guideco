<?php
// Include necessary files
include('conn.php');
include('head.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user data
    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql_update_user = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt_update_user = $conn->prepare($sql_update_user);
        if ($password) {
            $stmt_update_user->bind_param("sssi", $username, $email, $password, $user_id);
        } else {
            $stmt_update_user->bind_param("ssi", $username, $email, $user_id);
        }
        $stmt_update_user->execute();
        $stmt_update_user->close();
    }
    // Update student data
    if (isset($_POST['update_student'])) {
        $student_id = $_POST['student_id'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];

        $contact_number = $_POST['contact_number'];
        $religion = $_POST['religion'];
        $birthdate = $_POST['birthdate'];

        $sql_update_student = "UPDATE students SET first_name = ?, middle_name = ?, last_name = ?, age = ?, sex = ?, contact_number = ?, religion = ?, birthdate = ? WHERE id = ?";
        $stmt_update_student = $conn->prepare($sql_update_student);
        $stmt_update_student->bind_param("ssssissssi", $first_name, $middle_name, $last_name, $age, $sex, $contact_number, $religion, $birthdate, $student_id);
        $stmt_update_student->execute();
        $stmt_update_student->close();
    }

    // Update mother data
    if (isset($_POST['update_mother'])) {
        $parent_id = $_POST['parent_id'];
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $occupation = $_POST['occupation'];
        $address = $_POST['address'];

        $sql_update_mother = "UPDATE mothers SET name = ?, contact_number = ?, email = ?, occupation = ?, address = ? WHERE parent_id = ?";
        $stmt_update_mother = $conn->prepare($sql_update_mother);
        $stmt_update_mother->bind_param("sssssi", $name, $contact_number, $email, $occupation, $address, $parent_id);
        $stmt_update_mother->execute();
        $stmt_update_mother->close();
    }

    // Update father data
    if (isset($_POST['update_father'])) {
        $parent_id = $_POST['parent_id'];
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $occupation = $_POST['occupation'];
        $address = $_POST['address'];

        $sql_update_father = "UPDATE fathers SET name = ?, contact_number = ?, email = ?, occupation = ?, address = ? WHERE parent_id = ?";
        $stmt_update_father = $conn->prepare($sql_update_father);
        $stmt_update_father->bind_param("sssssi", $name, $contact_number, $email, $occupation, $address, $parent_id);
        $stmt_update_father->execute();
        $stmt_update_father->close();
    }
}


// Fetch the logged-in user's data from the users table
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

// Fetch all data from the students table who have the user_id
$sql_students = "SELECT id, first_name, middle_name, last_name, age, sex, contact_number, religion, birthdate, user_id FROM students WHERE user_id = ?";
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $user_id);
$stmt_students->execute();
$result_students = $stmt_students->get_result();
$students_data = $result_students->fetch_all(MYSQLI_ASSOC);

// Fetch all data from mothers and fathers table based on student_id
$students_ids = array_column($students_data, 'id');
$mothers_data = [];
$fathers_data = [];

if (!empty($students_ids)) {
    $placeholders = implode(',', array_fill(0, count($students_ids), '?'));

    // Fetch data from mothers table
    $sql_mothers = "SELECT parent_id, student_id, name, contact_number, email, occupation, address FROM mothers WHERE student_id IN ($placeholders)";
    $stmt_mothers = $conn->prepare($sql_mothers);
    $stmt_mothers->bind_param(str_repeat('i', count($students_ids)), ...$students_ids);
    $stmt_mothers->execute();
    $result_mothers = $stmt_mothers->get_result();
    $mothers_data = $result_mothers->fetch_all(MYSQLI_ASSOC);

    // Fetch data from fathers table
    $sql_fathers = "SELECT parent_id, student_id, name, contact_number, email, occupation, address FROM fathers WHERE student_id IN ($placeholders)";
    $stmt_fathers = $conn->prepare($sql_fathers);
    $stmt_fathers->bind_param(str_repeat('i', count($students_ids)), ...$students_ids);
    $stmt_fathers->execute();
    $result_fathers = $stmt_fathers->get_result();
    $fathers_data = $result_fathers->fetch_all(MYSQLI_ASSOC);
}

// Close statements and connection
$stmt_user->close();
$stmt_students->close();
$stmt_mothers->close();
$stmt_fathers->close();
$conn->close();
?>


<div class="container mt-4">
    <div class="border border-success rounded p-3 mb-4">
        <div class="text-center bg-white border-bottom border-success mb-3 pb-3">
            <strong>Account Settings</strong>
        </div>
        <form method="post">
            <input type="hidden" name="update_user" value="1">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="username" class="form-label mt-2"><strong>Username:</strong></label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                </div>

                <div class="col-md-3">
                    <label for="password" class="form-label mt-2"><strong>Password:</strong></label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text show-password" onclick="togglePassword()">
                                <i class="fa fa-eye" id="eye-icon"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label mt-2"><strong>Email:</strong></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <!-- Personal Information -->
    <div id="personal-information" class="border border-success rounded p-3 mb-4">
        <div class="text-center bg-white border-bottom border-success mb-3 pb-3">
            <strong>Personal Information</strong>
        </div>
        <?php foreach ($students_data as $student) : ?>
            <form method="post" class="mb-3">
                <input type="hidden" name="update_student" value="1">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">

                <div class="row">
                    <div class="col-md-4">
                        <label for="first_name_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>First Name:</strong></label>
                        <input type="text" id="first_name_<?php echo $student['id']; ?>" name="first_name" class="form-control" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="middle_name_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Middle Name:</strong></label>
                        <input type="text" id="middle_name_<?php echo $student['id']; ?>" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($student['middle_name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="last_name_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Last Name:</strong></label>
                        <input type="text" id="last_name_<?php echo $student['id']; ?>" name="last_name" class="form-control" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>
                </div>

                <div class="row mt-2 mb-3">
                    <div class="col-md-2">
                        <label for="birthdate_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Birthdate:</strong></label>
                        <input type="date" id="birthdate_<?php echo $student['id']; ?>" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($student['birthdate']); ?>" onchange="calculateAge('<?php echo $student['id']; ?>')" required>
                    </div>
                    <div class="col-md-1">
                        <label for="age_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Age:</strong></label>
                        <input type="text" id="age_<?php echo $student['id']; ?>" name="age" class="form-control" value="<?php echo htmlspecialchars($student['age']); ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="sex_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Sex:</strong></label>
                        <select id="sex_<?php echo $student['id']; ?>" name="sex" class="form-control" required>
                            <option value="Male" <?php echo ($student['sex'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($student['sex'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="religion_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Religion:</strong></label>
                        <input type="text" id="religion_<?php echo $student['id']; ?>" name="religion" class="form-control" value="<?php echo htmlspecialchars($student['religion']); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="contact_number_<?php echo $student['id']; ?>" class="form-label mt-2"><strong>Contact Number:</strong></label>
                        <input type="text" id="contact_number_<?php echo $student['id']; ?>" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($student['contact_number']); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

    <!-- Mother Information -->
    <div id="mother-info" class="border border-success rounded p-3 mb-4">
        <div class="text-center bg-white border-bottom border-success mb-3 pb-3">
            <strong>Mother Info</strong>
        </div>
        <?php foreach ($mothers_data as $mother) : ?>
            <form method="post">
                <input type="hidden" name="update_mother" value="1">
                <input type="hidden" name="parent_id" value="<?php echo htmlspecialchars($mother['parent_id']); ?>">
                <label for="name_<?php echo $mother['parent_id']; ?>" class="form-label mt-2"><strong>Name:</strong></label>
                <input type="text" id="name_<?php echo $mother['parent_id']; ?>" name="name" class="form-control" value="<?php echo htmlspecialchars($mother['name']); ?>" required>
                <br>
                <label for="contact_number_<?php echo $mother['parent_id']; ?>" class="form-label mt-2"><strong>Contact Number:</strong></label>
                <input type="text" id="contact_number_<?php echo $mother['parent_id']; ?>" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($mother['contact_number']); ?>" required>
                <br>
                <label for="email_<?php echo $mother['parent_id']; ?>" class="form-label mt-2"><strong>Email:</strong></label>
                <input type="email" id="email_<?php echo $mother['parent_id']; ?>" name="email" class="form-control" value="<?php echo htmlspecialchars($mother['email']); ?>" required>
                <br>
                <label for="occupation_<?php echo $mother['parent_id']; ?>" class="form-label mt-2"><strong>Occupation:</strong></label>
                <input type="text" id="occupation_<?php echo $mother['parent_id']; ?>" name="occupation" class="form-control" value="<?php echo htmlspecialchars($mother['occupation']); ?>" required>
                <br>
                <label for="address_<?php echo $mother['parent_id']; ?>" class="form-label mt-2"><strong>Address:</strong></label>
                <input type="text" id="address_<?php echo $mother['parent_id']; ?>" name="address" class="form-control" value="<?php echo htmlspecialchars($mother['address']); ?>" required>
                <br>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

    <!-- Father Information -->
    <div id="father-info" class="border border-success rounded p-3 mb-4">
        <div class="text-center bg-white border-bottom border-success mb-3 pb-3">
            <strong>Father Info</strong>
        </div>
        <?php foreach ($fathers_data as $father) : ?>
            <form method="post">
                <input type="hidden" name="update_father" value="1">
                <input type="hidden" name="parent_id" value="<?php echo htmlspecialchars($father['parent_id']); ?>">
                <label for="name_<?php echo $father['parent_id']; ?>" class="form-label mt-2"><strong>Name:</strong></label>
                <input type="text" id="name_<?php echo $father['parent_id']; ?>" name="name" class="form-control" value="<?php echo htmlspecialchars($father['name']); ?>" required>
                <br>
                <label for="contact_number_<?php echo $father['parent_id']; ?>" class="form-label mt-2"><strong>Contact Number:</strong></label>
                <input type="text" id="contact_number_<?php echo $father['parent_id']; ?>" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($father['contact_number']); ?>" required>
                <br>
                <label for="email_<?php echo $father['parent_id']; ?>" class="form-label mt-2"><strong>Email:</strong></label>
                <input type="email" id="email_<?php echo $father['parent_id']; ?>" name="email" class="form-control" value="<?php echo htmlspecialchars($father['email']); ?>" required>
                <br>
                <label for="occupation_<?php echo $father['parent_id']; ?>" class="form-label mt-2"><strong>Occupation:</strong></label>
                <input type="text" id="occupation_<?php echo $father['parent_id']; ?>" name="occupation" class="form-control" value="<?php echo htmlspecialchars($father['occupation']); ?>" required>
                <br>
                <label for="address_<?php echo $father['parent_id']; ?>" class="form-label mt-2"><strong>Address:</strong></label>
                <input type="text" id="address_<?php echo $father['parent_id']; ?>" name="address" class="form-control" value="<?php echo htmlspecialchars($father['address']); ?>" required>
                <br>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var toggleIcon = document.getElementById('toggleIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>