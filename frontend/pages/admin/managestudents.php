<?php
include("admin_auth.php");
include("../../../config/no_cache.php");
?>
<?php



if(!isset($_SESSION['admin_id']))
{
    header("Location: adminlogin.php");
    exit();
}

include("../../../config/db.php");

$sql = "SELECT * FROM students ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$total_students = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM students")
)['total'];

$placed_students = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE placement_status='Placed'")
)['total'];

$not_placed_students = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE placement_status='Not Placed'")
)['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Manage Students</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                <a href="admindashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="managedrives.php">
                    Manage Drives
                </a>
            </li>

            <li class="active">
                <a href="managestudents.php">
                    Manage Students
                </a>
            </li>

            <li>
                <a href="managenotices.php">
                    Manage Notices
                </a>
            </li>

            <li>
                <a href="reports.php">
                    Reports
                </a>
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
                <h3>Manage Students</h3>
                <p>View and manage student placement records</p>
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-download"></i>
                Export List
            </button>

        </div>

        <!-- STATS -->

        <div class="row g-4 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $total_students; ?></h2>
                    <p>Total Students</p>
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
                    <h2><?php echo $not_placed_students; ?></h2>
                    <p>Seeking Placement</p>
                </div>
            </div>

            <!-- <div class="col-md-3">
                <div class="stat-card">
                    <h2>--</h2>
<p>Profile Completion</p>
                    
                </div>
            </div> -->

        </div>

        <!-- SEARCH -->

        <div class="content-card mb-4">

            <div class="row">

                <div class="col-md-4">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Name">
                </div>

                <div class="col-md-4">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Roll Number">
                </div>

                <div class="col-md-4">
                    <select class="form-select">

                        <option>
                            All Status
                        </option>

                        <option>
                            Placed
                        </option>

                        <option>
                            Not Placed
                        </option>

                    </select>
                </div>

            </div>

        </div>

        <!-- STUDENTS TABLE -->

        <div class="content-card">

            <h4 class="mb-3">
                Student Records
            </h4>

            <table class="table table-hover align-middle">

                <thead>

                <tr>

                    <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Roll No</th>
    <th>Branch</th>
    <th>CGPA</th>
    <th>Status</th>
    <th>Email</th>
    <th>Actions</th>
</tr>

                </tr>

                </thead>

                <tbody>

<?php while($student = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?php echo $student['id']; ?></td>

    <td><?php echo $student['fullname']; ?></td>

    <td><?php echo $student['rollno']; ?></td>

    <td><?php echo $student['branch']; ?></td>

    <td><?php echo $student['cgpa']; ?></td>

    <td><?php echo $student['placement_status']; ?></td>

    <td><?php echo $student['email']; ?></td>
    
    <td>

    <a href="viewstudent.php?id=<?php echo $student['id']; ?>"
       class="btn btn-sm btn-primary">
       View
    </a>

    <a href="deletestudent.php?id=<?php echo $student['id']; ?>"
       class="btn btn-sm btn-danger"
       onclick="return confirm('Delete this student?')">
       Delete
    </a> 

</td>

</td>

</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </main>

</div>

<!-- STUDENT DETAILS MODAL -->

<div class="modal fade"
     id="studentModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Student Profile
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">

                        <p>
                            <strong>Name:</strong>
                            Tarinee
                        </p>

                        <p>
                            <strong>Roll No:</strong>
                            CSE202401
                        </p>

                        <p>
                            <strong>Branch:</strong>
                            CSE
                        </p>

                    </div>

                    <div class="col-md-6">

                        <p>
                            <strong>CGPA:</strong>
                            8.4
                        </p>

                        <p>
                            <strong>Status:</strong>
                            Not Placed
                        </p>

                        <p>
                            <strong>Email:</strong>
                            tarinee@campus.edu
                        </p>

                    </div>

                </div>

                <hr>

                <h6>Skills</h6>

                <div class="mb-3">

                    <span class="badge bg-primary">
                        HTML
                    </span>

                    <span class="badge bg-primary">
                        CSS
                    </span>

                    <span class="badge bg-primary">
                        JavaScript
                    </span>

                    <span class="badge bg-primary">
                        PHP
                    </span>

                </div>

                <h6>Applications</h6>

                <ul>

                    <li>Infosys - Applied</li>
                    <li>TCS - Interview Scheduled</li>
                    <li>Accenture - Rejected</li>

                </ul>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>