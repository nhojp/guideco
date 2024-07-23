<?php

include "conn.php";
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

// Initialize variables
$first_name = "Student"; 
$last_name = ""; 
$grade = "Unknown"; 
$section = "Unknown"; 
$violation_count = 0; // Initialize violation count

$student_id = $_SESSION['user_id'] ?? null;

if ($student_id) {
    // Fetch student data based on student_id
    $sql = "SELECT 
        s.first_name, 
        s.last_name, 
        sec.section_name, 
        g.grade_name 
    FROM students s
    JOIN sections sec ON s.section_id = sec.id
    JOIN grades g ON sec.grade_id = g.id
    WHERE s.id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            // Fetch student details
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $grade = $row['grade_name'];
            $section = $row['section_name'];
        }

        $stmt->close();
    } else {
        // Handle query preparation error
        echo "Error preparing the student data query.";
    }

    // Fetch violation count for the student
    $sql_violations = "SELECT COUNT(*) AS violation_count FROM violations WHERE student_id = ?";

    if ($stmt = $conn->prepare($sql_violations)) {
        $stmt->bind_param("i", $student_id);
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
    // Handle case where student ID is not set in session
    echo "Student ID not set in session.";
}

include 'head.php';
?>
<style>
    /* Adjust font size for smaller screens */
    @media (max-width: 768px) {
        .welcome-message {
            font-size: 1.5rem;
            /* Adjust the font size as needed */
        }
    }
    
</style>
<body class="bg">
<div class="container-fluid mt-4 bg">
    <div class="container-fluid pt-4 rounded-lg pl-4" id="animate-area">
        <div class="row float-right mr-4">
            <form action="logout.php">
                <button class="bg-danger rounded-circle p-2">
                    <i class="fa-solid fa-power-off"></i>
                </button>
            </form>
        </div>
        <div class="row justify-content-between">
            <div class="col-md-12">
                <h2 class="font-weight-bold welcome-message">
                    <b><?php echo ucwords($first_name . ' ' . $last_name); ?></b>
                </h2>
                <b><?php echo ucwords($grade . ' - ' . $section); ?></b><br>
                <p class="pt-2"><b>
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
                    </b></p>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white">
        <div class="row ">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item w-30 ">
                        <a class="nav-link active font-weight-bold text-dark" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            Home
                        </a>
                    </li>
                    <li class="nav-item w-30">
                        <a class="nav-link font-weight-bold text-dark" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
                    </li>
                    <li class="nav-item w-30">
                        <a class="nav-link font-weight-bold text-dark" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Recommender</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white rounded-lg">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container-fluid bg-light pt-4 rounded-lg">
                    <h1>Welcome to GuideCo!</h1>
                    <h2>The Guidance and Counseling System of Nasugbu East Senior High School</h2>
                </div>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <?php include "student-profile.php" ?>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <h3>Recommender</h3>
                <p>Content for Recommender tab.</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- ChatLing Widget Integration -->
    <div id="chatling-embed-container"></div>
    <script async data-id="7485494224" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</div>

<?php include('footer.php'); ?>
