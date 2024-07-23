<?php
include "head.php";
include "conn.php";

session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit;
}

include "admin-header.php";

// Fetch data for filters
$sql_sections = "SELECT id, section_name FROM sections";
$result_sections = $conn->query($sql_sections);

$sql_grades = "SELECT id, grade_name FROM grades";
$result_grades = $conn->query($sql_grades);

$sql_violations_list = "SELECT id, violation_description FROM violation_list";
$result_violations_list = $conn->query($sql_violations_list);

// Handle form submission and filtering
$section_id = isset($_POST['section']) ? intval($_POST['section']) : 0;
$grade_id = isset($_POST['grade']) ? intval($_POST['grade']) : 0;
$time_period = isset($_POST['time_period']) ? $_POST['time_period'] : 'today';
$violation_id = isset($_POST['violation']) ? intval($_POST['violation']) : 0;

$time_conditions = [
    'today' => "DATE(reported_at) = CURDATE()",
    'this_month' => "MONTH(reported_at) = MONTH(CURDATE()) AND YEAR(reported_at) = YEAR(CURDATE())",
    'this_year' => "YEAR(reported_at) = YEAR(CURDATE())"
];

$time_condition = $time_conditions[$time_period];

// SQL for total violations count
$sql_violations = "SELECT COUNT(*) AS total FROM violations 
                    JOIN students ON violations.student_id = students.id 
                    JOIN sections ON students.section_id = sections.id 
                    WHERE $time_condition";

if ($section_id > 0) {
    $sql_violations .= " AND sections.id = $section_id";
}
if ($grade_id > 0) {
    $sql_violations .= " AND sections.id IN (SELECT id FROM sections WHERE grade_id = $grade_id)";
}

if ($violation_id > 0) {
    $sql_violations .= " AND violations.violation_id = $violation_id";
}

$result_violations = $conn->query($sql_violations);
$row_violations = $result_violations->fetch_assoc();
$total_violations = $row_violations['total'];

// SQL for total violations by violation description
$sql_violations_by_type = "SELECT violation_list.violation_description, COUNT(violations.id) AS total 
                            FROM violations 
                            JOIN violation_list ON violations.violation_id = violation_list.id
                            JOIN students ON violations.student_id = students.id 
                            JOIN sections ON students.section_id = sections.id 
                            WHERE $time_condition";

if ($section_id > 0) {
    $sql_violations_by_type .= " AND sections.id = $section_id";
}
if ($grade_id > 0) {
    $sql_violations_by_type .= " AND sections.id IN (SELECT id FROM sections WHERE grade_id = $grade_id)";
}

if ($violation_id > 0) {
    $sql_violations_by_type .= " AND violations.violation_id = $violation_id";
}

$sql_violations_by_type .= " GROUP BY violation_list.violation_description 
                             ORDER BY total DESC";

$result_violations_by_type = $conn->query($sql_violations_by_type);

$violation_descriptions = [];
$violation_counts = [];

while ($row = $result_violations_by_type->fetch_assoc()) {
    $violation_descriptions[] = $row['violation_description'];
    $violation_counts[] = $row['total'];
}

// SQL for the section with the most violations
$sql_most_violations_section = "SELECT sections.id, sections.section_name, COUNT(violations.id) AS total
                                FROM violations
                                JOIN students ON violations.student_id = students.id
                                JOIN sections ON students.section_id = sections.id
                                WHERE $time_condition";

if ($section_id > 0) {
    $sql_most_violations_section .= " AND sections.id = $section_id";
}
if ($grade_id > 0) {
    $sql_most_violations_section .= " AND sections.id IN (SELECT id FROM sections WHERE grade_id = $grade_id)";
}

if ($violation_id > 0) {
    $sql_most_violations_section .= " AND violations.violation_id = $violation_id";
}

$sql_most_violations_section .= " GROUP BY sections.id
                                  ORDER BY total DESC
                                  LIMIT 1";

$result_most_violations_section = $conn->query($sql_most_violations_section);
$row_most_violations_section = $result_most_violations_section->fetch_assoc();

// SQL for the grade with the most violations
$sql_most_violations_grade = "SELECT grades.id, grades.grade_name, COUNT(violations.id) AS total
                              FROM violations
                              JOIN students ON violations.student_id = students.id
                              JOIN sections ON students.section_id = sections.id
                              JOIN grades ON sections.grade_id = grades.id
                              WHERE $time_condition";

if ($section_id > 0) {
    $sql_most_violations_grade .= " AND sections.id = $section_id";
}
if ($grade_id > 0) {
    $sql_most_violations_section .= " AND sections.id IN (SELECT id FROM sections WHERE grade_id = $grade_id)";
}

if ($violation_id > 0) {
    $sql_most_violations_grade .= " AND violations.violation_id = $violation_id";
}

$sql_most_violations_grade .= " GROUP BY grades.id
                                ORDER BY total DESC
                                LIMIT 1";

$result_most_violations_grade = $conn->query($sql_most_violations_grade);
$row_most_violations_grade = $result_most_violations_grade->fetch_assoc();

// SQL for gender distribution of violations
$sql_violations_by_gender = "SELECT students.sex, COUNT(violations.id) AS total
                             FROM violations
                             JOIN students ON violations.student_id = students.id
                             WHERE $time_condition";

if ($section_id > 0) {
    $sql_violations_by_gender .= " AND students.section_id = $section_id";
}
if ($grade_id > 0) {
    $sql_violations_by_gender .= " AND students.section_id IN (SELECT id FROM sections WHERE grade_id = $grade_id)";
}

if ($violation_id > 0) {
    $sql_violations_by_gender .= " AND violations.violation_id = $violation_id";
}

$sql_violations_by_gender .= " GROUP BY students.sex";

$result_violations_by_gender = $conn->query($sql_violations_by_gender);

$genders = [];
$gender_counts = [];

while ($row = $result_violations_by_gender->fetch_assoc()) {
    $genders[] = $row['sex'];
    $gender_counts[] = $row['total'];
}

// SQL for today's student violations
$sql_students_today = "SELECT students.first_name, students.last_name
                       FROM violations
                       JOIN students ON violations.student_id = students.id
                       WHERE DATE(violations.reported_at) = CURDATE()";

$result_students_today = $conn->query($sql_students_today);

$students_today = [];

while ($row = $result_students_today->fetch_assoc()) {
    $students_today[] = $row['first_name'] . ' ' . $row['last_name'];
}

// Fetch data for violations by type
$sql_violations_by_type = "SELECT violation_list.violation_description, COUNT(violations.id) AS total 
                            FROM violations 
                            JOIN violation_list ON violations.violation_id = violation_list.id
                            JOIN students ON violations.student_id = students.id 
                            JOIN sections ON students.section_id = sections.id 
                            WHERE $time_condition";

if ($section_id > 0) {
    $sql_violations_by_type .= " AND sections.id = $section_id";
}
if ($grade_id > 0) {
    $sql_violations_by_type .= " AND sections.grade_id = $grade_id";
}
if ($violation_id > 0) {
    $sql_violations_by_type .= " AND violations.violation_id = $violation_id";
}

$sql_violations_by_type .= " GROUP BY violation_list.violation_description 
                             ORDER BY total DESC";

$result_violations_by_type = $conn->query($sql_violations_by_type);

$violation_descriptions = [];
$violation_counts = [];

while ($row = $result_violations_by_type->fetch_assoc()) {
    $violation_descriptions[] = $row['violation_description'];
    $violation_counts[] = $row['total'];
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3><strong>Dashboard</strong></h3>
        </div>
    </div>
</div>
<div class="container bg-white m-2 rounded">
    <div class="row pt-4 pr-4 pl-4 ">
        <div class="col-md-12">
            <form id="filterForm" method="post" action="">
                <div class="row">
                    <div class="col-md-3">
                        <select class="form-control" id="section" name="section">
                            <option value="0">All Sections</option>
                            <?php while ($row = $result_sections->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php if ($row['id'] == $section_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($row['section_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="grade" name="grade">
                            <option value="0">All Grades</option>
                            <?php while ($row = $result_grades->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php if ($row['id'] == $grade_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($row['grade_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="violation" name="violation">
                            <option value="0">All Violations</option>
                            <?php while ($row = $result_violations_list->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>" <?php if ($row['id'] == $violation_id) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($row['violation_description']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="time_period" name="time_period">
                            <option value="today" <?php if ($time_period == 'today') echo 'selected'; ?>>Today</option>
                            <option value="this_month" <?php if ($time_period == 'this_month') echo 'selected'; ?>>This Month</option>
                            <option value="this_year" <?php if ($time_period == 'this_year') echo 'selected'; ?>>This Year</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container bg-white m-2 rounded">
    <div class="row p-2">
        <!-- Total Violations Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-primary text-white rounded">
                <div class="card-body">
                    <h5 class="card-title">Total Violations</h5>
                    <h2 class="card-text"><?php echo htmlspecialchars($total_violations); ?></h2>
                </div>
            </div>
        </div>

        <!-- Grade with Most Violations Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-warning text-dark rounded">
                <div class="card-body">
                    <h5 class="card-title">Grade with Most Violations</h5>
                    <?php if ($row_most_violations_grade) { ?>
                        <h2 class="card-text"><?php echo htmlspecialchars($row_most_violations_grade['total']); ?></h2>
                        <p class="card-text"><?php echo htmlspecialchars($row_most_violations_grade['grade_name']); ?></p>
                    <?php } else { ?>
                        <p class="card-text">No data available</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Section with Most Violations Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-secondary text-white rounded">
                <div class="card-body">
                    <h5 class="card-title">Section with Most Violations</h5>
                    <?php if ($row_most_violations_section) { ?>
                        <h2 class="card-text"><?php echo htmlspecialchars($row_most_violations_section['total']); ?></h2>
                        <p class="card-text"><?php echo htmlspecialchars($row_most_violations_section['section_name']); ?></p>
                    <?php } else { ?>
                        <p class="card-text">No data available</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container bg-white m-2 rounded">

    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Violations by Type</h5>
            <canvas id="violationsByTypeChart" style="height: 300px; width: 100%;"></canvas>
        </div>
        <div class="col-md-3">
            <h5>Gender Distribution</h5>
            <canvas id="violationsByGenderChart" style="height: 300px; width: 100%;"></canvas>
        </div>
        <div class="col-md-3">
            <h5><strong>Violations by Type</strong></h5>
            <canvas id="violationsPieChart" style="height: 300px; width: 100%;"></canvas>
        </div>
    </div>
</div>
<div class="container bg-white m-2 rounded">

    <div class="row mt-4">
        <!-- Students with Violations Today Carousel -->
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Students with Violations Today</h5>
                    <?php if (count($students_today) > 0) { ?>
                        <div id="studentsCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($students_today as $index => $student) { ?>
                                    <div class="carousel-item <?php if ($index == 0) echo 'active'; ?>">
                                        <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                                            <p class="text-center"><?php echo htmlspecialchars($student); ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#studentsCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#studentsCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    <?php } else { ?>
                        <p class="text-center">No students with violations today.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Violations by Type Pie Chart
    const ctxPie = document.getElementById('violationsPieChart').getContext('2d');
    const violationsPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($violation_descriptions); ?>,
            datasets: [{
                data: <?php echo json_encode($violation_counts); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        filterForm.addEventListener('change', function() {
            filterForm.submit();
        });


        const ctx1 = document.getElementById('violationsByTypeChart').getContext('2d');
        const violationsByTypeChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($violation_descriptions); ?>,
                datasets: [{
                    label: 'Violations by Type',
                    data: <?php echo json_encode($violation_counts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('violationsByGenderChart').getContext('2d');
        const violationsByGenderChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($genders); ?>,
                datasets: [{
                    label: 'Violations by Gender',
                    data: <?php echo json_encode($gender_counts); ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    });
</script>
<?php include "footer.php" ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>