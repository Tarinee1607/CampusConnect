<?php
include("../../../config/no_cache.php");
include("../../../config/db.php");

if(!isset($_GET['token']))

{

die("Invalid token");

}

$token = mysqli_real_escape_string(

$conn,

$_GET['token']

);
$result = mysqli_query(
    $conn,
    "SELECT *
     FROM students
     WHERE reset_token='$token'
     AND token_expiry > NOW()"
);

if(mysqli_num_rows($result) == 0)
{
    die("Reset link expired or invalid");
}

if(mysqli_num_rows($result) == 0)

{

die("Reset link expired or invalid");

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Reset Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5" style="max-width:500px;">

<div class="card p-4 shadow">

<h3 class="mb-3">Reset Password</h3>

<form action="update_password.php" method="POST">

<input

type="hidden"

name="token"

value="<?php echo $token; ?>">

<input

type="password"

name="password"

class="form-control mb-3"

placeholder="Enter New Password"

required>

<button type="submit" class="btn btn-success w-100">

Update Password

</button>

</form>

</div>

</div>

</body>

</html>