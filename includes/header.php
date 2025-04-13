<?php
require_once 'functions.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<header class="bg-dark py-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="text-white mb-0">Link Manager</h1>
        <nav>
            <ul class="nav">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a href="../pages/dashboard.php" class="nav-link text-white">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="../pages/logout.php" class="nav-link text-danger">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="../pages/login.php" class="nav-link text-white">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="../pages/register.php" class="nav-link text-success">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<style>
header {
    position: sticky;
    top: 0;
    z-index: 1000;
}

h1 {
    font-weight: bold;
    font-size: 1.8rem;
    letter-spacing: 1px;
}

.nav-link {
    padding: 10px 15px;
    transition: background-color 0.3s, color 0.3s;
}

.nav-link:hover {
    background-color: #ffffff33;
    border-radius: 5px;
}

.nav-link.text-danger:hover {
    background-color: #ff000033;
}

.nav-link.text-success:hover {
    background-color: #28a74533;
}

.nav-link.text-white:hover {
    background-color: #ffffff33;
}
</style>