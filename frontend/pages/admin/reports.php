<?php
include("admin_auth.php");
include("../../../config/db.php");
include("../../../config/no_cache.php");
?>
<?php

$totalStudents = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM students"
    )
);

$totalApplications = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM applications"
    )
);

$totalPlaced = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM students
         WHERE placement_status='Placed'"
    )
);

$placementRate = 0;

if($totalStudents['total'] > 0)
{
    $placementRate =
    round(
        ($totalPlaced['total'] /
        $totalStudents['total']) * 100,
        2
    );
}
$branchData = mysqli_query(
    $conn,
    "SELECT
        branch,
        COUNT(*) AS total
     FROM students
     WHERE placement_status='Placed'
     GROUP BY branch"
);
$branches = [];
$placedCounts = [];

while($row = mysqli_fetch_assoc($branchData))
{
    $branches[] = $row['branch'];
    $placedCounts[] = $row['total'];
}
$placedStudents = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM students
         WHERE placement_status='Placed'"
    )
);

$notPlacedStudents =
$totalStudents['total']
-
$placedStudents['total'];


$highestPackage = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT MAX(
            CAST(REPLACE(package,' LPA','') AS DECIMAL(10,2))
         ) AS highest_package
         FROM placement_drives"
    )
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Reports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <li>
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

            <li class="active">
                <a href="reports.php">Reports</a>
            </li>
            <li>
             <a href="../../../auth/adminlogout.php">Logout</a>
</li>
        </ul>

    </aside> -->

    <!-- MAIN -->

    <main class="main-content">

        <div class="topbar">

            <div>
                <h3>Placement Reports</h3>
                <p>Placement statistics and analytics</p>
            </div>

            <div>

                <a href="export_excel.php"
   class="btn btn-success">
    Export Excel
</a>

                <a href="export_pdf.php"
   class="btn btn-danger">
    Export PDF
</a>

            </div>

        </div>

        <!-- STATISTICS -->

        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $totalStudents['total']; ?></h2>
                    <p>Total Students</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $totalPlaced['total']; ?></h2>
                    <p>Placed Students</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $placementRate; ?>%</h2>
                    <p>Placement Rate</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                  <h2>
<?php
echo !empty($highestPackage['highest_package'])
     ? round($highestPackage['highest_package'],2)." LPA"
     : "N/A";
?>
</h2>
                    <p>Highest Package</p>
                </div>
            </div>

        </div>

        <!-- CHARTS -->

        <div class="row">

            <div class="col-lg-6 mb-4">

                <div class="content-card">

                    <h5 class="mb-3">
                        Branch-wise Placements
                    </h5>

                    <canvas id="branchChart"></canvas>

                </div>

            </div>

            <div class="col-lg-6 mb-4">

                <div class="content-card">

                    <h5 class="mb-3">
                        Placement Status
                    </h5>

                    <canvas id="statusChart"></canvas>

                </div>

            </div>

        </div>

        <!-- TABLE -->

        <div class="content-card">

            <h4 class="mb-3">
                Department Statistics
            </h4>

            <table class="table table-hover">

                <thead>

                <tr>
                    <th>Department</th>
                    <th>Students</th>
                    <th>Placed</th>
                    <th>Rate</th>
                </tr>

                </thead>

            <tbody>

<?php

$deptQuery = mysqli_query(
    $conn,
    "SELECT
        branch,
        COUNT(*) AS total_students,

        SUM(
            CASE
            WHEN placement_status='Placed'
            THEN 1
            ELSE 0
            END
        ) AS placed_students

     FROM students

     GROUP BY branch"
);

while($dept = mysqli_fetch_assoc($deptQuery))
{
    $rate = 0;

    if($dept['total_students'] > 0)
    {
        $rate =
        round(
            ($dept['placed_students'] /
            $dept['total_students']) * 100,
            2
        );
    }

?>

<tr>

    <td><?php echo $dept['branch']; ?></td>

    <td><?php echo $dept['total_students']; ?></td>

    <td><?php echo $dept['placed_students']; ?></td>

    <td><?php echo $rate; ?>%</td>

</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </main>

</div>

<script>

new Chart(
document.getElementById('branchChart'),
{
    type:'bar',
    data:{
       labels: <?php echo json_encode($branches); ?>,

datasets:[{
    label:'Placed Students',
    data: <?php echo json_encode($placedCounts); ?>
}]
    }
}
);

new Chart(
document.getElementById('statusChart'),
{
    type:'pie',
    data:{
        labels:[
            'Placed',
            'Not Placed'
        ],
        datasets:[{
            data:[
    <?php echo $placedStudents['total']; ?>,
    <?php echo $notPlacedStudents; ?>
]
        }]
    }
}
);

</script>

</body>
</html>