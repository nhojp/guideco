<style>
    /* Custom CSS for student navbar */
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
    <a class="navbar-brand" href="student-index.php">GuideCo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="student-index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student-profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="student-recommender.php">Recommender</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>