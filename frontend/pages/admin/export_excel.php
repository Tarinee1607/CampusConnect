<?php
include("../../../config/no_cache.php");
include("admin_auth.php");
include("../../../config/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=placement_report.xls");

echo "Department\tTotal Students\tPlaced Students\tPlacement Rate\n";

$query = mysqli_query(
    $conn,
    "SELECT
        branch,
        COUNT(*) AS total_students,
        SUM(
            CASE
            WHEN placement_status='Placed'
            THEN 1
            ELSE 0
            END
        ) AS placed_students
     FROM students
     GROUP BY branch"
);

while($row = mysqli_fetch_assoc($query))
{
    $rate = 0;

    if($row['total_students'] > 0)
    {
        $rate =
        round(
            ($row['placed_students'] /
            $row['total_students']) * 100,
            2
        );
    }

    echo
    $row['branch']."\t".
    $row['total_students']."\t".
    $row['placed_students']."\t".
    $rate."%\n";
}
?>