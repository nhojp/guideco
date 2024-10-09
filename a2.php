<?php
include 'conn.php'; // Use conn.php for your database connection

// Handle teacher creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement for inserting a teacher
    $stmt = $conn->prepare("INSERT INTO teachers (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $username, $password); // 'ssss' indicates four string parameters

    // Execute the statement
    if ($stmt->execute()) {
        echo "Teacher added successfully!";
    } else {
        echo "Error adding teacher: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
}

// Fetch teachers from the database
$result = $conn->query("SELECT * FROM teachers");
$teachers = $result->fetch_all(MYSQLI_ASSOC);
?>

    <h1>Manage Teachers</h1>
    <form method="POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Add Teacher</button>
    </form>

    <h2>Existing Teachers</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
        </tr>
        <?php foreach ($teachers as $teacher): ?>
        <tr>
            <td><?php echo $teacher['id']; ?></td>
            <td><?php echo $teacher['first_name']; ?></td>
            <td><?php echo $teacher['last_name']; ?></td>
            <td><?php echo $teacher['username']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>


