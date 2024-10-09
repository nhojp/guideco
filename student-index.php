<?php

include "conn.php";
include "head.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Initialize variables
$first_name = "Student";
$last_name = "";
$grade = "Unknown";
$section = "Unknown";
$violation_count = 0; // Initialize violation count

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Fetch student ID and data based on user_id
    $sql = "SELECT 
        s.id AS student_id, 
        s.first_name, 
        s.last_name, 
        sec.section_name, 
        sec.grade_level
    FROM students s
    JOIN sections sec ON s.section_id = sec.id
    WHERE s.user_id = ?"; // Match the user_id

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            // Fetch student details
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $grade = $row['grade_level']; // Ensure this field exists
            $section = $row['section_name'];
            $student_id = $row['student_id']; // Get the student ID
        } else {
            // Handle case where no student was found
            echo "No student found with this user ID.";
        }

        $stmt->close();
    } else {
        // Handle query preparation error
        echo "Error preparing the student data query.";
    }

    // Fetch violation count for the student using student_id
    $sql_violations = "SELECT COUNT(*) AS violation_count 
                       FROM violations 
                       WHERE student_id = ?"; // Use student_id here

    if ($stmt = $conn->prepare($sql_violations)) {
        $stmt->bind_param("i", $student_id); // Ensure this matches the right student ID
        $stmt->execute();
        $result_violations = $stmt->get_result();

        if ($result_violations && $result_violations->num_rows == 1) {
            $row_violations = $result_violations->fetch_assoc();
            $violation_count = $row_violations['violation_count'];
        }

        $stmt->close();
    } else {
        // Handle query preparation error
        echo "Error preparing the violation count query.";
    }
} else {
    // Handle case where user ID is not set in session
    echo "User ID not set in session.";
}

include 'student-nav.php';
?>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-md-12" id="animate-area">
                        <h2 class="font-weight-bold">
                            <?php echo ucwords($first_name . ' ' . $last_name); ?>
                        </h2>
                        <p>
                            <?php echo ucwords($grade . ' - ' . $section); ?>
                        </p>
                        <p>
                            <?php
                            // Display violation count with badge color
                            if ($violation_count == 0) {
                                echo '<span class="badge badge-success">No Violations</span>';
                            } elseif ($violation_count == 1 || $violation_count == 2) {
                                echo '<span class="badge badge-warning">' . $violation_count . ' Violations</span>';
                            } elseif ($violation_count >= 3) {
                                echo '<span class="badge badge-danger">' . $violation_count . ' Violations</span>';
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="bg-light p-4 rounded mt-4">
                    <h1>Welcome to GuideCo!</h1>
                    <h2>The Guidance and Counseling System of Nasugbu East Senior High School</h2>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <!-- ChatLing Widget Integration -->
            <div id="chatling-embed-container"></div>
            <script async data-id="7485494224" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
