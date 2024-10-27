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
$violation_count = 0;

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
    WHERE s.user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $grade = $row['grade_level'];
            $section = $row['section_name'];
            $student_id = $row['student_id'];
        } else {
            echo "No student found with this user ID.";
        }

        $stmt->close();
    } else {
        echo "Error preparing the student data query.";
    }

    // Fetch violation count for the student
    $sql_violations = "SELECT COUNT(*) AS violation_count 
                       FROM violations 
                       WHERE student_id = ?";

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
        echo "Error preparing the violation count query.";
    }
} else {
    echo "User ID not set in session.";
}

// Fetch names from teachers, guards, and admin
$names_sql = "SELECT first_name, last_name, role FROM users WHERE role IN ('teacher', 'guard', 'admin')";
$names_result = $conn->query($names_sql);
$names_list = [];

if ($names_result) {
    while ($row = $names_result->fetch_assoc()) {
        $names_list[] = $row;
    }
}

include 'student-nav.php';

// Initialize an array to store names
$names_list = [];

// Fetch names from guards table
$guards_sql = "SELECT first_name, last_name FROM guards";
$guards_result = $conn->query($guards_sql);

if ($guards_result && $guards_result->num_rows > 0) {
    while ($row = $guards_result->fetch_assoc()) {
        $names_list[] = [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'role' => 'Guard'
        ];
    }
}

// Fetch names from teachers table
$teachers_sql = "SELECT first_name, last_name FROM teachers";
$teachers_result = $conn->query($teachers_sql);

if ($teachers_result && $teachers_result->num_rows > 0) {
    while ($row = $teachers_result->fetch_assoc()) {
        $names_list[] = [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'role' => 'Teacher'
        ];
    }
}

// Fetch names from admin table
$admin_sql = "SELECT first_name, last_name FROM admin";
$admin_result = $conn->query($admin_sql);

if ($admin_result && $admin_result->num_rows > 0) {
    while ($row = $admin_result->fetch_assoc()) {
        $names_list[] = [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'role' => 'Admin'
        ];
    }
}
?>
<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    .card-title {
        overflow: hidden;
        /* Hide overflow text */
        text-overflow: ellipsis;
        /* Show ellipsis (...) */
        white-space: nowrap;
        /* Prevent text from wrapping */
        max-width: 100%;
        /* Limit the title width */
    }

</style>
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
                <div class="row">
                    <div class="col-md-12 text-center mb-2 border-top mt-4">
                        <h1 class="mt-4"><b>Welcome to GuideCo!</b></h1>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6">
                        <p>GuideCo is a guidance and counseling management system specifically designed for Nasugbu East Senior High School. It aims to facilitate the communication between students and their mentors, streamline the management of student records, and provide support for academic and personal development.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <img src="img/logoguideco.jpg" class="card-img-top" alt="GuideCo Image">
                        </div>
                    </div>
                </div>


                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6">
                        <div class="card">
                            <img src="img/st.jpg" class="card-img-top" alt="GuideCo Image">
                        </div>
                    </div>
                    <div class="col-md-6">Nasugbu East Senior High School is dedicated to fostering a nurturing educational environment. Our goal is to equip students with the necessary tools and resources to excel academically and socially.</div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-center mb-2">
                        <h3><b>School Personnels</b></h3>
                    </div>
                </div>

                <div class="row mt-2">
                    <?php if (!empty($names_list)): ?>
                        <?php foreach ($names_list as $name): ?>
                            <div class="col-md-2 mb-4">
                                <div class="card">
                                    <img src="https://via.placeholder.com/150" alt="Default Profile" style="width: 100%; height: auto;">
                                    <div class="card-body text-center">
                                        <h5 class="card-title" style="font-size: 14px;"><?php echo htmlspecialchars(ucwords($name['first_name'] . ' ' . $name['last_name'])); ?></h5>
                                        <p class="card-text" style="font-size: 12px;"><?php echo htmlspecialchars($name['role']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No staff members found.</p>
                    <?php endif; ?>
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