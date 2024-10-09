<?php
include 'conn.php'; // Include your connection file

// Handle strand creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $strand_name = $_POST['strand_name'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO strands (name) VALUES (?)");
    $stmt->bind_param("s", $strand_name); // 's' specifies the variable type => 'string'

    // Execute the statement
    if ($stmt->execute()) {
        echo "Strand added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
}

// Fetch strands
$strands = $conn->query("SELECT * FROM strands");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Strands</title>
</head>
<body>
    <h1>Manage Strands</h1>
    <form method="POST">
        <input type="text" name="strand_name" placeholder="Strand Name" required>
        <button type="submit">Add Strand</button>
    </form>

    <h2>Existing Strands</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Strand Name</th>
        </tr>
        <?php if ($strands->num_rows > 0): ?>
            <?php while ($strand = $strands->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($strand['id']); ?></td>
                    <td><?php echo htmlspecialchars($strand['name']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">No strands found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); // Close the connection ?>

</body>
</html>
