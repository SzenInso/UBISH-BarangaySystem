<?php
    include "../../config/dbfetch.php";

    if (isset($_POST['cancel'])) {
        header('location:../main/dashboard.php');
        exit;
    }

    if (isset($_POST['register'])) {
        /* Global error handling */
        $errors = [];

        /* Employee Registration */
        $fname = trim($_POST['fname'] ?? '');
        $mname = trim($_POST['mname'] ?? '');
        $lname = trim($_POST['lname'] ?? '');
        
        $dob = trim($_POST['dob'] ?? '');
        $sex = trim($_POST['sex'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $religion = trim($_POST['religion'] ?? '');
        $civilstatus = trim($_POST['civilstatus'] ?? '');
        
        $phonenum = trim($_POST['phonenum'] ?? '');
        $phPhoneNumRegex = '/^09\d{9}$/'; // regex for Philippine phone numbers
        
        $legislature = trim($_POST['legislature'] ?? '');
        $committee = trim($_POST['committee'] ?? '');
        $accesslvl = 0; // no access level
        $limitedAccess = array(
            "Sangguniang Kabataan Member", 
            "Other Barangay Personnel"
        );
        $standardAccess = array(
            "Sangguniang Barangay Member", 
            "Sangguniang Kabataan Chairperson",
            "Sangguniang Kabataan Secretary",
            "Sangguniang Kabataan Treasurer",
            "Barangay Treasurer"
        );
        $fullAccess = array(
            "Punong Barangay", 
            "Barangay Secretary"
        );
        if (in_array($legislature, $limitedAccess)) { $accesslvl = 1; }         // limited access
        elseif (in_array($legislature, $standardAccess)) { $accesslvl = 2; }    // standard access
        elseif (in_array($legislature, $fullAccess)) { $accesslvl = 3; }        // full access
        
        /* Login Credentials */
        $username = trim($_POST['username'] ?? '');
        $email = $_POST['email'] ?? '';
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirmPassword'] ?? '');

        /* Input validation */
        if (empty($fname)) $errors[] = "First name is required.";
        if (empty($lname)) $errors[] = "Last name is required.";
        if (empty($dob)) $errors[] = "Date of birth is required.";
        if (empty($sex)) $errors[] = "Biological sex is required.";
        if (empty($address)) $errors[] = "Residential address is required.";
        if (empty($religion)) $errors[] = "Residential address is required.";
        if (empty($civilstatus)) $errors[] = "Civil status is required.";
        if (empty($phonenum)) $errors[] = "Phone number is required.";
        if (!preg_match($phPhoneNumRegex, $phonenum)) $errors[] = "Invalid phone number format.";
        if (empty($legislature)) $errors[] = "Legislature is required.";
        if (empty($username)) $errors[] = "Username is required.";
        if (!$email) $errors[] = "Invalid email address.";
        if (empty($password)) $errors[] = "Password is required.";
        if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";
        
        /* Profile Picture */
        $uploadedFilePath = "../../uploads/default_profile.jpg"; // default profile picture path
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['picture']['tmp_name'];
            $fileName = $_FILES['picture']['name'];
            $fileSize = $_FILES['picture']['size'];
            $fileType = $_FILES['picture']['type'];
            $fileExtension = strtolower(pathinfo(basename($fileName), PATHINFO_EXTENSION));
            $allowedFileExtensions = array('jpg', 'png', 'jpeg');
            $maxFileSize = 10 * 1024 * 1024; // 10MB

            if (in_array($fileExtension, $allowedFileExtensions)) {
                if ($fileSize <= $maxFileSize) {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $targetFilePath = "../../uploads/temp/" . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                        $uploadedFilePath = $targetFilePath;
                    } else {
                        $errors[] = "Failed to upload file.";
                    }
                } else {
                    $errors[] = "File size exceeds the maximum limit of 10MB.";
                }
            } else {
                $errors[] = "Invalid file type. Only PNG, JPG, and JPEG are allowed.";
            }
        }

        if (!empty($errors)) {
            $errorMsg = "";
            foreach ($errors as $err) {
                $errorMsg .= "<p>" . htmlspecialchars($err) . "</p>";
            }
            echo "
                <link rel='stylesheet' href='../../assets/css/style.css'>
                <script src='../../assets/js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Unable to register.',
                            html: `$errorMsg`,
                            icon: 'error'
                        });
                    });
                </script>
            ";
        } else {
            try {
                $pdo->beginTransaction();

                $registerQuery = "
                    INSERT INTO employee_details (
                        first_name, middle_name, last_name, 
                        date_of_birth, sex, address, 
                        religion, civil_status, legislature, 
                        access_level, phone_no, picture,
                        committee
                    ) VALUES (
                        :fname, :mname, :lname, 
                        :dob, :sex, :address, 
                        :religion, :civilstatus, :legislature, 
                        :accesslvl, :phonenum, :picture,
                        :committee
                    )
                ";
                $register = $pdo->prepare($registerQuery);
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
                    ":accesslvl" => $accesslvl,
                    ":phonenum" => $phonenum,
                    ":picture" => $uploadedFilePath,
                    ":committee" => $committee
                ]);

                $emp_id = $pdo->lastInsertId();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $signupQuery = "INSERT INTO login_details (emp_id, username, email, password) VALUES (:emp_id, :username, :email, :password)";
                $signup = $pdo->prepare($signupQuery);
                $signup->execute([
                    ":emp_id" => $emp_id,
                    ":username" => $username,
                    ":email" => $email,
                    ":password" => $hashedPassword
                ]);

                $pdo->commit();
                echo "
                    <link rel='stylesheet' href='../../assets/css/style.css'>
                    <script src='../../assets/js/sweetalert2.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Registered successfully.',
                                icon: 'success'
                            });
                        });
                    </script>
                ";
            } catch (Exception $e) {
                error_log("Error occurred: " . $e->getMessage());
                echo "
                    <link rel='stylesheet' href='../../assets/css/style.css'>
                    <script src='../../assets/js/sweetalert2.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Registration error.',
                                text: 'An error occurred while registering an account. Please try again',
                                icon: 'error'
                            });
                        });
                    </script>
                ";
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Account Creation</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
            <form method="POST">
                <nav>
                    <ul>
                        <li>
                            <button class="logout" style="cursor: pointer;" name="logout">Log Out</button>
                        </li>
                    </ul>
                </nav>
            </form>
        </div>
        <hr>
    </header>
    <main>
        <div class="dashboard-main">
            <div class="dashboard-sidebar">
                <ul>
                    <h3>Home</h3>
                    <li><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <li class="active"><a href="../main/account_creation.php">Account Creation</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>
                    <li><a href="../main/account_requests.php">Account Requests</a></li>
                    <li><a href="../main/certificates.php">Certificate of Residency</a></li>
                    <li><a href="../main/permits.php">Barangay Permit</a></li>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Create an Account</center>
                </h1><br>
                <form method="POST" enctype="multipart/form-data">
                    <div class="account-creation-profile">
                        <img 
                            id="profile-preview"
                            src="../../uploads/default_profile.jpg" 
                            alt="Profile Preview"
                            style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;"
                        >
                        <br><input type="file" id="picture" name="picture" placeholder="Upload Photo">
                        <script src="../../assets/js/profilePreview.js"></script>
                    </div>
                    <div class="account-creation-main">
                        <style>
                            .account-creation-main {
                                display: flex;
                                justify-content: center;
                                margin: 0 auto;
                            }
                        </style>
                        <!-- Employee Registration -->
                        <div class="account-creation-container">
                            <h3>Employee Details</h3>
                            <table>
                                <tr>
                                    <td>First Name<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="fname" placeholder="Enter First Name"></td>
                                </tr>
                                <tr>
                                    <td>Middle Name</td>
                                    <td><input type="text" name="mname" placeholder="Enter Middle Name"></td>
                                </tr>
                                <tr>
                                    <td>Last Name<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="lname" placeholder="Enter Last Name"></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="date" name="dob"></td>
                                </tr>
                                <tr>
                                    <td>Biological Sex<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td>
                                        <select name="sex">
                                            <option value="" disabled selected>Select Biological Sex</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                            <option value="I">Intersex</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Residential Address<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="address" placeholder="Enter Current Residential Address"></td>
                                </tr>
                                <tr>
                                    <td>Religion<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="religion" placeholder="Enter Religion"></td>
                                </tr>
                                <tr>
                                    <td>Civil Status<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td>
                                        <select name="civilstatus">
                                            <option value="" disabled selected>Select Civil Status</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Legally Separated">Legally Separated</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Legislature<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td>
                                        <select name="legislature">
                                            <option value="" disabled selected>Select Legislature</option>
                                            <option value="Punong Barangay">Punong Barangay</option>
                                            <option value="Barangay Secretary">Barangay Secretary</option>
                                            <option value="Barangay Treasurer">Barangay Treasurer</option>
                                            <option value="Sangguniang Barangay Member">Sangguniang Barangay Member</option>
                                            <option value="Sangguniang Kabataan Chairperson">Sangguniang Kabataan Chairperson</option>
                                            <option value="Sangguniang Kabataan Secretary">Sangguniang Kabataan Secretary</option>
                                            <option value="Sangguniang Kabataan Treasurer">Sangguniang Kabataan Treasurer</option>
                                            <option value="Sangguniang Kabataan Member">Sangguniang Kabataan Member</option>
                                            <option value="Other Barangay Personnel">Other Barangay Personnel</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Committee</td>
                                    <td><input type="text" name="committee" placeholder="Enter Committee"></td>
                                </tr>
                                <tr>
                                    <td>Phone Number<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="phonenum" placeholder="Enter Valid Phone Number"></td>
                                </tr>
                            </table>
                        </div>
                        <!-- Login Credentials Registration -->
                        <div class="account-creation-container">
                            <h3>Login Credentials</h3>
                            <table>
                                <tr>
                                    <td>Username<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="text" name="username" placeholder="Enter Username"></td>
                                </tr>
                                <tr>
                                    <td>Email Address</td>
                                    <td><input type="email" name="email" placeholder="Enter Email"></td>
                                </tr>
                                <tr>
                                    <td>Password<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="password" name="password" id="password" placeholder="Enter Password"></td>
                                </tr>
                                <tr>
                                    <td>Confirm Password<span title="Required" style="color: crimson; cursor: pointer;">&nbsp;*</span></td>
                                    <td><input type="password" name="confirmPassword" id="confirm-password" placeholder="Re-enter Password"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="account-creation-register">
                        <button name="register">Register</button>
                        <button name="cancel">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>