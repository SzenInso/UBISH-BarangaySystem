<?php
require_once('tcpdf-main/tcpdf.php');
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
    } elseif ($legislature === 'kagawad' && count($kagawads) < 7) {
        $kagawads[] = $fullname;
    } elseif ($legislature === 'sk chairperson') {
        $skChair = $fullname;
    } elseif ($legislature === 'barangay secretary') {
        $secretary = $fullname;
    } elseif ($legislature === 'barangay treasurer') {
        $treasurer = $fullname;
    }
}

// Setup PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();

// Logos
$pdf->Image('../../../assets/img/GreenwaterLogo.jpg', 15, 10, 25);
$pdf->Image('../../../assets/img/baguio-logo.jpg', 170, 10, 25);

// Header
$pdf->SetY(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, 'Province of Benguet', 0, 1, 'C');
$pdf->Cell(0, 5, 'City of Baguio', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 5, 'BARANGAY GREENWATER VILLAGE', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Email: greenwatervillage12345@gmail.com', 0, 1, 'C');
$pdf->Cell(0, 5, '(074) 661-3656', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, 'OFFICE OF THE PUNONG BARANGAY', 0, 1, 'C');

$pdf->Ln(10);

// Dynamic values
$name = "{$request['firstname']} " . strtoupper(substr($request['middle_initial'], 0, 1)) . ". {$request['lastname']}";
$gender = strtolower($request['gender']) === 'm' ? 'he' : 'she';
$address = "{$request['street']}, Greenwater Village, Baguio City";
$duration = $request['years_residency'] ? $request['years_residency'] . ' year(s)' : $request['months_residency'] . ' month(s)';
$purpose = ucfirst($request['purpose']);
$issuedDate = date('jS') . ' day of ' . date('F') . ', ' . date('Y');

// Certificate content
$html = '
<style>
p {
    text-align: justify;
    text-indent: 40px;
    font-family: helvetica;
    font-size: 11pt;
}
</style>

<table border="0" cellpadding="5">
<tr>
<td width="30%" valign="top" style="border:1px solid #000;">
    <p style="text-align:center; text-indent:0;"><strong>SANGGUNIANG BARANGAY</strong></p>
    <p style="text-align:center; text-indent:0;">' . $punongBarangay . '<br>Punong Barangay</p>';

foreach ($kagawads as $k) {
    $html .= '<p style="text-align:center; text-indent:0;">' . $k . '<br>Kagawad</p>';
}

$html .= '
    <p style="text-align:center; text-indent:0;">' . $skChair . '<br>SK Chairperson</p>
    <p style="text-align:center; text-indent:0;">' . $secretary . '<br>Barangay Secretary</p>
    <p style="text-align:center; text-indent:0;">' . $treasurer . '<br>Barangay Treasurer</p>
</td>

<td width="70%" valign="top" style="border:1px solid #000;">
    <p style="text-align:center; text-indent:0;"><strong>CERTIFICATE OF RESIDENCY</strong></p>
    <p>To Whom It May Concern:</p>
    <p>This is to certify that ' . $name . ', ' . $request['age'] . ' years of age and a Filipino citizen, is a bona fide resident of ' . $address . ' and that ' . $gender . ' has been a resident in the barangay for ' . $duration . '.</p>
    <p>This certificate is issued upon the verbal request of the above-named person for ' . $purpose . '.</p>
    <p>Issued this ' . $issuedDate . ' at Greenwater Village, Baguio City, Philippines.</p>

    <br><br><br><br><br>
    <p style="text-align:center; text-indent:0;"><strong>' . $punongBarangay . '</strong><br>Punong Barangay</p>
</td>
</tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Certificate_of_Residency.pdf', 'I');
?>
