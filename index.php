<?php

include("config/db.php");

$totalStudents = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM students")
);

$totalDrives = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM placement_drives
         WHERE deadline >= CURDATE()"
    )
);

$totalApplications = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM applications"
    )
);

$placedStudents = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM students
         WHERE placement_status='Placed'"
    )
);

$placementRate = 0;

if($totalStudents > 0)
{
    $placementRate =
    round(
        ($placedStudents/$totalStudents)*100
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="frontend/assets/css/landing.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">

        <div class="container">

            <a class="navbar-brand fw-bold" href="#">
                CampusConnect
            </a>

            <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#Howitworks">How it Works</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>

                </ul>

                <a href="frontend/pages/student/login.php"
                   class="btn btn-primary me-2">
                    Student Login
                </a>

                <a href="frontend/pages/admin/adminlogin.php"
                   class="btn btn-dark">
                    Admin Login
                </a>

            </div>

        </div>

    </nav>
<!-- HERO -->
<section id="home" class="hero-section">

    <div class="container">

        <div class="row align-items-center min-vh-100">

            <div class="col-lg-6">

                <h1 class="hero-title">
    Launch Your Career With Confidence
</h1>

<p class="hero-subtitle">
    A complete placement management platform for students,
    recruiters and placement officers.
</p>

                <p class="hero-text">
                    CampusConnect streamlines campus placements by helping
                    students discover opportunities, apply for drives,
                    track applications and connect with recruiters.
                    <p class="mt-4">

<strong><?php echo $totalStudents; ?></strong> Students •

<strong><?php echo $totalDrives; ?></strong> Active Drives •

<strong><?php echo $placedStudents; ?></strong> Successfully Placed

</p>
                </p>

                <div class="hero-buttons">

                    <a href="frontend/pages/student/login.php"
                       class="btn btn-primary btn-lg me-3">
                        Student Login
                    </a>

                    <a href="frontend/pages/admin/adminlogin.php"
                       class="btn btn-outline-light btn-lg">
                        Admin Login
                    </a>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <img
                    src="frontend/assets/images/hero-placement.jpg"
                    class="img-fluid hero-image"
                    alt="Placement">

            </div>

        </div>

    </div>

</section>
    <section class="stats-section">

    <div class="container">

        <div class="row text-center">

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $totalStudents; ?></h2>
<p>Registered Students</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $totalDrives; ?></h2>
<p>Active Drives</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <h2><?php echo $totalApplications; ?></h2>
<p>Applications Submitted</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                   <h2><?php echo $placementRate; ?>%</h2>
<p>Placement Rate</p>
                </div>
            </div>

        </div>

    </div>
</section>
    <section id="about" class="about-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <img
                    src="frontend/assets/images/about-placement.jpg"
                    class="img-fluid">

            </div>

            <div class="col-lg-6">

                <h2>About CampusConnect</h2>

                <p>
                    CampusConnect is a modern placement management platform
                    that simplifies interactions between students,
                    placement officers and recruiters.
                </p>

                <p>
                    From profile creation to application tracking,
                    everything is available through one unified portal.
                </p>

            </div>

        </div>

    </div>

</section>

   <section id="features" class="features-section">

    <div class="container">

        <h2 class="section-title">
            Features
        </h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Student Profiles</h4>
                    <p>Create and manage professional profiles.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Placement Drives</h4>
                    <p>Apply for active placement opportunities.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Application Tracking</h4>
                    <p>Track every application in one place.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Notices</h4>
                    <p>Stay updated with latest announcements.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Analytics</h4>
                    <p>View performance and placement insights.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <h4>Admin Dashboard</h4>
                    <p>Manage students, drives and notices.</p>
                </div>
            </div>

        </div>

    </div>

</section>

<section id= "Howitworks" class="steps-section">

    <div class="container">

        <h2 class="section-title">
            How It Works
        </h2>

        <div class="row text-center">

            <div class="col-md-3">
                <div class="step-card">
                    <h3>1</h3>
                    <p>Create Profile</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="step-card">
                    <h3>2</h3>
                    <p>Browse Drives</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="step-card">
                    <h3>3</h3>
                    <p>Apply Online</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="step-card">
                    <h3>4</h3>
                    <p>Get Placed</p>
                </div>
            </div>

        </div>

    </div>

</section>
    <!-- CONTACT -->

    <section id="contact">

        <div class="container">
            <div class="text-center mb-4">




<h2>Get In Touch</h2>

<p>
Have questions about placements or recruitment?
Contact our placement office.
</p></div>
            <h2>Contact Us</h2>
<div class="text-center mb-4">



</div>
            <form
action="auth/contact_submit.php"
method="POST">

                <input
type="text"
name="name"
class="form-control mb-3"
placeholder="Your Name"
required>

               <input
type="email"
name="email"
class="form-control mb-3"
placeholder="Your Email"
required>

               <textarea
name="message"
class="form-control mb-3"
rows="5"
placeholder="Message"
required></textarea>

                <button class="btn btn-primary">
                    Send Message
                </button>

            </form>
<?php
if(isset($_GET['msg']) && $_GET['msg'] == 'success')
{
?>
<div class="alert alert-success alert-dismissible fade show text-center m-3">
    Message sent successfully!
    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>
</div>
<?php
}
?>
        </div>

    </section>

    <!-- FOOTER -->
    <footer class="footer">

<div class="container text-center">

<h4>CampusConnect</h4>

<p>
Connecting Students, Recruiters and Opportunities
</p>

<p>
© <?php echo date("Y"); ?> CampusConnect. All Rights Reserved.
</p>

</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>