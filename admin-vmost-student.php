<?php
include 'head.php';
include 'conn.php';
include 'admin-header.php';

// Query to get students with the most violation count, excluding those with zero violations
$query = "
    SELECT s.id, s.first_name, s.last_name, g.grade_name, sec.section_name, COUNT(v.id) AS violation_count
    FROM students s
    JOIN sections sec ON s.section_id = sec.id
    JOIN grades g ON sec.grade_id = g.id
    LEFT JOIN violations v ON s.id = v.student_id
    GROUP BY s.id
    HAVING violation_count > 0
    ORDER BY violation_count DESC
";

$result = $conn->query($query);
?>

<div class="container">
    <h2>Students with Most Violations</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Grade</th>
                <th>Section</th>
                <th>Violation Count</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['grade_name']); ?></td>
                <td><?php echo htmlspecialchars($row['section_name']); ?></td>
                <td><?php echo htmlspecialchars($row['violation_count']); ?></td>
                <td>
                    <!-- Button to navigate to admin-vcontact.php -->
                    <a href="admin-vcontact-student.php?student_id=<?php echo $row['id']; ?>" class="btn btn-primary">
                        <i class="fa fa-phone"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
