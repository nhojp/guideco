<?php
// Including necessary files and starting session
include 'conn.php';
include 'head.php';
session_start();

// Include guard-header.php for session checks and database setup
include 'guard-header.php';

// Check if user is logged in and is a guard
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['guard'])) {
    header('Location: index.php'); // Redirect if not logged in or not a guard
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
    $guard_id = $_SESSION['guard_id'];

    // Fetch the violation ID from the violation description
    $violation_query = "SELECT id FROM violation_list WHERE violation_description = ?";
    $violation_stmt = $conn->prepare($violation_query);
    $violation_stmt->bind_param("s", $violation_description);
    $violation_stmt->execute();
    $violation_stmt->bind_result($violation_id);
    $violation_stmt->fetch();
    $violation_stmt->close();

    if ($violation_id) {
        $query = "INSERT INTO violations (student_id, violation_id, guard_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $student_id, $violation_id, $guard_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success' role='alert'>Violation reported successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to report violation.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Invalid violation description.</div>";
    }
}

// Reset result set pointers
mysqli_data_seek($sections_result, 0);
mysqli_data_seek($grades_result, 0);
mysqli_data_seek($violations_result, 0);
?>

<style>
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

    @media (max-width: 767.98px) {
        .table-responsive table {
            font-size: 0.8rem;
            /* Smaller font size for table data */
        }

        .table-responsive th,
        .table-responsive td {
            padding: 0.25rem;
            /* Smaller padding for table cells */
        }

        .table-responsive .btn {
            padding: 0.25rem 0.5rem;
            /* Smaller padding for buttons */
            font-size: 0.75rem;
            /* Smaller font size for buttons */
        }
    }
</style>
<div class="container-fluid mt-2 mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3 class="p-3"><b>
                            Student List</b>
                    </h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="container-fluid p-2 rounded-lg">
                    <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="filter_grade">Filter by Grade:</label>
                    <select class="form-control" id="filter_grade" name="filter_grade">
                        <option value="">All Grades</option>
                        <?php while ($grade_row = mysqli_fetch_assoc($grades_result)) : ?>
                            <option value="<?php echo $grade_row['id']; ?>"><?php echo ucfirst($grade_row['grade_name']); ?>
                            </option>
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
                            <option value="<?php echo $section_row['id']; ?>">
                                <?php echo ucfirst($section_row['section_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table text-center table-hover table-responsive">
                <thead class="bg-dark text-white">
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
                            <td><?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?>
                            </td>
                            <td><?php echo ucfirst($row['grade_name']); ?></td>
                            <td><?php echo ucfirst($row['section_name']); ?></td>
                            <td><button class="btn btn-danger btn-block" data-toggle="modal" data-target="#reportModal" data-id="<?php echo $row['id']; ?>" data-fullname="<?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?>" data-section="<?php echo ucfirst($row['section_name']); ?>">Report</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Report Modal -->
    <div class="modal fade modal-custom" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Violation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                        <?php echo htmlspecialchars($violation_row['violation_description']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>

                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?php
include 'footer.php';
include 'teacher-footer.php'; ?>
<script>
    $('#reportModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        var fullname = button.data('fullname')
        var section = button.data('section')

        var modal = $(this)
        modal.find('.modal-body #student_id').val(id)
        modal.find('.modal-body #full_name').val(fullname)
        modal.find('.modal-body #section').val(section)
    });

    document.getElementById('violation').addEventListener('change', function() {
        var othersDetail = document.getElementById('others-detail');
        if (this.value === 'Others') {
            othersDetail.style.display = 'block';
        } else {
            othersDetail.style.display = 'none';
        }
    });

    // Filter functionality
    document.getElementById('filter_section').addEventListener('change', filterStudents);
    document.getElementById('filter_grade').addEventListener('change', filterStudents);

    function filterStudents() {
        var sectionId = document.getElementById('filter_section').value;
        var gradeId = document.getElementById('filter_grade').value;

        var rows = document.querySelectorAll('#students_table tr');
        rows.forEach(function(row) {
            var rowSectionId = row.getAttribute('data-section-id');
            var rowGradeId = row.getAttribute('data-grade-id');

            var sectionMatch = sectionId === '' || rowSectionId === sectionId;
            var gradeMatch = gradeId === '' || rowGradeId === gradeId;

            if (sectionMatch && gradeMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchInput');
        const rows = document.querySelectorAll("tbody tr");

        searchInput.addEventListener("input", function() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            rows.forEach(row => {
                const victimName = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                const complainedPerson = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

                if (victimName.includes(searchTerm) || complainedPerson.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>