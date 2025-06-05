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
        
        $phonenum = "+63" . trim($_POST['phonenum'] ?? '');
        $phPhoneNumRegex = '/^\+639\d{9}$/'; // regex for Philippine phone numbers
        
        $legislature = trim($_POST['legislature'] ?? '');   // append 0 to the beginning of the phone number
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
    <link rel="stylesheet" href="css/dash.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Greenwater Village | Account Creation</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul>
                <h2>
                    <div class="dashboard-greetings">
                        <?php 
                            $query = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
                            $empDetails = $pdo->prepare($query);
                            $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);
                            foreach ($empDetails as $row) {
                        ?>
                        <?php
                            }
                        ?>
                        <center>
                        <div class="user-info d-flex align-items-center">
                            <img src="<?php echo $row['picture']; ?>" 
                                class="avatar img-fluid rounded-circle me-2" 
                                alt="<?php echo $row['first_name']; ?>" 
                                width="70" height="70">
                        </div>
                        <span class="text-dark fw-semibold"><?php echo $row['first_name']; ?></span>
                        </center>
                    </div>
                </h2> </br>
                <h3><i class="fas fa-home"></i> Home</h3>
                <li class="active"><a href="../main/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../main/account.php"><i class="fas fa-user"></i> Account</a></li>
                <li><a href="../main/account_creation.php"><i class="fas fa-user-plus"></i> Account Creation</a></li>

                <h3><i class="fas fa-folder-open"></i> Documents & Disclosure</h3>
                <li><a href="../main/documents.php"><i class="fas fa-file-alt"></i> Documents</a></li>
                <li><a href="../main/announcements.php"><i class="fas fa-bullhorn"></i> Post Announcement</a></li>

                <h3><i class="fas fa-table"></i> Tables & Requests</h3>
                <li><a href="../main/employee_table.php"><i class="fas fa-users"></i> Employee Table</a></li>
                <li><a href="../main/account_requests.php"><i class="fas fa-user-check"></i> Account Requests</a></li>
                <li><a href="certificates/certificates.php"><i class="fas fa-certificate"></i> Certificate Requests</a></li>

                <h3><i class="fas fa-chart-bar"></i> Reports</h3>
                <li><a href="../main/incident_table.php"><i class="fas fa-exclamation-circle"></i> Incident History</a></li>
                <li><a href="../main/reports.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <header class="main-header">
                <button class="hamburger" id="toggleSidebar">&#9776;</button>
                <div class="header-container">
                    <div class="logo">
                        <img src="../../assets/img/GreenwaterLogo.jpg" alt="Greenwater Village Logo">
                        <h1><span>Greenwater</span> <span>Village</span></h1>
                    </div>
                    <nav class="nav" id="nav-menu">
                        <form method="POST">
                            <ul class="nav-links">
                                <li>
                                    <button class="logout-btn" name="logout">Log Out</button>
                                </li>
                            </ul>
                        </form>
                    </nav>
                </div>
            </header>
            <main class="content">
             <div class="dashboard-content">
        <div class="container">
        <h1>Create an Account</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="account-creation-profile">
                <img id="profile-preview" src="../../uploads/default_profile.jpg" alt="Profile Preview">
                <br><br>
                <label class="form-label"><strong>Upload Profile Picture:</strong></label>
                <input type="file" class="form-control" id="picture" name="picture">
            </div>

            <div class="account-creation-main">
                <div class="account-creation-container">
                    <h3>Employee Details</h3>
                    <table>
                        <tr>
                            <td>First Name<span style="color: crimson"> *</span></td>
                            <td><input type="text" name="fname" placeholder="Enter First Name"></td>
                        </tr>
                        <tr>
                            <td>Middle Name</td>
                            <td><input type="text" name="mname" placeholder="Enter Middle Name"></td>
                        </tr>
                        <tr>
                            <td>Last Name<span style="color: crimson"> *</span></td>
                            <td><input type="text" name="lname" placeholder="Enter Last Name"></td>
                        </tr>
                        <tr>
                            <td>Date of Birth<span style="color: crimson"> *</span></td>
                            <td><input type="date" name="dob"></td>
                        </tr>
                        <tr>
                            <td>Biological Sex<span style="color: crimson"> *</span></td>
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
                            <td>Residential Address<span style="color: crimson"> *</span></td>
                            <td><input type="text" name="address" placeholder="Enter Address"></td>
                        </tr>
                        <tr>
                            <td>Religion<span style="color: crimson"> *</span></td>
                            <td><input type="text" name="religion" placeholder="Enter Religion"></td>
                        </tr>
                        <tr>
                            <td>Civil Status<span style="color: crimson"> *</span></td>
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
                            <td>Legislature<span style="color: crimson"> *</span></td>
                            <td>
                                <select name="legislature">
                                    <option value="" disabled selected>Select Legislature</option>
                                    <option>Punong Barangay</option>
                                    <option>Barangay Secretary</option>
                                    <option>Barangay Treasurer</option>
                                    <option>Sangguniang Barangay Member</option>
                                    <option>Sangguniang Kabataan Chairperson</option>
                                    <option>Sangguniang Kabataan Secretary</option>
                                    <option>Sangguniang Kabataan Treasurer</option>
                                    <option>Sangguniang Kabataan Member</option>
                                    <option>Other Barangay Personnel</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Committee</td>
                            <td><input type="text" name="committee" placeholder="Enter Committee"></td>
                        </tr>
                        <tr>
                            <td>Phone Number<span style="color: crimson"> *</span></td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 5px;">+63</span>
                                    <input type="text" id="phonenum" name="phonenum" placeholder="e.g. 9123456789" maxlength="10" required
                                        style="flex: 1; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="account-creation-container">
                    <h3>Login Credentials</h3>
                    <table>
                        <tr>
                            <td>Username<span style="color: crimson"> *</span></td>
                            <td><input type="text" name="username" placeholder="Enter Username"></td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td><input type="email" name="email" placeholder="Enter Email"></td>
                        </tr>
                        <tr>
                            <td>Password<span style="color: crimson"> *</span></td>
                            <td><input type="password" name="password" placeholder="Enter Password"></td>
                        </tr>
                        <tr>
                            <td>Confirm Password<span style="color: crimson"> *</span></td>
                            <td><input type="password" name="confirmPassword" placeholder="Confirm Password"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="account-creation-register">
                <button type="submit" name="register">Register</button>
                <button type="reset" name="cancel">Cancel</button>
            </div>
        </form>
    </div>
    </div>
            </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        </div>
    </div>
    <script>
        document.getElementById('picture').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phonenum');
            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    if (this.value.length > 1 && this.value.charAt(0) === '0') {
                        this.value = this.value.slice(1);
                    }
                });
            }
        });
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    <style>
        .container {
            max-width: 1000px;
            margin-top: 40px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .account-creation-profile {
            text-align: center;
            margin-bottom: 30px;
        }
        .account-creation-profile img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ccc;
        }
        .account-creation-container {
            margin-bottom: 30px;
        }
        .account-creation-container h3 {
            margin-bottom: 20px;
            font-weight: 600;
        }
        table {
            width: 100%;
        }
        td {
            padding: 10px;
            vertical-align: middle;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .account-creation-register {
            text-align: center;
            margin-top: 30px;
        }
        .account-creation-register button {
            margin: 0 10px;
            padding: 10px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .account-creation-register button[name="register"] {
            background-color: #28a745;
            color: white;
        }
        .account-creation-register button[name="cancel"] {
            background-color: #dc3545;
            color: white;
        }
    </style>
</body>
</html>