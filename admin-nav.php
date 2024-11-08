<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;700;900&family=Old+English+Text+MT:wght@700&family=Times+New+Roman:wght@700&display=swap');

/* Custom CSS for the navbar */
.navbar {
    padding: 10px 20px;
    background-color: white; 
    border-bottom: 2px solid #1D5B1B; 
    font-family: 'Montserrat', sans-serif; 
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1); 
}

.navbar-brand {
    font-weight: 900; 
    font-size: 24px; 
}

.navbar-brand .guide {
    color: #1D5B1B; 
}

.navbar-brand .co {
    color: #8B4513; 
}

.navbar-nav {
    margin-left: auto;
    margin-right: auto;
    font-weight: 700;
    font-size: 15px;
    font-family: 'Montserrat', sans-serif; 
    display: flex;
    justify-content: center; 
}

.navbar-nav .nav-item {
    margin: 0 15px; 
}

.navbar-nav .nav-link {
    color: #1F5F1E !important; 
    padding: 5px 10px;
    border-bottom: 2px solid transparent; 
    text-decoration: none; 
    position: relative;
    transition: color 0.3s ease; 
}

.navbar-nav .nav-link::before {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background-color: #1D5B1B; 
    visibility: hidden;
    transition: all 0.3s ease-in-out;
}

.navbar-nav .nav-link:hover::before {
    visibility: visible;
    width: 100%; 
}

.navbar-nav .nav-item.active .nav-link {
    font-weight: bold;
    color: #1F5F1E !important; 
}

.dropdown-menu {
    font-family: 'Montserrat', sans-serif; 
    background-color: #f8f9fa; 
    border: 1px solid #ddd; 
}

.dropdown-item {
    color: #1F5F1E; 
    transition: background-color 0.3s ease, color 0.3s ease;
}

.dropdown-item:hover {
    background-color: #1D5B1B; 
    color: white; 
}







</style>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand" href="admin-index.php">
        <span class="guide">Guide</span><span class="co">Co</span>
    </a>
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
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-violators.php">Violators</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Users
                </a>
                <div class="dropdown-menu" aria-labelledby="usersDropdown">
                    <a class="dropdown-item" href="admin-admins.php">Admin</a>
                    <a class="dropdown-item" href="admin-user-student.php">Students</a>
                    <a class="dropdown-item" href="admin-user-teacher.php">Teachers</a>
                    <a class="dropdown-item" href="admin-user-personnel.php">Guards</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="schoolDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    School
                </a>
                <div class="dropdown-menu" aria-labelledby="schoolDropdown">
                    <a class="dropdown-item" href="admin-nav-sections.php">Sections</a>
                    <a class="dropdown-item" href="admin-nav-strands.php">Strands</a>
                    <a class="dropdown-item" href="admin-nav-violations.php">Violations</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="myAccountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    My Account
                </a>
                <div class="dropdown-menu" aria-labelledby="myAccountDropdown">
                    <a class="dropdown-item" href="admin-credentials.php">Profile</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
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
