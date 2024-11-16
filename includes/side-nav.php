<?php
session_start();
?>

<div class="side-nav">
    <div class="logo">
        <img src="images/keys.png" alt="profile picture">
        <p class="name"><?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?></p>
        <p class="role"><?php echo $_SESSION['role']; ?></p>
    </div>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="inmate.php">Inmates</a></li>
        <li><a href="visitation.php">Visitation</a></li>
        <li><a href="crime.php">Crimes</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        var path = window.location.pathname;
        var page = path.split("/").pop();
        var links = document.querySelectorAll(".side-nav ul li a");

        links.forEach(function(link) {
            if (link.getAttribute("href") === page) {
                link.classList.add("active");
            }
        });
    });
</script>