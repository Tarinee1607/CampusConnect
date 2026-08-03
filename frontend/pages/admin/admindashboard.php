<?php
include("admin_auth.php");
include("../../../config/db.php");
include("../../../config/no_cache.php");
$total_students = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM students")
);

$total_drives = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM placement_drives
         WHERE deadline >= CURDATE()"
    )
);

$total_applications = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM applications")
);

$placed_students = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM students WHERE placement_status='Placed'"
    )
);
$totalFeedback = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM contact_messages"
    )
);
$recent_drives = mysqli_query(
    $conn,
    "SELECT * FROM placement_drives
     ORDER BY created_at DESC
     LIMIT 5"
);
$recent_notices = mysqli_query(
    $conn,
    "SELECT *
     FROM notices
     ORDER BY created_at DESC
     LIMIT 5"
);

$highestPackage = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT MAX(
            CAST(REPLACE(package,' LPA','') AS DECIMAL(10,2))
         ) AS highest_package
         FROM placement_drives"
    )
);
$placementRate = 0;

if($total_students > 0)
{
    $placementRate =
    round(
        ($placed_students / $total_students) * 100
    );
}
$averagePackage = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT AVG(
            CAST(REPLACE(package,' LPA','') AS DECIMAL(10,2))
         ) AS avg_package
         FROM placement_drives"
    )
);
$topCompanies = mysqli_query(
    $conn,
    "SELECT
        placement_drives.company_name,
        COUNT(applications.id) AS total_applicants
     FROM applications
     JOIN placement_drives
     ON applications.drive_id = placement_drives.id
     GROUP BY placement_drives.company_name
     ORDER BY total_applicants DESC
     LIMIT 5"
);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
     <?php include('adnav.php');?>
    <!-- <aside class="sidebar">

        <div class="logo">
            CampusConnect
        </div>

        <ul class="nav-links">

            <li class="active">
                <a href="admindashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="managedrives.php">Manage Drives</a>
            </li>

            <li>
                <a href="managestudents.php">Manage Students</a>
            </li>

            <li>
                <a href="managenotices.php">Manage Notices</a>
            </li>

            <li>
                <a href="reports.php">Reports</a>
            </li>

            <li>
                <a href="../../../auth/adminlogout.php">Logout</a>
            </li>

        </ul>

    </aside> -->

    <!-- MAIN CONTENT -->

    <main class="main-content">

        <!-- TOPBAR -->

        <div class="topbar">

            <div>
                <h3>Placement Officer Dashboard</h3>
                <p>Manage placements and monitor student activities</p>
            </div>

            <div class="profile-mini">
                Admin
            </div>

        </div>

        <!-- STATS -->

        <div class="row g-4">

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $total_students; ?></h2>
                    <p>Total Students</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $total_drives; ?></h2>
                    <p>Active Drives</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $total_applications; ?></h2>
                    <p>Applications</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $placed_students; ?></h2>
                    <p>Placed Students</p>
                </div>
            </div>
            <div class="col-md-3">
    <div class="stat-card">
        <h2><?php echo $totalFeedback['total']; ?></h2>
        <p>Feedback Received</p>
    </div>
</div>

        </div>

        <!-- RECENT DRIVES -->

        <div class="content-card mt-4">

            <div class="d-flex justify-content-between align-items-center">

                <h4>Recent Placement Drives</h4>

              <a href="managedrives.php" class="btn btn-primary">
    + Add Drive
</a>

            </div>

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

<?php while($drive = mysqli_fetch_assoc($recent_drives)) { ?>

<tr>
    <td><?php echo $drive['company_name']; ?></td>
    <td><?php echo $drive['role_name']; ?></td>
    <td><?php echo $drive['package']; ?></td>
    <td><?php echo $drive['deadline']; ?></td>
</tr>

<?php } ?>

</tbody>

            </table>

        </div>

        <!-- RECENT NOTICES -->

        <div class="content-card mt-4">

            <div class="d-flex justify-content-between align-items-center">

                <h4>Recent Notices</h4>

                <a href="managenotices.php" class="btn btn-success">
    + Add Notice
</a>
            </div>

           <?php while($notice = mysqli_fetch_assoc($recent_notices)) { ?>

<div class="notice-item">

    <strong>
        <?php echo $notice['title']; ?>
    </strong>

    <br>

    <?php echo $notice['description']; ?>

</div>

<?php } ?>

        </div>

        <!-- QUICK OVERVIEW -->

        <div class="row mt-4">

            <div class="col-lg-6">

                <div class="content-card">

                    <h4><h4>Placement Summary</h4></h4>

                    <ul class="list-group list-group-flush mt-3">

                        <li class="list-group-item">
    Highest Package:
    <?php echo round($highestPackage['highest_package'],2); ?> LPA
</li>

<li class="list-group-item">
    Average Package:
    <?php echo round($averagePackage['avg_package'],2); ?> LPA
</li>

<li class="list-group-item">
    Placement Rate:
    <?php echo $placementRate; ?>%
</li>
                    </ul>

                </div>

            </div>

              <div class="col-lg-6">

                <div class="content-card">

                    <h4>Top Hiring Companies</h4>

<ul class="list-group list-group-flush mt-3">

<?php while($company = mysqli_fetch_assoc($topCompanies)) { ?>

<li class="list-group-item">

    <?php echo $company['company_name']; ?>

    <span class="float-end">

        <?php echo $company['total_applicants']; ?>

        Applicants

    </span>

</li>

<?php } ?>

</ul>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>