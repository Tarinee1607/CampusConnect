<?php

include("../config/db.php");

$name = mysqli_real_escape_string(
    $conn,
    $_POST['name']
);

$email = mysqli_real_escape_string(
    $conn,
    $_POST['email']
);

$message = mysqli_real_escape_string(
    $conn,
    $_POST['message']
);

$sql = "
INSERT INTO contact_messages
(name,email,message)
VALUES
('$name','$email','$message')
";

mysqli_query($conn,$sql);

header("Location: ../index.php?msg=success");
exit();

?>