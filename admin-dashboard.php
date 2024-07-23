<?php
include 'head.php';
include 'conn.php';

// Get filters
$violationType = $_GET['violationType'] ?? '';
$grade = $_GET['grade'] ?? '';
$section = $_GET['section'] ?? '';
$timePeriod = $_GET['timePeriod'] ?? 'thisYear';

// Date filter logic
$currentDate = date('Y-m-d');
if ($timePeriod == 'thisDay') {
    $startDate = $currentDate;
    $endDate = $currentDate;
} elseif ($timePeriod == 'thisMonth') {
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-t');
} else {
    $startDate = date('Y-01-01');
    $endDate = date('Y-12-31');
}

// Fetch violations
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
    return $violation['reported_at'] == $currentDate;
});
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

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>
        <form id="filterForm" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-3">
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
                <div class="form-group col-md-3">
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
                <div class="form-group col-md-3">
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
                <div class="form-group col-md-3">
                    <label for="timePeriod">Time Period</label>
                    <select class="form-control" id="timePeriod" name="timePeriod">
                        <option value="thisYear" <?= $timePeriod == 'thisYear' ? 'selected' : '' ?>>This Year</option>
                        <option value="thisMonth" <?= $timePeriod == 'thisMonth' ? 'selected' : '' ?>>This Month</option>
                        <option value="thisDay" <?= $timePeriod == 'thisDay' ? 'selected' : '' ?>>This Day</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-12">
                <h4>Total Violations: <?= $totalViolations ?></h4>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <canvas id="violationsBarChart"></canvas>
            </div>
            <div class="col-md-3">
                <canvas id="violationsPieChart"></canvas>
            </div>
            <div class="col-md-3">
                <div id="studentsCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($studentsWithViolationsToday)) : ?>
                            <?php foreach ($studentsWithViolationsToday as $index => $student) : ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <div class="d-flex justify-content-center">
                                        <div class="p-4 bg-light border">
                                            <h5><?= ucwords($student['first_name'] . ' ' . $student['last_name']) ?></h5>
                                            <p>Violation ID: <?= $student['violation_id'] ?></p>
                                            <p>Reported At: <?= $student['reported_at'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="carousel-item active">
                                <div class="d-flex justify-content-center">
                                    <div class="p-4 bg-light border">
                                        <h5>No Violations Today</h5>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Carousel Controls -->
                    <a class="carousel-control-prev" href="#studentsCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#studentsCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $('#studentsCarousel').carousel();
        });
    </script>

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
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
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
                        backgroundColor: ['#FF6384', '#36A2EB']
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            });
        });
    </script>
</body>

</html>