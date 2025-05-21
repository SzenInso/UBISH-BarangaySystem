<?php
include '../../config/dbconfig.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (
        isset($_POST['firstname'], $_POST['lastname'], $_POST['age'], $_POST['contactNumber'], $_POST['street'], $_POST['barangay'], $_POST['gender'], $_POST['purpose'], $_POST['duration_unit'], $_POST['years_residency'])
    ) {
        $firstname = trim($_POST['firstname']);
        $middle_initial = isset($_POST['middle_initial']) ? trim($_POST['middle_initial']) : null;
        $lastname = trim($_POST['lastname']);
        $suffix = isset($_POST['suffix']) ? trim($_POST['suffix']) : null;
        $age = intval($_POST['age']);
        $contact = trim($_POST['contactNumber']);
        $street = trim($_POST['street']);
        $barangay = trim($_POST['barangay']);
        $gender = $_POST['gender'];
        $residency_value = intval($_POST['years_residency']);
        $duration_unit = $_POST['duration_unit'];
        $purpose = trim($_POST['purpose']);

        //checks the contact number
        if (!preg_match('/^09\d{9}$/', $contact)) {
            die('Invalid contact number. Must be 11 digits and start with 09.');
        }

        if (
            $firstname === '' || $lastname === '' || $age <= 0 || $street === '' ||
            $barangay === '' || !in_array($gender, ['Male', 'Female']) ||
            $residency_value <= 0 || $purpose === '' || !in_array($duration_unit, ['years', 'months'])
        ) {
            die('Please fill all required fields correctly.');
        }

        $years_residency = null;
        $months_residency = null;

        if ($duration_unit === 'years') {
            $years_residency = $residency_value;
        } else if ($duration_unit === 'months') {
            $months_residency = $residency_value;
        }
        try {
            $sql = "INSERT INTO residencycertreq 
                    (firstname, middle_initial, lastname, suffix, age, contactNumber, street, barangay, gender, years_residency, months_residency, purpose, status, created_at) 
                    VALUES 
                    (:firstname, :middle_initial, :lastname, :suffix, :age, :contact, :street, :barangay, :gender, :years_residency, :months_residency, :purpose, 'pending', NOW())";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':firstname' => $firstname,
                ':middle_initial' => $middle_initial,
                ':lastname' => $lastname,
                ':suffix' => $suffix,
                ':age' => $age,
                ':contact' => $contact,
                ':street' => $street,
                ':barangay' => $barangay,
                ':gender' => $gender,
                ':years_residency' => $years_residency,
                ':months_residency' => $months_residency,
                ':purpose' => $purpose
            ]);

            header('Location: residencyCert.php');
            exit();

        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    } else {
        die('Missing form data.');
    }
} else {
    die('Invalid request method.');
}