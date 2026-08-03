<?php

session_start();

if(!isset($_SESSION['student_id']))
{
    header("Location: ../frontend/pages/student/login.html");
    exit();
}

include "../config/db.php";

$id = $_SESSION['student_id'];

$fullname = mysqli_real_escape_string(
    $conn,
    $_POST['fullname']
);

$rollno = mysqli_real_escape_string(
    $conn,
    $_POST['rollno']
);

$branch = mysqli_real_escape_string(
    $conn,
    $_POST['branch']
);

$graduation_year = mysqli_real_escape_string(
    $conn,
    $_POST['graduation_year']
);

$cgpa = mysqli_real_escape_string(
    $conn,
    $_POST['cgpa']
);

$email = mysqli_real_escape_string(
    $conn,
    $_POST['email']
);

$skills = mysqli_real_escape_string(
    $conn,
    $_POST['skills']
);

$updateProfile = "
UPDATE students
SET
fullname='$fullname',
rollno='$rollno',
branch='$branch',
graduation_year='$graduation_year',
cgpa='$cgpa',
email='$email',
skills='$skills'
WHERE id='$id'
";
// PROFILE PHOTO

if(
    isset($_FILES['profile_photo']) &&
    $_FILES['profile_photo']['error'] == 0
)
{
    $photoName =
    time().'_'.$_FILES['profile_photo']['name'];

    move_uploaded_file(
        $_FILES['profile_photo']['tmp_name'],
        "../uploads/".$photoName
    );

    $sql = "
    UPDATE students
    SET profile_photo='$photoName'
    WHERE id='$id'
    ";

    mysqli_query($conn,$sql);
}


// RESUME

if(
    isset($_FILES['resume']) &&
    $_FILES['resume']['error'] == 0
)
{
    $resumeName =
    time().'_'.$_FILES['resume']['name'];

    move_uploaded_file(
        $_FILES['resume']['tmp_name'],
        "../uploads/".$resumeName
    );

    $sql = "
    UPDATE students
    SET resume='$resumeName'
    WHERE id='$id'
    ";

    mysqli_query($conn,$sql);
}
if(mysqli_query($conn,$updateProfile))
{
    $_SESSION['fullname'] = $fullname;

    echo "
    <script>
    alert('Profile Updated Successfully');
   window.location.href='../frontend/pages/student/profile.php';
    </script>
    ";
}
else
{
    echo mysqli_error($conn);
}

?>