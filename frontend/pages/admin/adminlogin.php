<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <div class="col-lg-6 left-panel d-none d-lg-flex">
            <div class="content">
                <h1>CampusConnect Admin</h1>
                <p>
                    Placement Cell Management Portal
                </p>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="login-card">

                <h2>Admin Login</h2>

                <form action="../../../auth/adminlogin.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

</body>
</html>