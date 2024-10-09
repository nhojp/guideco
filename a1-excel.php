<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

include "conn.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Fetch strands
$strands = [];
$result = $conn->query("SELECT id, name FROM strands");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $strands[] = $row;
    }
}

// Fetch sections
$sections = [];
$result = $conn->query("SELECT id, section_name FROM sections");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

// Fetch grades
$grades = [];
$result = $conn->query("SELECT id, grade_name FROM grades");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }
}

// Define school years (static loop)
$school_years = [
    '2024-2025',
    '2025-2026',
    '2026-2027',
    // Add more years as needed
];

// Prepare arrays for displaying names
$strand_names = array_column($strands, 'name', 'id');
$section_names = array_column($sections, 'section_name', 'id');
$grade_names = array_column($grades, 'grade_name', 'id');

if (isset($_POST['import'])) {
    $file_mimes = array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    // Get selected values
    $selected_strand = $_POST['strand'];
    $selected_section = $_POST['section'];
    $selected_grade = $_POST['grade'];
    $selected_school_year = $_POST['school_year']; // Add selected school year

    if (isset($_FILES['excel_file']['name']) && in_array($_FILES['excel_file']['type'], $file_mimes)) {
        $file_path = $_FILES['excel_file']['tmp_name'];

        // Load the Excel file
        $spreadsheet = IOFactory::load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // Get the last two digits of the selected school year
        $year_parts = explode('-', $selected_school_year);
        $last_two_digits_year = substr($year_parts[0], -2); // Get the last two digits of the first year

        // Iterate through each row in the sheet starting from the second row
        for ($row = 2; $row <= $highestRow; $row++) { // Assuming first row is for headers
            $first_name = $sheet->getCell('B' . $row)->getValue();
            $middle_name = $sheet->getCell('C' . $row)->getValue();
            $last_name = $sheet->getCell('D' . $row)->getValue();
            $lrn = intval($sheet->getCell('E' . $row)->getValue());
            $sex = $sheet->getCell('F' . $row)->getValue();
            $barangay = $sheet->getCell('G' . $row)->getValue();

            // Fetch the highest ID from the students table
            $result_max_id = $conn->query("SELECT MAX(id) AS max_id FROM students");
            $row_max_id = $result_max_id->fetch_assoc();
            $new_student_id = $row_max_id['max_id'] + 1; // Increment the highest ID for the new student

            // Insert data into the users table
            $username = $new_student_id; // Use the new student ID as username
            $password = $username . strtolower($first_name[0]) . strtolower($last_name[0]);

            // Check if username already exists
            $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows === 0) {
                // Insert data into the users table
                $sql_user = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt_user = $conn->prepare($sql_user);
                $stmt_user->bind_param("ss", $username, $password); // Use plain password without hashing

                if ($stmt_user->execute()) {
                    $last_inserted_user_id = $stmt_user->insert_id; // Get the last inserted user_id

                    // Insert data into the students table using selected strand, section, grade, and school year
                    $sql_student = "INSERT INTO students (first_name, middle_name, last_name, lrn, sex, barangay, strand_id, grade_id, section_id, school_year, user_id)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_student = $conn->prepare($sql_student);
                    $stmt_student->bind_param("sssssssssss", $first_name, $middle_name, $last_name, $lrn, $sex, $barangay, $selected_strand, $selected_grade, $selected_section, $selected_school_year, $last_inserted_user_id);

                    if (!$stmt_student->execute()) {
                        $message .= "Error inserting student row $row: " . $stmt_student->error . "<br>";
                    }
                } else {
                    $message .= "Error inserting user row $row: " . $stmt_user->error . "<br>";
                }
            } else {
                $message .= "Username '$username' already exists for row $row. Skipping user insert.<br>";
            }
        }

        if (empty($message)) {
            $message = "Data imported successfully!";
        }
    } else {
        $message = "Please upload a valid Excel file.";
    }
}

// Fetch added students with usernames
$students = [];
$result = $conn->query("SELECT s.*, u.username FROM students s JOIN users u ON s.user_id = u.id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Import Excel File</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-3">
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

                <div class="col-md-3">
                    <label for="strand" class="form-label">Select Strand:</label>
                    <select name="strand" class="form-control" required>
                        <option value="">Select Strand</option>
                        <?php foreach ($strands as $strand): ?>
                            <option value="<?php echo $strand['id']; ?>"><?php echo htmlspecialchars($strand['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="section" class="form-label">Select Section:</label>
                    <select name="section" class="form-control" required>
                        <option value="">Select Section</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo $section['id']; ?>"><?php echo htmlspecialchars($section['section_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="grade" class="form-label">Select Grade:</label>
                    <select name="grade" class="form-control" required>
                        <option value="">Select Grade</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['grade_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="excel_file" class="form-label">Upload Excel File:</label>
                <input type="file" class="form-control" name="excel_file" id="excel_file" required>
            </div>

            <button type="submit" name="import" class="btn btn-primary">Import</button>
        </form>

        <h3 class="mt-5">Added Students</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>LRN</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Barangay</th>
                    <th>Sex</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['lrn']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['middle_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['barangay']); ?></td>
                            <td><?php echo htmlspecialchars($student['sex']); ?></td>
                            <td><?php echo htmlspecialchars($student['username']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>