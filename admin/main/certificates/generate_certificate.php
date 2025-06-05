<?php
session_start();
require '../../../assets/libs/fpdf186/fpdf.php';
include '../../../config/dbfetch.php';

if (!isset($_GET['id'])) {
    die('Missing request ID.');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM residencycertreq WHERE id = ?");
$stmt->execute([$id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    die('Request not found.');
}

$officials = $pdo->query("SELECT * FROM employee_details")->fetchAll(PDO::FETCH_ASSOC);

// Organize officials
$punongBarangay = '';
$kagawads = [];
$skChair = '';
$secretary = '';
$treasurer = '';

foreach ($officials as $emp) {
    $fullname = 'HON. ' . $emp['first_name'] . ' ' . strtoupper(substr($emp['middle_name'], 0, 1)) . '. ' . $emp['last_name'];
    $legislature = strtolower($emp['legislature']);

    if ($legislature === 'punong barangay') {
        $punongBarangay = $fullname;
    } elseif ($legislature === 'sangguniang barangay member' && count($kagawads) < 7) {
        $kagawads[] = $fullname;
    } elseif ($legislature === 'sangguniang kabataan chairperson') {
        $skChair = $fullname;
    } elseif ($legislature === 'barangay secretary') {
        $secretary = $fullname;
    } elseif ($legislature === 'barangay treasurer') {
        $treasurer = $fullname;
    }
}

// Prepare request details
$name = "{$request['firstname']} " . strtoupper(substr($request['middle_initial'], 0, 1)) . ". {$request['lastname']}";
$gender = strtolower($request['gender']) === 'm' ? 'he' : 'she';
$pronoun = strtolower($request['gender']) === 'm' ? 'his' : 'her';
$address = "{$request['street']}, Greenwater Village, Baguio City";
$duration = $request['years_residency'] ? "{$request['years_residency']} year(s)" : "{$request['months_residency']} month(s)";
$purpose = ucfirst($request['purpose']);
$issuedDate = date('jS') . ' day of ' . date('F') . ', ' . date('Y');

// Start PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 10, 20);

// Header Logos and Info
$pdf->Image('../../../assets/img/GreenwaterLogo.jpg', 20, 10, 25);
$pdf->Image('../../../assets/img/baguio-logo.jpg', 165, 10, 25);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, '          Republic of the Philippines', 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 5, 'Province of Benguet', 0, 1, 'C');
$pdf->Cell(0, 5, 'City of Baguio', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'BARANGAY GREENWATER VILLAGE', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, 'Email: greenwatervillage12345@gmail.com | Tel. No.: (074) 661-3656', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'BU', 12);
$pdf->Cell(0, 6, 'OFFICE OF THE PUNONG BARANGAY', 0, 1, 'C');
$pdf->Ln(7);

// Column layout
$startY = $pdf->GetY();
$leftX = 20;
$leftW = 60; //left table width
$rightX = $leftX + $leftW;
$rightW = 115; //right table width
$cellPadding = 2;

// ---------- LEFT COLUMN (Draw Box First) ----------
$pdf->Rect($leftX, $startY, $leftW, 180); // Adjust height as needed

$pdf->SetXY($leftX + $cellPadding, $startY + $cellPadding);
$pdf->SetFont('Arial', 'B', 11); //font family, bold, font size
$pdf->Cell($leftW - 2*$cellPadding, 5, 'SANGGUNIANG', 0, 1, 'C');
$pdf->Cell($leftW - 2*$cellPadding, 5, 'BARANGAY', 0, 1, 'C');
$pdf->Cell($leftW - 2*$cellPadding, 5, '_____________________', 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($leftW - 2*$cellPadding, 3, strtoupper($punongBarangay), 0, 1, 'C');
$pdf->Cell($leftW - 2*$cellPadding, 3, 'Punong Barangay', 0, 1, 'C');
$pdf->Ln(5);

foreach ($kagawads as $k) {
    $pdf->Cell($leftW - 2*$cellPadding, 3, strtoupper($k), 0, 1, 'C');
    $pdf->Cell($leftW - 2*$cellPadding, 3, 'Kagawad', 0, 1, 'C');
    $pdf->Ln(5);
}
if ($skChair) {
    $pdf->Cell($leftW - 2*$cellPadding, 3, strtoupper($skChair), 0, 1, 'C');
    $pdf->Cell($leftW - 2*$cellPadding, 3, 'SK Chairperson', 0, 1, 'C');
    $pdf->Ln(3);
}
$pdf->Cell($leftW - 2*$cellPadding, 3, '_____________________', 0, 1, 'C');
$pdf->Ln(3);
if ($secretary) {
    $pdf->Cell($leftW - 2*$cellPadding, 3, strtoupper($secretary), 0, 1, 'C');
    $pdf->Cell($leftW - 2*$cellPadding, 3, 'Barangay Secretary', 0, 1, 'C');
    $pdf->Ln(5);
}
if ($treasurer) {
    $pdf->Cell($leftW - 2*$cellPadding, 3, strtoupper($treasurer), 0, 1, 'C');
    $pdf->Cell($leftW - 2*$cellPadding, 3, 'Barangay Treasurer', 0, 1, 'C');
}

// ---------- RIGHT COLUMN (Draw Box and Write Inside) ----------
$pdf->Rect($rightX, $startY, $rightW, 180); // Same height as left column

// Set position inside the right box (title)
$pdf->SetXY($rightX + $cellPadding, $startY + $cellPadding);
$pdf->SetFont('Arial', 'BU', 13);
$pdf->Cell($rightW - 2 * $cellPadding, 8, 'CERTIFICATE OF RESIDENCY', 0, 1, 'C');

// Certificate body text starts below the title
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);
$pdf->SetX($rightX + $cellPadding); // Align left edge of text

$certText = "TO WHOM IT MAY CONCERN:\n\n";
$certText .= "     This is to certify that " . strtoupper($name) . ", " . $request['age'] . " years of age and a Filipino citizen, is a bona fide resident of " . $address . " and that $gender has been a resident in the barangay for $duration.\n\n";
$certText .= "     This certificate is issued upon the request of the above-named person for the purpose of $purpose.\n\n";
$certText .= "     Issued this $issuedDate at Greenwater Village, Baguio City, Philippines.";

// Align text manually using Write and SetX for proper paragraph control
$pdf->SetX($rightX + $cellPadding);
$pdf->MultiCell($rightW - 2 * $cellPadding, 7, $certText, 0, 'J');

// ---------- Signature Block (Bottom Right of Right Box) ----------
$signatureY = $startY + 160 - 30; // Move 30 units from bottom of right box
$signatureX = $rightX + $rightW - 70; // Position signature near right edge (adjust width if needed)

$pdf->SetXY($signatureX, $signatureY);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 6, strtoupper($punongBarangay), 0, 1, 'C');

$pdf->SetX($signatureX);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(60, 6, 'Punong Barangay', 0, 1, 'C');


$pdf->Output('I', 'Certificate_of_Residency.pdf');
?>
