<?php
include("admin_auth.php");
include("../../../config/db.php");
include("../../../config/no_cache.php");
$id = $_GET['id'];

$result = mysqli_query(
    $conn,
    "SELECT * FROM placement_drives WHERE id=$id"
);

$drive = mysqli_fetch_assoc($result);

if(isset($_POST['update_drive']))
{
    $company_name = $_POST['company_name'];
    $role_name = $_POST['role_name'];
    $package = $_POST['package'];
    $min_cgpa = $_POST['min_cgpa'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];

    mysqli_query(
        $conn,
        "UPDATE placement_drives
         SET company_name='$company_name',
             role_name='$role_name',
             package='$package',
             min_cgpa='$min_cgpa',
             description='$description',
             deadline='$deadline'
         WHERE id=$id"
    );

    header("Location: managedrives.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Drive</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Edit Placement Drive</h2>

<form method="POST">

    <div class="mb-3">
        <label>Company Name</label>
        <input
            type="text"
            name="company_name"
            class="form-control"
            value="<?php echo $drive['company_name']; ?>"
            required>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <input
            type="text"
            name="role_name"
            class="form-control"
            value="<?php echo $drive['role_name']; ?>"
            required>
    </div>

    <div class="mb-3">
        <label>Package</label>
        <input
            type="text"
            name="package"
            class="form-control"
            value="<?php echo $drive['package']; ?>"
            required>
    </div>

    <div class="mb-3">
        <label>Minimum CGPA</label>
        <input
            type="number"
            step="0.1"
            name="min_cgpa"
            class="form-control"
            value="<?php echo $drive['min_cgpa']; ?>"
            required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea
            name="description"
            rows="4"
            class="form-control"
            required><?php echo $drive['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label>Deadline</label>
        <input
            <input
type="date"
name="deadline"
class="form-control"
min="<?php echo date('Y-m-d'); ?>"
required>
    </div>

    <button
        type="submit"
        name="update_drive"
        class="btn btn-primary">
        Update Drive
    </button>

    <a href="managedrives.php"
       class="btn btn-secondary">
       Cancel
    </a>

</form>

</body>
</html>