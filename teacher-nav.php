<style>
    /* Custom CSS for teacher navbar */
    .navbar-nav {
        margin-left: auto;
        margin-right: auto;
    }

    .navbar-nav .nav-item {
        margin-left: 15px;
    }

    .navbar-nav .nav-item.active .nav-link {
        font-weight: bold;
    }
    .small-text {
        font-size: 0.5em; /* Adjust the size as needed */
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-guideco fixed-top">
    <a class="navbar-brand" href="teacher-index.php">    <strong>GuideCo<sup class="small-text">Teacher</sup></strong>
</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="teacher-index.php">Report</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="teacher-section.php">My Section</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="teacher-account.php">My Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- Popper.js (needed for Bootstrap dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS (for dropdowns) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
