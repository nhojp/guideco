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

<style>

    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #f5f5f5;
    }

 
    .modal-header {
        background-color: #1F5F1E;
        color: white;
    }

    .btn-primary {
        background-color: #1F5F1E;
        border: none;
        margin-bottom: 20px;
    }

    .btn-primary:hover {
        background-color: #145214;
    }

 
    .table {
        border-collapse: collapse;
        background-color: #f9f9f9;
    }

    .table th, .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
       
    }

    .table-hover tbody tr:hover {
        background-color: #e2e2e2;
    }


   
    .success-message {
        color: green;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .error-message {
        color: red;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .thead-custom {
        background-color: #0C2D0B;
        color: white;
    }

        .table-container {
        max-height: 400px; 
        overflow-y: auto; 
        
        }

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Strands</title>
</head>
<body>
            <div class="container-fluid mb-5">
                <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">

                    <h1>Manage Strands</h1>
                    <form method="POST" class="mt-3">
                        <div class="form-group">
                            <label for="strand-name">Strand Name</label>
                            <input type="text" class="form-control" id="strand-name" name="strand_name" placeholder="Enter strand name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Strand</button>
                    </form>


                   
                    <div class="table-responsive">
                        <table class="table table-hover mt-4">
                            <thead class="thead-custom">
                                <tr>
                                    <th>ID</th>
                                    <th>Strand Name</th>
                                </tr>
                            </thead>
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
                </div>
            </div>
                

</body>
</html>
