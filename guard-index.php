<?php
session_start();
include 'conn.php';
include 'head.php';
include 'guard-nav.php';

$reportSuccess = false;
$errorMessage = '';

// Check if user is logged in and is a guard
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['guard'])) {
    header('Location: index.php'); // Redirect if not logged in or not a guard
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

// SQL query to fetch sections data
$sections_query = "SELECT id, section_name FROM sections";
$sections_result = mysqli_query($conn, $sections_query);

if (!$sections_result) {
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
                                echo 'Report sent successfully';
                            } elseif (!empty($errorMessage)) {
                                echo $errorMessage;
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover mt-4 border">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width:40%;">Full Name</th>
                                    <th style="width:25%;">Grade</th>
                                    <th style="width:25%;">Section</th>
                                    <th style="width:10%;" class="text-center">Report</th>
                                </tr>
                            </thead>
                            <tbody id="personnelTable">
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr data-section-id="<?php echo $row['section_id']; ?>" data-grade-level="<?php echo $row['grade_level']; ?>">
                                        <td><?php echo ucfirst($row['first_name']) . ' ' . ucfirst($row['middle_name']) . ' ' . ucfirst($row['last_name']); ?></td>
                                        <td><?php echo ucfirst($row['grade_level']); ?></td> <!-- Displaying grade_level from sections table -->
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Send Report</button>
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
    $('#reportModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var fullname = button.data('fullname');
        var section = button.data('section');

        var modal = $(this);
        modal.find('.modal-body #student_id').val(id);
        modal.find('.modal-body #full_name').val(fullname);
        modal.find('.modal-body #section').val(section);
    });

    document.getElementById('violation').addEventListener('change', function() {
        var othersDetail = document.getElementById('others-detail');
        if (this.value === 'Others') {
            othersDetail.style.display = 'block';
        } else {
            othersDetail.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.querySelectorAll('#personnelTable tr');

            rows.forEach(function(row) {
                var name = row.cells[0].innerText.toLowerCase();
                var position = row.cells[1].innerText.toLowerCase();

                if (name.includes(input) || position.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
