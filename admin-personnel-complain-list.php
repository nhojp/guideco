<?php
$sql = "SELECT 
            *
        FROM complaints";

$result = $conn->query($sql);

$successMessage = '';
$errorMessage = '';
$deleteSuccess = false; // Flag to indicate success

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['delete_id']);
    $query = "DELETE FROM complaints WHERE id = $id";
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
        .thead-custom {
            background-color: #0C2D0B;
            color: white;
        }

        .modal-header.bg-guideco {
            background-color: #1F5F1E; 
            color: #fff; 
        }

        .section-header {
            background-color: #0C2D0B; 
            color: #fff; 
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 5px; 
            text-align: center;
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
                    <h3><strong>School Personnel Complaints</strong></h3>
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
                        <li><a class="dropdown-item" href="admin-p-1.php">Complain a School Personnel</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Alerts -->
                <?php if ($deleteSuccess) : ?>
                    <div class="alert alert-success mt-4" role="alert">
                        Record deleted successfully
                    </div>
                <?php elseif (!empty($errorMessage)) : ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

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
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-guideco text-white">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $row['id']; ?>">Delete Record</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this record?
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                                    <div class="modal-header bg-guideco text-white">
                                        <h5 class="modal-title" id="viewModalLabel<?php echo $row['id']; ?>">Complaint Details</h5>
                                        <button type="button" class="btn-danger btn btn btn-circle" data-bs-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row section-header">
                                                <div class="col text-center">
                                                    <h5><b>Victim Information</b></h5>
                                                </div>
                                            </div>

                                            <div class="row pt-2">
                                                <div class="col-md-6">
                                                    <p><strong>Victim Name:</strong> <?php echo ucwords($row['victimFirstName']) . ' ' . ucwords($row['victimMiddleName']) . ' ' . ucwords($row['victimLastName']); ?></p>
                                                    <p><strong>Date of Birth:</strong> <?php echo $row['victimDOB']; ?></p>
                                                    <p><strong>Age:</strong> <?php echo $row['victimAge']; ?></p>
                                                    <p><strong>Sex:</strong> <?php echo ucwords($row['victimSex']); ?></p>
                                                    <p><strong>Grade:</strong> <?php echo ucwords($row['victimGrade']); ?></p>
                                                    <p><strong>Section:</strong> <?php echo ucwords($row['victimSection']); ?></p>
                                                    <p><strong>Adviser:</strong> <?php echo ucwords($row['victimAdviser']); ?></p>
                                                    <p><strong>Victim Contact:</strong> <?php echo $row['victimContact']; ?></p>
                                                    <p><strong>Victim Address:</strong> <?php echo ucwords($row['victimAddress']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Mother Name:</strong> <?php echo ucwords($row['motherName']); ?></p>
                                                    <p><strong>Occupation:</strong> <?php echo ucwords($row['motherOccupation']); ?></p>
                                                    <p><strong>Contact:</strong> <?php echo $row['motherContact']; ?></p>
                                                    <p><strong>Address:</strong> <?php echo ucwords($row['motherAddress']); ?></p>
                                                    <p><strong>Father Name:</strong> <?php echo ucwords($row['fatherName']); ?></p>
                                                    <p><strong>Occupation:</strong> <?php echo ucwords($row['fatherOccupation']); ?></p>
                                                    <p><strong>Contact:</strong> <?php echo $row['fatherContact']; ?></p>
                                                    <p><strong>Address:</strong> <?php echo ucwords($row['fatherAddress']); ?></p>
                                                </div>
                                            </div>
                                            <div class="row section-header">
                                                <div class="col text-center">
                                                    <h5><b>Complainant Information</b></h5>
                                                </div>
                                            </div>

                                            <div class="row pt-2">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> <?php echo ucwords($row['complainantFirstName']) . ' ' . ucwords($row['complainantMiddleName']) . ' ' . ucwords($row['complainantLastName']); ?></p>
                                                    <p><strong>Relationship to Victim:</strong> <?php echo ucwords($row['relationshipToVictim']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Contact:</strong> <?php echo $row['complainantContact']; ?></p>
                                                    <p><strong>Address:</strong> <?php echo ucwords($row['complainantAddress']); ?></p>
                                                </div>
                                            </div>
                                            <!-- Complained Person Information -->
                                            <div class="row section-header">
                                                <div class="col text-center">
                                                    <h5><b>Complained Person Information</b></h5>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-6">
                                                    <p><strong>Name:</strong> <?php echo ucwords($row['complainedFirstName']) . ' ' . ucwords($row['complainedMiddleName']) . ' ' . ucwords($row['complainedLastName']); ?></p>
                                                    <p><strong>Position:</strong> <?php echo ucwords($row['complainedDesignation']); ?></p>
                                                </div>
                                            </div>
                                            <!-- Case Details -->
                                            <div class="row section-header">
                                                <div class="col text-center">
                                                    <h5><b>Case Details</b></h5>
                                                </div>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="col-md-12">
                                                    <p><strong>Details:</strong> <?php echo $row['caseDetails']; ?></p>
                                                </div>
                                            </div>
                                            <!-- Action Taken -->
                                            <div class="row section-header">
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
                                            <div class="row section-header">
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
                                    <a href="admin-print-personnel.php?id=<?php echo $row['id']; ?>" class="btn btn-success float-right" target= "_blank" style="width: 100%;">Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">No complaints found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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