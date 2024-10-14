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
        echo "<div class='alert alert-success'>Section added successfully!</div>";
    } else {
        // Debugging: log the error
        error_log("SQL Error: " . $stmt->error);
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Apply Montserrat font */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            margin-top: 20px;
        }

        .thead-custom {
            background-color: #0C2D0B;
            color: white;
        }

        .btn-primary {
            background-color: #1F5F1E;
            border: none;
        }

        .btn-primary:hover {
            background-color: #145214;
        }

        .table-container {
            max-height: 400px; 
            overflow-y: auto; 
        }
        .modal-header {
        background-color: #1F5F1E;
        color: white;
    }
    </style>
</head>
<body>

<div class="container">
    <div class="bg-white p-4 rounded-lg border">
        <h1>Manage Sections</h1>

        
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addSectionModal">
            Add Section
        </button>

   
        <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" name="section_name" class="form-control" placeholder="Section Name" required>
                            </div>

                            <div class="form-group">
                                <label for="strand">Select Strand:</label>
                                <select name="strand_id" class="form-control" required>
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
                            </div>

                            <div class="form-group">
                                <label for="grade_level">Select Grade Level:</label>
                                <select name="grade_level" class="form-control" required>
                                    <?php foreach ($grade_levels as $key => $value): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="teacher">Select Teacher:</label>
                                <select name="teacher_id" class="form-control" required>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Add Section</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

       
        <div class="table-container">
            <table class="table table-hover mt-4">
                <thead class="thead-custom">
                    <tr>
                        <th>ID</th>
                        <th>Section Name</th>
                        <th>Strand</th>
                        <th>Grade Level</th>
                        <th>Assigned Teacher</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $conn->close(); // Close the connection ?>


</body>
</html>
