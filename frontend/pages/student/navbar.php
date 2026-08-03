
<aside class="sidebar">

    <div class="logo">CampusConnect</div>

    <ul class="nav-links">

        <li class="<?php echo ($current_page=="dashboard") ? "active" : ""; ?>">
            <a href="dashboard.php">Dashboard</a>
        </li>

        <li class="<?php echo ($current_page=="profile") ? "active" : ""; ?>">
            <a href="profile.php">Profile</a>
        </li>

        <li class="<?php echo ($current_page=="drives") ? "active" : ""; ?>">
            <a href="drives.php">Placement Drives</a>
        </li>

        <li class="<?php echo ($current_page=="applications") ? "active" : ""; ?>">
            <a href="applications.php">Applications</a>
        </li>

        <li class="<?php echo ($current_page=="notices") ? "active" : ""; ?>">
            <a href="notices.php">Notices</a>
        </li>

        <li class="<?php echo ($current_page=="analytics") ? "active" : ""; ?>">
            <a href="analytics.php">Analytics</a>
        </li>

        <li>
            <a href="/CAMPUSCONNECT/auth/logout.php">Logout</a>
        </li>

    </ul>

</aside>