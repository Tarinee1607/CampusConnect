<?php
include("../../../config/no_cache.php");
include("student_auth.php");
if(!isset($_SESSION['student_id']))
{
    header("Location: login.php");
exit();

}

include("../../../config/db.php");

$student_id = $_SESSION['student_id'];
$student = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT branch, graduation_year
         FROM students
         WHERE id='$student_id'"
    )
);

$branch = $student['branch'];
$year = $student['graduation_year'];
$totalApplications = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id"
    )
);
$interviews = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id
         AND status='Interview'"
    )
);
$activeDrives = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM placement_drives
         WHERE deadline >= CURDATE()
         AND eligible_branch='$branch'
         AND eligible_year='$year'"
    )
);

$notices = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM notices"
    )
);

$recentDrives = mysqli_query(
    $conn,
    "SELECT *
     FROM placement_drives
     WHERE deadline >= CURDATE()
     AND eligible_branch = '$branch'
     AND eligible_year = '$year'
     ORDER BY id DESC
     LIMIT 5"
);
$recentNotices = mysqli_query(
    $conn,
    "SELECT *
     FROM notices
     ORDER BY created_at DESC
     LIMIT 5"
);




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
<?php include('navbar.php');?>
    <!-- <aside class="sidebar">

        <div class="logo">
            CampusConnect
        </div>

        <ul class="nav-links">

            <li class="active">
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

            <li>
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

        <!-- TOPBAR -->

        <div class="topbar">

            <div>
                <h3>Dashboard</h3>
               <p>
Welcome back,
<?php echo $_SESSION['fullname']; ?> 👋
</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- STAT CARDS -->

        <div class="row g-4">

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $totalApplications['total']; ?></h2>
                    <p>Total Applications</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $interviews['total']; ?></h2>
                    <p>Upcoming Interviews</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $activeDrives['total']; ?></h2>
                    <p>Active Drives</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $notices['total']; ?></h2>
                   <p>Total Notices</p>
                </div>
            </div>

        </div>

        <!-- RECENT DRIVES -->

        <div class="content-card mt-4">

            <h4>Recent Placement Drives</h4>

            <table class="table mt-3">

                <thead>

                <tr>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Package</th>
                    <th>Deadline</th>
                </tr>

                </thead>

                <tbody>
<?php while($drive = mysqli_fetch_assoc($recentDrives)) { ?>

<tr>

    <td><?php echo $drive['company_name']; ?></td>

    <td><?php echo $drive['role_name']; ?></td>

   <td><?php echo $drive['package'] ; ?>LPA</td>

    <td><?php echo $drive['deadline']; ?></td>

</tr>

<?php } ?>

              

                </tbody>

            </table>

        </div>

        <!-- NOTICES -->

        <div class="content-card mt-4">

            <h4>Recent Notices</h4>

            <?php while($notice = mysqli_fetch_assoc($recentNotices)) { ?>

<div class="notice-item">

    <strong>
        <?php echo $notice['title']; ?>
    </strong>

    <br>

    <?php echo $notice['description']; ?>

</div>

<?php } ?>

        </div>

    </main>

</div>

</body>
</html>