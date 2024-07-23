<?php
// Include the database connection
include "conn.php";

// Include the header
include "head.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

// Get the selected student ID and other parameters from the URL
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Fetch details of the selected student
$sql_selected_student = "SELECT s.id, s.first_name, s.last_name, sec.section_name, g.grade_name
                         FROM students s
                         INNER JOIN sections sec ON s.section_id = sec.id
                         INNER JOIN grades g ON sec.grade_id = g.id
                         WHERE s.id = ?";
$stmt = $conn->prepare($sql_selected_student);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_selected_student = $stmt->get_result();
$selected_student = $result_selected_student->fetch_assoc();

// Check if selected student exists
if (!$selected_student) {
    echo "Error: No student found with ID $student_id.";
    exit;
}

// Fetch all students for victim selection
$sql_all_students = "SELECT s.id, s.first_name, s.last_name, sec.section_name, g.grade_name
                     FROM students s
                     INNER JOIN sections sec ON s.section_id = sec.id
                     INNER JOIN grades g ON sec.grade_id = g.id";
$result_all_students = $conn->query($sql_all_students);

// Check for query error
if ($result_all_students === false) {
    echo "Error: " . $conn->error;
    exit;
}

include "admin-header.php";
?>

<div class="container-fluid mt-2 mb-5">
    <div class="container-fluid bg-white pt-4 rounded-lg">
        <div class="row">
            <div class="col-md-12">
                <h5 class="mb-4 font-weight-bold">Select Victim of <span class="text-danger"><?php echo ucwords($selected_student['first_name']) . " "
                                                                        . ucwords($selected_student['last_name']) . " of "
                                                                        . ucwords($selected_student['grade_name']) . " - " . ucwords($selected_student['section_name']); ?></span></h5>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white pt-4 mt-2 rounded-lg">
        <table class="table table-borderless text-center table-hover ">
            <thead>
                <tr>
                    <th style="width: 50%;">Full Name</th>
                    <th style="width: 20%;">Grade</th>
                    <th style="width: 20%;">Section</th>
                    <th style="width: 10%;">Select</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_all_students->num_rows > 0) : ?>
                    <?php while ($student = $result_all_students->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo ucwords($student['first_name']) . " " . ucwords($student['last_name']); ?></td>
                            <td><?php echo ucwords($student['grade_name']); ?></td>
                            <td><?php echo ucwords($student['section_name']); ?></td>
                            <td>
                                <form action="admin-s-3.php" method="GET">
                                    <input type="hidden" name="offender_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                    <input type="hidden" name="victim_id" value="<?php echo htmlspecialchars($student['id']); ?>">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">Select</button>
                                </form>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No students found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Include footer
include "admin-footer.php";

// Close the database connection
$conn->close();
?>