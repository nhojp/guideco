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
           students.age, students.sex, sections.id as section_id, sections.section_name, sections.grade_level
    FROM students
    JOIN sections ON students.section_id = sections.id
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// SQL query to fetch sections data (only grades 11 and 12)
$sections_query = "
    SELECT id, section_name, grade_level FROM sections WHERE grade_level IN ('11', '12')
";
$sections_result = mysqli_query($conn, $sections_query);

if (!$sections_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch grades directly from the sections
$grades = ['11', '12']; // Hardcoded since we are filtering only grades 11 and 12

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
mysqli_data_seek($violations_result, 0);
?>

<style>
    .btn-custom {
        background-color: #1F5F1E; 
        color: white; 
        border: none; 
    }

    .btn-custom:hover {
        background-color: #389434; 
        color: white; 
    }

    .btn-custom:focus, .btn-custom:active {
        box-shadow: none; 
        outline: none; 
    }

    .thead-custom {
        background-color: #0C2D0B;
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

    .table-container {
        max-height: 400px; 
        overflow-y: auto; 
    }
</style>

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
                                    <?php foreach ($grades as $grade) : ?>
                                        <option value="<?php echo $grade; ?>"><?php echo ucfirst($grade); ?></option>
                                    <?php endforeach; ?>
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

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mt-4 border">
                                <thead class="thead-custom">
                                    <tr>
                                        <th style="width:40%;">Full Name</th>
                                        <th style="width:25%;">Grade</th>
                                        <th style="width:25%;">Section</th>
                                        <th style="width:10%;">Report</th>
                                    </tr>
                                </thead>
                                <tbody id="students_table">
                                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                        <tr data-section-id="<?php echo $row['section_id']; ?>" data-grade-id="<?php echo $row['grade_level']; ?>">
                                            <td><?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?></td>
                                            <td><?php echo ucfirst($row['grade_level']); ?></td>
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
                                <div class="modal-header text-white" style="background-color: #1F5F1E;">
                                    <h5 class="modal-title" id="reportModalLabel">Report Violation</h5>
                                    <button type="button" class="btn-danger btn btn btn-circle" data-bs-dismiss="modal" aria-label="Close">
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
                                            <label for="violation">Select Violation</label>
                                            <select class="form-control" name="violation" id="violation">
                                                <?php while ($violation_row = mysqli_fetch_assoc($violations_result)) : ?>
                                                    <option value="<?php echo $violation_row['violation_description']; ?>"><?php echo ucfirst($violation_row['violation_description']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Report Violation</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End of Report Modal -->
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Filter functionality for the students table
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const filterGrade = document.getElementById("filter_grade");
        const filterSection = document.getElementById("filter_section");
        const studentsTable = document.getElementById("students_table");

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const gradeValue = filterGrade.value;
            const sectionValue = filterSection.value;

            Array.from(studentsTable.getElementsByTagName("tr")).forEach(row => {
                const name = row.children[0].textContent.toLowerCase();
                const grade = row.dataset.gradeId;
                const section = row.dataset.sectionId;

                const matchesSearch = name.includes(searchValue);
                const matchesGrade = gradeValue === "" || grade === gradeValue;
                const matchesSection = sectionValue === "" || section === sectionValue;

                if (matchesSearch && matchesGrade && matchesSection) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        searchInput.addEventListener("input", filterTable);
        filterGrade.addEventListener("change", filterTable);
        filterSection.addEventListener("change", filterTable);

        // Fill the modal with student details
        $('#reportModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            const studentId = button.data('id'); // Extract info from data-* attributes
            const fullName = button.data('fullname');
            const sectionName = button.data('section');

            const modal = $(this);
            modal.find('#student_id').val(studentId);
            modal.find('#full_name').val(fullName);
        });
    });
</script>

<?php
include 'footer.php';
?>
