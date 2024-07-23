<?php
// Including necessary files and starting session
include 'conn.php';
include 'head.php';
include 'admin-header.php';
// Check if user is logged in and is a guard
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not a guard
    exit;
}

// SQL query to fetch violations data
$violations_query = "SELECT * FROM violation_list";
$violations_result = mysqli_query($conn, $violations_query);

if (!$violations_result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle form submission to add a new violation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_violation'])) {
        $violation_description = $_POST['violation_description'];

        $query = "INSERT INTO violation_list (violation_description) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $violation_description);

        try {
            $stmt->execute();
            echo "<div class='alert alert-success' role='alert'>Violation added successfully.</div>";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Duplicate entry error code
                echo "<div class='alert alert-danger' role='alert'>Violation already exists.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Failed to add violation: " . $e->getMessage() . "</div>";
            }
        }

        $stmt->close();
    } elseif (isset($_POST['edit_violation'])) {
        $violation_id = $_POST['violation_id'];
        $violation_description = $_POST['violation_description'];

        $query = "UPDATE violation_list SET violation_description = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $violation_description, $violation_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Violation updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to update violation: " . $conn->error . "</div>";
        }

        $stmt->close();
    } elseif (isset($_POST['delete_violation'])) {
        $violation_id = $_POST['violation_id'];

        $query = "DELETE FROM violation_list WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $violation_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Violation deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to delete violation: " . $conn->error . "</div>";
        }

        $stmt->close();
    }
}

?>

<div class="container mt-5">
    <h2>Violation List</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addViolationModal">Add Violation</button>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Violation Description</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($violations_result)) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['violation_description']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editViolationModal<?php echo $row['id']; ?>">Edit</button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteViolationModal<?php echo $row['id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <!-- Edit Violation Modal -->
                    <div class="modal fade" id="editViolationModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editViolationModalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editViolationModalLabel">Edit Violation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="violation_description">Violation Description</label>
                                            <input type="text" class="form-control" id="violation_description" name="violation_description" value="<?php echo htmlspecialchars($row['violation_description']); ?>" required>
                                            <input type="hidden" name="violation_id" value="<?php echo $row['id']; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="edit_violation">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Add Violation Modal -->
                    <div class="modal fade" id="addViolationModal" tabindex="-1" role="dialog" aria-labelledby="addViolationModalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addViolationModalLabel">Add Violation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="violation_description">Violation Description</label>
                                            <input type="text" class="form-control" id="violation_description" name="violation_description" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="add_violation">Add Violation</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Violation Modal -->
                    <div class="modal fade" id="deleteViolationModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteViolationModalLabel" aria-hidden="true" data-backdrop="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteViolationModalLabel">Delete Violation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this violation?</p>
                                        <input type="hidden" name="violation_id" value="<?php echo $row['id']; ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger" name="delete_violation">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<?php include "footer.php";
include "admin-footer.php"; ?>