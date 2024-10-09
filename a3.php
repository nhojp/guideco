<?php
include 'conn.php'; // Include your connection file

// Handle section creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_name = $_POST['section_name'];
    $strand_id = $_POST['strand_id'];
    $grade_level = $_POST['grade_level']; // New field for grade level
    $teacher_id = $_POST['teacher_id']; // Get teacher_id from form

    // Debugging: log the input values
    error_log("Section Name: $section_name, Strand ID: $strand_id, Grade Level: $grade_level, Teacher ID: $teacher_id");

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO sections (section_name, strand_id, grade_level, teacher_id) VALUES (?, ?, ?, ?)"); 
    $stmt->bind_param("ssii", $section_name, $strand_id, $grade_level, $teacher_id); // 's' for string, 'i' for integer

    // Execute the statement
    if ($stmt->execute()) {
        echo "Section added successfully!";
    } else {
        // Debugging: log the error
        error_log("SQL Error: " . $stmt->error);
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
}

// Fetch sections with assigned teachers
$sections = $conn->query("
    SELECT sec.id, sec.section_name, st.name AS strand_name, sec.grade_level, t.first_name, t.last_name 
    FROM sections sec 
    JOIN strands st ON sec.strand_id = st.id
    LEFT JOIN teachers t ON sec.teacher_id = t.id
");

// Fetch strands for dropdown
$strands = $conn->query("SELECT * FROM strands");

// Fetch teachers for dropdown
$teachers = $conn->query("SELECT * FROM teachers");

// Grade levels for dropdown (only 11 and 12)
$grade_levels = [
    '11' => 'Grade 11',
    '12' => 'Grade 12',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Sections</title>
</head>
<body>
    <h1>Manage Sections</h1>
    <form method="POST">
        <input type="text" name="section_name" placeholder="Section Name" required>
        
        <label for="strand">Select Strand:</label>
        <select name="strand_id" required>
            <?php if ($strands->num_rows > 0): ?>
                <?php while ($strand = $strands->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($strand['id']); ?>">
                        <?php echo htmlspecialchars($strand['name']); ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No strands available</option>
            <?php endif; ?>
        </select>

        <label for="grade_level">Select Grade Level:</label>
        <select name="grade_level" required>
            <?php foreach ($grade_levels as $key => $value): ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="teacher">Select Teacher:</label>
        <select name="teacher_id" required>
            <?php if ($teachers->num_rows > 0): ?>
                <?php while ($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($teacher['id']); ?>">
                        <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No teachers available</option>
            <?php endif; ?>
        </select>

        <button type="submit">Add Section</button>
    </form>

    <h2>Existing Sections</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Section Name</th>
            <th>Strand</th>
            <th>Grade Level</th>
            <th>Assigned Teacher</th>
        </tr>
        <?php if ($sections->num_rows > 0): ?>
            <?php while ($section = $sections->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($section['id']); ?></td>
                <td><?php echo htmlspecialchars($section['section_name']); ?></td>
                <td><?php echo htmlspecialchars($section['strand_name']); ?></td>
                <td><?php echo htmlspecialchars($section['grade_level']); ?></td>
                <td><?php echo htmlspecialchars($section['first_name'] . ' ' . $section['last_name']); ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No sections found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); // Close the connection ?>

</body>
</html>
