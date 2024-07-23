<?php

function calculateAge($birthdate)
{
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob)->y;
    return $age;
}

// Include the database connection
include "conn.php";

// Include the header
include "head.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

// Ensure required data is provided
if (!isset($_GET['student_id']) || !isset($_GET['person_id']) || !isset($_GET['person_type'])) {
    // Redirect if data is missing
    header('Location: admin-p-2.php');
    exit;
}

// Fetch posted data
$student_id = $_GET['student_id'];
$person_id = $_GET['person_id'];
$person_type = $_GET['person_type'];

// Fetch student, teacher/guard details from database
$sql_student_teacher = "SELECT s.first_name AS student_first_name, 
                            s.middle_name, 
                            s.last_name AS student_last_name, 
                              s.birthdate, s.sex,
                              sec.section_name, 
                              g.grade_name, 
                              t.first_name AS teacher_first_name, 
                              t.last_name AS teacher_last_name,
                              m.name as mother_name, 
                              m.contact_number as mother_contact, 
                              m.occupation AS mother_occupation, 
                              m.address AS mother_address,
                              f.name as father_name, 
                              f.contact_number as father_contact, 
                              f.occupation AS father_occupation, 
                              f.address AS father_address
                       FROM students s
                       INNER JOIN sections sec ON s.section_id = sec.id
                       INNER JOIN grades g ON sec.grade_id = g.id
                       INNER JOIN teachers t ON sec.teacher_id = t.id
                       left JOIN mothers m ON s.id = m.student_id
                       left JOIN fathers f ON s.id = f.student_id
                       WHERE s.id = $student_id";

$result_student_teacher = $conn->query($sql_student_teacher);

// Check if the query was successful and fetched data
if ($result_student_teacher === false) {
    echo "Error: " . $conn->error;
} elseif ($result_student_teacher->num_rows > 0) {
    $row = $result_student_teacher->fetch_assoc();

    // Initialize student details
    $student_first_name = $row['student_first_name'] ?? '';
    $middle_name = $row['middle_name'] ?? '';
    $student_last_name = $row['student_last_name'] ?? '';
    $birthdate = $row['birthdate'] ?? '';
    $sex = $row['sex'] ?? '';
    $section_name = $row['section_name'] ?? '';
    $grade_name = $row['grade_name'] ?? '';
    $teacher_name = ($row['teacher_first_name'] ?? '') . " " . ($row['teacher_last_name'] ?? '');
    $mother_name = $row['mother_name'] ?? '';
    $mother_occupation = $row['mother_occupation'] ?? '';
    $mother_address = $row['mother_address'] ?? '';
    $mother_contact = $row['mother_contact'] ?? '';
    $father_name = $row['father_name'] ?? '';
    $father_occupation = $row['father_occupation'] ?? '';
    $father_address = $row['father_address'] ?? '';
    $father_contact = $row['father_contact'] ?? '';
} else {
    // Set default values if no student data is found
    $student_first_name = '';
    $middle_name = '';
    $student_last_name = '';
    $birthdate = '';
    $sex = '';
    $section_name = '';
    $grade_name = '';
    $teacher_name = '';
    $mother_name = '';
    $mother_occupation = '';
    $mother_address = '';
    $mother_contact = '';
    $father_name = '';
    $father_occupation = '';
    $father_address = '';
    $father_contact = '';
    echo "No student data found for student_id: $student_id";
}

// Fetch details of the selected personnel (teacher or guard)
$person_name = "Unknown";
if ($person_type === 'teacher') {
    $sql_person = "SELECT * FROM teachers WHERE id = $person_id";
} elseif ($person_type === 'guard') {
    $sql_person = "SELECT * FROM guards WHERE id = $person_id";
}

$result_person = $conn->query($sql_person);
if ($result_person->num_rows > 0) {
    $person = $result_person->fetch_assoc();
}

// Prepare and execute SQL INSERT Query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data for student and personnel

    // Victim Details
    $student_first_name = $row['student_first_name'] ?? '';
    $middle_name = $row['middle_name'] ?? '';
    $student_last_name = $row['student_last_name'] ?? '';
    $birthdate = $row['birthdate'] ?? '';
    $sex = $row['sex'] ?? '';
    $section_name = $row['section_name'] ?? '';
    $grade_name = $row['grade_name'] ?? '';
    $teacher_name = ($row['teacher_first_name'] ?? '') . " " . ($row['teacher_last_name'] ?? '');
    $mother_name = $row['mother_name'] ?? '';
    $mother_occupation = $row['mother_occupation'] ?? '';
    $mother_address = $row['mother_address'] ?? '';
    $mother_contact = $row['mother_contact'] ?? '';
    $father_name = $row['father_name'] ?? '';
    $father_occupation = $row['father_occupation'] ?? '';
    $father_address = $row['father_address'] ?? '';
    $father_contact = $row['father_contact'] ?? '';

    // Offender/Complained Details
    $offender_first_name = $person['first_name'] ?? '';
    $offender_middle_name = $person['middle_name'] ?? '';
    $offender_last_name = $person['last_name'] ?? '';
    $offender_designation = ucfirst($person_type); // 'Teacher' or 'Guard'
    $offender_birthdate = $person['birthdate'] ?? '';
    $offender_sex = $person['sex'] ?? '';
    $offender_contact = $person['contact_number'] ?? '';
    $offender_address = $person['address'] ?? '';

    // Complainant Details (You need to fetch these from your form POST data)
    $complainantFirstName = $_POST['complainantFirstName'] ?? '';
    $complainantMiddleName = $_POST['complainantMiddleName'] ?? '';
    $complainantLastName = $_POST['complainantLastName'] ?? '';
    $relationshipToVictim = $_POST['relationshipToVictim'] ?? '';
    $complainantContact = $_POST['complainantContact'] ?? '';
    $complainantAddress = $_POST['complainantAddress'] ?? '';

    // Case Details, Action Taken, Recommendations (You need to fetch these from your form POST data)
    $caseDetails = $_POST['caseDetails'] ?? '';
    $actionTaken = $_POST['actionTaken'] ?? '';
    $recommendations = $_POST['recommendations'] ?? '';

    // Calculate age of victim and offender based on birthdates
    $victimAge = calculateAge($birthdate);
    $offenderAge = calculateAge($offender_birthdate);

    // Escape variables for security
    $student_first_name = mysqli_real_escape_string($conn, $student_first_name);
    $middle_name = mysqli_real_escape_string($conn, $middle_name);
    $student_last_name = mysqli_real_escape_string($conn, $student_last_name);
    $birthdate = mysqli_real_escape_string($conn, $birthdate);
    $victimAge = mysqli_real_escape_string($conn, $victimAge);
    $sex = mysqli_real_escape_string($conn, $sex);
    $section_name = mysqli_real_escape_string($conn, $section_name);
    $grade_name = mysqli_real_escape_string($conn, $grade_name);
    $teacher_name = mysqli_real_escape_string($conn, $teacher_name);
    $mother_name = mysqli_real_escape_string($conn, $mother_name);
    $mother_occupation = mysqli_real_escape_string($conn, $mother_occupation);
    $mother_address = mysqli_real_escape_string($conn, $mother_address);
    $mother_contact = mysqli_real_escape_string($conn, $mother_contact);
    $father_name = mysqli_real_escape_string($conn, $father_name);
    $father_occupation = mysqli_real_escape_string($conn, $father_occupation);
    $father_address = mysqli_real_escape_string($conn, $father_address);
    $father_contact = mysqli_real_escape_string($conn, $father_contact);

    $offender_first_name = mysqli_real_escape_string($conn, $offender_first_name);
    $offender_middle_name = mysqli_real_escape_string($conn, $offender_middle_name);
    $offender_last_name = mysqli_real_escape_string($conn, $offender_last_name);
    $offender_designation = mysqli_real_escape_string($conn, $offender_designation);
    $offender_birthdate = mysqli_real_escape_string($conn, $offender_birthdate);
    $offenderAge = mysqli_real_escape_string($conn, $offenderAge);
    $offender_sex = mysqli_real_escape_string($conn, $offender_sex);
    $offender_contact = mysqli_real_escape_string($conn, $offender_contact);
    $offender_address = mysqli_real_escape_string($conn, $offender_address);

    $complainantFirstName = mysqli_real_escape_string($conn, $complainantFirstName);
    $complainantMiddleName = mysqli_real_escape_string($conn, $complainantMiddleName);
    $complainantLastName = mysqli_real_escape_string($conn, $complainantLastName);
    $relationshipToVictim = mysqli_real_escape_string($conn, $relationshipToVictim);
    $complainantContact = mysqli_real_escape_string($conn, $complainantContact);
    $complainantAddress = mysqli_real_escape_string($conn, $complainantAddress);

    $caseDetails = mysqli_real_escape_string($conn, $caseDetails);
    $actionTaken = mysqli_real_escape_string($conn, $actionTaken);
    $recommendations = mysqli_real_escape_string($conn, $recommendations);

    // SQL INSERT Query
    $sql = "INSERT INTO `complaints`(`victimFirstName`, 
    `victimMiddleName`, 
    `victimLastName`, 
    `victimDOB`, 
    `victimAge`, 
    `victimSex`, 
    `victimGrade`, 
    `victimSection`, 
    `victimAdviser`, 
    `motherName`, 
    `motherOccupation`,
     `motherAddress`,
      `motherContact`, 
      `fatherName`,
       `fatherOccupation`, 
       `fatherAddress`, 
       `fatherContact`, 
       `complainantFirstName`, 
       `complainantMiddleName`,
        `complainantLastName`, 
        `relationshipToVictim`, 
        `complainantContact`, 
        `complainantAddress`, 
        `caseDetails`, 
        `actionTaken`,
         `recommendations`,
          `complainedFirstName`,
        `complainedMiddleName`, 
        `complainedLastName`, 
        `complainedDOB`, 
        `complainedAge`, 
        `complainedSex`, 
        `complainedDesignation`,
        `complainedContact`, 
        `complainedAddress`,
        `teacher_id`)
     VALUES ('$student_first_name', 
     '$middle_name', 
     '$student_last_name',
     '$birthdate',
     '$victimAge',
     '$sex',
     '$grade_name',
     '$section_name',
     '$teacher_name',
     '$mother_name',
     '$mother_occupation',
     '$mother_address',
     '$mother_contact',
     '$father_name',
     '$father_occupation',
     '$father_address',
     '$father_contact',
     '$complainantFirstName',
     '$complainantMiddleName',
     '$complainantLastName',
     '$relationshipToVictim',
     '$complainantContact',
     '$complainantAddress',
     '$caseDetails',
     '$actionTaken',
     '$recommendations',
     '$offender_first_name',
     '$offender_middle_name',
     '$offender_last_name',
     '$offender_designation',
     '$offender_birthdate',
     '$offenderAge',
     '$offender_sex',
     '$offender_contact',
     '$offender_address',
     '$person_id')";

    if ($conn->query($sql) === TRUE) {
        // Successfully inserted, redirect
        header('Location: admin-personnel-complain-list.php');
        exit;
    } else {
        // Error occurred
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


include "admin-header.php";
?>

<div class="container-fluid mt-2 mb-5">
    <div class="container-fluid bg-white rounded-lg">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid">
                    <h3 class="p-2"><strong>
                            COMPLAINT DETAILS</strong>
                    </h3>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid bg-white pt-4 mt-2 rounded-lg">
        <form action="" method="post">
            <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Victim Details</b></h5>
            <?php if ($result_student_teacher->num_rows > 0) : ?>
                <?php $row = $result_student_teacher->fetch_assoc(); ?>
                <div class="form-row mt-3">
                    <div class="col-md-4">
                        <strong>First Name:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student_first_name)); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <strong>Middle Name:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($middle_name)); ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <strong>Last Name:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($student_last_name)); ?>" readonly>
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col-md-8"><strong>Date of Birth:</strong>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($birthdate); ?>" readonly>
                    </div>

                    <div class="col-md-4"><strong>Sex:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($sex)); ?>" readonly>
                    </div>

                </div>

                <div class="form-row mt-3 pb-4">
                    <div class="col-md-3"><strong>Grade:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($grade_name)); ?>" readonly>
                    </div>
                    <div class="col-md-3"><strong>Section:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($section_name)); ?>" readonly>
                    </div>
                    <div class="col-md-6"><strong>Adviser:</strong>
                        <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($teacher_name)); ?>" readonly>
                    </div>

                </div>
                <hr>
                <div class="form-row mt-3 pb-3">
                    <div class="col-md-6">
                        <div class="container-fluid">
                            <h5 class="text-center"><b>Mother</b></h5>
                            <div class="row">
                                <strong>Name:</strong>
                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($mother_name)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Occupation:</strong>

                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($mother_occupation)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Address:</strong>

                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($mother_address)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Contact:</strong>

                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($mother_contact); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="container-fluid">
                            <h5 class="text-center"><b>Father</b></h5>
                            <div class="row">
                                <strong>Name:</strong>
                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($father_name)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Occupation:</strong>

                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($father_occupation)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Address:</strong>

                                <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($father_address)); ?>" readonly>
                            </div>
                            <div class="row mt-3"><strong>Contact:</strong>

                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($father_contact); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

    </div>

    <div class="container-fluid bg-white pt-4 mt-2 rounded-lg">
        <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Offender Details</b></h5>
        <?php if (isset($person)) : ?>

            <div class="form-row mt-3">
                <div class="col-md-4">
                    <strong>First Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['first_name'])); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Middle Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['middle_name'])); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Last Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['last_name'])); ?>" readonly>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="col-md-8"><strong>Date of Birth:</strong>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($person['birthdate']); ?>" readonly>
                </div>

                <div class="col-md-4"><strong>Sex:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['sex'])); ?>" readonly>
                </div>

            </div>

            <div class="form-row mt-3">
                <div class="col-md-12"><strong>Designation/Position:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['position'])); ?>" readonly>
                </div>
            </div>

            <div class="form-row mt-3 pb-3">
                <div class="col-md-6">
                    <strong>Address:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($person['address'])); ?>" readonly>
                </div>
                <div class="col-md-6"><strong>Contact:</strong>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($person['contact_number']); ?>" readonly>
                </div>

            </div>
        <?php endif; ?>

    </div>




    <div class="container-fluid bg-white p-4 rounded-lg mt-4">
        <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Complainant Details</b></h5>
        <div class="form-row mt-3">
            <div class="form-group col-md-4">
                <label for="complainantFirstName"><strong>First Name:</strong></label>
                <input type="text" class="form-control" id="complainantFirstName" name="complainantFirstName">
            </div>
            <div class="form-group col-md-4">
                <label for="complainantMiddleName"><strong>Middle Name:</strong></label>
                <input type="text" class="form-control" id="complainantMiddleName" name="complainantMiddleName">
            </div>
            <div class="form-group col-md-4">
                <label for="complainantLastName"><strong>Last Name:</strong></label>
                <input type="text" class="form-control" id="complainantLastName" name="complainantLastName">
            </div>
        </div>
        <div class="form-group">
            <label for="relationshipToVictim"><strong>Relationship to Victim:</strong></label>
            <input type="text" class="form-control" id="relationshipToVictim" name="relationshipToVictim">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="complainantContact"><strong>Contact Number:</strong></label>
                <input type="text" class="form-control" id="complainantContact" name="complainantContact">
            </div>
            <div class="form-group col-md-6">
                <label for="complainantAddress"><strong>Address:</strong></label>
                <input type="text" class="form-control" id="complainantAddress" name="complainantAddress">
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white p-4 rounded-lg mt-4">
        <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Details of the Case</b></h5>

        <div class="form-group">
            <textarea class="form-control" id="caseDetails" name="caseDetails" rows="5" placeholder="Enter the Details of the case..."></textarea>
        </div>

        <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Action Taken</b></h5>
        <div class="form-group">
            <textarea class="form-control" id="actionTaken" name="actionTaken" rows="5" placeholder="Enter the Details of the action taken by the school..."></textarea>
        </div>

        <h5 class="text-center bg-dark text-white p-2 rounded-lg"><b>Recommendations</b></h5>
        <div class="form-group">
            <textarea class="form-control" id="recommendations" name="recommendations" rows="5" placeholder="Enter the recommendation of the school..."></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-success btn-lg mt-4">Finish</button>

    </form>
</div>

<?php
// Include footer
include "admin-footer.php"; include "footer.php";
?>