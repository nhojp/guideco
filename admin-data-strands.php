<?php
include 'conn.php'; // Include your connection file

// Handle strand creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['strand_name']) && !isset($_POST['strand_id'])) {
    $strand_name = $_POST['strand_name'];
    $strand_description = $_POST['strand_description'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO strands (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $strand_name, $strand_description); // 'ss' for two strings

    // Execute the statement
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Strand added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close(); // Close the statement
}
$editSuccess = isset($_SESSION['edit_success']) ? $_SESSION['edit_success'] : false;
$addSuccess = isset($_SESSION['add_success']) ? $_SESSION['add_success'] : false;
$errorMessage = '';

// Handle strand editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['strand_id']) && isset($_POST['edit_strand'])) {
    $strand_id = $_POST['strand_id'];
    $strand_name = $_POST['strand_name'];
    $strand_description = $_POST['strand_description'];

    // Prepare and bind update query
    $stmt = $conn->prepare("UPDATE strands SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $strand_name, $strand_description, $strand_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Strand updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close(); // Close the statement
}

// Handle strand deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_strand'])) {
    $strand_id = $_POST['strand_id'];

    // Prepare and bind delete query
    $stmt = $conn->prepare("DELETE FROM strands WHERE id = ?");
    $stmt->bind_param("i", $strand_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Strand deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close(); // Close the statement
}

// Fetch strands
$strands = $conn->query("SELECT * FROM strands");

?>
<style>
    .thead-custom {
        background-color: #0C2D0B;
        color: white;
    }
</style>
<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>Strand List</strong></h3>
                </div>
            </div>
            <div class="col-md-6">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a section name...">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addStrandModal">
                        Add
                    </button>
                </div>
            </div>
        </div>

        <?php if ($editSuccess || $addSuccess || !empty($errorMessage)) : ?>
            <div class="alert <?php echo $editSuccess || $addSuccess ? 'alert-success' : 'alert-danger'; ?> mt-4" role="alert">
                <?php
                if ($editSuccess) {
                    echo 'Section updated successfully';
                } elseif ($addSuccess) {
                    echo 'Section added successfully';
                } elseif (!empty($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="card-body">
            <!-- Add Strand Modal -->
            <div class="modal fade" id="addStrandModal" tabindex="-1" role="dialog" aria-labelledby="addStrandModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStrandModalLabel">Add New Strand</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="strand-name">Strand Name</label>
                                    <input type="text" class="form-control" id="strand-name" name="strand_name" placeholder="Enter strand name" required>
                                </div>
                                <div class="form-group">
                                    <label for="strand-description">Strand Description</label>
                                    <textarea class="form-control" id="strand-description" name="strand_description" placeholder="Enter strand description" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Strand</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Strand Table -->
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="thead-custom">
                        <tr>
                            <th style="width:80%">Strand Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($strands->num_rows > 0): ?>
                            <?php while ($strand = $strands->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($strand['name']); ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editModal-<?php echo $strand['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-<?php echo $strand['id']; ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal-<?php echo $strand['id']; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Strand</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="strand_id" value="<?php echo $strand['id']; ?>">
                                                    <input type="hidden" name="edit_strand" value="1">
                                                    <div class="form-group">
                                                        <label for="strand-name-<?php echo $strand['id']; ?>">Strand Name</label>
                                                        <input type="text" class="form-control" id="strand-name-<?php echo $strand['id']; ?>" name="strand_name" value="<?php echo htmlspecialchars($strand['name']); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="strand-description-<?php echo $strand['id']; ?>">Strand Description</label>
                                                        <textarea class="form-control" id="strand-description-<?php echo $strand['id']; ?>" name="strand_description" required><?php echo htmlspecialchars($strand['description']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal-<?php echo $strand['id']; ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Deletion</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this strand?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form method="POST">
                                                    <input type="hidden" name="strand_id" value="<?php echo $strand['id']; ?>">
                                                    <input type="hidden" name="delete_strand" value="1">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No strands available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>

</html>