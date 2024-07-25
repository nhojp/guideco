<?php
include "conn.php";

session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['admin'])) {
    header('Location: index.php'); // Redirect if not logged in or not admin
    exit;
}

include 'head.php'; // Include head section
include 'admin-nav.php'; // Include navbar
?>
<main class="flex-fill mt-5">
    <div class="container mt-4">
        <?php include "admin-dashboard.php"; ?>
    </div>
</main>
<?php include 'footer.php'; // Include footer section 
?>