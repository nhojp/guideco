<?php
include 'conn.php'; // Database connection
include 'head.php'; // Include head section
include 'admin-nav.php';

// Get student ID from URL
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch student data
$query = "SELECT s.*, sec.section_name, sec.grade_level, m.name AS mother_name, f.name AS father_name, 
                 m.contact_number AS mother_contact, f.contact_number AS father_contact, 
                 m.email AS mother_email, f.email AS father_email, 
                 m.occupation AS mother_occupation, f.occupation AS father_occupation, 
                 m.address AS mother_address, f.address AS father_address
          FROM students s
          JOIN sections sec ON s.section_id = sec.id
          LEFT JOIN mothers m ON s.id = m.student_id
          LEFT JOIN fathers f ON s.id = f.student_id
          WHERE s.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$violation_query = "SELECT v.reported_at, vl.violation_description, 
                           COALESCE(t.first_name, g.first_name) AS reported_by
                    FROM violations v
                    JOIN violation_list vl ON v.violation_id = vl.id
                    LEFT JOIN teachers t ON v.teacher_id = t.id
                    LEFT JOIN guards g ON v.guard_id = g.id
                    WHERE v.student_id = ?";

$violation_stmt = $conn->prepare($violation_query);
$violation_stmt->bind_param("i", $student_id);
$violation_stmt->execute();
$violations = $violation_stmt->get_result();
$violation_count = $violations->num_rows; // Count the number of violations
?>
<style>
    .scrollable-content {
        max-height: 395px;
        /* Adjust the height as needed */
        overflow-y: auto;
    }
</style>
<main class="flex-fill mt-5">
    <div class="container mt-4">
        <div class="container-fluid mb-5">
            <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
                <div class="row pt-3">
                    <div class="col-md-1">
                        <button onclick="window.location.href='admin-user-student.php'">back</button>
                    </div>
                    <div class="col-md-8">
                        <div class="container-fluid p-2">
                            <?php if ($student) : ?>
                                <h3><strong><span class="text-success"><?php echo ucwords(htmlspecialchars($student['first_name'] . ' ' . $student['last_name'])); ?></span> from <span class="text-success"><?php echo ucwords(htmlspecialchars($student['grade_level'])); ?> - <?php echo ucwords(htmlspecialchars($student['section_name'])); ?></span></strong></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <!-- Conditionally render the button based on the violation count -->
                        <?php if ($violation_count > 5): ?>
                            <button class="btn btn-secondary btn-block" disabled>
                                <i class="fas fa-ban"></i> Good Moral (Disabled)
                            </button>
                        <?php else: ?>
                            <a href="goodmoral.php?id=<?php echo urlencode($student['id']); ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-print"></i> Good Moral
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
                <?php if ($student) : ?>
                    <div class="row">
                        <!-- Personal Information and Violations -->
                        <div class="col-md-6">  
                            <h3 class="text-center"><strong>Personal Information</strong></h3>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Age:</strong> <?php echo ucwords(htmlspecialchars($student['age'])); ?></li>
                                <li class="list-group-item"><strong>Sex:</strong> <?php echo ucwords(htmlspecialchars($student['sex'])); ?></li>
                                <li class="list-group-item"><strong>Section:</strong> <?php echo ucwords(htmlspecialchars($student['section_name'])); ?></li>
                                <li class="list-group-item"><strong>Grade:</strong> <?php echo ucwords(htmlspecialchars($student['grade_level'])); ?></li>
                                <li class="list-group-item"><strong>Contact Number:</strong> <?php echo ucwords(htmlspecialchars($student['contact_number'])); ?></li>
                                <li class="list-group-item"><strong>Religion:</strong> <?php echo ucwords(htmlspecialchars($student['religion'])); ?></li>
                                <li class="list-group-item"><strong>Birthdate:</strong> <?php echo ucwords(htmlspecialchars($student['birthdate'])); ?></li>
                            </ul>
                        </div>

                        <!-- Violations Section -->
                        <div class="col-md-6">
                            <h3 class="text-center"><strong>Violations</strong></h3>
                            <div class="scrollable-content">
                                <?php if ($violations->num_rows > 0) : ?>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Violation</th>
                                                <th>Reported By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($violation = $violations->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo ucwords(htmlspecialchars($violation['violation_description'])); ?></td>
                                                    <td><?php echo ucwords(htmlspecialchars($violation['reported_by'] ?: 'N/A')); ?></td>
                                                    <td><?php echo ucwords(htmlspecialchars($violation['reported_at'])); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else : ?>
                                    <p>No violations reported.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <!-- Mother and Father Information -->
                        <div class="col-md-6">
                            <h3 class="text-center"><strong>Mother's Information</strong></h3>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Mother's Name:</strong> <?php echo ucwords(htmlspecialchars($student['mother_name'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Mother's Contact:</strong> <?php echo ucwords(htmlspecialchars($student['mother_contact'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Mother's Email:</strong> <?php echo htmlspecialchars($student['mother_email'] ?: 'N/A'); ?></li>
                                <li class="list-group-item"><strong>Mother's Occupation:</strong> <?php echo ucwords(htmlspecialchars($student['mother_occupation'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Mother's Address:</strong> <?php echo ucwords(htmlspecialchars($student['mother_address'] ?: 'N/A')); ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-center"><strong>Father's Information</strong></h3>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Father's Name:</strong> <?php echo ucwords(htmlspecialchars($student['father_name'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Father's Contact:</strong> <?php echo ucwords(htmlspecialchars($student['father_contact'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Father's Email:</strong> <?php echo htmlspecialchars($student['father_email'] ?: 'N/A'); ?></li>
                                <li class="list-group-item"><strong>Father's Occupation:</strong> <?php echo ucwords(htmlspecialchars($student['father_occupation'] ?: 'N/A')); ?></li>
                                <li class="list-group-item"><strong>Father's Address:</strong> <?php echo ucwords(htmlspecialchars($student['father_address'] ?: 'N/A')); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php else : ?>
                    <p>No student found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
