<?php
include("../../../config/no_cache.php");
include("../../../config/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

$email = mysqli_real_escape_string(
    $conn,
    $_POST['email']
);

$result = mysqli_query(
    $conn,
    "SELECT * FROM students
     WHERE email='$email'"
);

if(mysqli_num_rows($result) == 0)
{
    echo "<script>
    alert('No account found with this email');
    window.location='forgot_password.php';
    </script>";
    exit();
}

$token = bin2hex(random_bytes(32));

mysqli_query(
    $conn,
    "UPDATE students
     SET
     reset_token='$token',
     token_expiry = DATE_ADD(NOW(), INTERVAL 30 MINUTE)
     WHERE email='$email'"
);

$resetLink =
"http://localhost:8080/CampusConnect/frontend/pages/student/reset_password.php?token=$token";

$mail = new PHPMailer(true);

try
{
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'demopython500@gmail.com';
    $mail->Password = 'xqfa eksr nwcj hcly';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(
        'demopython500@gmail.com',
        'CampusConnect'
    );

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject =
    'CampusConnect Password Reset';

    $mail->Body =
    "
    <h2>Password Reset Request</h2>

    <p>
    We received a request to reset your password.
    </p>

    <p>
    Click the button below:
    </p>

    <p>
    <a href='$resetLink'
       style='padding:10px 20px;
              background:#0d6efd;
              color:white;
              text-decoration:none;
              border-radius:5px;'>

       Reset Password

    </a>
    </p>

    <p>
    This link will expire in 30 minutes.
    </p>

    <p>
    If you did not request this,
    simply ignore this email.
    </p>
    ";

    $mail->send();

    echo "<script>
    alert('Password reset link sent to your email');
    window.location='login.php';
    </script>";
}
catch(Exception $e)
{
    echo $mail->ErrorInfo;
}