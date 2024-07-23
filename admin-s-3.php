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
if (!isset($_GET['offender_id']) || !isset($_GET['victim_id'])) {
    // Redirect if data is missing
    header('Location: admin-s-2.php');
    exit;
}

// Fetch posted data
$offender_id = intval($_GET['offender_id']);
$victim_id = intval($_GET['victim_id']);

$sql_victim = "SELECT s.first_name AS victim_first_name, 
                      s.middle_name, 
                      s.last_name AS victim_last_name, 
                      s.birthdate, s.sex,
                      s.contact_number AS contact,
                      sec.section_name, 
                      g.grade_name, 
                      t.first_name AS teacher_fname, 
                      t.last_name AS teacher_lname,
                      m.name AS mother_name, 
                      m.contact_number AS mother_contact, 
                      m.occupation AS mother_occupation, 
                      m.address AS mother_address,
                      f.name AS father_name, 
                      f.contact_number AS father_contact, 
                      f.occupation AS father_occupation, 
                      f.address AS father_address
              FROM students s
              INNER JOIN sections sec ON s.section_id = sec.id
              INNER JOIN grades g ON sec.grade_id = g.id
              LEFT JOIN teachers t ON sec.teacher_id = t.id
              LEFT JOIN mothers m ON s.id = m.student_id
              LEFT JOIN fathers f ON s.id = f.student_id
              WHERE s.id = ?";

$stmt_victim = $conn->prepare($sql_victim);
$stmt_victim->bind_param("i", $victim_id);
$stmt_victim->execute();
$result_victim = $stmt_victim->get_result();

// Check if the query was successful and fetched data
if ($result_victim === false) {
    echo "Error: " . $conn->error;
    exit;
} elseif ($result_victim->num_rows > 0) {
    $row = $result_victim->fetch_assoc();
    $victim_first_name = $row['victim_first_name'] ?? '';
    $victim_middle_name = $row['middle_name'] ?? '';
    $victim_last_name = $row['victim_last_name'] ?? '';
    $victim_birthdate = $row['birthdate'] ?? '';
    $victim_sex = $row['sex'] ?? '';
    $victim_contact = $row['contact'] ?? '';
    $victim_section_name = $row['section_name'] ?? '';
    $victim_teacher_name = ($row['teacher_fname'] ?? '') . ' ' . ($row['teacher_lname'] ?? '');
    $victim_grade_name = $row['grade_name'] ?? '';
    $victim_mother_name = $row['mother_name'] ?? '';
    $victim_mother_contact = $row['mother_contact'] ?? '';
    $victim_mother_occupation = $row['mother_occupation'] ?? '';
    $victim_mother_address = $row['mother_address'] ?? '';
    $victim_father_name = $row['father_name'] ?? '';
    $victim_father_contact = $row['father_contact'] ?? '';
    $victim_father_occupation = $row['father_occupation'] ?? '';
    $victim_father_address = $row['father_address'] ?? '';
} else {
    echo "No victim data found for victim_id: $victim_id";
    exit;
}

// Fetch offender details from database
$sql_offender = "SELECT s.first_name AS offender_first_name, 
                        s.middle_name, 
                        s.last_name AS offender_last_name, 
                        s.birthdate, s.sex, 
                        s.contact_number AS contact,
                        sec.section_name AS offender_section_name,
                        g.grade_name AS offender_grade_name,
                        t.first_name AS teacher_fname, 
                        t.last_name AS teacher_lname,
                        m.name AS offender_mother_name, 
                        m.contact_number AS offender_mother_contact, 
                        m.occupation AS offender_mother_occupation, 
                        m.address AS offender_mother_address,
                        f.name AS offender_father_name, 
                        f.contact_number AS offender_father_contact, 
                        f.occupation AS offender_father_occupation, 
                        f.address AS offender_father_address
                FROM students s
                LEFT JOIN sections sec ON s.section_id = sec.id
                LEFT JOIN grades g ON sec.grade_id = g.id
                LEFT JOIN teachers t ON sec.teacher_id = t.id
                LEFT JOIN mothers m ON s.id = m.student_id
                LEFT JOIN fathers f ON s.id = f.student_id
                WHERE s.id = ?";

$stmt_offender = $conn->prepare($sql_offender);
$stmt_offender->bind_param("i", $offender_id);
$stmt_offender->execute();
$result_offender = $stmt_offender->get_result();

// Check if the query was successful and fetched data
if ($result_offender === false) {
    echo "Error: " . $conn->error;
    exit;
} elseif ($result_offender->num_rows > 0) {
    $person = $result_offender->fetch_assoc();
    $offender_first_name = $person['offender_first_name'] ?? '';
    $offender_middle_name = $person['middle_name'] ?? '';
    $offender_last_name = $person['offender_last_name'] ?? '';
    $offender_birthdate = $person['birthdate'] ?? '';
    $offender_sex = $person['sex'] ?? '';
    $offender_contact = $person['contact'] ?? '';
    $offender_grade_name = $person['offender_grade_name'] ?? '';
    $offender_section_name = $person['offender_section_name'] ?? '';
    $offender_teacher_name = ($person['teacher_fname'] ?? '') . ' ' . ($person['teacher_lname'] ?? '');
    $offender_contact = $person['contact_number'] ?? '';
    $offender_address = $person['address'] ?? '';
    $offender_mother_name = $person['offender_mother_name'] ?? '';
    $offender_mother_contact = $person['offender_mother_contact'] ?? '';
    $offender_mother_occupation = $person['offender_mother_occupation'] ?? '';
    $offender_mother_address = $person['offender_mother_address'] ?? '';
    $offender_father_name = $person['offender_father_name'] ?? '';
    $offender_father_contact = $person['offender_father_contact'] ?? '';
    $offender_father_occupation = $person['offender_father_occupation'] ?? '';
    $offender_father_address = $person['offender_father_address'] ?? '';
} else {
    echo "No offender data found for offender_id: $offender_id";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['caseDetails'])) {
    // Fetch and sanitize POST data
    $complainantFirstName = $_POST['complainantFirstName'] ?? '';
    $complainantMiddleName = $_POST['complainantMiddleName'] ?? '';
    $complainantLastName = $_POST['complainantLastName'] ?? '';
    $relationshipToVictim = $_POST['relationshipToVictim'] ?? '';
    $complainantContact = $_POST['complainantContact'] ?? '';
    $complainantAddress = $_POST['complainantAddress'] ?? '';

    $caseDetails = $_POST['caseDetails'] ?? '';
    $actionTaken = $_POST['actionTaken'] ?? '';
    $recommendations = $_POST['recommendations'] ?? '';
    $reportedAt = $_POST['reportedAt'] ?? '';

    // Calculate age of victim and offender based on birthdates
    $victimAge = calculateAge($victim_birthdate);
    $offenderAge = calculateAge($offender_birthdate);

    // Prepare the SQL query directly
    $sql_insert = "INSERT INTO complaints_student (
    victimFirstName, victimMiddleName, victimLastName, victimDOB, victimAge, victimSex, victimGrade, victimSection, victimAdviser, victimContact,
    motherName, motherOccupation, motherAddress, motherContact,
    fatherName, fatherOccupation, fatherAddress, fatherContact,
    complainantFirstName, complainantMiddleName, complainantLastName, relationshipToVictim, complainantContact, complainantAddress,
    complainedFirstName, complainedMiddleName, complainedLastName, complainedDOB, complainedAge, complainedSex, complainedGrade, complainedSection, complainedAdviser, complainedContact,
    complainedMotherName, complainedMotherOccupation, complainedMotherAddress, complainedMotherContact,
    complainedFatherName, complainedFatherOccupation, complainedFatherAddress, complainedFatherContact,
    caseDetails, actionTaken, recommendations, reportedAt, student_id
) VALUES (
    '$victim_first_name', '$victim_middle_name', '$victim_last_name', '$victim_birthdate', '$victimAge', '$victim_sex',
    '$victim_grade_name', '$victim_section_name', '$victim_teacher_name', '$victim_contact',
    '$victim_mother_name', '$victim_mother_occupation', '$victim_mother_address', '$victim_mother_contact',
    '$victim_father_name', '$victim_father_occupation', '$victim_father_address', '$victim_father_contact',
    '$complainantFirstName', '$complainantMiddleName', '$complainantLastName', '$relationshipToVictim', '$complainantContact', '$complainantAddress',
    '$offender_first_name', '$offender_middle_name', '$offender_last_name', '$offender_birthdate', '$offenderAge', '$offender_sex', '$offender_grade_name', '$offender_section_name', '$offender_teacher_name', '$offender_contact',
    '$offender_mother_name', '$offender_mother_occupation', '$offender_mother_address', '$offender_mother_contact',
    '$offender_father_name', '$offender_father_occupation', '$offender_father_address', '$offender_father_contact',
    '$caseDetails', '$actionTaken', '$recommendations', '$reportedAt', '$offender_id)'
)";

    // Execute the query
    if ($conn->query($sql_insert) === TRUE) {
        header("Location: admin-student-complain-list.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

include "admin-header.php";
?>
<div class="container-fluid mt-2 mb-5">
    <div class="container-fluid bg-white pt-4 rounded-lg">
        <div class="row">
            <div class="col-md-4">
                <h2 class="mb-4 font-weight-bold">Complaint Details</h2>
            </div>

            <div class="col-md-8">
                <div class="search-wrapper float-right">
                    <div class="input-holder">
                        <input type="text" class="search-input" id="searchInput" placeholder="Type to search">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white pt-4 mt-2 rounded-lg">
        <form action="" method="post">
            <h5 class="text-center bg-dark text-white p-2"><b>Victim Details</b></h5>
            <input type="hidden" name="reportedAt" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <div class="row mt-3">
                <div class="col-md-4">
                    <strong>First Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_first_name)); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Middle Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_middle_name)); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Last Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_last_name)); ?>" readonly>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8"><strong>Date of Birth:</strong>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($victim_birthdate); ?>" readonly>
                </div>

                <div class="col-md-4"><strong>Sex:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_sex)); ?>" readonly>
                </div>

            </div>

            <div class="row mt-3 pb-4">
                <div class="col-md-3"><strong>Grade:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_grade_name)); ?>" readonly>
                </div>
                <div class="col-md-3"><strong>Section:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_section_name)); ?>" readonly>
                </div>
                <div class="col-md-6"><strong>Adviser:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_teacher_name)); ?>" readonly>
                </div>

            </div>
            <hr>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="container-fluid">
                        <h5 class="text-center"><b>Mother</b></h5>
                        <div class="row">
                            <strong>Name:</strong>
                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_mother_name)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Occupation:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_mother_occupation)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Address:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_mother_address)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Contact:</strong>

                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($victim_mother_contact); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container-fluid">
                        <h5 class="text-center"><b>Father</b></h5>
                        <div class="row">
                            <strong>Name:</strong>
                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_father_name)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Occupation:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_father_occupation)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Address:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($victim_father_address)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Contact:</strong>

                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($victim_father_contact); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="container-fluid bg-white pt-4 mt-2 rounded-lg">
        <form action="" method="post">
            <h5 class="text-center bg-dark text-white p-2"><b>Offender Details</b></h5>
            <div class="row mt-3">
                <div class="col-md-4">
                    <strong>First Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_first_name)); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Middle Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_middle_name)); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <strong>Last Name:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_last_name)); ?>" readonly>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8"><strong>Date of Birth:</strong>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($offender_birthdate); ?>" readonly>
                </div>

                <div class="col-md-4"><strong>Sex:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_sex)); ?>" readonly>
                </div>

            </div>

            <div class="row mt-3 pb-4">
                <div class="col-md-3"><strong>Grade:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_grade_name)); ?>" readonly>
                </div>
                <div class="col-md-3"><strong>Section:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_section_name)); ?>" readonly>
                </div>
                <div class="col-md-6"><strong>Adviser:</strong>
                    <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_teacher_name)); ?>" readonly>
                </div>

            </div>
            <hr>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="container-fluid">
                        <h5 class="text-center"><b>Mother</b></h5>
                        <div class="row">
                            <strong>Name:</strong>
                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_mother_name)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Occupation:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_mother_occupation)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Address:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_mother_address)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Contact:</strong>

                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($offender_mother_contact); ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container-fluid">
                        <h5 class="text-center"><b>Father</b></h5>
                        <div class="row">
                            <strong>Name:</strong>
                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_father_name)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Occupation:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_father_occupation)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Address:</strong>

                            <input type="text" class="form-control" value="<?php echo ucwords(htmlspecialchars($offender_father_address)); ?>" readonly>
                        </div>
                        <div class="row mt-3"><strong>Contact:</strong>

                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($offender_father_contact); ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>


    </div>

    <div class="container-fluid bg-white p-4 rounded-lg mt-4">

        <!-- Complainant Section -->
        <h4>B. Complainant</h4>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="complainantFirstName">First Name:</label>
                <input type="text" class="form-control" id="complainantFirstName" name="complainantFirstName">
            </div>
            <div class="form-group col-md-4">
                <label for="complainantMiddleName">Middle Name:</label>
                <input type="text" class="form-control" id="complainantMiddleName" name="complainantMiddleName">
            </div>
            <div class="form-group col-md-4">
                <label for="complainantLastName">Last Name:</label>
                <input type="text" class="form-control" id="complainantLastName" name="complainantLastName">
            </div>
        </div>
        <div class="form-group">
            <label for="relationshipToVictim">Relationship to Victim:</label>
            <input type="text" class="form-control" id="relationshipToVictim" name="relationshipToVictim">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="complainantContact">Contact Number:</label>
                <input type="text" class="form-control" id="complainantContact" name="complainantContact">
            </div>
            <div class="form-group col-md-6">
                <label for="complainantAddress">Address:</label>
                <input type="text" class="form-control" id="complainantAddress" name="complainantAddress">
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white p-4 rounded-lg mt-4">

        <!-- Details of the Case Section -->
        <h4>II. Details of the Case</h4>
        <div class="form-group">
            <label for="caseDetails">Details of the Case:</label>
            <textarea class="form-control" id="caseDetails" name="caseDetails" rows="5"></textarea>
        </div>

        <!-- Action Taken Section -->
        <h4>III. Action Taken</h4>
        <div class="form-group">
            <label for="actionTaken">Action Taken:</label>
            <textarea class="form-control" id="actionTaken" name="actionTaken" rows="5"></textarea>
        </div>

        <!-- Recommendations Section -->
        <h4>IV. Recommendations</h4>
        <div class="form-group">
            <label for="recommendations">Recommendations:</label>
            <textarea class="form-control" id="recommendations" name="recommendations" rows="5"></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg mt-4">Submit</button>

    </form>
</div>

<?php
// Include footer
include "admin-footer.php";
?>
</div>