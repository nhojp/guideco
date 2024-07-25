<?php
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit;
}
function getAllSectionsData($conn, $filterGrade = null)
{
    $sql = "SELECT sections.*, grades.grade_name, teachers.first_name AS teacher_first_name, teachers.last_name AS teacher_last_name
            FROM sections 
            LEFT JOIN grades ON sections.grade_id = grades.id
            LEFT JOIN teachers ON sections.teacher_id = teachers.id";

    // Append WHERE clause if filter grade is selected
    if (!empty($filterGrade)) {
        $sql .= " WHERE sections.grade_id = $filterGrade";
    }

    $result = mysqli_query($conn, $sql);
    $sectionsData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sectionsData[] = $row;
    }
    return $sectionsData;
}
function getAllGradeNames($conn)
{
    $sql = "SELECT id, grade_name FROM grades";
    $result = mysqli_query($conn, $sql);
    $grades = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $grades[] = $row;
    }
    return $grades;
}
function getAllTeachers($conn)
{
    $sql = "SELECT id, first_name, last_name FROM teachers";
    $result = mysqli_query($conn, $sql);
    $teachers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $teachers[] = $row;
    }
    return $teachers;
}

$editSuccess = isset($_SESSION['edit_success']) ? $_SESSION['edit_success'] : false;
$addSuccess = isset($_SESSION['add_success']) ? $_SESSION['add_success'] : false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_section_name'])) {
    $sectionId = $_POST['edit_section_id'];
    $sectionName = $_POST['edit_section_name'];
    $teacherId = $_POST['edit_teacher_id'];

    $updateSql = "UPDATE sections SET section_name = '$sectionName', teacher_id = $teacherId WHERE id = $sectionId";

    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['edit_success'] = true;
        $editSuccess = true;
    } else {
        $errorMessage = 'Error updating section record: ' . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['section_name']) && isset($_POST['grade_id']) && isset($_POST['teacher_id'])) {
    $sectionName = $_POST['section_name'];
    $gradeId = $_POST['grade_id'];
    $teacherId = $_POST['teacher_id'];

    $insertSql = "INSERT INTO sections (section_name, grade_id, teacher_id) VALUES ('$sectionName', $gradeId, $teacherId)";

    if (mysqli_query($conn, $insertSql)) {
        $_SESSION['add_success'] = true;
        $addSuccess = true;
    } else {
        $errorMessage = 'Error adding new section: ' . mysqli_error($conn);
    }
}

unset($_SESSION['edit_success']);
unset($_SESSION['add_success']);

// Fetching data
$filterGrade = isset($_GET['filter_grade']) ? $_GET['filter_grade'] : null;
$sectionsData = getAllSectionsData($conn, $filterGrade);
$grades = getAllGradeNames($conn);
$teachers = getAllTeachers($conn);
?>

<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>Section List</strong></h3>
                </div>
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSectionModal">
                        +
                    </button>
                </div>
            </div>

            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
            </div>

        </div>

        <?php if ($editSuccess || $addSuccess || !empty($errorMessage)) : ?>
            <div class="alert <?php echo $editSuccess || $addSuccess ? 'alert-success' : 'alert-danger'; ?> mt-4" role="alert">
                <?php
                if ($editSuccess) {
                    echo 'Section updated successfully';
                } elseif ($addSuccess) {
                    echo 'Section added successfully';
                } elseif (!empty($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </div>
            <script>
                // Check if the page has already been reloaded
                if (!localStorage.getItem('reloaded')) {
                    localStorage.setItem('reloaded', 'true'); // Set the flag
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000); // 1000 milliseconds = 1 second
                }
            </script>
        <?php endif; ?>

        <!-- Modal for adding a section -->
        <div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-guideco text-white">
                        <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="sectionName">Section Name</label>
                                <input type="text" class="form-control" id="sectionName" name="section_name" required>
                            </div>
                            <div class="form-group">
                                <label for="gradeFilter">Filter by Grade</label>
                                <select class="form-control" id="gradeFilter" name="grade_id">
                                    <option value="">All Grades</option>
                                    <?php foreach ($grades as $grade) : ?>
                                        <option value="<?php echo $grade['id']; ?>"><?php echo $grade['grade_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="teacherSelect">Select Adviser</label>
                                <select class="form-control" id="teacherSelect" name="teacher_id" required>
                                    <option value="">Select Teacher</option>
                                    <?php foreach ($teachers as $teacher) : ?>
                                        <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Add Section</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-hover mt-4 border">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 30%;">Section Name</th>
                    <th style="width: 20%;">
                        <select class="form-control" id="gradeFilterTable">
                            <option value="">All Grades</option>
                            <?php foreach ($grades as $grade) : ?>
                                <option value="<?php echo $grade['id']; ?>"><?php echo $grade['grade_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th style="width: 40%;">Adviser</th>
                    <th style="width: 10%;">Action</th>
                </tr>
            </thead>
            <tbody id="sectionsTableBody">
                <?php foreach ($sectionsData as $section) : ?>
                    <tr class="grade-<?php echo $section['grade_id']; ?>">
                        <td><?php echo ucwords($section['section_name']); ?></td>
                        <td><?php echo ucwords($section['grade_name']); ?></td>
                        <td>
                            <?php
                            if (!empty($section['teacher_first_name']) && !empty($section['teacher_last_name'])) {
                                echo ucwords($section['teacher_first_name']) . ' ' . ucwords($section['teacher_last_name']);
                            } else {
                                echo 'Not Assigned';
                            }
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editSectionModal<?php echo $section['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Modal for editing a section -->
                            <div class="modal fade" id="editSectionModal<?php echo $section['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-guideco text-white">
                                            <h5 class="modal-title" id="editSectionModalLabel">Edit Section</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="">
                                                <input type="hidden" name="edit_section_id" value="<?php echo $section['id']; ?>">
                                                <div class="form-group">
                                                    <label for="editSectionName">Section Name</label>
                                                    <input type="text" class="form-control" id="editSectionName" name="edit_section_name" value="<?php echo $section['section_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="editTeacherSelect">Select Adviser</label>
                                                    <select class="form-control" id="editTeacherSelect" name="edit_teacher_id" required>
                                                        <option value="">Select Teacher</option>
                                                        <?php foreach ($teachers as $teacher) : ?>
                                                            <option value="<?php echo $teacher['id']; ?>" <?php echo ($teacher['id'] == $section['teacher_id']) ? 'selected' : ''; ?>>
                                                                <?php echo ucwords($teacher['first_name']) . ' ' . ucwords($teacher['last_name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Filter sections by grade in table header dropdown
        $('#gradeFilterTable').change(function() {
            var selectedGrade = $(this).val();
            if (selectedGrade == '') {
                $('#sectionsTableBody tr').show(); // Show all rows if "All Grades" is selected
            } else {
                $('#sectionsTableBody tr').hide(); // Hide all rows initially
                $('#sectionsTableBody tr.grade-' + selectedGrade).show(); // Show rows matching the selected grade
            }
        });

        // Filter sections by grade in modal dropdown
        $('#gradeFilter').change(function() {
            var selectedGrade = $(this).val();
            if (selectedGrade == '') {
                $('#teacherSelect option').show(); // Show all teachers if "All Grades" is selected
            } else {
                $('#teacherSelect option').hide(); // Hide all teachers initially
                $('#teacherSelect option[data-grade="' + selectedGrade + '"]').show(); // Show teachers for the selected grade
            }
        });
    });
</script>