<!-- PHP CODE -->
<?php
    include '../../config/dbconfig.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Employee Registration</title>
    <script src="../../assets/js/sweetalert2.js"></script>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
                <h1>UBISH</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="../account/login.php">Log In</a>
                    </li>
                </ul>
            </nav>
        </div>
        <hr>
    </header>
    <main>
        <form method="POST" enctype="multipart/form-data">
            <div class="signup-form">
                <h1>Register to UBISH</h1><br>
                <a href="../../index.php">← Go Home</a>
                <div class="signup-credentials">
                    <p>First Name</p>
                    <input type="text" name="fname" placeholder="Enter First Name">
                </div>
                <div class="signup-credentials">
                    <p>Middle Name</p>
                    <input type="text" name="mname" placeholder="Enter Middle Name">
                </div>
                <div class="signup-credentials">
                    <p>Last Name</p>
                    <input type="text" name="lname" placeholder="Enter Last Name">
                </div>
                <div class="signup-credentials">
                    <p>Date of Birth</p>
                    <input type="date" name="dob">
                </div>
                <div class="signup-credentials">
                    <p>Biological Sex</p>
                    <select name="sex">
                        <option value="" disabled selected>Select Biological Sex</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="I">Intersex</option>
                    </select>
                </div>
                <div class="signup-credentials">
                    <p>Residential Address</p>
                    <input type="text" name="address" placeholder="Enter Address">
                </div>
                <div class="signup-credentials">
                    <p>Religion</p>
                    <input type="text" name="religion" placeholder="Enter Religion">
                </div>
                <div class="signup-credentials">
                    <p>Civil Status</p>
                    <select name="civilstatus">
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
                    <select name="legislature">
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
                    <input type="text" name="phonenum" placeholder="Enter Phone Number">
                </div>
                <div class="signup-credentials">
                    <p>Profile Picture</p>
                    <input type="file" id="picture" name="picture" placeholder="Upload Photo">
                    <br>
                    <img 
                        id="profile-preview"
                        src="../../uploads/default_profile.jpg" 
                        alt="Profile Preview"
                        style="width: 150px; height: 150px; object-fit: cover;"
                    >
                    <script src="../../assets/js/profilePreview.js"></script>
                </div>
                <?php
                    if (isset($_POST['register'])) {
                        $fname = !empty($_POST['fname']) ? trim($_POST['fname']) : null;
                        $mname = !empty($_POST['mname']) ? trim($_POST['mname']) : null;
                        $lname = !empty($_POST['lname']) ? trim($_POST['lname']) : null;
                        $dob = !empty($_POST['dob']) ? $_POST['dob'] : null;
                        $sex = !empty($_POST['sex']) ? $_POST['sex'] : null;
                        $address = !empty($_POST['address']) ? trim($_POST['address']) : null;
                        $religion = !empty($_POST['religion']) ? trim($_POST['religion']) : null;
                        $civilstatus = !empty($_POST['civilstatus']) ? $_POST['civilstatus'] : null;
                        $legislature = !empty($_POST['legislature']) ? $_POST['legislature'] : null;
                        $phonenum = !empty($_POST['phonenum']) ? trim($_POST['phonenum']) : null;
                        $phPhoneNumRegex = '/^09\d{9}$/'; // regex for Philippine phone numbers
                
                        $accesslvl = 0; // no access level
                        $limitedAccess = array("Sangguniang Kabataan Member", "Other Barangay Personnel");
                        $standardAccess = array("Sangguniang Barangay Member", "Sangguniang Kabataan Chairperson", "Barangay Treasurer");
                        $fullAccess = array("Punong Barangay", "Barangay Secretary");
                        if (in_array($legislature, $limitedAccess)) {
                            $accesslvl = 1; // limited access
                        } elseif (in_array($legislature, $standardAccess)) {
                            $accesslvl = 2; // standard access
                        } elseif (in_array($legislature, $fullAccess)) {
                            $accesslvl = 3; // full access
                        }
                
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
                                        echo "<br><p><center>Failed to upload file.<center></p>";
                                    }
                                } else {
                                    echo "<br><p><center>File size exceeds the maximum limit of 10MB.<center></p>";
                                }
                            } else {
                                echo "<br><p><center>Invalid file type. Only PNG, JPG, and JPEG are allowed.<center></p>";
                            }
                        }
                
                        if (empty($fname) || empty($mname) || empty($lname) || 
                            empty($dob) || empty($sex) || empty($address) || 
                            empty($religion) || empty($civilstatus) || empty($legislature) || 
                            empty($phonenum) || empty($accesslvl) || empty($uploadedFilePath)) {
                ?>
                                <script>
                                    Swal.fire({
                                        title: "Failed to register.",
                                        text: "Please enter all required fields.",
                                        icon: "error"
                                    }).then((result) => {
                                        window.location.href = '../account/register.php'
                                    });
                                </script>
                <?php
                            exit;
                        } else if (!preg_match($phPhoneNumRegex, $phonenum)) {
                ?>
                            <script>
                                Swal.fire({
                                    title: "Failed to register.",
                                    text: "Please enter a valid phone number.",
                                    icon: "error"
                                }).then((result) => {
                                    window.location.href = '../account/register.php'
                                });
                            </script>
                <?php
                            exit;
                        } else {
                            $registerQuery = "
                                INSERT INTO employee_registration (
                                    first_name, middle_name, last_name, 
                                    date_of_birth, sex, address, 
                                    religion, civil_status, legislature, 
                                    access_level, phone_no, picture
                                ) VALUES (
                                    :fname, :mname, :lname, 
                                    :dob, :sex, :address, 
                                    :religion, :civilstatus, :legislature, 
                                    :accesslvl, :phonenum, :picture
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
                                ":picture" => $uploadedFilePath
                            ]);
                            
                            if ($register) {
                                $_SESSION['registration_emp_id'] = $pdo->lastInsertId();
                                echo "<script>window.location.href = '../account/signup.php';</script>";
                                exit;
                            } else {
                ?>
                                <script>
                                    Swal.fire({
                                        title: "Failed to register.",
                                        text: "An error occurred while registering employee information. Please try again.",
                                        icon: "error"
                                    }).then((result) => {
                                        window.location.href = '../account/register.php'
                                    });
                                </script>
                <?php
                            }
                        }
                    }
                ?>
                <div class="signup-btns">
                    <button name="register">Register</button>
                </div>
            </div>
        </form>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
