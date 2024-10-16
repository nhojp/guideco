<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

session_start(); // Start the session
include 'conn.php'; // Database connection
include 'head.php'; // Include head section
include 'admin-nav.php';

// Assume user is authenticated and $admin_id is the logged-in admin's ID
$admin_id = $_SESSION['admin_id']; // This should be set during the login process

// Fetch admin details
$query = "SELECT first_name, last_name, sex FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$admin_name = ucwords($admin['first_name']) . ' ' . ucwords($admin['last_name']);
$admin_gender = strtolower($admin['sex']);
$salutation = ($admin_gender == 'male') ? 'Mr.' : 'Ms.';

// Get student ID from URL
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch student data and parent contact details
$query = "SELECT s.*, m.email AS mother_email, f.email AS father_email
          FROM students s
          LEFT JOIN mothers m ON s.id = m.student_id
          LEFT JOIN fathers f ON s.id = f.student_id
          WHERE s.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Fetch all violation details
$violation_query = "SELECT violation_description 
                    FROM violations 
                    JOIN violation_list ON violations.violation_id = violation_list.id
                    WHERE student_id = ?";
$violation_stmt = $conn->prepare($violation_query);
$violation_stmt->bind_param("i", $student_id);
$violation_stmt->execute();
$violation_result = $violation_stmt->get_result();

$violations = [];
while ($row = $violation_result->fetch_assoc()) {
    $violations[] = $row['violation_description'];
}
$violation_list = !empty($violations) ? implode(', ', $violations) : 'No violation details available';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = new PHPMailer(true);

    $to = [];
    if (!empty($student['mother_email'])) {
        $to[] = $student['mother_email'];
    }
    if (!empty($student['father_email'])) {
        $to[] = $student['father_email'];
    }

    if (count($to) > 0) {
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'guideconeshs@gmail.com'; // Your Gmail address
            $mail->Password = 'fzzjmkjikwtccylc'; // Your generated app password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('guideconeshs@gmail.com', 'Admin');

            foreach ($to as $email) {
                $mail->addAddress($email);
            }

            $mail->isHTML(true);
            $mail->Subject = "Guidance and Counseling Office";

            // Generate the email body
            $date = htmlspecialchars($_POST['date']);
            $student_name = ucwords(htmlspecialchars($student['first_name'] . ' ' . $student['last_name']));
            $message_body = "Dear Parents,<br><br>
                            Please meet $salutation $admin_name at Nasugbu East Senior High School on $date.<br><br>
                            The reason for this meeting is that your child, $student_name, has been reported for the following violations: $violation_list.<br><br>
                            Thank you and God bless.<br><br>
                            Sincerely,<br>
                            The Administration";

            $mail->Body = $message_body;

            if (isset($_POST['send'])) {
                $mail->send();
                $success_message = "Email sent successfully!";

                // Save the schedule to the database
                $insert_query = "INSERT INTO schedules (admin_id, student_id, date, description, location) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                $description = "Meeting with parents of $student_name";
                $location = "Nasugbu East Senior High School"; // Optional, you can change or make it dynamic
                $insert_stmt->bind_param("iisss", $admin_id, $student_id, $date, $description, $location);
                $insert_stmt->execute();
            } elseif (isset($_POST['preview'])) {
                $preview_message = $message_body;
            }
        } catch (Exception $e) {
            $error_message = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "No email addresses found for parents.";
    }
}
?>

<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="container-fluid mb-5">
            <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
                <div class="row pt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div class="container-fluid p-2">
                                    <h3><strong>Contact Parents of <span class="text-success"><?php echo ucwords(htmlspecialchars($student['first_name'] . ' ' . $student['last_name'])); ?></span></strong></h3>
                                </div>
                                <a href="admin-schedule.php" class="btn btn-outline-success">Schedules</a>
                            </div>
                        </div>

                        <!-- Display the email addresses that will be used -->
                        <div class="alert alert-info">
                            <strong>Email will be sent to:</strong>
                            <ul>
                                <?php if (!empty($student['mother_email'])) : ?>
                                    <li><?php echo htmlspecialchars($student['mother_email']); ?></li>
                                <?php endif; ?>
                                <?php if (!empty($student['father_email'])) : ?>
                                    <li><?php echo htmlspecialchars($student['father_email']); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php if (isset($success_message)) : ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success_message); ?>
                                <a href="admin-schedule.php">View Schedules</a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error_message)) : ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" class="form-control" required value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
                            </div>
                            <?php if (isset($preview_message)) : ?>
                                <div class="mt-4">
                                    <h4><strong>Preview of the Email</strong></h4>
                                    <div class="border p-3">
                                        <?php echo $preview_message; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <button type="submit" name="preview" class="btn btn-primary mt-2">Preview</button>
                            <button type="submit" name="send" class="btn btn-success mt-2">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
