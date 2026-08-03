<?php
include("admin_auth.php");
include "../../../config/db.php";
include("../../../config/no_cache.php");
$title = $_POST['title'];
$category = $_POST['category'];
$description = $_POST['description'];

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO notices(title, category, description)
     VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $title,
    $category,
    $description
);

if(mysqli_stmt_execute($stmt))
{
    header("Location: managenotices.php");
    exit();
}
else
{
    echo mysqli_error($conn);
}
?>