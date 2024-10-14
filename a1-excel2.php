<?php
include "conn.php";

session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit;
}

include 'head.php'; // Include head section
//include 'admin-nav.php'; // Include navbar
require 'vendor/autoload.php'; // Include the PhpSpreadsheet autoload file

use PhpOffice\PhpSpreadsheet\IOFactory;

// Handle student creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if an Excel file has been uploaded
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
        $filePath = $_FILES['excel_file']['tmp_name'];

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Collect usernames from the Excel file
        $excelUsernames = [];
        foreach ($sheetData as $index => $row) {
            if ($index === 1) continue; // Skip the header row
            $excelUsernames[] = trim($row['A']); // Assuming usernames are in column A
        }

        // Fetch existing usernames from the database
        $existingUsernames = [];
        $placeholders = implode(',', array_fill(0, count($excelUsernames), '?'));
        $stmt = $conn->prepare("SELECT username FROM users WHERE username IN ($placeholders)");
        $stmt->bind_param(str_repeat('s', count($excelUsernames)), ...$excelUsernames);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $existingUsernames[] = $row['username'];
        }
        $stmt->close();

        // Identify duplicates
        $duplicates = array_intersect($excelUsernames, $existingUsernames);
        if (!empty($duplicates)) {
            echo "Error: The following usernames already exist: " . implode(', ', $duplicates) . "<br>";
        } else {
            // Proceed to insert users since no duplicates were found
            $conn->begin_transaction();
            try {
                // Get selected values from the form
                $section_id = $_POST['section_id'];
                $school_year_id = $_POST['school_year_id'];

                // Validate section existence and fetch associated teacher_id
                $stmt = $conn->prepare("SELECT teacher_id FROM sections WHERE id = ?");
                $stmt->bind_param("i", $section_id);
                $stmt->execute();
                $stmt->bind_result($teacher_id);
                $stmt->fetch();
                $stmt->close();

                if (!$teacher_id) {
                    throw new Exception("Error: Section with ID $section_id does not exist or has no assigned teacher.");
                }

                // Loop through each row of the spreadsheet
                foreach ($sheetData as $index => $row) {
                    if ($index === 1) continue; // Skip the header row

                    // Assuming columns in the spreadsheet are: username, password, first_name, last_name
                    $username = trim($row['A']);
                    $password = trim($row['B']);
                    $first_name = trim($row['C']);
                    $last_name = trim($row['D']);

                    // Check if username is empty
                    if (empty($username)) {
                        continue; // Skip to the next row
                    }

                    // Insert into the users table
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $password);
                    $stmt->execute();
                    $user_id = $stmt->insert_id; // Get the last inserted user ID
                    $stmt->close();

                    // Insert into the students table
                    $stmt = $conn->prepare("INSERT INTO students (user_id, first_name, last_name, section_id) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("issi", $user_id, $first_name, $last_name, $section_id);
                    $stmt->execute();
                    $student_id = $stmt->insert_id; // Get the last inserted student ID
                    $stmt->close();

                    // Insert into the section_assignment table
                    $stmt = $conn->prepare("INSERT INTO section_assignment (student_id, teacher_id, section_id, school_year_id) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiii", $student_id, $teacher_id, $section_id, $school_year_id);
                    $stmt->execute();
                    $stmt->close();

                    // Insert into mothers table
                    $stmt = $conn->prepare("INSERT INTO mothers (student_id) VALUES (?)");
                    if (!$stmt) {
                        throw new Exception("Prepare statement failed: " . $conn->error);
                    }
                    $stmt->bind_param("i", $student_id);
                    $stmt->execute();
                    $stmt->close();

                    // Insert into fathers table
                    $stmt = $conn->prepare("INSERT INTO fathers (student_id) VALUES (?)");
                    if (!$stmt) {
                        throw new Exception("Prepare statement failed: " . $conn->error);
                    }
                    $stmt->bind_param("i", $student_id);
                    $stmt->execute();
                    $stmt->close();
                }

                // Commit the transaction
                $conn->commit();
                echo "Students added successfully!";
            } catch (Exception $exception) {
                // Rollback the transaction on error
                $conn->rollback();
                echo "Error adding students: " . $exception->getMessage();
            }
        }
    }
}

// Fetch existing students with their related data
$result = $conn->query("
    SELECT s.id, u.username, s.first_name, s.last_name, 
           st.name AS strand_name, sec.section_name, sec.grade_level, 
           sy.year_start, sy.year_end, 
           CONCAT(st.name, ' - ', sec.section_name, ' (Grade ', sec.grade_level, ')') AS section_display,
           CONCAT(sy.year_start, ' - ', sy.year_end) AS school_year_display,
           CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
           u.password
    FROM students s 
    JOIN users u ON s.user_id = u.id
    JOIN section_assignment sa ON s.id = sa.student_id
    JOIN sections sec ON sa.section_id = sec.id
    JOIN strands st ON sec.strand_id = st.id
    JOIN school_year sy ON sa.school_year_id = sy.id
    JOIN teachers t ON sa.teacher_id = t.id
");
$students = $result->fetch_all(MYSQLI_ASSOC);

// Fetch sections for the dropdown
$sections_result = $conn->query("
    SELECT s.id, CONCAT(st.name, ' - ', s.section_name, ' (Grade ', s.grade_level, ')') AS section_display 
    FROM sections s 
    JOIN strands st ON s.strand_id = st.id
");
$sections = $sections_result->fetch_all(MYSQLI_ASSOC);

// Fetch school years for the dropdown
$school_years_result = $conn->query("SELECT id, CONCAT(year_start, ' - ', year_end) AS year_display FROM school_year");
$school_years = $school_years_result->fetch_all(MYSQLI_ASSOC);
?>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <h1 class="mb-4">Import Excel</h1>

        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <!-- Section Dropdown -->
            <div class="mb-3">
                <label for="section_id" class="form-label">Select Section:</label>
                <select name="section_id" class="form-select" required>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo $section['id']; ?>"><?php echo $section['section_display']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- School Year Dropdown -->
            <div class="mb-3">
                <label for="school_year_id" class="form-label">Select School Year:</label>
                <select name="school_year_id" class="form-select" required>
                    <?php foreach ($school_years as $school_year): ?>
                        <option value="<?php echo $school_year['id']; ?>"><?php echo $school_year['year_display']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Excel File Upload -->
            <div class="mb-3">
                <label for="excel_file" class="form-label">Upload Excel File:</label>
                <input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Import Students</button>
            </div>
        </form>

    </div>
</main>
<?php include 'footer.php'; // Include footer section 
?>