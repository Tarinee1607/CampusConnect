<?php

session_start();
include("../../../config/no_cache.php");
include("../../../config/db.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$drive_id = $_GET['drive_id'];
/* Get Student Details */

$studentQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM students
     WHERE id='$student_id'"
);

$student = mysqli_fetch_assoc($studentQuery);

/* Get Drive Details */

$driveQuery = mysqli_query(
    $conn,
    "SELECT *
     FROM placement_drives
     WHERE id='$drive_id'"
);

$drive = mysqli_fetch_assoc($driveQuery);

/* Eligibility Check */

if(
    $student['cgpa'] < $drive['min_cgpa']
    ||
    $student['branch'] != $drive['eligible_branch']
    ||
    $student['graduation_year'] != $drive['eligible_year']
)
{
    echo "
    <script>
    alert('You are not eligible for this drive');
    window.location='drives.php';
    </script>
    ";
    exit();
}
/* Check if already applied */

$check = mysqli_query(
    $conn,
    "SELECT * FROM applications
     WHERE student_id='$student_id'
     AND drive_id='$drive_id'"
);

if(mysqli_num_rows($check) > 0)
{
    echo "<script>
    alert('You have already applied for this drive');
    window.location='drives.php';
    </script>";
    exit();
}

/* Insert application */

$sql = "INSERT INTO applications
(student_id, drive_id, status)
VALUES
('$student_id', '$drive_id', 'Applied')";



if(mysqli_query($conn, $sql))
{
    echo "
    <script>
        alert('Application Submitted Successfully');
        window.location='drives.php';
    </script>
    ";
}
else
{
    echo mysqli_error($conn);
}


?>