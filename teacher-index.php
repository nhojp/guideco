<?php
session_start();
include 'conn.php';
include 'head.php';
include 'teacher-nav.php';

$reportSuccess = false;
$errorMessage = '';

// Check if user is logged in and is a teacher
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['teacher'])) {
    header('Location: index.php'); // Redirect if not logged in or not a teacher
    exit;
}

// SQL query to fetch students data
$query = "
    SELECT students.id, students.first_name, students.middle_name, students.last_name, 
           students.age, students.sex, sections.id as section_id, sections.section_name, grades.id as grade_id, grades.grade_name
    FROM students
    JOIN sections ON students.section_id = sections.id
    JOIN grades ON sections.grade_id = grades.id
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch sections data
$sections_query = "SELECT id, section_name FROM sections";
$sections_result = mysqli_query($conn, $sections_query);

if (!$sections_result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch grades data
$grades_query = "SELECT id, grade_name FROM grades";
$grades_result = mysqli_query($conn, $grades_query);

if (!$grades_result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch violations data
$violations_query = "SELECT violation_description FROM violation_list";
$violations_result = mysqli_query($conn, $violations_query);

if (!$violations_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $violation_description = $_POST['violation'];
    $teacher_id = $_SESSION['teacher_id'];

    // Fetch the violation ID from the violation description
    $violation_query = "SELECT id FROM violation_list WHERE violation_description = ?";
    $violation_stmt = $conn->prepare($violation_query);
    $violation_stmt->bind_param("s", $violation_description);
    $violation_stmt->execute();
    $violation_stmt->bind_result($violation_id);
    $violation_stmt->fetch();
    $violation_stmt->close();

    if ($violation_id) {
        $query = "INSERT INTO violations (student_id, violation_id, teacher_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $student_id, $violation_id, $teacher_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $reportSuccess = true; // Set success variable to true
        } else {
            $errorMessage = "Failed to report violation.";
        }

        $stmt->close();
    } else {
        $errorMessage = "Invalid violation description.";
    }
}

// Reset result set pointers
mysqli_data_seek($sections_result, 0);
mysqli_data_seek($grades_result, 0);
mysqli_data_seek($violations_result, 0);
?>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="row">
            <div class="container-fluid mt-2 mb-5">
                <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="container-fluid p-2">
                                <h3><strong>Student List</strong></h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
                        </div>
                    </div>

                    <?php if ($reportSuccess || !empty($errorMessage)) : ?>
                        <div class="alert <?php echo $reportSuccess ? 'alert-success' : 'alert-danger'; ?> mt-4" role="alert">
                            <?php
                            if ($reportSuccess) {
                                echo 'Violation reported successfully.';
                            } elseif (!empty($errorMessage)) {
                                echo $errorMessage;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filter_grade">Filter by Grade:</label>
                                <select class="form-control" id="filter_grade" name="filter_grade">
                                    <option value="">All Grades</option>
                                    <?php while ($grade_row = mysqli_fetch_assoc($grades_result)) : ?>
                                        <option value="<?php echo $grade_row['id']; ?>"><?php echo ucfirst($grade_row['grade_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="filter_section">Filter by Section:</label>
                                <select class="form-control" id="filter_section" name="filter_section">
                                    <option value="">All Sections</option>
                                    <?php while ($section_row = mysqli_fetch_assoc($sections_result)) : ?>
                                        <option value="<?php echo $section_row['id']; ?>"><?php echo ucfirst($section_row['section_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mt-4 border">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:40%;">Full Name</th>
                                    <th style="width:25%;">Grade</th>
                                    <th style="width:25%;">Section</th>
                                    <th style="width:10%;">Report</th>
                                </tr>
                            </thead>
                            <tbody id="students_table">
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr data-section-id="<?php echo $row['section_id']; ?>" data-grade-id="<?php echo $row['grade_id']; ?>">
                                        <td><?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?></td>
                                        <td><?php echo ucfirst($row['grade_name']); ?></td>
                                        <td><?php echo ucfirst($row['section_name']); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal" data-id="<?php echo $row['id']; ?>" data-fullname="<?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?>" data-section="<?php echo ucfirst($row['section_name']); ?>">Report</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Report Modal -->
                <div class="modal fade modal-custom" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true" data-backdrop="false">
                    <div class="modal-dialog modal-custom" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-guideco text-white">
                                <h5 class="modal-title" id="reportModalLabel">Report Violation</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="student_id" id="student_id">
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="section">Section</label>
                                        <input type="text" class="form-control" id="section" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="violation">Violation</label>
                                        <select class="form-control" id="violation" name="violation">
                                            <?php while ($violation_row = mysqli_fetch_assoc($violations_result)) : ?>
                                                <option value="<?php echo htmlspecialchars($violation_row['violation_description']); ?>">
                                                    <?php echo ucwords(htmlspecialchars($violation_row['violation_description'])); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Send Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include 'footer.php';
?>
<script>
    $('#reportModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var studentId = button.data('id');
        var fullName = button.data('fullname');
        var section = button.data('section');

        var modal = $(this);
        modal.find('#student_id').val(studentId);
        modal.find('#full_name').val(fullName);
        modal.find('#section').val(section);
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#students_table tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Filter functionality
    $('#filter_grade, #filter_section').on('change', function() {
        var gradeId = $('#filter_grade').val();
        var sectionId = $('#filter_section').val();

        $("#students_table tr").each(function() {
            var rowGradeId = $(this).data('grade-id');
            var rowSectionId = $(this).data('section-id');

            $(this).toggle(
                (gradeId === "" || gradeId == rowGradeId) &&
                (sectionId === "" || sectionId == rowSectionId)
            );
        });
    });
</script>
