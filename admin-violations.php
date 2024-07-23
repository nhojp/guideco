<?php
// Including the necessary files
include 'conn.php';
include 'head.php';
include 'admin-header.php';

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
    SELECT violations.id, students.first_name, students.middle_name, students.last_name, 
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
</style>
<div class="container-fluid mt-2 mb-5">
    <div class="container">
        <div class="row">
            <a href="admin-vmost-student.php" type="button" class="btn btn-primary">Student with Most Violation</a>
        </div>
    </div>
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2">
        <div class="row pt-3">
            <div class="col-md-5">
                <div class="container-fluid p-2">
                    <div class="row">
                        <h3 class="p-3"><b>
                                Violations</b>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-circle mt-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    +
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="admin-s-1.php">Complain a Student</a>
                    <a class="dropdown-item" href="admin-p-1.php">Complain a School Personnel</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="container-fluid p-2 rounded-lg">
                    <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
            </div>
        </div>

        <div class="row pt-2 pb-2">
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
                            echo "<option value='{$grade_row['grade_name']}'>{$grade_row['grade_name']}</option>";
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
                            echo "<option value='{$section_row['section_name']}'>{$section_row['section_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="filter_reported_by_type">Filter by Reported By Type:</label>
                <select class="form-control" id="filter_reported_by_type">
                    <option value="">All</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Guard">Guard</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="filter_violation">Filter by Violation:</label>
                <select class="form-control" id="filter_violation">
                    <option value="">All Violations</option>
                    <?php
                    // Fetching violations for filter
                    $violations_query = "SELECT DISTINCT violation FROM violations ORDER BY violation";
                    $violations_result = mysqli_query($conn, $violations_query);

                    if ($violations_result) {
                        while ($violation_row = mysqli_fetch_assoc($violations_result)) {
                            echo "<option value='{$violation_row['violation']}'>{$violation_row['violation']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <table id="violations_table" class="table text-center table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="width:25%;">Name</th>
                    <th style="width:15%;">Grade</th>
                    <th style="width:20%;">Violation</th>
                    <th style="width:15%;">Reported by</th>
                    <th style="width:15%;">Reported at</th>
                    <th style="width:10%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($combined_data as $row) : ?>
                    <tr data-grade="<?php echo strtolower($row['grade_name'] ?? $row['grade']); ?>" data-section="<?php echo strtolower($row['section_name'] ?? $row['section']); ?>" data-reported-by-type="<?php echo strtolower($row['reported_by_type'] ?? ''); ?>" data-reported-by-name="<?php echo strtolower($row['reported_by_name'] ?? ''); ?>" data-violation="<?php echo strtolower($row['violation']); ?>">
                        <td><?php echo ucwords($row['first_name'] ?? $row['complainedName']) . ' ' . ucwords($row['middle_name'] ?? '') . ' ' . ucwords($row['last_name'] ?? ''); ?></td>
                        <td><?php
                            $gradeName = str_replace("grade ", "", strtolower($row['grade_name'] ?? $row['grade']));
                            $sectionName = strtolower($row['section_name'] ?? $row['section']);
                            echo ucwords($gradeName) . ' - ' . ucwords($sectionName);
                            ?></td>
                        <td><?php echo ucwords($row['violation']); ?></td>
                        <td><?php echo ucwords($row['reported_by_name'] ?? $row['reportedByName']); ?></td>
                        <td><?php echo $row['reportedAt']; ?></td>
                        <td>
                            <button class="btn btn-danger delete-violation" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirmDeleteModal">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this entry?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="post" action="">
                    <input type="hidden" name="delete_id" id="delete_id" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Bind events
        $('#filter_grade, #filter_section, #filter_reported_by_type, #filter_violation').change(applyFilters);
        $('#searchInput').on('input', onSearchInput);
        $('.delete-violation').click(onDeleteButtonClick);
        $('#confirmDeleteModal').on('hide.bs.modal', clearDeleteId);
    });

    function applyFilters() {
        // Retrieve filter values
        var gradeFilter = $('#filter_grade').val().toLowerCase();
        var sectionFilter = $('#filter_section').val().toLowerCase();
        var reportedByTypeFilter = $('#filter_reported_by_type').val().toLowerCase();
        var violationFilter = $('#filter_violation').val().toLowerCase();

        // Iterate over table rows and apply filters
        $('#violations_table tbody tr').each(function() {
            var grade = $(this).data('grade').toLowerCase();
            var section = $(this).data('section').toLowerCase();
            var reportedByType = $(this).data('reported-by-type').toLowerCase();
            var violation = $(this).data('violation').toLowerCase();

            var gradeMatch = (gradeFilter === '' || grade === gradeFilter);
            var sectionMatch = (sectionFilter === '' || section === sectionFilter);
            var reportedByTypeMatch = (reportedByTypeFilter === '' || reportedByType === reportedByTypeFilter);
            var violationMatch = (violationFilter === '' || violation.includes(violationFilter));

            if (gradeMatch && sectionMatch && reportedByTypeMatch && violationMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function onSearchInput() {
        var searchTerm = $(this).val().toLowerCase();

        $('#violations_table tbody tr').each(function() {
            var name = $(this).find('td:first').text().toLowerCase();
            var violation = $(this).find('td:nth-child(4)').text().toLowerCase();

            if (name.includes(searchTerm) || violation.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function onDeleteButtonClick() {
        var deleteId = $(this).data('id');
        $('#delete_id').val(deleteId);
    }

    function clearDeleteId() {
        $('#delete_id').val('');
    }
</script>

<?php include "footer.php";
include "admin-footer.php"; ?>