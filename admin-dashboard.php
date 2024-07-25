<?php
// Get filters
$violationType = $_GET['violationType'] ?? '';
$grade = $_GET['grade'] ?? '';
$section = $_GET['section'] ?? '';
$timePeriod = $_GET['timePeriod'] ?? 'thisYear';
$sex = $_GET['sex'] ?? '';

// Date filter logic
$currentDate = date('Y-m-d');
if ($timePeriod == 'thisDay') {
    $startDate = $currentDate;
    $endDate = $currentDate . ' 23:59:59';
} elseif ($timePeriod == 'thisMonth') {
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-t') . ' 23:59:59';
} else {
    $startDate = date('Y-01-01');
    $endDate = date('Y-12-31') . ' 23:59:59';
}

// Fetch violations with all filters applied
$query = "SELECT v.id, v.student_id, v.reported_at, v.guard_id, v.teacher_id, v.violation_id, s.first_name, s.last_name, s.sex 
          FROM violations v
          JOIN students s ON v.student_id = s.id
          JOIN sections sec ON s.section_id = sec.id
          JOIN grades g ON sec.grade_id = g.id
          WHERE v.reported_at BETWEEN '$startDate' AND '$endDate'";

if ($violationType) {
    $query .= " AND v.violation_id = '$violationType'";
}
if ($grade) {
    $query .= " AND g.id = '$grade'";
}
if ($section) {
    $query .= " AND sec.id = '$section'";
}
if ($sex) {
    $query .= " AND s.sex = '$sex'";
}

$result = mysqli_query($conn, $query);
$violations = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch filters data
$violationListResult = mysqli_query($conn, "SELECT * FROM violation_list");
$violationList = mysqli_fetch_all($violationListResult, MYSQLI_ASSOC);

$gradesResult = mysqli_query($conn, "SELECT * FROM grades");
$grades = mysqli_fetch_all($gradesResult, MYSQLI_ASSOC);

$sectionsResult = mysqli_query($conn, "SELECT * FROM sections");
$sections = mysqli_fetch_all($sectionsResult, MYSQLI_ASSOC);

// Group data for charts
$totalViolations = count($violations);
$violationsByType = array_count_values(array_column($violations, 'violation_id'));
$violationsByGender = array_count_values(array_column($violations, 'sex'));
$studentsWithViolationsToday = array_filter($violations, function ($violation) use ($currentDate) {
    return strpos($violation['reported_at'], $currentDate) === 0;
});

// SQL for today's student violations with violation details
$sql_students_today = "
    SELECT students.first_name, students.last_name, violations.violation_id, violations.reported_at, violation_list.violation_description
    FROM violations
    JOIN students ON violations.student_id = students.id
    JOIN violation_list ON violations.violation_id = violation_list.id
    WHERE DATE(violations.reported_at) = CURDATE()
";

$result_students_today = $conn->query($sql_students_today);

$students_today = [];

while ($row = $result_students_today->fetch_assoc()) {
    $students_today[] = [
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'violation_description' => $row['violation_description'],
        'reported_at' => $row['reported_at']
    ];
}

// Fetch top violators
$topViolatorsQuery = "
    SELECT s.first_name, s.last_name, COUNT(v.id) AS violation_count
    FROM violations v
    JOIN students s ON v.student_id = s.id
    WHERE v.reported_at BETWEEN '$startDate' AND '$endDate'
    GROUP BY s.id
    ORDER BY violation_count DESC
    LIMIT 5
";
$topViolatorsResult = mysqli_query($conn, $topViolatorsQuery);
$topViolators = mysqli_fetch_all($topViolatorsResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <!-- Include Bootstrap from head.php -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterForm = document.getElementById('filterForm');
            filterForm.querySelectorAll('select').forEach(function(select) {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        });
    </script>
</head>

<body style="background-color: #DBD8AE;">

    <div class="container-fluid mb-5 ">
        <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
            <div class="row pt-3">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <div class="container-fluid p-2">
                        <h3><strong>DASHBOARD</strong></h3>
                    </div>
                    <a href="admin-schedule.php" class="btn btn-outline-success">Schedules</a>
                </div>
            </div>



            <div class="row">
                <div class="col-md-2">
                    <form id="filterForm" class="mb-4">
                        <div class="form-row p-2 rounded border">
                            <div class="form-group" style="width:100%">
                                <label for="violationType">Violation Type</label>
                                <select class="form-control" id="violationType" name="violationType">
                                    <option value="">All</option>
                                    <?php foreach ($violationList as $violation) : ?>
                                        <option value="<?= $violation['id'] ?>" <?= $violation['id'] == $violationType ? 'selected' : '' ?>>
                                            <?= ucwords($violation['violation_description']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row p-2 rounded mt-2 border">
                            <div class="form-group" style="width:100%">
                                <label for="grade">Grade</label>
                                <select class="form-control" id="grade" name="grade">
                                    <option value="">All</option>
                                    <?php foreach ($grades as $gradeItem) : ?>
                                        <option value="<?= $gradeItem['id'] ?>" <?= $gradeItem['id'] == $grade ? 'selected' : '' ?>>
                                            <?= ucwords($gradeItem['grade_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row p-2 rounded mt-2 border">
                            <div class="form-group" style="width:100%">
                                <label for="section">Section</label>
                                <select class="form-control" id="section" name="section">
                                    <option value="">All</option>
                                    <?php foreach ($sections as $sectionItem) : ?>
                                        <option value="<?= $sectionItem['id'] ?>" <?= $sectionItem['id'] == $section ? 'selected' : '' ?>>
                                            <?= ucwords($sectionItem['section_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row p-2 rounded mt-2 border">
                            <div class="form-group" style="width:100%">
                                <label for="timePeriod">Time Period</label>
                                <select class="form-control" id="timePeriod" name="timePeriod">
                                    <option value="thisYear" <?= $timePeriod == 'thisYear' ? 'selected' : '' ?>>This Year</option>
                                    <option value="thisMonth" <?= $timePeriod == 'thisMonth' ? 'selected' : '' ?>>This Month</option>
                                    <option value="thisDay" <?= $timePeriod == 'thisDay' ? 'selected' : '' ?>>This Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row p-2 rounded mt-2 border">
                            <div class="form-group" style="width:100%">
                                <label for="sex">Sex</label>
                                <select class="form-control" id="sex" name="sex">
                                    <option value="">All</option>
                                    <option value="Male" <?= $sex == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $sex == 'Female' ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-10">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="container p-2 pt-3 rounded border bg-guideco text-white">
                                <h4><strong>Total Violations : <?= $totalViolations ?></strong></h4>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4>Violations by Type</h4>
                            <div class="container p-2 rounded border">
                                <canvas id="violationsBarChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4>Gender of Violators</h4>
                            <div class="container p-2 rounded border">
                                <canvas id="violationsPieChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Violators Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Violators Today</h4>
                            <div class="container bg-light p-2 rounded border">
                                <?php if (count($students_today) > 0) { ?>
                                    <div id="studentsCarousel" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php foreach ($students_today as $index => $student) { ?>
                                                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
                                                        <h4><strong><?= ucwords(htmlspecialchars($student['name'])) ?></strong></h4><br>

                                                        <p class="text-center">
                                                            Violation: <span class="text-danger font-weight-bold"><?= ucwords(htmlspecialchars($student['violation_description'])) ?></span><br>
                                                            Time: <?= htmlspecialchars($student['reported_at']) ?>
                                                        </p>
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
                        <div class="col-md-6">
                            <h4>Top Violators</h4>
                            <ul class="list-group">
                                <?php foreach ($topViolators as $violator) : ?>
                                    <li class="list-group-item">
                                        <?= ucwords(htmlspecialchars($violator['first_name'] . ' ' . $violator['last_name'])) ?> - <?= $violator['violation_count'] ?> Violations
                                    </li>
                                <?php endforeach; ?>
                                <?php if (empty($topViolators)) : ?>
                                    <li class="list-group-item">No top violators found.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                #violationsBarChart,
                #violationsPieChart {
                    height: 100%;
                    max-height: 250px;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Bar Chart
                    var ctxBar = document.getElementById('violationsBarChart').getContext('2d');
                    var barChart = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode(array_map(fn ($id) => ucwords($violationList[array_search($id, array_column($violationList, 'id'))]['violation_description']), array_keys($violationsByType))) ?>,
                            datasets: [{
                                label: 'Violations',
                                data: <?= json_encode(array_values($violationsByType)) ?>,
                                backgroundColor: ['#082D0F'],
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Violation Type'
                                    },
                                    ticks: {
                                        autoSkip: false, // Ensures all labels are shown
                                        maxRotation: 90, // Rotate labels if needed
                                        minRotation: 45 // Rotate labels if needed
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Violations'
                                    },
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            backgroundColor: '#f5f5f5' // Custom Background Color for Chart Area
                        }


                    });

                    // Pie Chart
                    var ctxPie = document.getElementById('violationsPieChart').getContext('2d');
                    var pieChart = new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: ['Male', 'Female'],
                            datasets: [{
                                data: [
                                    <?= $violationsByGender['Male'] ?? 0 ?>,
                                    <?= $violationsByGender['Female'] ?? 0 ?>
                                ],
                                backgroundColor: ['#36A2EB', '#FF6384']
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            }
                        }
                    });
                });
            </script>
</body>

</html>