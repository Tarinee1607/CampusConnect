<?php
include("../../../config/no_cache.php");
include("admin_auth.php");
include("../../../config/db.php");

require_once "../../../library/fpdf186/fpdf.php";

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',16);

$pdf->Cell(
    190,
    10,
    'CampusConnect Placement Report',
    0,
    1,
    'C'
);

$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(50,10,'Department',1);
$pdf->Cell(40,10,'Students',1);
$pdf->Cell(40,10,'Placed',1);
$pdf->Cell(40,10,'Rate',1);

$pdf->Ln();

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

$pdf->SetFont('Arial','',11);

while($row = mysqli_fetch_assoc($query))
{
    $rate = 0;

    if($row['total_students'] > 0)
    {
        $rate = round(
            ($row['placed_students'] /
            $row['total_students']) * 100,
            2
        );
    }

    $pdf->Cell(50,10,$row['branch'],1);
    $pdf->Cell(40,10,$row['total_students'],1);
    $pdf->Cell(40,10,$row['placed_students'],1);
    $pdf->Cell(40,10,$rate.'%',1);

    $pdf->Ln();
}

$pdf->Output();