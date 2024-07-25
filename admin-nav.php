<style>
    /* Custom CSS to align navbar items to the left */
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
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-guideco fixed-top">
    <a class="navbar-brand" href="admin-index.php">GuideCo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin-index.php">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="complaintsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Complaints
                </a>
                <div class="dropdown-menu" aria-labelledby="complaintsDropdown">
                    <a class="dropdown-item" href="admin-complaints-teacher.php">Teacher</a>
                    <a class="dropdown-item" href="admin-complaints-student.php">Student</a>
                    <!-- Add more dropdown items as needed -->
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-violators.php">Violators</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="complaintsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Users
                </a>
                <div class="dropdown-menu" aria-labelledby="complaintsDropdown">
                    <a class="dropdown-item" href="admin-admins.php">Admin</a>
                    <a class="dropdown-item" href="admin-user-student.php">Students</a>
                    <a class="dropdown-item" href="admin-user-personnel.php">School Personnel</a>
                    <!-- Add more dropdown items as needed -->
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="complaintsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    School
                </a>
                <div class="dropdown-menu" aria-labelledby="complaintsDropdown">
                    <a class="dropdown-item" href="admin-nav-sections.php">Sections</a>
                    <a class="dropdown-item" href="admin-nav-violations.php">Violations</a>
                    <!-- Add more dropdown items as needed -->
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="complaintsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    My Account
                </a>
                <div class="dropdown-menu" aria-labelledby="complaintsDropdown">
                    <a class="dropdown-item" href="admin-credentials.php">Profile</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                    <!-- Add more dropdown items as needed -->
                </div>
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
