<?php
include("admin_auth.php");
?>
<?php

include "../../../config/db.php";

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM notices WHERE id=$id"
);

header("Location: managenotices.php");
exit();

?>