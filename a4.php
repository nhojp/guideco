<?php
include 'conn.php'; // Use conn.php for your database connection

// Handle student creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user details from the form
    $username = $_POST['username'];
    $password = $_POST['password']; // Hash the password
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $section_id = $_POST['section_id']; // Get the selected section ID from the form
    $school_year_id = $_POST['school_year_id']; // Get the school year ID

    // Start a transaction
    $conn->begin_transaction();

    try {
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

        // Check if the section exists and get the teacher_id
        $stmt = $conn->prepare("SELECT teacher_id FROM sections WHERE id = ?");
        $stmt->bind_param("i", $section_id);
        $stmt->execute();
        $stmt->bind_result($teacher_id);
        $stmt->fetch();
        $stmt->close();

        if (!$teacher_id) {
            throw new Exception("Error: Section with ID $section_id does not exist.");
        }

        // Insert into the section_assignment table
        $stmt = $conn->prepare("INSERT INTO section_assignment (student_id, teacher_id, section_id, school_year_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $student_id, $teacher_id, $section_id, $school_year_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();
        echo "Student added successfully!";
    } catch (Exception $exception) {
        // Rollback the transaction on error
        $conn->rollback();
        echo "Error adding student: " . $exception->getMessage();
    }
}

// Fetch existing students with their related data
$result = $conn->query("SELECT s.id, u.username, s.first_name, s.last_name, 
           st.name AS strand_name, sec.section_name, sec.grade_level, 
           sy.year_start, sy.year_end, 
           CONCAT(st.name, ' - ', sec.section_name, ' (Grade ', sec.grade_level, ')') AS section_display,
           CONCAT(sy.year_start, ' - ', sy.year_end) AS school_year_display,
           CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
    FROM students s 
    JOIN users u ON s.user_id = u.id
    JOIN section_assignment sa ON s.id = sa.student_id
    JOIN sections sec ON sa.section_id = sec.id
    JOIN strands st ON sec.strand_id = st.id
    JOIN school_year sy ON sa.school_year_id = sy.id
    JOIN teachers t ON sa.teacher_id = t.id");
$students = $result->fetch_all(MYSQLI_ASSOC);

// Fetch sections for the dropdown
$sections_result = $conn->query("SELECT s.id, CONCAT(st.name, ' - ', s.section_name, ' (Grade ', s.grade_level, ')') AS section_display 
    FROM sections s 
    JOIN strands st ON s.strand_id = st.id");
$sections = $sections_result->fetch_all(MYSQLI_ASSOC);

// Fetch school years for the dropdown
$school_years_result = $conn->query("SELECT id, CONCAT(year_start, ' - ', year_end) AS year_display FROM school_year");
$school_years = $school_years_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
</head>
<body>
    <h1>Add Student</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        
        <label for="section_id">Select Section:</label>
        <select name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>"><?php echo $section['section_display']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="school_year_id">Select School Year:</label>
        <select name="school_year_id" id="school_year_id" required>
            <?php foreach ($school_years as $year): ?>
                <option value="<?php echo $year['id']; ?>"><?php echo $year['year_display']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit">Add Student</button>
    </form>

    <h2>Existing Students</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Strand</th>
            <th>Section</th>
            <th>Grade</th>
            <th>School Year</th>
            <th>Teacher</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?php echo $student['id']; ?></td>
            <td><?php echo $student['username']; ?></td>
            <td><?php echo $student['first_name']; ?></td>
            <td><?php echo $student['last_name']; ?></td>
            <td><?php echo $student['strand_name']; ?></td>
            <td><?php echo $student['section_name']; ?></td>
            <td><?php echo $student['grade_level']; ?></td>
            <td><?php echo $student['school_year_display']; ?></td>
            <td><?php echo $student['teacher_name']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
