<?php
include 'conn.php'; // Database connection

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
    $stmt->bind_param("ssss", $first_name, $last_name, $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='success-message'>Teacher added successfully!</div>";
    } else {
        echo "<div class='error-message'>Error adding teacher: " . $stmt->error . "</div>";
    }

    $stmt->close(); // Close the statement
}

// Fetch teachers from the database
$result = $conn->query("SELECT * FROM teachers");
$teachers = $result->fetch_all(MYSQLI_ASSOC);
?>


<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


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

    .btn-circle {
        width: 35px;   
        height: 35px;  
        border-radius: 50%; 
       display: flex;
       justify-content: center;  
       align-items: center;      
       padding: 0;
    }

</style>


<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <h1>Manage Teachers</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTeacherModal">
            Add Teacher
        </button>

            
            <div class="table-container">
                <table class="table table-hover mt-4 border">
                    <thead class="thead-custom">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?php echo $teacher['id']; ?></td>
                            <td><?php echo $teacher['first_name']; ?></td>
                            <td><?php echo $teacher['last_name']; ?></td>
                            <td><?php echo $teacher['username']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add Teacher</h5>
                <button type="button" class="btn-danger btn btn btn-circle" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="teacher-form">
                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="First Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" placeholder="Last Name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>


