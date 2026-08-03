<?php

include("../../../config/db.php");
include("../../../config/no_cache.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
require '../../../vendor/autoload.php';




$application_id = $_POST['application_id'];
$status = $_POST['status'];
$interview_date = $_POST['interview_date'];
$oldResult = mysqli_query(
    $conn,
    "SELECT status
     FROM applications
     WHERE id='$application_id'"
);

$oldRow = mysqli_fetch_assoc($oldResult);

$old_status = $oldRow['status'];
if(
    !empty($interview_date)
    &&
    $interview_date < date('Y-m-d')
)
{
    echo "<script>
    alert('Interview date cannot be in the past');
    window.history.back();
    </script>";
    exit();
}
mysqli_query(
    $conn,
    "UPDATE applications
SET
status='$status',
interview_date='$interview_date'
WHERE id='$application_id'"
);


$result = mysqli_query(
    $conn,
    "SELECT
        applications.student_id,
        students.fullname,
        students.email,
        placement_drives.company_name,
        placement_drives.role_name

     FROM applications

     JOIN students
     ON applications.student_id = students.id

     JOIN placement_drives
     ON applications.drive_id = placement_drives.id

     WHERE applications.id='$application_id'"
);

$row = mysqli_fetch_assoc($result);

$student_id = $row['student_id'];
$student_name = $row['fullname'];
$email = $row['email'];
$company = $row['company_name'];
$role = $row['role_name'];

if($old_status != $status)
{

    

    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'demopython500@gmail.com';                 //SMTP username
        $mail->Password = 'xqfa eksr nwcj hcly';                         //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        //Enable TLS encryption
        $mail->Port = 587;                                        //TCP port to connect to

        //Recipients
        $mail->setFrom('demopython500@gmail.com', 'Placement Cell');
        $mail->addAddress($email);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
       if($status == "Interview")
{
    $mail->Subject =
    "Interview Scheduled - CampusConnect";
}
elseif($status == "Selected")
{
    $mail->Subject =
    "Congratulations! You Have Been Selected";
}
elseif($status == "Rejected")
{
    $mail->Subject =
    "Application Status Update";
}
else
{
    $mail->Subject =
    "Application Status Updated";
}
        
        if($status == "Interview")
{

$mail->Body = "
<html>
<body>

<p>Dear <strong>$student_name</strong>,</p>

<p>
Congratulations!
</p>

<p>
You have been shortlisted for the next stage of recruitment.
</p>

<p>
<strong>Company:</strong> $company<br>
<strong>Role:</strong> $role<br>
<strong>Interview Date:</strong> $interview_date
</p>

<p>
Please be available on the scheduled date.
</p>

<p>
Placement Cell<br>
CampusConnect
</p>

</body>
</html>
";

}
elseif($status == "Selected")
{
$formatted_interview_date = date(
    "d F Y",
    strtotime($interview_date)
);
$mail->Body = "
<html>
<body>

<p>Dear <strong>$student_name</strong>,</p>

<p>
We are delighted to inform you that you have been selected for:
</p>

<p>
<strong>Company:</strong> $company<br>
<strong>Role:</strong> $role
<strong>Interview Date:</strong>
<span style='color:#0d6efd;font-size:16px;'>
$formatted_interview_date
</span>
</p>

<p>
Congratulations on this achievement.
</p>

<p>
Placement Cell<br>
CampusConnect
</p>

</body>
</html>
";

}
elseif($status == "Rejected")
{

$mail->Body = "
<html>
<body>

<p>Dear <strong>$student_name</strong>,</p>

<p>
Thank you for participating in the recruitment process.
</p>

<p>
<strong>Company:</strong> $company<br>
<strong>Role:</strong> $role
</p>

<p>
Unfortunately you have not been selected for the next stage.
</p>

<p>
We encourage you to continue applying for future opportunities.
</p>

<p>
Placement Cell<br>
CampusConnect
</p>

</body>
</html>
";

}
else
{

$mail->Body = "
<html>
<body>

<p>Dear <strong>$student_name</strong>,</p>

<p>
Your application status has been updated.
</p>

<p>
<strong>Company:</strong> $company<br>
<strong>Role:</strong> $role<br>
<strong>Current Status:</strong> $status
</p>

<p>
Please log in to CampusConnect to view the latest details.
</p>

<p>
Placement Cell<br>
CampusConnect
</p>

</body>
</html>
";

}

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if($status == "Selected")
{
    mysqli_query(
        $conn,
        "UPDATE students
         SET placement_status='Placed'
         WHERE id='$student_id'"
    );
}
else
{
    $selectedCheck = mysqli_query(
        $conn,
        "SELECT *
         FROM applications
         WHERE student_id='$student_id'
         AND status='Selected'"
    );

    if(mysqli_num_rows($selectedCheck) == 0)
    {
        mysqli_query(
            $conn,
            "UPDATE students
             SET placement_status='Not Placed'
             WHERE id='$student_id'"
        );
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();

?>