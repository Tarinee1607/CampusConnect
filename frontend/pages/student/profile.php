<?php
include("../../../config/no_cache.php");
include("student_auth.php");
if(!isset($_SESSION['student_id']))
{
    header("Location: login.php");
    exit();
}

include("../../../config/db.php");

$id = $_SESSION['student_id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM students WHERE id='$id'"
);

$student = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Profile</title>

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

            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>

            <li class="active">
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

    <!-- MAIN CONTENT -->

    <main class="main-content">

        <div class="topbar">

            <div>
                <h3>My Profile</h3>
                <p>Manage your academic and placement details</p>
            </div>

            <div class="profile-mini">
                Student
            </div>

        </div>

        <!-- PROFILE CARD -->

        <div class="content-card">

            <div class="text-center mb-4">

                <img
src="<?php
if(!empty($student['profile_photo']))
{
    echo '../../../uploads/'.$student['profile_photo'];
}
else
{
    echo 'https://via.placeholder.com/120';
}
?>"
class="profile-image"
alt="Profile">

                <h4 class="mt-3"><h4 class="mt-3">
    <?php echo $student['fullname']; ?>
</h4></h4>

                <p class="text-muted">
    <?php echo $student['branch']; ?>
</p>

            </div>

           <form
action="../../../auth/update_profile.php"
method="POST"
enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Full Name
                        </label>

                        <input
type="text"
name="fullname"
class="form-control"
value="<?php echo $student['fullname']; ?>">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Roll Number
                        </label>

                       <input
type="text"
name="rollno"
class="form-control"
value="<?php echo $student['rollno']; ?>">

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Branch
                        </label>

                        <input
type="text"
name="branch"
class="form-control"
value="<?php echo $student['branch']; ?>">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Graduation Year
                        </label>

                       <input
type="text"
name="graduation_year"
class="form-control"
value="<?php echo $student['graduation_year']; ?>">

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            CGPA
                        </label>

                        <input
type="number"
step="0.01"
name="cgpa"
class="form-control"
value="<?php echo $student['cgpa']; ?>">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
type="email"
name="email"
class="form-control"
value="<?php echo $student['email']; ?>">

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Skills
                    </label>

                   <textarea
rows="4"
name="skills"
class="form-control"><?php echo $student['skills']; ?></textarea>

                </div>
<div class="mb-4">

    <label class="form-label">
        Upload Profile Photo
    </label>

    <input
        type="file"
        name="profile_photo"
        class="form-control">

</div>
                <div class="mb-4">

                    <label class="form-label">
                        Upload Resume
                    </label>

                    <input
    type="file"
    name="resume"
    class="form-control">

                </div>
<?php
if(!empty($student['resume']))
{
?>
<p class="mt-2">

<a
href="../../../uploads/<?php echo $student['resume']; ?>"
target="_blank"
class="btn btn-success btn-sm">

View Current Resume

</a>

</p>
<?php
}
?>
                <button
                    class="btn btn-primary">
                    Update Profile
                </button>

            </form>

        </div>

    </main>

</div>

</body>
</html>