<?php

session_start();


include "../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = $_POST['password'];

    $sql = "SELECT * FROM students
            WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1)
    {
        $student = mysqli_fetch_assoc($result);

        if(password_verify(
            $password,
            $student['password']
        ))
        {
            $_SESSION['student_id']
                = $student['id'];

            $_SESSION['fullname']
                = $student['fullname'];

            $_SESSION['email']
                = $student['email'];

            header(
                "Location: ../frontend/pages/student/dashboard.php"
            );

            exit();
        }
        else
        {
            echo "
            <script>
            alert('Invalid Password');
            window.history.back();
            </script>";
        }
    }
    else
    {
        echo "
        <script>
        alert('Student Not Found');
        window.history.back();
        </script>";
    }
}

?>