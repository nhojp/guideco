<?php
include 'conn.php'; // Use conn.php for your database connection

// Function to check if username already exists in any user table
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

// Handle student creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {

    // Get user details from the form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
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
        $stmt = $conn->prepare("INSERT INTO students (user_id, first_name, last_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $first_name, $last_name);
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

// Handle Filters and Pagination
$filter_strand = isset($_GET['strand']) ? intval($_GET['strand']) : '';
$filter_section = isset($_GET['section']) ? intval($_GET['section']) : '';
$filter_grade = isset($_GET['grade']) ? intval($_GET['grade']) : '';

// Pagination parameters
$limit = 40;
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

$filter_school_year = isset($_GET['school_year']) ? intval($_GET['school_year']) : '';

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

// Fetch existing students with related data based on filters and pagination
$data_sql = "SELECT s.id, u.username, s.first_name, s.last_name, 
           st.name AS strand_name, sec.section_name, sec.grade_level AS grade,
           CONCAT(sec.grade_level, ' - ', st.name, ' - ', sec.section_name) AS section_display,
           CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
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


$school_years_result = $conn->query("SELECT id, CONCAT(year_start, ' - ', year_end) AS year_display FROM school_year ORDER BY year_start DESC");
$school_years = $school_years_result->fetch_all(MYSQLI_ASSOC);

$teachers = $conn->query("SELECT * FROM teachers");
include "head.php";
?>

<body>
    <div class="container mt-4">
        <h1>Add Student</h1>

        <!-- Display Success or Error Messages -->
        <?php
        // Success and error messages are already echoed in the PHP above
        ?>

        <!-- Buttons for Adding and Importing -->
        <div class="mb-3">
            <button class="btn btn-success" onclick="window.location.href='admin-user-student-single.php';">Add Student</button>
            <button class="btn btn-success" onclick="window.location.href='a1-excel2.php';">Import from Excel</button>
        </div>

        <!-- Buttons for Adding Strands, Sections, and Teachers -->
        <div class="mb-3">
            <button class="btn btn-info" onclick="window.location.href='admin-nav-strands.php?type=strand';">Add Strand</button>
            <button class="btn btn-warning" onclick="window.location.href='admin-nav-sections.php?type=section';">Add Section</button>
            <button class="btn btn-secondary" onclick="window.location.href='admin-user-teacher.php?type=teacher';">Add Teacher</button>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="mb-4" id="filterForm">
            <div class="form-row">
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
                                <?php echo htmlspecialchars($section['section_display']); ?>
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

                <div class="form-group col-md-3">
                    <label for="school_year">Filter by School Year</label>
                    <select name="school_year" id="school_year" class="form-control" onchange="this.form.submit()">
                        <option value="">All School Years</option>
                        <?php foreach ($school_years as $school_year): ?>
                            <option value="<?php echo $school_year['id']; ?>" <?php if ($filter_school_year == $school_year['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($school_year['year_display']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear Filters</button>
        </form>

        <script>
            function clearFilters() {
                // Reset the form
                document.getElementById("filterForm").reset();
                // Submit the form to apply the cleared filters
                document.getElementById("filterForm").submit();
            }
        </script>

        <!-- Data Indicator -->
        <div class="mb-2">
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

        <!-- Students Table within a Responsive Container -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Strand</th>
                        <th>Section</th>
                        <th>Grade</th>
                        <th>Teacher</th>
                        <th>Actions</th> <!-- Added Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['username']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['strand_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['section_display']); ?></td>
                                <td><?php echo htmlspecialchars($student['grade']); ?></td>
                                <td><?php echo htmlspecialchars($student['teacher_name']); ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm" onclick="viewStudent(<?php echo $student['id']; ?>)">View</button>

                                    <button class="btn btn-warning btn-sm" onclick="editStudent(<?php echo $student['id']; ?>)">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?php echo $student['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No students found.</td>
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
                        <a class="page-link" href="?page=<?= $i ?>&strand=<?= $filter_strand ?>&section=<?= $filter_section ?>&grade=<?= $filter_grade ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>


        <!-- Add Student Modal -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Student</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
<script>
    function viewStudent(studentId) {
    // Redirect to the student detail view page
    window.location.href = 'admin-student-profile.php?id=' + studentId; // Adjust the page URL as necessary
}

</script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>