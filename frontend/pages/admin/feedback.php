<?php
include("admin_auth.php");
include("../../../config/db.php");
include("../../../config/no_cache.php");
$result = mysqli_query(
    $conn,
    "SELECT *
     FROM contact_messages
     ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>


<div class="wrapper">

<?php include('adnav.php'); ?>

<main class="main-content">

<div class="topbar">

    <div>
        <h3>Student Feedback</h3>
        <p>Messages received from the Contact Us form</p>
    </div>

</div>

<div class="content-card">

<table class="table table-bordered">

<thead>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Message</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['message']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>
</main>
</div>
</body>
</html>