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

// Total Applications

$totalApplications = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id"
    )
);

// Interviews

$totalInterviews = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id
         AND status='Interview'"
    )
);

// Selected

$totalSelected = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id
         AND status='Selected'"
    )
);

// Rejected

$totalRejected = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id = $student_id
         AND status='Rejected'"
    )
);
$successRate = 0;

if($totalApplications['total'] > 0)
{
    $successRate =
    round(
        ($totalSelected['total']
        / $totalApplications['total']) * 100
    );
}
$highestPackage = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT MAX(
            CAST(REPLACE(package,' LPA','') AS DECIMAL(10,2))
         ) AS highest_package
         FROM placement_drives
         WHERE id IN
         (
            SELECT drive_id
            FROM applications
            WHERE student_id = $student_id
         )"
    )
);
$monthlyData = [];

$query = mysqli_query(
    $conn,
    "SELECT
        MONTH(applied_at) AS month_num,
        COUNT(*) AS total
     FROM applications
     WHERE student_id = $student_id
     GROUP BY MONTH(applied_at)"
);

while($row = mysqli_fetch_assoc($query))
{
    $monthlyData[$row['month_num']] = $row['total'];
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Analytics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->

   <?php include('navbar.php');?>

    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="topbar">

            <div>
                <h3>Analytics Dashboard</h3>
                <p>Your placement journey at a glance</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- STATS -->

        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $totalApplications['total']; ?></h2>
                    <p>Applications</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                  <h2><?php echo $totalInterviews['total']; ?></h2>
                    <p>Interviews</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $totalSelected['total']; ?></h2>
                    <p>Offers</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $successRate; ?>%</h2>
                    <p>Success Rate</p>
                </div>
            </div>

        </div>

        <!-- CHARTS -->

        <div class="row">

            <div class="col-lg-8 mb-4">

                <div class="content-card">

                    <h5 class="mb-3">
                        Applications Per Month
                    </h5>

                    <canvas id="applicationsChart"></canvas>

                </div>

            </div>

            <div class="col-lg-4 mb-4">

                <div class="content-card">

                    <h5 class="mb-3">
                        Application Status
                    </h5>

                    <canvas id="statusChart"></canvas>

                </div>

            </div>

        </div>

        <!-- PERFORMANCE SUMMARY -->

        <div class="content-card">

            <h5 class="mb-3">
                Placement Summary
            </h5>

            <div class="row">

                <div class="col-md-4">

                    <div class="summary-box">
                       <h6>Attempts Made</h6>
                        <p><?php echo $totalRejected['total']; ?></p>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="summary-box">
                        <h6>Highest Package Applied</h6>
                        <p>
<?php
echo $highestPackage['highest_package']
     ? $highestPackage['highest_package']." LPA"
     : "N/A";
?>
</p>
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="summary-box">
                        <h6>Current Status</h6>
                        <p>

<?php

if($totalSelected['total'] > 0)
{
    echo "Placed";
}
elseif($totalInterviews['total'] > 0)
{
    echo "Interview Ongoing";
}
else
{
    echo "Actively Applying";
}

?>

</p>
                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

<script>

const applicationsChart =
new Chart(
document.getElementById('applicationsChart'),
{
    type:'bar',

    data:{
        labels:[
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun'
        ],

        datasets:[{
            label:'Applications',

           data:[
    <?php echo isset($monthlyData[1]) ? $monthlyData[1] : 0; ?>,
    <?php echo isset($monthlyData[2]) ? $monthlyData[2] : 0; ?>,
    <?php echo isset($monthlyData[3]) ? $monthlyData[3] : 0; ?>,
    <?php echo isset($monthlyData[4]) ? $monthlyData[4] : 0; ?>,
    <?php echo isset($monthlyData[5]) ? $monthlyData[5] : 0; ?>,
    <?php echo isset($monthlyData[6]) ? $monthlyData[6] : 0; ?>
]
        }]
    }
}
);

const statusChart =
new Chart(
document.getElementById('statusChart'),
{
    type:'doughnut',

    data:{
        labels:[
            'Applied',
            'Interview',
            'Selected',
            'Rejected'
        ],

        datasets:[{
           data:[
    <?php echo $totalApplications['total']
            - $totalInterviews['total']
            - $totalSelected['total']
            - $totalRejected['total']; ?>,

    <?php echo $totalInterviews['total']; ?>,

    <?php echo $totalSelected['total']; ?>,

    <?php echo $totalRejected['total']; ?>
]
        }]
    }
}
);

</script>

</body>
</html>