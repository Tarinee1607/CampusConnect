<?php
include("../../../config/no_cache.php");
include("../../../config/db.php");

$token = mysqli_real_escape_string(
    $conn,
    $_POST['token']
);

$password = $_POST['password'];

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM students
     WHERE reset_token='$token'
     AND token_expiry > NOW()"
);

if(mysqli_num_rows($result) == 0)
{
    die("Invalid or expired token");
}

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

mysqli_query(
    $conn,
    "UPDATE students
     SET
     password='$hashedPassword',
     reset_token=NULL,
     token_expiry=NULL
     WHERE reset_token='$token'"
);

echo "
<script>
alert('Password updated successfully');
window.location='login.php';
</script>
";
?>