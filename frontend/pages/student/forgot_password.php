<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5" style="max-width:500px;">

    <div class="card p-4 shadow">

        <h3 class="mb-3">
            Forgot Password
        </h3>

        <p>
            Enter your registered email address.
        </p>

        <form action="send_reset_link.php" method="POST">

            <input
                type="email"
                name="email"
                class="form-control mb-3"
                placeholder="Enter Email"
                required>

            <button
                type="submit"
                class="btn btn-primary w-100">

                Send Reset Link

            </button>

        </form>

    </div>

</div>

</body>
</html>