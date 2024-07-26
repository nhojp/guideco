<?php
// Including the necessary files
include 'conn.php';
include 'head.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not an admin
    exit;
}

// Handle deletion request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // SQL query to delete violation
    $delete_query = "DELETE FROM violations WHERE id = '$delete_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if (!$delete_result) {
        die("Deletion failed: " . mysqli_error($conn));
    }

    exit;
}

// SQL query to fetch violations data
$violations_query = "
    SELECT violations.id, students.id AS student_id, students.first_name, students.middle_name, students.last_name, 
    students.age, students.sex, sections.section_name, grades.grade_name, 
    violation_list.violation_description AS violation, 
    CASE
        WHEN violations.guard_id IS NOT NULL THEN guards.first_name
        WHEN violations.teacher_id IS NOT NULL THEN teachers.first_name
        ELSE 'Unknown'
    END AS reported_by_name,
    CASE
        WHEN violations.guard_id IS NOT NULL THEN 'Guard'
        WHEN violations.teacher_id IS NOT NULL THEN 'Teacher'
        ELSE 'Unknown'
    END AS reported_by_type,
    violations.reported_at AS reportedAt
FROM violations
JOIN students ON violations.student_id = students.id
JOIN sections ON students.section_id = sections.id
JOIN grades ON sections.grade_id = grades.id
LEFT JOIN guards ON violations.guard_id = guards.id
LEFT JOIN teachers ON violations.teacher_id = teachers.id
JOIN violation_list ON violations.violation_id = violation_list.id
ORDER BY violations.reported_at DESC
";

$violations_result = mysqli_query($conn, $violations_query);

if (!$violations_result) {
    die("Violations query failed: " . mysqli_error($conn));
}

// SQL query to fetch complaints data
$complaints_query = "
    SELECT id, CONCAT(complainedFirstName, ' ', complainedMiddleName, ' ', complainedLastName) AS complainedName,
           complainedGrade AS grade, complainedSection AS section, caseDetails AS violation,
           CONCAT(complainantFirstName, ' ', complainantMiddleName, ' ', complainantLastName) AS reportedByName,
           reportedAt
    FROM complaints_student
    ORDER BY reportedAt DESC
";

$complaints_result = mysqli_query($conn, $complaints_query);

if (!$complaints_result) {
    die("Complaints query failed: " . mysqli_error($conn));
}

// Combine results
$combined_data = [];
while ($row = mysqli_fetch_assoc($violations_result)) {
    $combined_data[] = $row;
}
while ($row = mysqli_fetch_assoc($complaints_result)) {
    $combined_data[] = $row;
}
?>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>Violators</strong></h3>
                </div>
            </div>
            <div class="col-md-6">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
            </div>
        </div>

        <div class="row pt-2 pb-2 filter-container">
            <div class="col-md-3 mb-3">
                <label for="filter_grade">Filter by Grade:</label>
                <select class="form-control" id="filter_grade">
                    <option value="">All Grades</option>
                    <?php
                    // Fetching grades for filter
                    $grades_query = "SELECT id, grade_name FROM grades";
                    $grades_result = mysqli_query($conn, $grades_query);

                    if ($grades_result) {
                        while ($grade_row = mysqli_fetch_assoc($grades_result)) {
                            echo "<option value='" . htmlspecialchars($grade_row['grade_name']) . "'>" . ucwords(htmlspecialchars($grade_row['grade_name'])) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="filter_section">Filter by Section:</label>
                <select class="form-control" id="filter_section">
                    <option value="">All Sections</option>
                    <?php
                    // Fetching sections for filter
                    $sections_query = "SELECT id, section_name FROM sections";
                    $sections_result = mysqli_query($conn, $sections_query);

                    if ($sections_result) {
                        while ($section_row = mysqli_fetch_assoc($sections_result)) {
                            echo "<option value='" . htmlspecialchars($section_row['section_name']) . "'>" . ucwords(htmlspecialchars($section_row['section_name'])) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="filter_reported_by_type">Reported By:</label>
                <select class="form-control" id="filter_reported_by_type">
                    <option value="">All</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Guard">Guard</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="filter_violation">Violation:</label>
                <select class="form-control" id="filter_violation">
                    <option value="">All Violations</option>
                    <?php
                    // Fetching violations for filter
                    $violations_list_query = "SELECT DISTINCT violation_description FROM violation_list ORDER BY violation_description";
                    $violations_list_result = mysqli_query($conn, $violations_list_query);

                    if ($violations_list_result) {
                        while ($violation_row = mysqli_fetch_assoc($violations_list_result)) {
                            echo "<option value='" . htmlspecialchars($violation_row['violation_description']) . "'>" . ucwords(htmlspecialchars($violation_row['violation_description'])) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
<div class="table-responsive">
        <table id="violations_table" class="table table-hover mt-4 border">
            <thead class="thead-dark">
                <tr>
                    <th style="width:20%;">Name</th>
                    <th style="width:20%;">Grade</th>
                    <th style="width:15%;">Violation</th>
                    <th style="width:15%;">Reported by</th>
                    <th style="width:15%;">Reported at</th>
                    <th class="text-center" style="width:15%">Action</th>
                    <th style="display:none;">Reported by Type</th> <!-- Hidden column for filter -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($combined_data as $row) : ?>
                    <tr data-grade="<?php echo htmlspecialchars(strtolower($row['grade_name'] ?? $row['grade'])); ?>" data-section="<?php echo htmlspecialchars(strtolower($row['section_name'] ?? $row['section'])); ?>" data-reported-by-type="<?php echo htmlspecialchars(strtolower($row['reported_by_type'] ?? '')); ?>" data-violation="<?php echo htmlspecialchars(strtolower($row['violation'])); ?>">
                        <td><?php echo isset($row['first_name']) && isset($row['last_name']) ? ucwords(htmlspecialchars($row['first_name'] . ' ' . $row['last_name'])) : ucwords(htmlspecialchars($row['complainedName'])); ?></td>
                        <td><?php echo isset($row['grade_name']) && isset($row['section_name']) ? ucwords(htmlspecialchars($row['grade_name'] . ' - ' . $row['section_name'])) : ucwords(htmlspecialchars($row['grade'] . ' - ' . $row['section'])); ?></td>
                        <td><?php echo ucwords(htmlspecialchars($row['violation'])); ?></td>
                        <td><?php echo ucwords(htmlspecialchars($row['reported_by_name'] ?? $row['reportedByName'])); ?></td>
                        <td><?php echo htmlspecialchars($row['reportedAt']); ?></td>
                        <td class="text-center">
                            <a href="admin-student-profile.php?id=<?php echo htmlspecialchars($row['student_id'] ?? ''); ?>" type="button" class="btn btn-info">
                                <i class="fas fa-eye fa-fw"></i>
                            </a>
                            <?php if (isset($row['id'])) : ?>
                                <button type="button" class="btn btn-danger" data-id="<?php echo htmlspecialchars($row['id']); ?>">
                                    <i class="fas fa-trash-alt fa-fw"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td style="display:none;"><?php echo ucwords(htmlspecialchars($row['reported_by_type'] ?? '')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const searchInput = document.getElementById('searchInput');
        const filterGrade = document.getElementById('filter_grade');
        const filterSection = document.getElementById('filter_section');
        const filterReportedByType = document.getElementById('filter_reported_by_type');
        const filterViolation = document.getElementById('filter_violation');
        const tableRows = document.querySelectorAll('#violations_table tbody tr');

        const filterTable = () => {
            const searchText = searchInput.value.toLowerCase();
            const gradeFilter = filterGrade.value.toLowerCase();
            const sectionFilter = filterSection.value.toLowerCase();
            const reportedByTypeFilter = filterReportedByType.value.toLowerCase();
            const violationFilter = filterViolation.value.toLowerCase();

            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const grade = row.getAttribute('data-grade');
                const section = row.getAttribute('data-section');
                const reportedByType = row.getAttribute('data-reported-by-type');
                const violation = row.getAttribute('data-violation');

                const matchesSearch = name.includes(searchText);
                const matchesGrade = gradeFilter ? grade === gradeFilter : true;
                const matchesSection = sectionFilter ? section === sectionFilter : true;
                const matchesReportedByType = reportedByTypeFilter ? reportedByType === reportedByTypeFilter : true;
                const matchesViolation = violationFilter ? violation === violationFilter : true;

                if (matchesSearch && matchesGrade && matchesSection && matchesReportedByType && matchesViolation) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        searchInput.addEventListener('input', filterTable);
        filterGrade.addEventListener('change', filterTable);
        filterSection.addEventListener('change', filterTable);
        filterReportedByType.addEventListener('change', filterTable);
        filterViolation.addEventListener('change', filterTable);

        // Handle delete button click
        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this record?')) {
                    fetch('admin-index.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `delete_id=${id}`
                    }).then(response => {
                        if (response.ok) {
                            button.closest('tr').remove();
                        } else {
                            alert('Failed to delete record.');
                        }
                    });
                }
            });
        });
    });
</script>
