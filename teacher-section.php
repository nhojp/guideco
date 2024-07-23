<?php
// Including the necessary files
include 'conn.php';
include 'head.php';
// Start the session
session_start();

include 'teacher-header.php';

// Check if user is logged in and is a teacher
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['teacher'])) {
    header('Location: index.php'); // Redirect if not logged in or not a teacher
    exit;
}

// Get the teacher_id from the session
$teacher_id = $_SESSION['teacher_id'];

// SQL query to fetch section and grade details for the current teacher
$section_query = "
    SELECT sections.id as section_id, sections.section_name, grades.grade_name
    FROM sections
    JOIN grades ON sections.grade_id = grades.id
    WHERE sections.teacher_id = ?
";
$section_stmt = $conn->prepare($section_query);
$section_stmt->bind_param("i", $teacher_id);
$section_stmt->execute();
$section_result = $section_stmt->get_result();

if (!$section_result) {
    die("Query failed: " . mysqli_error($conn));
}

$section_data = $section_result->fetch_assoc();

if (!$section_data) {
    die("No section found for this teacher.");
}

// SQL query to fetch students data for the current section
$query = "
    SELECT students.id, students.first_name, students.middle_name, students.last_name, 
           students.age, students.sex, sections.id as section_id, sections.section_name, grades.id as grade_id, grades.grade_name
    FROM students
    JOIN sections ON students.section_id = sections.id
    JOIN grades ON sections.grade_id = grades.id
    WHERE sections.id = ?
";
$student_stmt = $conn->prepare($query);
$student_stmt->bind_param("i", $section_data['section_id']);
$student_stmt->execute();
$result = $student_stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch sections data (if needed elsewhere)
$sections_query = "SELECT id, section_name FROM sections";
$sections_result = mysqli_query($conn, $sections_query);

if (!$sections_result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch grades data (if needed elsewhere)
$grades_query = "SELECT id, grade_name FROM grades";
$grades_result = mysqli_query($conn, $grades_query);

if (!$grades_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $violation = $_POST['violation'];
    $teacher_id = $_SESSION['teacher_id'];

    $query = "INSERT INTO violations (student_id, violation, teacher_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $student_id, $violation, $teacher_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<div class='alert alert-success' role='alert'>Violation reported successfully.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Failed to report violation.</div>";
    }

    $stmt->close();
}
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
            margin-top: 100px;
            max-width: 100%;
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
            <div class="col-md-5">
                <div class="container-fluid p-2">
                    <h3 class="p-3"><b>
                            <?php echo ucfirst($section_data['grade_name']) . ' - ' . ucfirst($section_data['section_name']); ?>
                        </b>
                    </h3>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-circle mt-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    +
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="admin-s-1.php" onclick="showLoading('admin-p-1.php')">Add Student</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="container-fluid p-2 rounded-lg">
                    <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-center table-hover" style="width: 100%;">
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
                            <td><?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['last_name']); ?>
                            </td>
                            <td><?php echo ucfirst($row['grade_name']); ?></td>
                            <td><?php echo ucfirst($row['section_name']); ?></td>
                            <td><button class="btn btn-danger btn-block" data-toggle="modal" data-target="#reportModal" data-id="<?php echo $row['id']; ?>" data-fullname="<?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?>" data-section="<?php echo ucfirst($row['section_name']); ?>">Report</button></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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
                            <option value="Over the Bakod">Over the Bakod</option>
                            <option value="Wearing Earring">Wearing Earring</option>
                            <option value="Improper Uniform">Improper Uniform</option>
                            <option value="Improper Haircut">Improper Haircut</option>
                            <option value="Cutting Classes">Cutting Classes</option>
                            <option value="Bullying">Bullying</option>
                            <option value="Cheating">Cheating</option>
                            <option value="Disrespect to Teachers">Disrespect to Teachers</option>
                            <option value="Littering">Littering</option>
                            <option value="Smoking">Smoking</option>
                            <option value="Vandalism">Vandalism</option>
                            <option value="Others">Others</option>
                        </select>
                        <div class="form-group mt-2" id="others-detail" style="display:none;">
                            <label for="violation_detail">Please specify:</label>
                            <input type="text" class="form-control" id="violation_detail" name="violation_detail">
                        </div>
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

<?php include 'footer.php'; ?>
<?php include 'teacher-footer.php'; ?>

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