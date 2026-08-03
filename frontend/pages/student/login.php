<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
<?php
error_reporting(0);
session_start();
   echo $_SESSION['student_id'];
if(isset($_SESSION['student_id']))
{
    header("Location: dashboard.php");
exit();

}
?>
<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- Left Section -->
        <div class="col-lg-6 left-panel d-none d-lg-flex">
            <div class="content">
                <h1>CampusConnect</h1>
                <p>
                    A complete Placement Management System designed to connect
                    students with placement opportunities and streamline
                    university recruitment activities.
                </p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="login-card">

                <h2 class="mb-2">Welcome Back</h2>
                <p class="text-muted mb-4">
                    Login to continue
                </p>

                <form action="../../../auth/login.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">University Email</label>
                        <input
    type="email"
    name="email"
    class="form-control"
    placeholder="Enter your email"
    required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
    type="password"
    name="password"
    class="form-control"
    placeholder="Enter your password"
    required>
                    </div>

                    <div class="d-flex justify-content-between mb-4">

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember">
                            <label
                                class="form-check-label"
                                for="remember">
                                Remember Me
                            </label>
                        </div>

                        <a href="forgot_password.php" class="forgot-link">
                            Forgot Password?
                        </a>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100 login-btn">
                        Login
                    </button>

                </form>

                <div class="text-center mt-4">

                    Don't have an account?

                    <a href="register.html" class="register-link">
                        Register
                    </a>

                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>