<?php
include("../../../config/no_cache.php");
include("student_auth.php");
if(!isset($_SESSION['student_id']))
{
    header("Location: login.php");
    exit();
}

include("../../../config/db.php");

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );
}

$query = "
SELECT *
FROM notices
WHERE title LIKE '%$search%'
OR category LIKE '%$search%'
OR description LIKE '%$search%'
ORDER BY created_at DESC
";

$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Notices</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
<?php include('navbar.php');?>
    <!-- <aside class="sidebar">

        <div class="logo">CampusConnect</div>

        <ul class="nav-links">

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="profile.php">Profile</a>
            </li>

            <li>
                <a href="drives.php">Placement Drives</a>
            </li>

            <li>
                <a href="applications.php">Applications</a>
            </li>

            <li class="active">
                <a href="notices.php">Notices</a>
            </li>

            <li>
                <a href="analytics.php">Analytics</a>
            </li>

            <li>
              <a href="../../../auth/logout.php">Logout</a>
            </li>

        </ul>

    </aside> -->

    <!-- MAIN -->

    <main class="main-content">

        <div class="topbar">

            <div>
                <h3>Placement Notices</h3>
                <p>Stay updated with the latest announcements</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- SEARCH -->

        <div class="content-card mb-4">

<form method="GET">

    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search notices..."
        value="<?php echo $search; ?>">

</form>

</div>

        <!-- NOTICE CARDS -->

        <?php while($notice = mysqli_fetch_assoc($result)) { ?>

<div class="notice-card">

    <div class="notice-header">

        <h5>
            <?php echo $notice['title']; ?>
        </h5>

        <span class="badge bg-primary">
            <?php echo $notice['category']; ?>
        </span>

    </div>

    <p>
        <?php echo $notice['description']; ?>
    </p>

    <small class="text-muted">

        Posted on:
        <?php echo date(
            "d M Y",
            strtotime($notice['created_at'])
        ); ?>

    </small>

</div>

<?php } ?>

    </main>

</div>

</body>
</html>