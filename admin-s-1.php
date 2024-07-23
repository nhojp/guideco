<?php
// Include the database connection
include "conn.php";

// Include the header
include "head.php";

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin_id'])) {
    // Redirect if not logged in
    header('Location: index.php');
    exit;
}

// Fetch all students with their related section and grade
$sql_students = "SELECT s.id, s.first_name, s.last_name, sec.section_name, g.grade_name
                 FROM students s
                 INNER JOIN sections sec ON s.section_id = sec.id
                 INNER JOIN grades g ON sec.grade_id = g.id";
$result_students = $conn->query($sql_students);

include "admin-header.php";
?>

<style>
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
    <div class="container-fluid bg-white mt-2 rounded-lg">
    <div class="row pt-3">
            <div class="col-md-6">
                <div class="container-fluid p-2">
                    <h3 class="p-3"><b>
                            Complain a Student</b>
                    </h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="container-fluid p-2 rounded-lg">
                    <input class="search" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
            </div>
        </div>

        <table class="table text-center table-hover ">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="width: 40%;">Name</th>
                    <th style="width: 25%;">Grade</th>
                    <th style="width: 25%;">Section</th>
                    <th style="width: 10%;">Select</th>
                </tr>
            </thead>
            <tbody id="personnelTable">
                <?php if ($result_students->num_rows > 0) : ?>
                    <?php while ($student = $result_students->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo ucwords($student['first_name']) . " " . ucwords($student['last_name']); ?></td>
                            <td><?php echo ucwords($student['grade_name']); ?></td>
                            <td><?php echo ucwords($student['section_name']); ?></td>
                            <td>
                                <form action="admin-s-2.php" method="get">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" class="btn btn-success mt-3 btn-block">
                                    Next                                    </button>                                </form>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No students found</td>
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

<?php
// Include footer
include "admin-footer.php";
?>