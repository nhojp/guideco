<?php
include 'conn.php';
include 'head.php';
include 'admin-header.php';

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

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete from students table
        $sql1 = "DELETE FROM students WHERE id = '$delete_id'";
        if (!$conn->query($sql1)) {
            throw new Exception("Error deleting from students table: " . $conn->error);
        }

        // Delete from users table
        $sql2 = "DELETE FROM users WHERE id = '$delete_id'";
        if (!$conn->query($sql2)) {
            throw new Exception("Error deleting from users table: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();
        $successMessage = "Student deleted successfully from both tables.";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        $errorMessage = $e->getMessage();
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

// Function to handle adding a student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['edit_id'])) {
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $section_id = isset($_POST['section_id']) ? $_POST['section_id'] : '';
    $username = generateId($conn);  // Generated ID
    $password = $username;  // Placeholder password, replace with actual password logic if needed

    // Check if all required POST variables are set
    if ($first_name && $last_name && $section_id) {
        // Insert into users table with ID, username, and password
        $sql1 = "INSERT INTO users (id, username, password) VALUES ('$username', '$username', '$password')";
        if ($conn->query($sql1)) {
            // Insert into students table with ID, user_id, first_name, last_name, and section_id
            $sql2 = "INSERT INTO students (id, user_id, first_name, last_name, section_id) VALUES ('$username', '$username', '$first_name', '$last_name', '$section_id')";
            if ($conn->query($sql2)) {
                // Insert into mothers and fathers tables
                $sql3 = "INSERT INTO mothers (parent_id, student_id) VALUES ('$username', '$username')";
                $sql4 = "INSERT INTO fathers (parent_id, student_id) VALUES ('$username', '$username')";

                if ($conn->query($sql3) && $conn->query($sql4)) {
                    $successMessage = "Student added successfully.";
                } else {
                    $errorMessage = "Error inserting into mothers or fathers table: " . $conn->error;
                }

                // Refresh student list
                $students = fetchStudents($conn);
            } else {
                $errorMessage = "Error inserting into students table: " . $conn->error;
            }
        } else {
            $errorMessage = "Error inserting into users table: " . $conn->error;
        }
    } else {
        $errorMessage = "Please fill in all required fields.";
    }
}


$sections = fetchSections($conn);
$students = fetchStudents($conn);

// Fetch grades for the filter dropdown
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

<style>
    .btn-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #28a745;
        /* Green color */
        color: white;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-circle:hover {
        background-color: #218838;
    }

    .modal-custom {
        margin-top: 100px;
    }

    @media (max-width: 767.98px) {
        .modal-custom {
            margin: 100px auto;
            max-width: 90%;
        }
    }

    .search {
        width: 100%;
        padding: 15px;
        border-radius: 60px;
        margin-top: 5px;
        border: none;
        background-color: lightgray;
    }
</style>

<div class="container-fluid mt-2 mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2">
        <div class="row pt-3">
            <div class="col-md-5">
                <div class="container-fluid p-2">
                    <div class="row">
                        <h3 class="p-3"><b>
                                Student List</b>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-circle mt-3" data-toggle="modal" data-target="#addStudentModal">
                    +
                </button>
            </div>
            <div class="col-md-6">
                <div class="container-fluid p-2 rounded-lg">
                    <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
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

        <div class="row pt-2 pb-2">
            <div class="col-md-6">
                <label for="gradeFilter">Filter by Grade:</label>
                <select id="gradeFilter" class="form-control">
                    <option value="">All Grades</option>
                    <?php foreach ($grades as $grade) : ?>
                        <option value="<?php echo htmlspecialchars($grade['grade_name']); ?>"><?php echo htmlspecialchars($grade['grade_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="sectionFilter">Filter by Section:</label>
                <select id="sectionFilter" class="form-control">
                    <option value="">All Sections</option>
                    <?php foreach ($sections as $section) : ?>
                        <option value="<?php echo ucwords(htmlspecialchars($section['section_name'])); ?>"><?php echo ucwords(htmlspecialchars($section['section_name'])); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <table class="table text-center table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="width:30%;">Full Name</th>
                    <th style="width:25%;">
                        Grade
                    </th>
                    <th style="width:25%;">
                        Section
                    </th>
                    <th style="width:20%;">Actions</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <?php if (count($students) > 0) : ?>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo ucwords(htmlspecialchars($student['first_name'])) . ' ' . ucwords(htmlspecialchars($student['last_name'])); ?></td>
                            <td><?php echo ucwords(htmlspecialchars($student['grade_name'])); ?></td>
                            <td><?php echo ucwords(htmlspecialchars($student['section_name'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editModal<?php echo urlencode($student['id']); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

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


        <!-- Modal for Adding a Student -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
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
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<?php foreach ($students as $student) : ?>
    <div class="modal fade" id="deleteModal<?php echo $student['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $student['id']; ?>" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
    <div class="modal fade modal-custom" id="editModal<?php echo urlencode($student['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $student['id']; ?>" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
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
                        <button type="submit" class="btn btn-primary">Submit</button>
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
    });
</script>

<?php
include "footer.php";
include "admin-footer.php";

?>