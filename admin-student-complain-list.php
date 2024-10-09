<?php
$sql = "SELECT 
                id,
                victimFirstName,
                victimMiddleName,
                victimLastName,
                victimDOB,
                victimAge,
                victimSex,
                victimGrade,
                victimSection,
                victimAdviser,
                victimContact,
                victimAddress,
                motherName,
                motherOccupation,
                motherAddress,
                motherContact,
                fatherName,
                fatherOccupation,
                fatherAddress,
                fatherContact,
                complainantFirstName,
                complainantMiddleName,
                complainantLastName,
                relationshipToVictim,
                complainantContact,
                complainantAddress,
                complainedFirstName,
                complainedMiddleName,
                complainedLastName,
                complainedDOB,
                complainedAge,
                complainedSex,
                complainedGrade,
                complainedSection,
                complainedAdviser,
                complainedContact,
                complainedAddress,
                complainedMotherName,
                complainedMotherOccupation,
                complainedMotherAddress,
                complainedMotherContact,
                complainedFatherName,
                complainedFatherOccupation,
                complainedFatherAddress,
                complainedFatherContact,
                caseDetails,
                actionTaken,
                recommendations
            FROM complaints_student";

$result = $conn->query($sql);

$successMessage = '';
$errorMessage = '';
$deleteSuccess = false; // Flag to indicate success

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    $query = "DELETE FROM complaints_student WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $deleteSuccess = true; // Set the success flag
    } else {
        $errorMessage = 'Error deleting record: ' . mysqli_error($conn);
    }
}
?>

<style>
        
        .btn-custom {
            background-color: #1F5F1E; 
            color: white; 
            border: none; 
        }

        .btn-custom:hover {
            background-color: #389434; 
            color: white; 
        }

        .btn-custom:focus, .btn-custom:active {
            box-shadow: none; 
            outline: none; 
        }

        .thead-custom {
            background-color: #0C2D0B;
            color: white;
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

        .table-container {
        max-height: 400px; 
        overflow-y: auto; 
        }
        

        

      
    </style>




<div class="container-fluid mb-5">
    <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
        <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3><strong>Student Complaints</strong></h3>
                </div>
            </div>
            
            <div class="col-md-5">
                <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
            </div>
            <div class="col-md-1">
                <div class="btn-group">
                    <button type="button" class="btn btn-custom" data-bs-toggle="dropdown" aria-expanded="false">
                        Add
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="admin-s-1.php" onclick="showLoading('admin-p-1.php')">Complain a Student</a></li>
                    </ul>
                    
                </div>
            </div>
        </div>

        <?php if ($deleteSuccess) : ?>
            <div class="alert alert-success mt-4" role="alert">
                Record deleted successfully
            </div>
            <script>
                // Check if the page has already been reloaded
                if (!localStorage.getItem('reloaded')) {
                    localStorage.setItem('reloaded', 'true'); // Set the flag
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000); // 1000 milliseconds = 1 second
                } else {
                    localStorage.removeItem('reloaded'); // Clear the flag
                }
            </script>
        <?php elseif (!empty($errorMessage)) : ?>
            <div class="alert alert-danger mt-4" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

    <div class="table-container">
        <table class="table table-hover mt-4 border">
            <thead class="thead-custom">
                <tr>
                    <th style="width:40%;">Victim Name</th>
                    <th style="width:40%;">Complained Person</th>
                    <th class="text-center" style="width:20%;">Action</th>
                </tr>
            </thead>
            <tbody id="personnelTable">
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo ucwords($row['victimFirstName']) . ' ' . ucwords($row['victimMiddleName']) . ' ' . ucwords($row['victimLastName']); ?></td>
                            <td><?php echo ucwords($row['complainedFirstName']) . ' ' . ucwords($row['complainedMiddleName']) . ' ' . ucwords($row['complainedLastName']); ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info " data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-guideco text-white">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Delete Record</h5>
                                        <button type="button" class="btn-danger btn" data-bs-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this record?
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header text-white" style="background-color: #1F5F1E;">
                                        <h5 class="modal-title" id="viewModalLabel<?php echo $row['id']; ?>">Complaint Details</h5>
                                        <button type="button" class="btn-danger btn btn btn-circle" data-bs-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Display detailed information here -->
                                        <div class="container">
                                            <div class="row text-light text-center justify-content-center pt-2 rounded" style="background-color: #0C2D0B;">
                                                <h5><b>Victim Information</b></h5>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-6">
                                                    <p><strong>Victim Name:</strong> <?php echo $row['victimFirstName'] . ' ' . $row['victimMiddleName'] . ' ' . $row['victimLastName']; ?></p>
                                                    <p><strong>Date of Birth:</strong> <?php echo $row['victimDOB']; ?></p>
                                                    <p><strong>Age:</strong> <?php echo $row['victimAge']; ?></p>
                                                    <p><strong>Sex:</strong> <?php echo $row['victimSex']; ?></p>
                                                    <p><strong>Grade:</strong> <?php echo $row['victimGrade']; ?></p>
                                                    <p><strong>Section:</strong> <?php echo $row['victimSection']; ?></p>
                                                    <p><strong>Adviser:</strong> <?php echo $row['victimAdviser']; ?></p>
                                                    <p><strong>Victim Contact:</strong> <?php echo $row['victimContact']; ?></p>
                                                    <p><strong>Victim Address:</strong> <?php echo $row['victimAddress']; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Mother Name:</strong> <?php echo $row['motherName']; ?></p>
                                                    <p><strong>Occupation:</strong> <?php echo $row['motherOccupation']; ?></p>
                                                    <p><strong>Contact:</strong> <?php echo $row['motherContact']; ?></p>
                                                    <p><strong>Address:</strong> <?php echo $row['motherAddress']; ?></p>

                                                    <p><strong>Father Name:</strong> <?php echo $row['fatherName']; ?></p>
                                                    <p><strong>Occupation:</strong> <?php echo $row['fatherOccupation']; ?></p>
                                                    <p><strong>Contact:</strong> <?php echo $row['fatherContact']; ?></p>
                                                    <p><strong>Address:</strong> <?php echo $row['fatherAddress']; ?></p>
                                                </div>
                                            </div>
                                            <div class="row text-light text-center justify-content-center pt-2 rounded" style="background-color: #0C2D0B;">
                                                <h5><b>Complainant Information</b></h5>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> <?php echo $row['complainantFirstName'] . ' ' . $row['complainantMiddleName'] . ' ' . $row['complainantLastName']; ?></p>
                                                    <p><strong>Relationship to Victim:</strong> <?php echo $row['relationshipToVictim']; ?></p>
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                <p><strong>Contact:</strong> <?php echo $row['complainantContact']; ?></p>
                                                <p><strong>Address:</strong> <?php echo $row['complainantAddress']; ?></p>
                                                </div>
                                            </div>
                                            <div class="row text-light pt-2 rounded" style="background-color: #0C2D0B;">
                                                <div class="col text-center">
                                                    <h5><b>Action Taken</b></h5>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-12">
                                                    <p><strong>Action:</strong> <?php echo $row['actionTaken']; ?></p>
                                                </div>
                                            </div>
                                            <!-- Recommendation -->
                                            <div class="row text-light pt-2 rounded"style="background-color: #0C2D0B;" >
                                                <div class="col text-center">
                                                    <h5><b>Recommendation</b></h5>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-12">
                                                    <p><strong>Recommendation:</strong> <?php echo $row['recommendations']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <a href="admin-print-student.php?id=<?php echo $row['id']; ?>" class="btn btn-success float-right" target="_blank" style="width: 100%;">Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.querySelectorAll('#personnelTable tr');

            rows.forEach(function(row) {
                var name = row.cells[0].innerText.toLowerCase();
                var position = row.cells[1].innerText.toLowerCase();

                if (name.includes(input) || position.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>