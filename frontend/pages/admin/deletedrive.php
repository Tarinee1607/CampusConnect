<?php

include("admin_auth.php");
include("../../../config/db.php");

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM placement_drives WHERE id=$id"
);

header("Location: managedrives.php");
exit();

?>