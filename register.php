<!-- PHP CODE -->
<?php
    include 'dbconfig.php';

    if (isset($_POST['register'])) {
        $fname = $_POST['fname'];
        $mname = $_POST['mname'];
        $lname = $_POST['lname'];
        $dob = $_POST['dob'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];
        $religion = $_POST['religion'];
        $civilstatus = $_POST['civilstatus'];
        $legislature = $_POST['legislature'];
        $phonenum = $_POST['phonenum'];
        $uploadedFilePath = "uploads/default_profile.jpg"; // default profile picture path
        
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
            $fileName = $_FILES['picture']['name'];
            $fileTmpPath = $_FILES['picture']['tmp_name'];
            $fileSize = $_FILES['picture']['size'];
            $fileType = $_FILES['picture']['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedFileTypes = array("png", "jpg", "jpeg");
            $maxFileSize = 10 * 1024 * 1024; // 10MB

            if (in_array($fileExtension, $allowedFileTypes)) {
                if ($fileSize <= $maxFileSize) {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $targetFilePath = 'uploads/' . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                        $uploadedFilePath = $targetFilePath; // update path to uploaded file
                    } else {
                        echo "<br><p><center>Failed to upload file.<center></p>";
                    }
                } else {
                    echo "<br><p><center>File size exceeds the maximum limit of 10MB.<center></p>";
                }
            } else {
                echo "<br><p><center>Invalid file type. Only PNG, JPG, and JPEG are allowed.<center></p>";
            }
        }

        $query = "  INSERT INTO employee_details (
                        first_name, middle_name, last_name, date_of_birth, sex, address, religion, civil_status, legislature, phone_no, picture
                    ) VALUES (
                        :fname, :mname, :lname, :dob, :sex, :address, :religion, :civilstatus, :legislature, :phonenum, :picture
                    )";
        $register = $pdo->prepare($query);
        $register->execute([
            ":fname" => $fname,
            ":mname" => $mname,
            ":lname" => $lname,
            ":dob" => $dob,
            ":sex" => $sex,
            ":address" => $address,
            ":religion" => $religion,
            ":civilstatus" => $civilstatus,
            ":legislature" => $legislature,
            ":phonenum" => $phonenum,
            ":picture" => $uploadedFilePath
        ]);

        if ($register) {
            $emp_id = $pdo->lastInsertId();
            header('location:signup.php?emp_id=' . $emp_id);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register Page</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="https://placehold.co/100" alt="UBISH Logo">
                <h1>UBISH</h1>
            </div>
        </div>
        <hr>
    </header>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <div class="signup-form">
                <h1>Register to UBISH</h1>
                <div class="signup-credentials">
                    <p>First Name</p>
                    <input type="text" name="fname" placeholder="Enter First Name" required>
                </div>
                <div class="signup-credentials">
                    <p>Middle Name</p>
                    <input type="text" name="mname" placeholder="Enter Middle Name" required>
                </div>
                <div class="signup-credentials">
                    <p>Last Name</p>
                    <input type="text" name="lname" placeholder="Enter Last Name" required>
                </div>
                <div class="signup-credentials">
                    <p>Date of Birth</p>
                    <input type="date" name="dob" required>
                </div>
                <div class="signup-credentials">
                    <p>Biological Sex</p>
                    <select name="sex" required>
                        <option value="" disabled selected>Select Biological Sex</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="I">Intersex</option>
                    </select>
                </div>
                <div class="signup-credentials">
                    <p>Residential Address</p>
                    <input type="text" name="address" placeholder="Enter Address" required>
                </div>
                <div class="signup-credentials">
                    <p>Religion</p>
                    <input type="text" name="religion" placeholder="Enter Religion" required>
                </div>
                <div class="signup-credentials">
                    <p>Civil Status</p>
                    <select name="civilstatus" required>
                        <option value="" disabled selected>Select Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Legally Separated">Legally Separated</option>
                    </select>
                </div>
                <div class="signup-credentials">
                    <p>Legislature</p>
                    <select name="legislature" required>
                        <option value="" disabled selected>Select Legislature</option>
                        <option value="Punong Barangay">Punong Barangay</option>
                        <option value="Sangguniang Barangay Member">Sangguniang Barangay Member</option>
                        <option value="Sangguniang Kabataan Chairperson">Sangguniang Kabataan Chairperson</option>
                        <option value="Sangguniang Kabataan Member">Sangguniang Kabataan Member</option>
                        <option value="Barangay Secretary">Barangay Secretary</option>
                        <option value="Barangay Treasurer">Barangay Treasurer</option>
                        <option value="Other Barangay Personnel">Other Barangay Personnel</option>
                    </select>
                </div>
                <div class="signup-credentials">
                    <p>Phone Number</p>
                    <input type="text" name="phonenum" placeholder="Enter Phone Number" required>
                </div>
                <div class="signup-credentials">
                    <p>Profile Picture</p>
                    <input type="file" name="picture" placeholder="Upload Photo">
                </div>
                <button name="register">Register</button>
            </div>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
