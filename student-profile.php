<?php
session_start();

// Include necessary files
include('conn.php');
include('head.php');
include 'student-nav.php';


// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

function calculateAge($birthdate)
{
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_student']) && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];
        $sex = $_POST['sex'];
        $religion = $_POST['religion'];
        $contact_number = $_POST['contact_number'];

        $sql_update_student = "UPDATE students SET first_name = ?, middle_name = ?, last_name = ?, birthdate = ?, age = ?, sex = ?, religion = ?, contact_number = ? WHERE id = ?";
        $stmt_update_student = $conn->prepare($sql_update_student);
        $stmt_update_student->bind_param("ssssisssi", $first_name, $middle_name, $last_name, $birthdate, $age, $sex, $religion, $contact_number, $student_id);
        if (!$stmt_update_student->execute()) {
            die('Error updating student: ' . $stmt_update_student->error);
        }
        $stmt_update_student->close();
    }

    if (isset($_POST['update_mother']) && isset($_POST['mother_id'])) {
        $mother_id = $_POST['mother_id'];
        $name = isset($_POST['name']) ? $_POST['name'] : 'N/A';
        $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : 'N/A';
        $email = isset($_POST['email']) ? $_POST['email'] : 'N/A';
        $occupation = isset($_POST['occupation']) ? $_POST['occupation'] : 'N/A';
        $address = isset($_POST['address']) ? $_POST['address'] : 'N/A';

        $sql_update_mother = "UPDATE mothers SET name = ?, contact_number = ?, email = ?, occupation = ?, address = ? WHERE parent_id = ?";
        $stmt_update_mother = $conn->prepare($sql_update_mother);
        $stmt_update_mother->bind_param("sssssi", $name, $contact_number, $email, $occupation, $address, $mother_id);
        if (!$stmt_update_mother->execute()) {
            die('Error updating mother: ' . $stmt_update_mother->error);
        }
        $stmt_update_mother->close();
    }

    if (isset($_POST['update_father']) && isset($_POST['father_id'])) {
        $father_id = $_POST['father_id'];
        $name = $_POST['name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $occupation = $_POST['occupation'];
        $address = $_POST['address'];

        $sql_update_father = "UPDATE fathers SET name = ?, contact_number = ?, email = ?, occupation = ?, address = ? WHERE parent_id = ?";
        $stmt_update_father = $conn->prepare($sql_update_father);
        $stmt_update_father->bind_param("sssssi", $name, $contact_number, $email, $occupation, $address, $father_id);
        if (!$stmt_update_father->execute()) {
            die('Error updating father: ' . $stmt_update_father->error);
        }
        $stmt_update_father->close();
    }

    if (isset($_POST['update_account'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql_update_account = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt_update_account = $conn->prepare($sql_update_account);
        $stmt_update_account->bind_param("sssi", $username, $email, $password, $user_id);
        if (!$stmt_update_account->execute()) {
            die('Error updating account: ' . $stmt_update_account->error);
        }

        $stmt_update_account->close();
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
$sql_students = "SELECT id, first_name, middle_name, last_name, age, sex, contact_number, religion, birthdate FROM students WHERE user_id = ?";
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

?>

<style>
    .card {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        overflow: hidden;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .card-body {
        padding: 20px;
    }


    .mb-3,
    .mb-4 {
        margin-bottom: 1.75rem !important;
    }


    strong {
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
    }

    h6 {
        font-family: 'Montserrat', sans-serif;
        color: #0C2D0B;
    }

    .btn-success {
        background-color: #0C2D0B;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-family: 'Montserrat', sans-serif;
    }


    .fa-edit {
        color: #6c757d;
        font-size: 1rem;
    }

    .bg-green {
        background-color: #0C2D0B;
        color: white;
    }

    .card-body .row {
        display: flex;
        align-items: center;
    }

    .card-body .form-control {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        padding: 0.375rem 0.75rem;
    }

    .card-body .form-control[readonly] {
        color: #555;
    }
</style>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="row">
            <!-- Personal Information -->
            <div class="col-md-12">
                <div class="card border-secondary mb-3">
                    <div class="card-header bg-green border-bottom border-secondary d-flex justify-content-between align-items-center">
                        <span><strong>Personal Information</strong></span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                            <i class="fa-solid fa-edit text-white"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- PHP to dynamically show student's personal information -->
                        <?php if ($students_data) : ?>
                            <?php foreach ($students_data as $student) : ?>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>First Name:</strong>
                                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['first_name'])) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Middle Name:</strong>
                                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['middle_name'])) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Last Name:</strong>
                                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['last_name'])) ?: 'N/A'; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <strong>Birthday:</strong>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['birthdate']) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Age:</strong>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars(calculateAge($student['birthdate'])); ?>" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Sex:</strong>
                                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['sex'])) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Contact Number:</strong>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['contact_number']) ?: 'N/A'; ?>" readonly>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No personal information available.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- Parents Information -->
            <div class="col-md-12">
                <div class="card border-secondary mb-3">
                    <div class="card-header bg-green  d-flex justify-content-between align-items-center">
                        <span><strong>Parents Information</strong></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card m-2">
                                <div class="card-header bg-white border-bottom border-success text-center">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editMotherInfoModal" class="d-inline-flex align-items-center text-dark text-decoration-none">
                                        <strong>Mother's Information</strong>
                                        <i class="fa-solid fa-edit ml-2"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <?php if ($mothers_data && count($mothers_data) > 0) : ?>
                                        <?php $mother = $mothers_data[0]; // Assuming only one mother record 
                                        ?>
                                        <div class="mb-3">
                                            <strong>Name:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($mother['name'])) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Contact Number:</strong><br>
                                            <?php echo htmlspecialchars($mother['contact_number']) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Email:</strong><br>
                                            <?php echo htmlspecialchars($mother['email']) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Occupation:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($mother['occupation'])) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Address:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($mother['address'])) ?: 'N/A'; ?>
                                        </div>
                                    <?php else : ?>
                                        <p>No mother's information available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card m-2">
                                <div class="card-header bg-white border-bottom border-success text-center">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editFatherInfoModal" class="d-inline-flex align-items-center text-dark text-decoration-none">
                                        <strong>Father's Information</strong>
                                        <i class="fa-solid fa-edit ml-2"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <?php if ($fathers_data && count($fathers_data) > 0) : ?>
                                        <?php $father = $fathers_data[0]; // Assuming only one father record 
                                        ?>
                                        <div class="mb-3">
                                            <strong>Name:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($father['name'])) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Contact Number:</strong><br>
                                            <?php echo htmlspecialchars($father['contact_number']) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Email:</strong><br>
                                            <?php echo htmlspecialchars($father['email']) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Occupation:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($father['occupation'])) ?: 'N/A'; ?>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Address:</strong><br>
                                            <?php echo ucwords(htmlspecialchars($father['address'])) ?: 'N/A'; ?>
                                        </div>
                                    <?php else : ?>
                                        <p>No father's information available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <!-- Account Settings -->
                    <div class="col-md-12">
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-green border-bottom border-secondary d-flex justify-content-between align-items-center">
                                <span><strong>Account Settings</strong></span>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editAccountInfoModal">
                                    <i class="fa-solid fa-edit text-white"></i>
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <strong>Username:</strong>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_data['username']) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Email:</strong>
                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']) ?: 'N/A'; ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Password:</strong>
                                        <input type="password" class="form-control" value="<?php echo htmlspecialchars($user_data['password']) ?: 'N/A'; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modals for editing information -->
    <!-- Modal for editing personal information -->
    <div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editPersonalInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-white">
                    <h5 class="modal-title" id="editPersonalInfoModalLabel">Edit Personal Information</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <?php foreach ($students_data as $student) : ?>
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['id']); ?>">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($student['middle_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($student['birthdate']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="sex">Sex</label>
                                <select class="form-control" id="sex" name="sex">
                                    <option value="Male" <?php echo ($student['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($student['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?php echo htmlspecialchars($student['religion']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($student['contact_number']); ?>">
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" name="update_student" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing mother's information -->
    <div class="modal fade" id="editMotherInfoModal" tabindex="-1" aria-labelledby="editMotherInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-white">
                    <h5 class="modal-title" id="editMotherInfoModalLabel">Edit Mother's Information</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <?php if ($mothers_data && count($mothers_data) > 0) : ?>
                            <?php $mother = $mothers_data[0]; // Assuming only one mother record 
                            ?>
                            <input type="hidden" name="mother_id" value="<?php echo htmlspecialchars($mother['parent_id']); ?>">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($mother['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($mother['contact_number']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($mother['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo htmlspecialchars($mother['occupation']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($mother['address']); ?>">
                            </div>
                        <?php endif; ?>
                        <button type="submit" name="update_mother" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing father's information -->
    <div class="modal fade" id="editFatherInfoModal" tabindex="-1" aria-labelledby="editFatherInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-center">
                    <h5 class="modal-title" id="editFatherInfoModalLabel">Edit Father's Information</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <?php if ($fathers_data && count($fathers_data) > 0) : ?>
                            <?php $father = $fathers_data[0]; // Assuming only one father record 
                            ?>
                            <input type="hidden" name="father_id" value="<?php echo htmlspecialchars($father['parent_id']); ?>">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($father['name']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($father['contact_number']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($father['email']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?php echo htmlspecialchars($father['occupation']); ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($father['address']); ?>">
                            </div>
                        <?php endif; ?>
                        <button type="submit" name="update_father" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing account settings -->
    <div class="modal fade" id="editAccountInfoModal" tabindex="-1" aria-labelledby="editAccountInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-center">
                    <h5 class="modal-title" id="editAccountInfoModalLabel">Edit Account Settings</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="form-text text-muted">Leave blank to keep current password.</small>
                        </div>
                        <button type="submit" name="update_account" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>

<?php include 'footer.php' ?>