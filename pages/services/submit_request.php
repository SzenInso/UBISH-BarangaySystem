<?php
// ===================== PHPMailer Starts here =======================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../services/PHPMailer-master/src/Exception.php';
require '../services/PHPMailer-master/src/PHPMailer.php';
require '../services/PHPMailer-master/src/SMTP.php';
// ===================== PHPMailer Ends here =======================

$dsn = 'mysql:host=localhost;dbname=ubish';
$user = 'root';
$pass = '';

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (
        !empty($_POST['first_name']) &&
        !empty($_POST['middle_name']) &&
        !empty($_POST['last_name']) &&
        !empty($_POST['request_date']) &&
        !empty($_POST['street']) &&
        !empty($_POST['barangay']) &&
        !empty($_POST['city']) &&
        !empty($_POST['province']) &&
        !empty($_POST['zipcode']) &&
        !empty($_POST['age']) &&
        !empty($_POST['civil_status']) &&
        !empty($_POST['citizenship']) &&
        !empty($_POST['purpose']) &&
        !empty($_POST['email']) &&
        !empty($_POST['contact_number'])
    ) {
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $request_date = $_POST['request_date'];
        $street = $_POST['street'];
        $barangay = $_POST['barangay'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $zipcode = $_POST['zipcode'];
        $age = $_POST['age'];
        $civil_status = $_POST['civil_status'];
        $citizenship = $_POST['citizenship'];
        $purpose = $_POST['purpose'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];

        $sql = "INSERT INTO residency_requests 
        (first_name, middle_name, last_name, request_date, street, barangay, city, province, zipcode, age, civil_status, citizenship, email, contact_number, purpose, status) 
        VALUES 
        (:first_name, :middle_name, :last_name, :request_date, :street, :barangay, :city, :province, :zipcode, :age, :civil_status, :citizenship, :email, :contact_number, :purpose, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'request_date' => $request_date,
            'street' => $street,
            'barangay' => $barangay,
            'city' => $city,
            'province' => $province,
            'zipcode' => $zipcode,
            'age' => $age,
            'civil_status' => $civil_status,
            'citizenship' => $citizenship,
            'email' => $email,
            'contact_number' => $contact_number,
            'purpose' => $purpose
        ]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';  // Your email
            $mail->Password = 'your_password';  // Your password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'UBISH Barangay Admin');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Certificate of Residency Request Received';
            $mail->Body = "Dear $first_name $middle_name $last_name,<br><br>
            Your request for a Certificate of Residency has been received and is currently under review.<br><br>
            You will be notified once it is approved or denied.<br><br>Thank you!<br>UBISH Barangay Office";

            $mail->send();

            echo "<script>alert('Your request has been submitted successfully. Please check your email for confirmation.'); window.location.href='service_page.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Your request has been submitted successfully but email notification failed to send.'); window.location.href='service_page.php';</script>";
        }
    } else {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
    }

    $conn = null;
} catch (PDOException $e) {
    echo "<script>alert('Connection failed: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>
