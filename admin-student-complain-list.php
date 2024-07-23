    <?php
    include "conn.php";
    // Start session
    session_start();

    // Check if user is logged in and is an admin
    if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
        header('Location: index.php'); // Redirect if not logged in or not admin
        exit;
    }

    include "head.php";
    include "admin-header.php";

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
        .btn-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #28a745;
            /* Green color */
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-circle:hover {
            background-color: #218838;
        }

        .modal-custom {
            margin-top: 100px;
        }

        @media (max-width: 767.98px) {
            .modal-custom {
                margin: 100px auto;
                max-width: 90%;
            }
        }

        .search {
            width: 100%;
            padding: 15px;
            border-radius: 60px;
            margin-top: 5px;
            border: none;
            background-color: lightgray;
        }
    </style>
    <div class="container-fluid mt-2 mb-5">
        <div class="container-fluid bg-white mt-2 rounded-lg pb-2">
            <div class="row pt-3">
                <div class="col-md-5">
                    <div class="container-fluid p-2">
                        <h3 class="p-3"><b>
                                Student Complaints</b>
                        </h3>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-circle mt-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        +
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="admin-s-1.php" onclick="showLoading('admin-p-1.php')">Complain a Student</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container-fluid p-2 rounded-lg">
                        <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                    </div>
                </div>
            </div>
            <div id="loadingScreen" class="loading-screen">
                <div class="spinner"></div>
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

            <table class="table text-center table-hover">
                <thead class="bg-dark text-white">
                    <tr>
                        <th style="width:40%;">Victim Name</th>
                        <th style="width:40%;">Complained Person</th>
                        <th style="width:20%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo ucwords($row['victimFirstName']) . ' ' . ucwords($row['victimMiddleName']) . ' ' . ucwords($row['victimLastName']); ?></td>
                                <td><?php echo ucwords($row['complainedFirstName']) . ' ' . ucwords($row['complainedMiddleName']) . ' ' . ucwords($row['complainedLastName']); ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewModal<?php echo $row['id']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>



                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $row['id']; ?>" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog modal-custom" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
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

                            <!-- Modal -->
                            <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?php echo $row['id']; ?>" aria-hidden="true" data-backdrop="false">
                                <div class="modal-dialog modal-lg modal-custom" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel<?php echo $row['id']; ?>">Complaint Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Display detailed information here -->
                                            <div class="container">
                                                <div class="row bg-dark text-light text-center justify-content-center pt-2 rounded">
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
                                                <div class="row bg-dark text-light text-center justify-content-center pt-2 rounded">
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
                                                <div class="row bg-dark text-light text-center justify-content-center pt-2 rounded">
                                                    <h5><b>Complained Person Information</b></h5>
                                                </div>
                                                <div class="row pt-2">
                                                    <div class="col-md-6">
                                                        <p><strong>Name:</strong> <?php echo $row['complainedFirstName'] . ' ' . $row['complainedMiddleName'] . ' ' . $row['complainedLastName']; ?></p>
                                                        <p><strong>Date of Birth:</strong> <?php echo $row['complainedDOB']; ?></p>
                                                        <p><strong>Age:</strong> <?php echo $row['complainedAge']; ?></p>
                                                        <p><strong>Sex:</strong> <?php echo $row['complainedSex']; ?></p>
                                                        <p><strong>Grade:</strong> <?php echo $row['complainedGrade']; ?></p>
                                                        <p><strong>Section:</strong> <?php echo $row['complainedSection']; ?></p>
                                                        <p><strong>Adviser:</strong> <?php echo $row['complainedAdviser']; ?></p>
                                                        <p><strong>Contact:</strong> <?php echo $row['complainedContact']; ?></p>
                                                        <p><strong>Address:</strong> <?php echo $row['complainedAddress']; ?></p>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <p><strong>Mother Name:</strong> <?php echo $row['complainedMotherName']; ?></p>
                                                        <p><strong>Occupation:</strong> <?php echo $row['complainedMotherOccupation']; ?></p>
                                                        <p><strong>Contact:</strong> <?php echo $row['complainedMotherContact']; ?></p>
                                                        <p><strong>Address:</strong> <?php echo $row['complainedMotherAddress']; ?></p>
                                                        <p><strong>Father Name:</strong> <?php echo $row['complainedFatherName']; ?></p>
                                                        <p><strong>Occupation:</strong> <?php echo $row['complainedFatherOccupation']; ?></p>
                                                        <p><strong>Contact:</strong> <?php echo $row['complainedFatherContact']; ?></p>
                                                        <p><strong>Address:</strong> <?php echo $row['complainedFatherAddress']; ?></p>

                                                    </div>

                                                </div>
                                                <div class="row bg-dark text-light text-center justify-content-center pt-2 rounded">
                                                    <h5><b>Details</b></h5>
                                                </div>
                                                <div class="row pt-2">
                                                    <div class="col-md-12">

                                                        <p><strong>Case Details:</strong> <?php echo $row['caseDetails']; ?></p>
                                                        <p><strong>Action Taken:</strong> <?php echo $row['actionTaken']; ?></p>
                                                        <p><strong>Recommendations:</strong> <?php echo $row['recommendations']; ?></p>
                                                    </div>

                                                </div>

                                            </div>
                                            <a href="admin-print-complaint-student.php?id=<?php echo $row['id']; ?>" class="btn btn-primary float-right" target="_blank">Print</a>

                                        </div>

                                        <!-- Add more details as needed -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll("tbody tr");

            searchInput.addEventListener("input", function() {
                const searchTerm = searchInput.value.toLowerCase().trim();

                rows.forEach(row => {
                    const victimName = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    const complainedPerson = row.querySelector("td:nth-child(2)").textContent.toLowerCase();

                    if (victimName.includes(searchTerm) || complainedPerson.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
    <script src="loading.js"></script>
    <?php include "admin-footer.php";
    include "footer.php" ?>
    <?php
    $conn->close();
    ?>