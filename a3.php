<style>
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
        border: 1px solid #ddd;
    }

    .modal-header {
        background-color: #1F5F1E;
        color: white;
    }

    table tbody td {
        text-transform: capitalize;
    }

    .table th,
    .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #e2e2e2;
    }

    .table {
        border-collapse: collapse;
        background-color: #f9f9f9;
    }

    .table thead {
        position: sticky;
        top: 0;
        background-color: #0C2D0B;
        z-index: 1;
        color: white;
    }

    .btn-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
    }
</style>

<?php
include 'conn.php'; // Include your connection file

// Handle section creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_section'])) {
        $section_name = ucwords(trim($_POST['section_name'])); // ucwords for section name
        $strand_id = $_POST['strand_id'];
        $grade_level = $_POST['grade_level']; // New field for grade level
        $teacher_id = $_POST['teacher_id']; // Get teacher_id from form

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO sections (section_name, strand_id, grade_level, teacher_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $section_name, $strand_id, $grade_level, $teacher_id); // 's' for string, 'i' for integer

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Section added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close(); // Close the statement
    }

    // Handle section edit
    if (isset($_POST['edit_section'])) {
        $section_id = $_POST['section_id'];
        $section_name = ucwords(trim($_POST['edit_section_name']));
        $strand_id = $_POST['edit_strand_id'];
        $grade_level = $_POST['edit_grade_level'];
        $teacher_id = $_POST['edit_teacher_id'];

        $stmt = $conn->prepare("UPDATE sections SET section_name = ?, strand_id = ?, grade_level = ?, teacher_id = ? WHERE id = ?");
        $stmt->bind_param("ssiii", $section_name, $strand_id, $grade_level, $teacher_id, $section_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Section updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close(); // Close the statement
    }

    // Handle section deletion
    if (isset($_POST['delete_section'])) {
        $section_id = $_POST['delete_section_id'];

        $stmt = $conn->prepare("DELETE FROM sections WHERE id = ?");
        $stmt->bind_param("i", $section_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Section deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close(); // Close the statement
    }
}

// Fetch sections with assigned teachers
$sections = $conn->query("SELECT sec.id, sec.section_name, st.name AS strand_name, sec.grade_level, t.first_name, t.last_name, sec.teacher_id, sec.strand_id
                           FROM sections sec 
                           JOIN strands st ON sec.strand_id = st.id
                           LEFT JOIN teachers t ON sec.teacher_id = t.id");

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

<div class="container">
    <div class="bg-white p-4 rounded-lg border">
        <h1>Manage Sections</h1>

        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addSectionModal">
            Add Section
        </button>

        <!-- Add Section Modal -->
        <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                        <button type="button" class="btn-danger btn btn-circle" data-dismiss="modal" aria-label="Close">
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
                            <button type="submit" name="add_section" class="btn btn-primary" style="width: 100%;">Add Section</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sections Table -->
        <div class="table-container">
            <table class="table table-hover">
                <thead class="thead-custom">
                    <tr>
                        <th>ID</th>
                        <th>Section Name</th>
                        <th>Strand</th>
                        <th>Grade Level</th>
                        <th>Assigned Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($sections->num_rows > 0): ?>
                        <?php while ($section = $sections->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($section['id']); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($section['section_name'])); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($section['strand_name'])); ?></td>
                                <td><?php echo htmlspecialchars($section['grade_level']); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($section['first_name'] . ' ' . $section['last_name'])); ?></td>
                                <td>
                                    <!-- View, Edit, and Delete Buttons with Icons -->
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewSectionModal<?php echo $section['id']; ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSectionModal<?php echo $section['id']; ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteSectionModal<?php echo $section['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <!-- View Section Modal -->
                            <div class="modal fade" id="viewSectionModal<?php echo $section['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewSectionModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewSectionModalLabel">View Section Details</h5>
                                            <button type="button" class="btn-danger btn btn-circle" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Section Name:</strong> <?php echo ucwords(htmlspecialchars($section['section_name'])); ?></p>
                                            <p><strong>Strand:</strong> <?php echo ucwords(htmlspecialchars($section['strand_name'])); ?></p>
                                            <p><strong>Grade Level:</strong> <?php echo htmlspecialchars($section['grade_level']); ?></p>
                                            <p><strong>Assigned Teacher:</strong> <?php echo ucwords(htmlspecialchars($section['first_name'] . ' ' . $section['last_name'])); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Edit Section Modal -->
                            <div class="modal fade" id="editSectionModal<?php echo $section['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
                                            <button type="button" class="btn-danger btn btn-circle" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">

                                                <div class="form-group">
                                                    <input type="text" name="edit_section_name" value="<?php echo ucwords(htmlspecialchars($section['section_name'])); ?>" class="form-control" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_strand">Select Strand:</label>
                                                    <select name="edit_strand_id" class="form-control" required>
                                                        <?php foreach ($strands as $strand): ?>
                                                            <option value="<?php echo $strand['id']; ?>" <?php echo ($strand['id'] == $section['strand_id']) ? 'selected' : ''; ?>>
                                                                <?php echo $strand['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_grade_level">Select Grade Level:</label>
                                                    <select name="edit_grade_level" class="form-control" required>
                                                        <?php foreach ($grade_levels as $key => $value): ?>
                                                            <option value="<?php echo $key; ?>" <?php echo ($key == $section['grade_level']) ? 'selected' : ''; ?>>
                                                                <?php echo $value; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_teacher">Select Teacher:</label>
                                                    <select name="edit_teacher_id" class="form-control" required>
                                                        <?php foreach ($teachers as $teacher): ?>
                                                            <option value="<?php echo $teacher['id']; ?>" <?php echo ($teacher['id'] == $section['teacher_id']) ? 'selected' : ''; ?>>
                                                                <?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="edit_section" class="btn btn-warning" style="width: 100%;">Update Section</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Section Modal -->
                            <div class="modal fade" id="deleteSectionModal<?php echo $section['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteSectionModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteSectionModalLabel">Delete Section</h5>
                                            <button type="button" class="btn-danger btn btn-circle" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="delete_section_id" value="<?php echo $section['id']; ?>">
                                                <p>Are you sure you want to delete this section?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="delete_section" class="btn btn-danger" style="width: 100%;">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No sections found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>
</div>