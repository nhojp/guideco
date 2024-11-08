<?php
include 'conn.php';

// Function to check if a username exists across multiple tables
function usernameExists($conn, $username)
{
    // Array of tables to check
    $tables = ['users', 'admin', 'principal', 'teachers', 'guards'];

    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM $table WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return true; // Username exists
        }
    }

    return false; // Username does not exist
}

// Handle Filters and Pagination
$filter_strand = isset($_GET['strand']) ? intval($_GET['strand']) : '';
$filter_section = isset($_GET['section']) ? intval($_GET['section']) : '';
$filter_grade = isset($_GET['grade']) ? intval($_GET['grade']) : '';
$filter_school_year = isset($_GET['school_year']) ? intval($_GET['school_year']) : '';

// Pagination parameters
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Build the WHERE clause based on filters
$where_clauses = [];
$params = [];
$types = '';

if ($filter_strand) {
    $where_clauses[] = "st.id = ?";
    $params[] = $filter_strand;
    $types .= 'i';
}

if ($filter_section) {
    $where_clauses[] = "sec.id = ?";
    $params[] = $filter_section;
    $types .= 'i';
}

if ($filter_grade && in_array($filter_grade, [11, 12])) {
    $where_clauses[] = "sec.grade_level = ?";
    $params[] = $filter_grade;
    $types .= 'i';
}

if ($filter_school_year) {
    $where_clauses[] = "sa.school_year_id = ?";
    $params[] = $filter_school_year;
    $types .= 'i';
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Get total count for pagination
$count_sql = "SELECT COUNT(*) FROM students s 
            JOIN users u ON s.user_id = u.id
            JOIN section_assignment sa ON s.id = sa.student_id
            JOIN sections sec ON sa.section_id = sec.id
            JOIN strands st ON sec.strand_id = st.id
            JOIN teachers t ON sa.teacher_id = t.id
            $where_sql";

$count_stmt = $conn->prepare($count_sql);
if ($count_stmt) {
    if ($types) {
        $count_stmt->bind_param($types, ...$params);
    }
    $count_stmt->execute();
    $count_stmt->bind_result($total_records);
    $count_stmt->fetch();
    $count_stmt->close();
} else {
    throw new Exception("Prepare statement failed: " . $conn->error);
}

// Fetch students data
$data_sql = "SELECT s.id, u.username, s.first_name, s.last_name, s.section_id,
                st.name AS strand_name, sec.section_name, sec.grade_level AS grade,
                CONCAT(sec.grade_level, ' - ', st.name, ' - ', sec.section_name) AS section_display,
                CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
                sa.school_year_id
            FROM students s 
            JOIN users u ON s.user_id = u.id
            JOIN section_assignment sa ON s.id = sa.student_id
            JOIN sections sec ON sa.section_id = sec.id
            JOIN strands st ON sec.strand_id = st.id
            JOIN teachers t ON sa.teacher_id = t.id
            $where_sql
            ORDER BY s.id ASC
            LIMIT ? OFFSET ?";

$data_stmt = $conn->prepare($data_sql);
if ($data_stmt) {
    if ($types) {
        $types_with_limit = $types . 'ii';
        $params_with_limit = array_merge($params, [$limit, $offset]);
        $data_stmt->bind_param($types_with_limit, ...$params_with_limit);
    } else {
        $data_stmt->bind_param("ii", $limit, $offset);
    }

    $data_stmt->execute();
    $data_result = $data_stmt->get_result();
    $students = $data_result->fetch_all(MYSQLI_ASSOC);
    $data_stmt->close();
} else {
    throw new Exception("Prepare statement failed: " . $conn->error);
}

// Fetch sections and strands for dropdowns
$sections_result = $conn->query("SELECT s.id, CONCAT(s.grade_level, ' - ', st.name, ' - ', s.section_name) AS section_display 
            FROM sections s 
            JOIN strands st ON s.strand_id = st.id
            ORDER BY st.name, s.section_name ASC");
$sections = $sections_result->fetch_all(MYSQLI_ASSOC);

$strands_result = $conn->query("SELECT id, name FROM strands ORDER BY name ASC");
$strands = $strands_result->fetch_all(MYSQLI_ASSOC);

$grades = [11, 12];

$school_years_result = $conn->query("SELECT id, CONCAT(year_start, ' - ', year_end) AS year_display FROM school_year ORDER BY year_start asc");
$school_years = $school_years_result->fetch_all(MYSQLI_ASSOC);

$teachers = $conn->query("SELECT * FROM teachers");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Add Student
    if (isset($_POST['add_student'])) {
        // Get user details from the form
        $username = trim($_POST['username']);
        $password = $_POST['password']; // Plain password (will be hashed)
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $section_id = intval($_POST['section_id']);
        $school_year_id = intval($_POST['school_year_id']);

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Check if username exists
            if (usernameExists($conn, $username)) {
                throw new Exception("Username already exists.");
            }

            // Hash the password
            $hashed_password = $password;

            // Insert into the users table
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();
            $user_id = $stmt->insert_id; // Get the last inserted user ID
            $stmt->close();

            // Insert into the students table
            $stmt = $conn->prepare("INSERT INTO students (user_id, first_name, last_name, section_id) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("issi", $user_id, $first_name, $last_name, $section_id);
            $stmt->execute();
            $student_id = $stmt->insert_id; // Get the last inserted student ID
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

            // Get the teacher_id from the sections table
            $stmt = $conn->prepare("SELECT teacher_id FROM sections WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
            $stmt->bind_result($teacher_id);
            if (!$stmt->fetch()) {
                throw new Exception("No teacher found for the selected section.");
            }
            $stmt->close();

            // Insert into the section_assignment table
            $stmt = $conn->prepare("INSERT INTO section_assignment (student_id, teacher_id, section_id, school_year_id) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("iiii", $student_id, $teacher_id, $section_id, $school_year_id);
            $stmt->execute();
            $stmt->close();

            // Commit the transaction
            $conn->commit();
            echo "<div class='alert alert-success'>Student added successfully!</div>";
            echo '<script>
        window.location.href = "admin-user-student.php"; // Replace with the desired page
    </script>';
        } catch (Exception $e) {
            $conn->rollback(); // Rollback the transaction on error
            echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }

    // Handle the edit functionality
    if (isset($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $section_id = $_POST['section_id'];

        // Update the student record
        $query = "UPDATE students SET first_name = ?, last_name = ?, section_id = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssii', $first_name, $last_name, $section_id, $edit_id);

        if ($stmt->execute()) {
            // Redirect or display a success message
            echo '<div class="alert alert-success">Student updated successfully!</div>';
            echo '<script>
        window.location.href = "admin-user-student.php"; // Replace with the desired page
    </script>';
        } else {
            echo '<div class="alert alert-danger">Failed to update student!</div>';
        }
    }

    // Handle the delete functionality
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        // Delete the student record
        $query = "DELETE FROM students WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $delete_id);

        if ($stmt->execute()) {
            // Redirect or display a success message
            echo '<div class="alert alert-success">Student deleted successfully!</div>';
            echo '<script>
        window.location.href = "admin-user-student.php"; // Replace with the desired page
    </script>';
        } else {
            echo '<div class="alert alert-danger">Failed to delete student!</div>';
        }
    }
}

// Display students table
?>

<body>
    <div class="container-fluid mb-5">
        <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
            <div class="row pt-3">
                <div class="col-md-6">
                    <div class="container-fluid p-2">
                        <h3><strong>Students</strong></h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="admin-search.php" method="GET">
                        <input type="text" name="search_query" class="form-control" placeholder="Search Students" required>
                    </form>

                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" class="mb-4" id="filterForm">
                <div class="filter-list mb-4">
                    <div class="form-row d-flex align-items-end">
                        <!-- Strand Filter -->
                        <div class="form-group col-md-3">
                            <label for="strand">Filter by Strand</label>
                            <select name="strand" id="strand" class="form-control" onchange="this.form.submit()">
                                <option value="">All Strands</option>
                                <?php foreach ($strands as $strand): ?>
                                    <option value="<?php echo $strand['id']; ?>" <?php if ($filter_strand == $strand['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($strand['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Section Filter -->
                        <div class="form-group col-md-3">
                            <label for="section">Filter by Section</label>
                            <select name="section" id="section" class="form-control" onchange="this.form.submit()">
                                <option value="">All Sections</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?php echo $section['id']; ?>" <?php if ($filter_section == $section['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars(ucwords($section['section_display'])); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Grade Filter -->
                        <div class="form-group col-md-3">
                            <label for="grade">Filter by Grade</label>
                            <select name="grade" id="grade" class="form-control" onchange="this.form.submit()">
                                <option value="">All Grades</option>
                                <?php foreach ($grades as $grade): ?>
                                    <option value="<?php echo $grade; ?>" <?php if ($filter_grade == $grade) echo 'selected'; ?>>
                                        Grade <?php echo $grade; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- School Year Filter -->
                        <div class="form-group col-md-3">
                            <label for="school_year">Filter by School Year</label>
                            <select name="school_year" id="school_year" class="form-control" onchange="this.form.submit()">
                                <option value="">All School Years</option>
                                <?php foreach ($school_years as $year): ?>
                                    <option value="<?php echo $year['id']; ?>" <?php if ($filter_school_year == $year['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($year['year_display']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

            </form>
            <!-- Display Record Count -->
            <div class="mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $start = $offset + 1;
                        $end = min($offset + $limit, $total_records);
                        if ($total_records > 0) {
                            echo "Showing $start to $end of $total_records entries.";
                        } else {
                            echo "No records found.";
                        }
                        ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success pr-4 pl-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Add
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" data-toggle="modal" data-target="#addStudentModal">Add a student</a></li>
                                <li><a class="dropdown-item" href="a1-excel2.php">Import from Excel</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="table-container">
                <table class="table table-hover mt-4 border text-center">
                    <thead style="background-color: #0C2D0B; color:white;">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Grade & Section</th>
                            <th>Teacher</th>
                            <th>Actions</th> <!-- Actions column -->
                        </tr>
                    </thead>
                    <tbody id="studentTable">
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(ucwords($student['first_name'])); ?></td>
                                    <td><?php echo htmlspecialchars(ucwords($student['last_name'])); ?></td>
                                    <td><?php echo htmlspecialchars(ucwords($student['section_display'])); ?></td>
                                    <td><?php echo htmlspecialchars(ucwords($student['teacher_name'])); ?></td>
                                    <td>
                                        <button class="btn btn-info " onclick="viewStudent(<?php echo $student['id']; ?>)"><i class="fas fa-eye"></i></button>

                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#editModal<?php echo $student['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#deleteModal<?php echo $student['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade modal-custom" id="editModal<?php echo $student['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $student['id']; ?>" aria-hidden="true">
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
                                                        <label for="edit_first_name<?php echo $student['id']; ?>">First Name:</label>
                                                        <input type="text" id="edit_first_name<?php echo $student['id']; ?>" name="first_name" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['first_name'])); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_last_name<?php echo $student['id']; ?>">Last Name:</label>
                                                        <input type="text" id="edit_last_name<?php echo $student['id']; ?>" name="last_name" class="form-control" value="<?php echo ucwords(htmlspecialchars($student['last_name'])); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_section_id<?php echo $student['id']; ?>">Section:</label>
                                                        <select id="edit_section_id<?php echo $student['id']; ?>" name="section_id" class="form-control" required>
                                                            <?php foreach ($sections as $section): ?>
                                                                <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo $section['id'] == $student['section_id'] ? 'selected' : ''; ?>>
                                                                    <?php echo ucwords(htmlspecialchars($section['section_display'])); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $student['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $student['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel<?php echo $student['id']; ?>">Delete Student</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($student['id']); ?>">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $total_pages = ceil($total_records / $limit);
                    for ($i = 1; $i <= $total_pages; $i++) :
                    ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&strand=<?= $filter_strand ?>&section=<?= $filter_section ?>&grade=<?= $filter_grade ?>&school_year=<?= $filter_school_year ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #0C2D0B;">
                    <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                    <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="studentForm">
                        <input type="hidden" name="add_student" value="1"> <!-- Hidden field to identify add student -->
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" required class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" required class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="first_name" placeholder="First Name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" placeholder="Last Name" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="section_id">Select Section:</label>
                            <select name="section_id" id="section_id" required class="form-control">
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?php echo $section['id']; ?>"><?php echo ucwords($section['section_display']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="school_year_id">Select School Year:</label>
                            <select name="school_year_id" id="school_year_id" required class="form-control">
                                <?php foreach ($school_years as $year): ?>
                                    <option value="<?php echo $year['id']; ?>"><?php echo $year['year_display']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" style="width: 100%;">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to search the table
        function searchTable() {
            // Get the value of the input field
            var input = document.getElementById("searchInput").value.toLowerCase();
            // Get the table body and all the rows
            var table = document.getElementById("studentTable");
            var rows = table.getElementsByTagName("tr");

            // Loop through all the rows in the table
            for (var i = 0; i < rows.length; i++) {
                var username = rows[i].getElementsByTagName("td")[0];
                var firstName = rows[i].getElementsByTagName("td")[1];
                var lastName = rows[i].getElementsByTagName("td")[2];
                var section = rows[i].getElementsByTagName("td")[3];
                var teacher = rows[i].getElementsByTagName("td")[4];

                if (username || firstName || lastName || section || teacher) {
                    // Check if the text in any of the columns matches the search input
                    var usernameValue = username.textContent.toLowerCase();
                    var firstNameValue = firstName.textContent.toLowerCase();
                    var lastNameValue = lastName.textContent.toLowerCase();
                    var sectionValue = section.textContent.toLowerCase();
                    var teacherValue = teacher.textContent.toLowerCase();

                    if (usernameValue.includes(input) || firstNameValue.includes(input) ||
                        lastNameValue.includes(input) || sectionValue.includes(input) ||
                        teacherValue.includes(input)) {
                        // If a match is found, display the row
                        rows[i].style.display = "";
                    } else {
                        // Otherwise, hide the row
                        rows[i].style.display = "none";
                    }
                }
            }
        }

        // Function to view student details
        function viewStudent(studentId) {
            // Redirect to the student detail view page
            window.location.href = 'admin-student-profile.php?id=' + studentId; // Adjust the page URL as necessary
        }

        // Automatically show the relevant modal if there was an error or success during edit/delete
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_POST['edit_id'])) {
                $edit_id = intval($_POST['edit_id']);
                echo "$('#editModal{$edit_id}').modal('show');";
            }
            if (isset($_POST['delete_id'])) {
                $delete_id = intval($_POST['delete_id']);
                echo "$('#deleteModal{$delete_id}').modal('show');";
            }
            ?>
        });
    </script>
</body>

</html>
