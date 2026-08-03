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
$search = "";
$status = "";

if(isset($_GET['status']))
{
    $status = $_GET['status'];
}
if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );
}
$query = "
SELECT
placement_drives.company_name,
placement_drives.role_name,
placement_drives.package,
applications.status,
applications.applied_at,
applications.interview_date

FROM applications

JOIN placement_drives
ON applications.drive_id = placement_drives.id

WHERE applications.student_id = '$student_id'

AND (
placement_drives.company_name LIKE '%$search%'
OR placement_drives.role_name LIKE '%$search%'
)
";

if($status != "" && $status != "All")
{
    $query .= "
    AND applications.status='$status'
    ";
}

$query .= "
ORDER BY applications.id DESC
";

$result = mysqli_query($conn, $query);
$totalApplied = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id='$student_id'"
    )
);

$interviewCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id='$student_id'
         AND status='Interview'"
    )
);

$selectedCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id='$student_id'
         AND status='Selected'"
    )
);

$rejectedCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE student_id='$student_id'
         AND status='Rejected'"
    )
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Applications</title>

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

            <li class="active">
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

    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="topbar">

            <div>
                <h3>My Applications</h3>
                <p>Track all your placement applications</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- APPLICATION STATS -->

        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $totalApplied['total']; ?></h2>
                    <p>Total Applied</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $interviewCount['total']; ?></h2>
                    <p>Interview Scheduled</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $selectedCount['total']; ?></h2>
                    <p>Selected</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $rejectedCount['total']; ?></h2>
                    <p>Rejected</p>
                </div>
            </div>

        </div>

        <!-- FILTER -->

        <div class="content-card mb-4">

            <div class="row">

                <div class="col-md-6">
                    <form method="GET">

<input
    type="text"
    name="search"
    class="form-control"
    placeholder="Search company..."
    value="<?php echo $search; ?>">


                </div>

                <div class="col-md-6">
                   <select
name="status"
class="form-select"
onchange="this.form.submit()">

<option value="All">All Status</option>

<option value="Applied"
<?php if($status=="Applied") echo "selected"; ?>>
Applied
</option>

<option value="Interview"
<?php if($status=="Interview") echo "selected"; ?>>
Interview
</option>

<option value="Selected"
<?php if($status=="Selected") echo "selected"; ?>>
Selected
</option>

<option value="Rejected"
<?php if($status=="Rejected") echo "selected"; ?>>
Rejected
</option>

</select>
                </div>

            </div>
 </form>
        </div>
       

        <!-- APPLICATION TABLE -->

        <div class="content-card">

            <h4 class="mb-3">Application History</h4>

            <table class="table table-hover">

                <thead>

                <tr>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Interview Date</th>
                </tr>

                </thead>

               <tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td>
        <?php echo $row['company_name']; ?>
    </td>

    <td>
        <?php echo $row['role_name']; ?>
    </td>

    <td>
    <?php
    echo date(
        "d M Y",
        strtotime($row['applied_at'])
    );
    ?>
</td>

    <td>

<?php

if($row['status'] == 'Applied')
{
    echo '<span class="badge bg-primary">Applied</span>';
}
elseif($row['status'] == 'Interview')
{
    echo '<span class="badge bg-warning text-dark">Interview</span>';
}
elseif($row['status'] == 'Selected')
{
    echo '<span class="badge bg-success">Selected</span>';
}
else
{
    echo '<span class="badge bg-danger">Rejected</span>';
}

?>

    </td>

   

<td>

<?php

if(
    $row['status'] == 'Interview'
    &&
    !empty($row['interview_date'])
)
{
    echo '<span class="badge bg-info text-dark">'
         . date(
             "d M Y",
             strtotime($row['interview_date'])
         )
         . '</span>';
}
else
{
    echo '-';
}

?>

</td>


</tr>

<?php } ?>

</tbody>
            </table>

        </div>

    </main>

</div>

</body>
</html>