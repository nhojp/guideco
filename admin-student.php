<?php

include 'conn.php';
include 'head.php';

$successMessage = "";
$errorMessage = "";

// Function to generate a new ID
function generateId($conn)
{
    $sql = "SELECT MAX(id) AS max_id FROM students";
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return null;
    }

    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];

    if ($max_id) {
        $generated_id = $max_id + 1; // Use numeric addition
    } else {
        $generated_id = 1;
    }

    return $generated_id;
}
$generated_id = generateId($conn);

// Function to fetch sections from the database
function fetchSections($conn)
{
    $sql = "
        SELECT sec.id, sec.section_name, g.grade_name
        FROM sections sec
        JOIN grades g ON sec.grade_id = g.id
    ";
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return [];
    }

    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }

    return $sections;
}

// Function to fetch all students with their sections and grades
function fetchStudents($conn)
{
    $sql = "
        SELECT s.id, s.first_name, s.last_name, sec.section_name, g.grade_name, s.section_id
        FROM students s
        JOIN sections sec ON s.section_id = sec.id
        JOIN grades g ON sec.grade_id = g.id
    ";
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return [];
    }

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    return $students;
}


// Function to handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Validate and sanitize delete_id
    if (!is_numeric($delete_id)) {
        $errorMessage = "Invalid ID.";
    } else {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare statements for deletion
            $stmt1 = $conn->prepare("DELETE FROM complaints_student WHERE student_id = ?");
            $stmt1->bind_param('i', $delete_id);
            if (!$stmt1->execute()) {
                throw new Exception("Error deleting from complaints_student table: " . $conn->error);
            }
            $stmt1->close();

            $stmt2 = $conn->prepare("DELETE FROM violations WHERE student_id = ?");
            $stmt2->bind_param('i', $delete_id);
            if (!$stmt2->execute()) {
                throw new Exception("Error deleting from violations table: " . $conn->error);
            }
            $stmt2->close();

            $stmt3 = $conn->prepare("DELETE FROM fathers WHERE student_id = ?");
            $stmt3->bind_param('i', $delete_id);
            if (!$stmt3->execute()) {
                throw new Exception("Error deleting from fathers table: " . $conn->error);
            }
            $stmt3->close();

            $stmt4 = $conn->prepare("DELETE FROM mothers WHERE student_id = ?");
            $stmt4->bind_param('i', $delete_id);
            if (!$stmt4->execute()) {
                throw new Exception("Error deleting from mothers table: " . $conn->error);
            }
            $stmt4->close();

            $stmt5 = $conn->prepare("DELETE FROM students WHERE id = ?");
            $stmt5->bind_param('i', $delete_id);
            if (!$stmt5->execute()) {
                throw new Exception("Error deleting from students table: " . $conn->error);
            }
            $stmt5->close();

            $stmt6 = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt6->bind_param('i', $delete_id);
            if (!$stmt6->execute()) {
                throw new Exception("Error deleting from users table: " . $conn->error);
            }
            $stmt6->close();

            // Commit transaction
            $conn->commit();
            $successMessage = "Student and related records deleted successfully.";
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            $errorMessage = $e->getMessage();
        }
    }
}

// Function to handle editing
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $section_id = $_POST['section_id'];

    $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', section_id = '$section_id' WHERE id = '$edit_id'";
    if ($conn->query($sql)) {
        $successMessage = "Student updated successfully.";
        $students = fetchStudents($conn);
    } else {
        $errorMessage = "Error updating student: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit_id'])) {
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : '';
    $username = generateId($conn);  // Generated ID
    $password = $username;  // Placeholder password, replace with actual password logic if needed

    if ($first_name && $last_name && $section_id) {
        $stmt1 = $conn->prepare("INSERT INTO users (id, username, password) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $username, $username, $password);

        if ($stmt1->execute()) {
            $stmt2 = $conn->prepare("INSERT INTO students (id, user_id, first_name, last_name, section_id) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssss", $username, $username, $first_name, $last_name, $section_id);

            if ($stmt2->execute()) {
                $stmt3 = $conn->prepare("INSERT INTO mothers (parent_id, student_id) VALUES (?, ?)");
                $stmt3->bind_param("ss", $username, $username);

                $stmt4 = $conn->prepare("INSERT INTO fathers (parent_id, student_id) VALUES (?, ?)");
                $stmt4->bind_param("ss", $username, $username);

                if ($stmt3->execute() && $stmt4->execute()) {
                    $successMessage = "Student added successfully.";
                } else {
                    error_log("Error inserting into mothers or fathers table: " . $conn->error);
                    $errorMessage = "Error inserting into mothers or fathers table.";
                }

                $students = fetchStudents($conn);
            } else {
                error_log("Error inserting into students table: " . $stmt2->error);
                $errorMessage = "Error inserting into students table.";
            }
        } else {
            error_log("Error inserting into users table: " . $stmt1->error);
            $errorMessage = "Error inserting into users table.";
        }
    } else {
        $errorMessage = "Please fill in all required fields.";
    }
}


$sections = fetchSections($conn);
$students = fetchStudents($conn);

function fetchGrades($conn)
{
    $sql = "SELECT id, grade_name FROM grades";
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return [];
    }

    $grades = [];
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }

    return $grades;
}
$grades = fetchGrades($conn);
?>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>
                            Student List</strong></h3>
                </div>
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStudentModal">
                        +
                    </button>
                </div>
            </div>

            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a student name...">
            </div>
        </div>

        <?php if ($successMessage) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php elseif ($errorMessage) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <div class="form-row pt-2 pb-2">
            <div class="col-md-5">
                <label for="gradeFilter">Filter by Grade:</label>
                <select id="gradeFilter" class="form-control">
                    <option value="">All Grades</option>
                    <?php foreach ($grades as $grade) : ?>
                        <option value="<?php echo htmlspecialchars($grade['grade_name']); ?>"><?php echo htmlspecialchars($grade['grade_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <label for="sectionFilter">Filter by Section:</label>
                <select id="sectionFilter" class="form-control">
                    <option value="">All Sections</option>
                    <?php foreach ($sections as $section) : ?>
                        <option value="<?php echo ucwords(htmlspecialchars($section['section_name'])); ?>"><?php echo ucwords(htmlspecialchars($section['section_name'])); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="printSection">Print List:</label>
                <div class="input-group">
                    <select id="printSection" class="form-control">
                        <option value="">All Sections</option>
                        <?php foreach ($sections as $section) : ?>
                            <option value="<?php echo htmlspecialchars($section['id']); ?>">
                                <?php echo ucwords(htmlspecialchars($section['section_name'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="input-group-append">
                        <button id="printButton" class="btn btn-primary">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
            </div>


        </div>
        <div class="table-responsive">
            <table class="table table-hover mt-4 border">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:30%;">Full Name</th>
                        <th style="width:25%;">
                            Grade
                        </th>
                        <th style="width:25%;">
                            Section
                        </th>
                        <th class="text-center" style="width:20%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <?php if (count($students) > 0) : ?>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?php echo ucwords(htmlspecialchars($student['first_name'])) . ' ' . ucwords(htmlspecialchars($student['last_name'])); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($student['grade_name'])); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($student['section_name'])); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editModal<?php echo urlencode($student['id']); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="admin-student-profile.php?id=<?php echo htmlspecialchars($student['id']); ?>" class="btn btn-info"><i class="fas fa-eye"></i></a>

                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo htmlspecialchars($student['id']); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">No students found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal for Adding a Student -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-guideco text-white">
                        <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="generated_username">ID:</label>
                                <input type="text" id="generated_username" name="generated_username" class="form-control" value="<?php echo htmlspecialchars($generated_id); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="section_id">Section:</label>
                                <select id="section_id" name="section_id" class="form-control" required>
                                    <?php foreach ($sections as $section) : ?>
                                        <option value="<?php echo htmlspecialchars($section['id']); ?>">
                                            <?php echo ucwords(htmlspecialchars($section['grade_name'])) . ' - ' . ucwords(htmlspecialchars($section['section_name'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<?php foreach ($students as $student) : ?>
    <div class="modal fade" id="deleteModal<?php echo $student['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $student['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-white">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $student['id']; ?>">Delete Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <form method="post" action="">
                        <input type="hidden" name="delete_id" value="<?php echo $student['id']; ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<!-- Edit Modal -->
<?php foreach ($students as $student) : ?>
    <div class="modal fade modal-custom" id="editModal<?php echo urlencode($student['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $student['id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-guideco text-white">
                    <h5 class="modal-title" id="editModalLabel<?php echo $student['id']; ?>">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($student['id']); ?>">
                        <div class="form-group">
                            <label for="edit_first_name">First Name:</label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['first_name'])); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_last_name">Last Name:</label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['last_name'])); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_section_id">Section:</label>
                            <select id="edit_section_id" name="section_id" class="form-control" required>
                                <?php foreach ($sections as $section) : ?>
                                    <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo $section['id'] == $student['section_id'] ? 'selected' : ''; ?>>
                                        <?php echo ucwords(htmlspecialchars($section['grade_name'])) . ' - ' . ucwords(htmlspecialchars($section['section_name'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($edit_id)) : ?>
            $('#editModal<?php echo $edit_id; ?>').modal('show');
        <?php endif; ?>
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Handle search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#studentTableBody tr');
            rows.forEach(function(row) {
                var name = row.cells[0].textContent.toLowerCase();
                row.style.display = name.indexOf(filter) > -1 ? '' : 'none';
            });
        });

        // Handle grade and section filters
        document.getElementById('gradeFilter').addEventListener('change', filterTable);
        document.getElementById('sectionFilter').addEventListener('change', filterTable);

        function filterTable() {
            var gradeFilter = document.getElementById('gradeFilter').value.toLowerCase();
            var sectionFilter = document.getElementById('sectionFilter').value.toLowerCase();
            var rows = document.querySelectorAll('#studentTableBody tr');
            rows.forEach(function(row) {
                var grade = row.cells[1].textContent.toLowerCase();
                var section = row.cells[2].textContent.toLowerCase();
                row.style.display = (gradeFilter === '' || grade.indexOf(gradeFilter) > -1) &&
                    (sectionFilter === '' || section.indexOf(sectionFilter) > -1) ? '' : 'none';
            });
        }

        // Populate edit modal with student data
        document.querySelectorAll('.editBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var row = document.querySelector(`#studentTableBody tr td button[data-id="${id}"]`).closest('tr');
                var firstName = row.cells[0].textContent.split(' ')[0];
                var lastName = row.cells[0].textContent.split(' ')[1];
                var sectionId = row.cells[2].textContent;

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_first_name').value = firstName;
                document.getElementById('edit_last_name').value = lastName;
                document.getElementById('edit_section_id').value = sectionId;
            });
        });

        // Populate delete modal with student data
        document.querySelectorAll('.deleteBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
            });
        });

        document.getElementById('printButton').addEventListener('click', function() {
            var sectionId = document.getElementById('printSection').value;
            var url = 'admin-student-print.php?section_id=' + encodeURIComponent(sectionId);
            window.location.href = url;
        });

    });
</script>