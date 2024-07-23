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

// Fetch all teachers and guards
$sql_personnel = "SELECT 'teacher' as type, id, first_name, last_name, position FROM teachers
                  UNION ALL
                  SELECT 'guard' as type, id, first_name, last_name, position FROM guards";
$result_personnel = $conn->query($sql_personnel);

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
                    <h3 class="p-2"><b>
                            Complain a School Personnel</b>
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
                    <th style="width:45%;">Name</th>
                    <th style="width:45%;">Position</th>
                    <th style="width:10%;">Select</th>
                </tr>
            </thead>
            <tbody id="personnelTable">
                <?php if ($result_personnel->num_rows > 0) : ?>
                    <?php while ($person = $result_personnel->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo ucwords($person['first_name']) . ' ' . ucwords($person['last_name']); ?></td>
                            <td><?php echo ucwords($person['position']); ?></td>
                            <td>
                                <form action="admin-p-2.php" method="post">
                                    <input type="hidden" name="person_id" value="<?php echo $person['id']; ?>">
                                    <input type="hidden" name="person_type" value="<?php echo $person['type']; ?>">
                                    <button type="submit" class="btn btn-success btn-block mt-3" >
                                        Next </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No personnel found</td>
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