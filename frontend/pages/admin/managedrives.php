<?php
include("admin_auth.php");
include("../../../config/no_cache.php");
?>
<?php
include("../../../config/db.php");

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );
}

$sql = "
SELECT *
FROM placement_drives
WHERE company_name LIKE '%$search%'
ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Manage Drives</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="wrapper">
<?php include('adnav.php');?>
    <!-- SIDEBAR -->

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

            <li class="active">
                <a href="managedrives.php">
                    Manage Drives
                </a>
            </li>

            <li>
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
                <a href="#">
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

        <div class="topbar">

            <div>
                <h3>Manage Placement Drives</h3>
                <p>Create and manage placement opportunities</p>
            </div>

            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addDriveModal">

                <i class="bi bi-plus-circle"></i>
                Add Drive

            </button>

        </div>

        <!-- SEARCH -->

        <div class="content-card mb-4">

<div class="content-card mb-4">

<form method="GET" class="d-flex gap-2">

    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search company name..."
        value="<?php echo $search; ?>">

    <button
        type="submit"
        class="btn btn-primary">

        Search

    </button>

</form>

</div>

</div>

        <!-- TABLE -->

        <div class="content-card">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>Company</th>
                        <th>Role</th>
                        <th>Package</th>
                        <th>CGPA</th>
                        <th>Deadline</th>
                        <th>Applicants</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?php echo $row['company_name']; ?></td>

    <td><?php echo $row['role_name']; ?></td>

    <td><?php echo $row['package']." LPA"; ?></td>

    <td><?php echo $row['min_cgpa']; ?></td>

    <td><?php echo $row['deadline']; ?></td>

   <td>

<?php

$count = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM applications
         WHERE drive_id=".$row['id']
    )
);

echo $count['total'];

?>

</td>

    <td>

        <a href="editdrive.php?id=<?php echo $row['id']; ?>"
           class="btn btn-sm btn-warning">
            Edit
        </a>

        <a href="deletedrive.php?id=<?php echo $row['id']; ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this drive?')">
            Delete
        </a>

        <a href="viewapplicants.php?drive_id=<?php echo $row['id']; ?>"
           class="btn btn-sm btn-success">
            Applicants
        </a>

    </td>

</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </main>

</div>

<!-- ADD DRIVE MODAL -->

<div class="modal fade"
     id="addDriveModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Placement Drive
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="driveForm" action="../../../auth/add_drive.php" method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Company Name</label>

                           <input
type="text"
name="company_name"
class="form-control"
required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Role</label>

                            <input
type="text"
name="role_name"
class="form-control"
required>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Package in LPA</label>

                            <input
type="text"
name="package"
class="form-control"
required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Minimum CGPA</label>

                            <input
type="number"
step="0.1"
name="min_cgpa"
class="form-control"
required>


                        </div>

                    </div>
<div class="mb-3">

<label>Eligible Branch</label>

<select
name="eligible_branch"
class="form-control"
required>

<option value="">Select Branch</option>
 <option>CSE</option>
                                <option>AI & DS</option>
                                <option>IT</option>
                                <option>ECE</option>
                                <option>EEE</option>
                                <option>ME</option>
                                <option>CE</option>

</select>

</div>

<div class="mb-3">

<label>Graduation Year</label>

<select
name="eligible_year"
class="form-control"
required>

<option value="">Select Year</option>

<?php
$currentYear = date("Y");

for($i = 0; $i < 50; $i++)
{
    $year = $currentYear + $i;

    echo "<option value='$year'>$year</option>";
}
?>

</select>

</div>


                    <div class="mb-3">

                        <label>Description</label>

                       <textarea
rows="4"
name="description"
class="form-control"
required></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Deadline</label>

                       <input
type="date"
name="deadline"
class="form-control"
min="<?php echo date('Y-m-d'); ?>"
required>

                    </div>

               

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
type="submit"
form="driveForm"
class="btn btn-primary">
    Save Drive
</button>
 </form>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>