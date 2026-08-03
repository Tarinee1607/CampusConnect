<?php

session_start();

include "../config/db.php";

$company_name = $_POST['company_name'];
$role_name = $_POST['role_name'];
$package = $_POST['package'];
$min_cgpa = $_POST['min_cgpa'];
$eligible_branch = $_POST['eligible_branch'];
$eligible_year = $_POST['eligible_year'];
$description = $_POST['description'];
$deadline = $_POST['deadline'];


if($deadline < date('Y-m-d'))
{
    die("Past dates are not allowed.");
}
$sql = "
INSERT INTO placement_drives
(
company_name,
role_name,
package,
min_cgpa,
eligible_branch,
eligible_year,
description,
deadline
)
VALUES
(
'$company_name',
'$role_name',
'$package',
'$min_cgpa',
'$eligible_branch',
'$eligible_year',
'$description',
'$deadline'
)
";


if(mysqli_query($conn,$sql))
{
    echo "
    <script>
    alert('Drive Added Successfully');
    window.location.href='../frontend/pages/admin/managedrives.php';
    </script>
    ";
}
else
{
    echo mysqli_error($conn);
}

?>