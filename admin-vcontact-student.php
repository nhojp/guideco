<?php
include 'head.php';
include 'conn.php';
include 'admin-header.php';

// Get student ID from query parameter
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Query to get student details, grade, section, and violations
$student_query = "
    SELECT s.id, s.first_name, s.last_name, g.grade_name, sec.section_name
    FROM students s
    JOIN sections sec ON s.section_id = sec.id
    JOIN grades g ON sec.grade_id = g.id
    WHERE s.id = ?
";

$violations_query = "
    SELECT v.reported_at, v.violation
    FROM violations v
    WHERE v.student_id = ?
";

// Query to get mother's details
$mother_query = "
    SELECT parent_id, name, contact_number, email, occupation, address
    FROM mothers
    WHERE student_id = ?
";

// Query to get father's details
$father_query = "
    SELECT parent_id, name, contact_number, email, occupation, address
    FROM fathers
    WHERE student_id = ?
";

// Prepare and execute the queries
$stmt_student = $conn->prepare($student_query);
$stmt_student->bind_param('i', $student_id);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
$student = $result_student->fetch_assoc();

$stmt_violations = $conn->prepare($violations_query);
$stmt_violations->bind_param('i', $student_id);
$stmt_violations->execute();
$result_violations = $stmt_violations->get_result();

$stmt_mother = $conn->prepare($mother_query);
$stmt_mother->bind_param('i', $student_id);
$stmt_mother->execute();
$result_mother = $stmt_mother->get_result();
$mother = $result_mother->fetch_assoc();

$stmt_father = $conn->prepare($father_query);
$stmt_father->bind_param('i', $student_id);
$stmt_father->execute();
$result_father = $stmt_father->get_result();
$father = $result_father->fetch_assoc();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
            <p><strong>Grade - Section:</strong> <?php echo htmlspecialchars($student['grade_name'] . ' - ' . $student['section_name']); ?></p>
        </div>
    </div>

    <div class="row mt-4">
        <?php if ($result_violations->num_rows > 0) : ?>
            <?php while ($violation = $result_violations->fetch_assoc()) : ?>
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h3 class="card-title">Violation</h3>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($violation['reported_at']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($violation['violation']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-md-12">
                <p class="card-text">No violations found for this student.</p>
            </div>
        <?php endif; ?>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Mother's Information</h3>
                    <?php if ($mother) : ?>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($mother['name']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($mother['contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($mother['email']); ?></p>
                        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($mother['occupation']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($mother['address']); ?></p>
                    <?php else : ?>
                        <p>No mother's information found for this student.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Father's Information</h3>
                    <?php if ($father) : ?>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($father['name']); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($father['contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($father['email']); ?></p>
                        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($father['occupation']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($father['address']); ?></p>
                    <?php else : ?>
                        <p>No father's information found for this student.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="contactMother" class="form-label">Contact Mother</label>
                <textarea id="contactMother" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="contactFather" class="form-label">Contact Father</label>
                <textarea id="contactFather" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Send Message</button>
    <a href="students-list.php" class="btn btn-secondary mt-4">Back to List</a>
</div>

<?php include 'admin-footer.php'; ?>
<?php include 'footer.php'; ?>

<!-- Include jQuery first, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>

</html>