<?php
include("admin_auth.php");
include("../../../config/no_cache.php");
?>
<?php

include "../../../config/db.php";

$search = "";
$category = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );
}

if(isset($_GET['category']))
{
    $category = mysqli_real_escape_string(
        $conn,
        $_GET['category']
    );
}

$query = "
SELECT *
FROM notices
WHERE 1
";

if($search != "")
{
    $query .= "
    AND (
        title LIKE '%$search%'
        OR description LIKE '%$search%'
    )";
}

if($category != "")
{
    $query .= "
    AND category = '$category'
    ";
}

$query .= "
ORDER BY created_at DESC
";

$result = mysqli_query($conn, $query);
// Total Notices
$totalNotices = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM notices"
    )
);

// This Month
$thisMonth = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM notices
         WHERE MONTH(created_at)=MONTH(CURDATE())
         AND YEAR(created_at)=YEAR(CURDATE())"
    )
);

// Today's Notices
$todayNotices = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM notices
         WHERE DATE(created_at)=CURDATE()"
    )
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect | Manage Notices</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/dashboard.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="wrapper">

    <!-- SIDEBAR -->
<?php include('adnav.php');?>
    <!-- <aside class="sidebar">

        <div class="logo">
            CampusConnect
        </div>

        <ul class="nav-links">

            <li>
                <a href="admindashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="managedrives.php">
                    Manage Drives
                </a>
            </li>

            <li>
                <a href="managestudents.php">
                    Manage Students
                </a>
            </li>

            <li class="active">
                <a href="managenotices.php">
                    Manage Notices
                </a>
            </li>

            <li>
                <a href="reports.php">
                    Reports
                </a>
            </li>

            <li>
               <a href="../../../auth/adminlogout.php">Logout</a>
            </li>

        </ul>

    </aside> -->

    <!-- MAIN CONTENT -->
<!-- <div style="background:red;color:white;padding:10px;">
    TEST MANAGENOTICES FILE
</div> -->
    <main class="main-content">

        <!-- TOPBAR -->

        <div class="topbar">

            <div>
                <h3>Manage Notices</h3>
                <p>Create and manage placement announcements</p>
            </div>

            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#noticeModal">

                <i class="bi bi-plus-circle"></i>
                Add Notice

            </button>

        </div>

        <!-- STATS -->

        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="stat-card">
                    <h2><?php echo $totalNotices['total']; ?></h2>
                    <p>Total Notices</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <h2><?php echo $thisMonth['total']; ?></h2>
                    <p>This Month</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                  <h2><?php echo $todayNotices['total']; ?></h2>
                    <p>Today's Updates</p>
                </div>
            </div>

        </div>

        <!-- SEARCH -->

        <div class="content-card mb-4">

<form method="GET">

    <div class="row">

        <div class="col-md-8">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search notices..."
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

        </div>

        <div class="col-md-4">

            <select
                name="category"
                class="form-select">

                <option value="">
                    All Categories
                </option>

                <option value="Placement Drive">
                    Placement Drive
                </option>

                <option value="Interview">
                    Interview
                </option>

                <option value="Workshop">
                    Workshop
                </option>

                <option value="Training">
                    Training
                </option>

            </select>

        </div>

    </div>

    <button
        type="submit"
        class="btn btn-primary mt-3">

        Search

    </button>

</form>

</div>

        <!-- NOTICE TABLE -->

        <div class="content-card">

            <h4 class="mb-3">
                Notice Records
            </h4>

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>

                </thead>

               <tbody>

<?php while($notice = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td>
        <?php echo $notice['title']; ?>
    </td>

    <td>
        <?php echo $notice['category']; ?>
    </td>

    <td>
        <?php echo date(
            "d M Y",
            strtotime($notice['created_at'])
        ); ?>
    </td>

    <td>

        <a
        href="delete_notice.php?id=<?php echo $notice['id']; ?>"
        class="btn btn-sm btn-danger"
        onclick="return confirm('Delete this notice?')">

            Delete

        </a>

    </td>

</tr>

<?php } ?>

</tbody>

            </table>

        </div>

    </main>

</div>

<!-- ADD NOTICE MODAL -->

<div class="modal fade"
     id="noticeModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Notice
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form action="add_notice.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Notice Title
                        </label>

                        <input
type="text"
name="title"
class="form-control"
required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Category
                        </label>

                       <select
name="category"
class="form-select">

                            <option>
                                Placement Drive
                            </option>

                            <option>
                                Interview
                            </option>

                            <option>
                                Workshop
                            </option>

                            <option>
                                Training
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
name="description"
rows="5"
class="form-control"
required></textarea>

                    </div>

                

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
type="submit"
class="btn btn-primary">
Publish Notice
</button>

            </div>
</form>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>