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

// Pagination parameters
$limit = 10; // Number of entries to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page from URL or default to 1
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// SQL query to count total records
$total_query = "SELECT COUNT(*) as total FROM violations";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$totalEntries = $total_row['total']; // Get total number of entries
$totalPages = ceil($totalEntries / $limit); // Calculate total number of pages

// SQL query to fetch violations data with LIMIT and OFFSET
$violations_query = "
    SELECT violations.id, students.id AS student_id, students.first_name, students.middle_name, students.last_name, 
    students.age, students.sex, sections.section_name, sections.grade_level,
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
LEFT JOIN guards ON violations.guard_id = guards.id
LEFT JOIN teachers ON violations.teacher_id = teachers.id
JOIN violation_list ON violations.violation_id = violation_list.id
ORDER BY violations.reported_at DESC
LIMIT $limit OFFSET $offset
";

$violations_result = mysqli_query($conn, $violations_query);

if (!$violations_result) {
    die("Violations query failed: " . mysqli_error($conn));
}
?>

<style>
    .table-scroll {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .thead-custom {
        background-color: #0C2D0B;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        
    }

    .pagination a {
        margin: 0 5px;
        padding: 8px 12px;
        border: 1px solid #007bff;
        color: #007bff;
        text-decoration: none;
        border-radius: 50px;

    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
    }

    .pagination a:hover {
        background-color: #0056b3;
        color: white;
    }
    table tbody td {
    text-transform: capitalize;
    }   

    .table th, .table td {
    padding: 15px; 
    text-align: left; 
    border-bottom: 1px solid #ddd; 
    }

    .table thead {
    position: sticky; 
    top: 0; 
    background-color: #0C2D0B; 
    z-index: 1; 
    color: white; 
}
</style>

<body style="background-color: #DBD8AE;">

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
                <div class="col-md-2 mb-3">
                    <label for="filter_grade">Filter by Grade:</label>
                    <select class="form-control" id="filter_grade">
                        <option value="">All Grades</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
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
                <div class="col-md-1 mb-3">
                    <label for="">Print:</label>
                    <a href="admin-violators-print.php" class="btn btn-success">
                        <i class="fas fa-print fa-fw"></i>
                    </a>
                </div>
            </div>

            <!-- Display total entries message -->
            <div class="mb-2">
                <strong>
                    <?php
                    // Update this message based on the total entries
                    if ($totalEntries > 0) {
                        $from = $offset + 1;
                        $to = min($offset + $limit, $totalEntries);
                        echo "Showing $from to $to of $totalEntries entries.";
                    } else {
                        echo "No entries found.";
                    }
                    ?>
                </strong>
            </div>

            <div class="table-scroll">
                <table id="violations_table" class="table table-hover border">
                    <thead class="thead-custom">
                        <tr>
                            <th style="width:30%;">Name</th>
                            <th style="width:10%;">Grade</th>
                            <th style="width:20%;">Violation</th>
                            <th style="width:15%;">Reported by</th>
                            <th style="width:15%;">Reported at</th>
                            <th class="text-center" style="width:15%">Action</th>
                            <th style="display:none;">Reported by Type</th> <!-- Hidden column for filter -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($violations_result)) : ?>
                            <tr data-grade="<?php echo htmlspecialchars(strtolower($row['grade_level'] ?? $row['grade'])); ?>" data-section="<?php echo htmlspecialchars(strtolower($row['section_name'])); ?>" data-reported-by-type="<?php echo htmlspecialchars(strtolower($row['reported_by_type'])); ?>" data-violation="<?php echo htmlspecialchars(strtolower($row['violation'])); ?>">
                                <td><?php echo ucwords(htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'])); ?></td>
                                <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($row['violation'])); ?></td>
                                <td><?php echo ucwords(htmlspecialchars($row['reported_by_name'])); ?></td>
                                <td><?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($row['reportedAt']))); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-danger" data-id="<?php echo htmlspecialchars($row['id']); ?>">Delete</button>
                                </td>
                                <td style="display:none;"><?php echo htmlspecialchars($row['reported_by_type']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">« Prev</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next »</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const filterGrade = document.getElementById('filter_grade');
            const filterSection = document.getElementById('filter_section');
            const filterReportedByType = document.getElementById('filter_reported_by_type');
            const filterViolation = document.getElementById('filter_violation');
            const tableRows = document.querySelectorAll('#violations_table tbody tr');

            searchInput.addEventListener('keyup', filterTable);
            filterGrade.addEventListener('change', filterTable);
            filterSection.addEventListener('change', filterTable);
            filterReportedByType.addEventListener('change', filterTable);
            filterViolation.addEventListener('change', filterTable);

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const gradeValue = filterGrade.value.toLowerCase();
                const sectionValue = filterSection.value.toLowerCase();
                const reportedByValue = filterReportedByType.value.toLowerCase();
                const violationValue = filterViolation.value.toLowerCase();

                tableRows.forEach(row => {
                    const rowGrade = row.getAttribute('data-grade').toLowerCase();
                    const rowSection = row.getAttribute('data-section').toLowerCase();
                    const rowReportedByType = row.getAttribute('data-reported-by-type').toLowerCase();
                    const rowViolation = row.getAttribute('data-violation').toLowerCase();
                    const rowName = row.children[0].textContent.toLowerCase();

                    const matchesSearch = rowName.includes(searchValue);
                    const matchesGrade = !gradeValue || rowGrade === gradeValue;
                    const matchesSection = !sectionValue || rowSection === sectionValue;
                    const matchesReportedBy = !reportedByValue || rowReportedByType === reportedByValue;
                    const matchesViolation = !violationValue || rowViolation === violationValue;

                    if (matchesSearch && matchesGrade && matchesSection && matchesReportedBy && matchesViolation) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Handling deletion
            document.querySelectorAll('.btn-danger').forEach(button => {
                button.addEventListener('click', () => {
                    const row = button.closest('tr');
                    const id = button.getAttribute('data-id');

                    if (confirm('Are you sure you want to delete this violation?')) {
                        const formData = new FormData();
                        formData.append('delete_id', id);

                        fetch('', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                row.remove(); // Remove the row from the table
                                // Update total entries display message if needed
                            } else {
                                alert('Error deleting violation. Please try again.');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
