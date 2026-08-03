<?php
include("admin_auth.php");
include("../../../config/db.php");
include("../../../config/no_cache.php");
$drive_id = $_GET['drive_id'];
$driveInfo = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT company_name, role_name
         FROM placement_drives
         WHERE id='$drive_id'"
    )
);
$query = "
SELECT
    applications.id AS application_id,
    students.fullname,
    students.rollno,
    students.email,
    students.cgpa,
    applications.status,
    applications.interview_date,
    placement_drives.company_name,
    students.resume,
    placement_drives.role_name
FROM applications
JOIN students
ON applications.student_id = students.id
JOIN placement_drives
ON applications.drive_id = placement_drives.id
WHERE applications.drive_id = $drive_id
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applicants</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<div class="wrapper">

<?php include('adnav.php'); ?>

<main class="main-content">

<div class="topbar">

    <div>
        <h3>
<?php echo $driveInfo['company_name']; ?>
 - Applicants
</h3>

<p>
Role:
<?php echo $driveInfo['role_name']; ?>
</p>
    </div>

</div>

<div class="content-card">

<a href="managedrives.php"
   class="btn btn-secondary mb-3">
    Back
</a>



    <table class="table table-bordered">

        <thead>
            <tr>
                <th>Name</th>
                <th>Roll No</th>
                <th>Email</th>
                <th>CGPA</th>
                <th>Resume</th>
                <th>Status</th>
                 <th>Interview Date</th>
                <th>Action</th>
                
            </tr>
        </thead>

        <tbody>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td><?php echo $row['fullname']; ?></td>

                <td><?php echo $row['rollno']; ?></td>

                <td><?php echo $row['email']; ?></td>

                <td><?php echo $row['cgpa']; ?></td>
<td>

<?php if(!empty($row['resume'])) { ?>

<a
href="../../../uploads/<?php echo $row['resume']; ?>"
target="_blank"
class="btn btn-info btn-sm">

View Resume

</a>

<?php } else { ?>

No Resume

<?php } ?>

</td>
                <td>

<form action="update_application_status.php" method="POST">

<input
type="hidden"
name="application_id"
value="<?php echo $row['application_id']; ?>">



<select
name="status"
class="form-select">

    <option value="Applied" <?php if($row['status']=="Applied") echo "selected"; ?>>
        Applied
    </option>

    <option value="Interview" <?php if($row['status']=="Interview") echo "selected"; ?>>
        Interview
    </option>

    <option value="Selected" <?php if($row['status']=="Selected") echo "selected"; ?>>
        Selected
    </option>

    <option value="Rejected" <?php if($row['status']=="Rejected") echo "selected"; ?>>
        Rejected
    </option>

</select>

</td>

<td>

<input
    type="date"
    name="interview_date"
    class="form-control"
    min="<?php echo date('Y-m-d'); ?>"
    value="<?php echo $row['interview_date']; ?>">

</td>

<td>

<button
type="submit"
class="btn btn-success btn-sm">
Update
</button>

</td>

</form>

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