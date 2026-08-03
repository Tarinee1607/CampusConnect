<?php
include("../../../config/no_cache.php");
include("student_auth.php");
if(!isset($_SESSION['student_id']))
{
    header("Location: login.php");
    exit();
}
?>
<?php

include("../../../config/db.php");
$studentId = $_SESSION['student_id'];
$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );
}
$studentQuery = mysqli_query(
    $conn,
    "SELECT * FROM students
     WHERE id='$studentId'"
);

$student = mysqli_fetch_assoc($studentQuery);

$studentBranch = $student['branch'];
$studentYear = $student['graduation_year'];
$query = "
SELECT *
FROM placement_drives
WHERE eligible_branch='$studentBranch'
AND eligible_year='$studentYear'
AND deadline >= CURDATE()
AND
(
    company_name LIKE '%$search%'
    OR role_name LIKE '%$search%'
)
ORDER BY id DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Placement Drives</title>

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

            <li class="active">
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

        <div class="topbar">

            <div>
                <h3>Placement Drives</h3>
                <p>Explore available placement opportunities</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- SEARCH -->

      <form method="GET">

    <div class="row">

        <div class="col-md-10">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search Company or Role..."
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

        </div>

        <div class="col-md-2">

            <button
                type="submit"
                class="btn btn-primary w-100">
                Search
            </button>

        </div>

    </div>

</form>
<br>
        <!-- DRIVES GRID -->

        <div class="row g-4">

<?php while($drive = mysqli_fetch_assoc($result)) { ?>

    <div class="col-lg-4">

        <div class="drive-card">

            <h4><?php echo $drive['company_name']; ?></h4>

            <p class="role">
                <?php echo $drive['role_name']; ?>
            </p>

            <div class="drive-info">
                <strong>Package:</strong>
                <?php echo $drive['package']; ?> LPA
            </div>

            <div class="drive-info">
                <strong>Eligibility:</strong>
                CGPA ≥ <?php echo $drive['min_cgpa']; ?>
            </div>

            <div class="drive-info">
                <strong>Deadline:</strong>
                <?php echo $drive['deadline']; ?>
            </div>

            <?php
if($student['cgpa'] >= $drive['min_cgpa'])
{
?>
    <a
        href="apply_drive.php?drive_id=<?php echo $drive['id']; ?>"
        class="btn btn-primary w-100 mt-3">

        Apply Now

    </a>
<?php
}
else
{
?>
    <button
        class="btn btn-danger w-100 mt-3"
        disabled>

        Ineligible

    </button>

    <small class="text-danger d-block mt-2">
        Required CGPA:
        <?php echo $drive['min_cgpa']; ?>
    </small>
<?php
}
?>

        </div>

    </div>

<?php } ?>

</div>

    </main>

</div>

</body>
</html>