<?php
include("admin_auth.php");
include("../../../config/no_cache.php");
include("../../../config/db.php");

$id = $_GET['id'];

$sql = "SELECT * FROM students WHERE id=$id";

$result = mysqli_query($conn, $sql);

$student = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>
<head>

    <title>View Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>
<body>

<div class="container mt-5">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Student Profile
        </h2>

        <table class="table">

            <tr>
                <th>ID</th>
                <td><?php echo $student['id']; ?></td>
            </tr>

            <tr>
                <th>Full Name</th>
                <td><?php echo $student['fullname']; ?></td>
            </tr>

            <tr>
                <th>Roll Number</th>
                <td><?php echo $student['rollno']; ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?php echo $student['email']; ?></td>
            </tr>

            <tr>
                <th>Branch</th>
                <td><?php echo $student['branch']; ?></td>
            </tr>

            <tr>
                <th>Graduation Year</th>
                <td><?php echo $student['graduation_year']; ?></td>
            </tr>

            <tr>
                <th>CGPA</th>
                <td><?php echo $student['cgpa']; ?></td>
            </tr>

            <tr>
                <th>Skills</th>
                <td><?php echo $student['skills']; ?></td>
            </tr>

            <tr>
                <th>Placement Status</th>
                <td><?php echo $student['placement_status']; ?></td>
            </tr>
<tr>
    <th>Resume</th>

    <td>

        <a href="../../../uploads/<?php echo $student['resume']; ?>"
           target="_blank">

            View Resume

        </a>

    </td>
</tr>
        </table>

        <a href="managestudents.php"
           class="btn btn-primary">

            Back

        </a>

    </div>

</div>

</body>
</html>