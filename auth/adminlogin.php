<?php

session_start();

include "../config/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE username='$username'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 1)
{
    $admin = mysqli_fetch_assoc($result);

    if(password_verify($password, $admin['password']))
    {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        header("Location: ../frontend/pages/admin/admindashboard.php");
        exit();
    }
}

echo "
<script>
alert('Invalid Username or Password');
window.location.href='../frontend/pages/admin/adminlogin.php';
</script>
";
?>