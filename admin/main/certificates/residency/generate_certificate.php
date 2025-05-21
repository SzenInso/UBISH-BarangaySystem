<?php
session_start();
require_once '../../../../assets/libs/tcpdf-main/tcpdf.php';
include '../../../../config/dbfetch.php';

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

// Fetch barangay officials
$officials = $pdo->query("SELECT * FROM employee_details")->fetchAll(PDO::FETCH_ASSOC);

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

// Format request data
$name = "{$request['firstname']} " . strtoupper(substr($request['middle_initial'], 0, 1)) . ". {$request['lastname']}";
$genderValue = strtolower($request['gender']);
$gender = $genderValue === 'male' ? 'he' : ($genderValue === 'female' ? 'she' : 'they');
$pronoun = $genderValue === 'male' ? 'his' : ($genderValue === 'female' ? 'her' : 'their');
$address = "{$request['street']}, Greenwater Village, Baguio City";
if (!empty($request['years_residency'])) {
    $years = (int)$request['years_residency'];
    $duration = $years === 1 ? "1 year" : "$years years";
} else {
    $months = (int)$request['months_residency'];
    $duration = "$months months";
}

$purpose = ucfirst($request['purpose']);
$issuedDate = date('jS') . ' day of ' . date('F') . ', ' . date('Y');

function numberToWords($number) {
    $dictionary = [
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three',
        4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven',
        8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven',
        12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
        15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
        18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty',
        30 => 'thirty', 40 => 'forty', 50 => 'fifty',
        60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety'
    ];

    if ($number < 21) return $dictionary[$number];
    elseif ($number < 100) {
        $tens = intval($number / 10) * 10;
        $units = $number % 10;
        return $units ? $dictionary[$tens] . '-' . $dictionary[$units] : $dictionary[$tens];
    }
    return $number;
}

$ageText = numberToWords($request['age']) . ' (' . $request['age'] . ')';

// --- Initialize TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(20, 10, 20);
$pdf->AddPage();
$pdf->SetFont('times', '', 12);
$pdf->SetAutoPageBreak(true, 20);

// --- Header Logos and Barangay Info
$logo1 = '../../../../assets/img/GreenwaterLogo.jpg';
$logo2 = '../../../../assets/img/baguio-logo.jpg';
$pdf->Image($logo1, 20, 10, 25);
$pdf->Image($logo2, 160, 10, 30);

$pdf->SetY(12);
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'C');
$pdf->SetFont('times', '', 11);
$pdf->Cell(0, 5, 'Province of Benguet', 0, 1, 'C');
$pdf->Cell(0, 5, 'City of Baguio', 0, 1, 'C');
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 6, 'BARANGAY GREENWATER VILLAGE', 0, 1, 'C');
$pdf->SetFont('times', '', 10);
$pdf->Cell(0, 5, 'Email: greenwatervillage12345@gmail.com | Tel. No.: (074) 661-3656', 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('times', 'BU', 12);
$pdf->Cell(0, 6, 'OFFICE OF THE PUNONG BARANGAY', 0, 1, 'C');
$pdf->Ln(5);

// --- Columns Layout (Two Boxes)
$startY = $pdf->GetY();
$leftW = 55;
$rightW = 115;
$leftX = 20;
$rightX = $leftX + $leftW;
$cellPadding = 2;

// LEFT COLUMN (Barangay Officials)
$pdf->Rect($leftX, $startY, $leftW, 200);
$pdf->SetXY($leftX + $cellPadding, $startY + 10);
$pdf->SetFont('times', 'BI', 11);
$pdf->MultiCell($leftW - 4, 5, "SANGGUNIANG\n  BARANGAY\n_____________________", 0, 'C');
$pdf->Ln(2);
$pdf->SetFont('times', 'BI', 10);
$pdf->MultiCell($leftW - 4, 4, strtoupper($punongBarangay) . "\nPunong Barangay", 0, 'C');
$pdf->Ln(3);

foreach ($kagawads as $k) {
    $pdf->MultiCell($leftW - 4, 4, strtoupper($k) . "\nKagawad", 0, 'C');
    $pdf->Ln(2);
}
if ($skChair) {
    $pdf->MultiCell($leftW - 4, 4, strtoupper($skChair) . "\nSK Chairperson", 0, 'C');
    $pdf->Ln(2);
}
$pdf->Cell($leftW - 4, 4, '_____________________', 0, 1, 'C');
$pdf->Ln(2);
if ($secretary) {
    $pdf->MultiCell($leftW - 4, 4, strtoupper($secretary) . "\nBarangay Secretary", 0, 'C');
    $pdf->Ln(2);
}
if ($treasurer) {
    $pdf->MultiCell($leftW - 4, 4, strtoupper($treasurer) . "\nBarangay Treasurer", 0, 'C');
}

// RIGHT COLUMN (Certificate Body)
$pdf->Rect($rightX, $startY, $rightW, 200); // Draw the box

// Title inside right box
$pdf->SetXY($rightX + $cellPadding, $startY + ($cellPadding+10));
$pdf->SetFont('times', 'BU', 13);
$pdf->Cell($rightW - 2 * $cellPadding, 8, 'CERTIFICATE OF RESIDENCY', 0, 1, 'C');

$upperName = strtoupper($name);

// Certificate body text using HEREDOC
$certText = <<<EOD
TO WHOM IT MAY CONCERN:<br><br><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <b>{$upperName}</b>, {$ageText} years of age and a Filipino citizen, is a bonafide resident of {$address} and that {$gender} has been a resident in the barangay for {$duration}.<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certificate is issued upon the request of the above-named person for  {$purpose}.<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this {$issuedDate} at Greenwater Village, Baguio City, Philippines.
EOD;

// Set font and position for the body
$pdf->SetFont('times', '', 12);
$pdf->SetXY($rightX + $cellPadding, $startY);

// Use writeHTMLCell to enable HTML and bold styling
$pdf->writeHTMLCell(
    $rightW - 2 * $cellPadding, // width
    0,                          // height (auto)
    $rightX + $cellPadding,     // x
    $startY+35,                // y
    $certText,                  // HTML content
    0, 1, 0, true, 'J', true    // border, ln, fill, reset, align, autopadding
);

// Signature Block
$pdf->Ln(20);
$pdf->SetX($rightX + 40);
$pdf->SetFont('times', 'B', 12);
$pdf->Cell(0, 5, strtoupper($punongBarangay), 0, 1, 'C');
$pdf->SetFont('times', '', 11);
$pdf->SetX($rightX + 40);
$pdf->Cell(0, 5, 'Punong Barangay', 0, 1, 'C');


// Notice about barangay seal
$pdf->Ln(50); // Add space below the signature
$pdf->SetFont('times', 'I', 10); // Italic font
$pdf->SetX($rightX); // Align to start of right column
$pdf->Cell($rightW, 5, 'This certificate is void without the BARANGAY SEAL.', 0, 0, 'C');

// Output PDF
$pdf->Output('Certificate_of_Residency.pdf', 'I');
?>
