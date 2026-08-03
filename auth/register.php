<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $rollno = mysqli_real_escape_string($conn, $_POST['rollno']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $graduation_year = mysqli_real_escape_string($conn, $_POST['graduation_year']);
    $cgpa = mysqli_real_escape_string($conn, $_POST['cgpa']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password check
    if($password !== $confirm_password)
    {
        die("Passwords do not match");
    }

    // Check existing email or roll number
    $checkQuery = mysqli_query(
        $conn,
        "SELECT * FROM students
         WHERE email='$email'
         OR rollno='$rollno'"
    );

    if(mysqli_num_rows($checkQuery) > 0)
    {
        die("Email or Roll Number already exists");
    }

    // Hash password
    $hashedPassword = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    // Insert data
    $sql = "INSERT INTO students
    (
        fullname,
        rollno,
        email,
        password,
        branch,
        graduation_year,
        cgpa
    )
    VALUES
    (
        '$fullname',
        '$rollno',
        '$email',
        '$hashedPassword',
        '$branch',
        '$graduation_year',
        '$cgpa'
    )";

    if(mysqli_query($conn, $sql))
    {
        echo "
        <script>
            alert('Registration Successful');
            window.location.href='../frontend/pages/student/login.php';
        </script>
        ";
    }
    else
    {
        echo mysqli_error($conn);
    }
}

?>