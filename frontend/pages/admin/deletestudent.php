<?php
include("admin_auth.php");
?>
<?php

session_start();

include("../../../config/db.php");

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM students WHERE id=$id"
);

header("Location: managestudents.php");
exit();

?>