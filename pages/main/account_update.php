<?php
include '../../config/dbfetch.php';

if (isset($_POST['confirm'])) {
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
    $standardAccess = array("Sangguniang Barangay Member", "Sangguniang Kabataan Chairperson", "Barangay Secretary", "Barangay Treasurer");
    $fullAccess = array("Punong Barangay");
    if (in_array($legislature, $limitedAccess)) {
        $accesslvl = 1; // limited access
    } elseif (in_array($legislature, $standardAccess)) {
        $accesslvl = 2; // standard access
    } elseif (in_array($legislature, $fullAccess)) {
        $accesslvl = 3; // full access
    }

    $filePathQuery = "SELECT picture FROM employee_details WHERE emp_id = :emp_id";
    $filePathDetails = $pdo->prepare($filePathQuery);
    $filePathDetails->execute([":emp_id" => $_SESSION['emp_id']]);
    $filePathDB = $filePathDetails->fetchColumn();
    $uploadedFilePath = $filePathDB; // current profile picture path
    if (isset($_FILES['update-picture']) && $_FILES['update-picture']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['update-picture']['tmp_name'];
        $fileName = $_FILES['update-picture']['name'];
        $fileSize = $_FILES['update-picture']['size'];
        $fileType = $_FILES['update-picture']['type'];
        $fileExtension = strtolower(pathinfo(basename($fileName), PATHINFO_EXTENSION));
        $allowedFileExtensions = array('jpg', 'png', 'jpeg');
        $maxFileSize = 10 * 1024 * 1024; // 10MB

        if (in_array($fileExtension, $allowedFileExtensions)) {
            if ($fileSize <= $maxFileSize) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $targetFilePath = "../../uploads/profiles/" . $newFileName;
                if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                    if ($filePathDB !== "../../uploads/default_profile.jpg") { // doesn't delete default profile pic
                        unlink($filePathDB); // delete old file
                    }
                    $uploadedFilePath = $targetFilePath;
                } else {
                    echo "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'File upload failed.',
                                    text: 'Failed to upload updated file.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href='../main/account_update.php';
                                });
                            });
                        </script>
                    ";
                    exit;
                }
            
            } else {
                echo "
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'File size limit exceeded.',
                                text: 'File size exceeds the maximum limit of 10MB.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href='../main/account_update.php';
                            });
                        });
                    </script>
                ";
                exit;
            }
        } else {
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'File type invalid.',
                            text: 'Invalid file type. Only PNG, JPG, and JPEG are allowed.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/account_update.php';
                        });
                    });
                </script>
            ";
            exit;
        }
    }

    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $reason = !empty($_POST['reason']) ? trim($_POST['reason']) : null;

    if (!preg_match($phPhoneNumRegex, $phonenum)) {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Invalid value.',
                        text: 'Please enter a valid phone number.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/account_update.php';
                    });
                });
            </script>
        ";
        exit;
    } else {
        try {
            $pdo->beginTransaction();

            // safe updates
            $updateLoginQuery = "UPDATE login_details SET username = :username, email = :email WHERE emp_id = :emp_id";
            $updateLogin = $pdo->prepare($updateLoginQuery);
            $updateLogin->execute([
                ":username" => $username,
                ":email" => $email,
                ":emp_id" => $_SESSION['emp_id']
            ]);

            $updateNumQuery = "UPDATE employee_details SET phone_no = :phonenum, picture = :picture WHERE emp_id = :emp_id";
            $updateNum = $pdo->prepare($updateNumQuery);
            $updateNum->execute([
                ":phonenum" => $phonenum,
                ":picture" => $uploadedFilePath,
                ":emp_id" => $_SESSION['emp_id']
            ]);

            $updateRequest = false;

            // under review updates
            $currentValQuery = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
            $currentVal = $pdo->prepare($currentValQuery);
            $currentVal->execute([":emp_id" => $_SESSION['emp_id']]);
            $currentValRow = $currentVal->fetch();

            $isSame = (
                $fname === $currentValRow['first_name'] &&
                $mname === $currentValRow['middle_name'] &&
                $lname === $currentValRow['last_name'] &&
                $dob === $currentValRow['date_of_birth'] &&
                $sex === $currentValRow['sex'] &&
                $address === $currentValRow['address'] &&
                $religion === $currentValRow['religion'] &&
                $civilstatus === $currentValRow['civil_status'] &&
                $legislature === $currentValRow['legislature'] &&
                $accesslvl === $currentValRow['access_level']
            );

            if (!$isSame) {
                $reviewUpdateQuery = "
                        INSERT INTO employee_update (
                            emp_id, update_first_name, update_middle_name, update_last_name, update_date_of_birth, 
                            update_sex, update_address, update_religion, update_civil_status, update_legislature, 
                            update_access_level, update_status, update_reason
                        ) VALUES (
                            :emp_id, :update_first_name, :update_middle_name, :update_last_name, :update_date_of_birth,
                            :update_sex, :update_address, :update_religion, :update_civil_status, :update_legislature,
                            :update_access_level, 'Pending', :update_reason
                        )
                    ";
                $reviewUpdate = $pdo->prepare($reviewUpdateQuery);
                $reviewUpdate->execute([
                    ":emp_id" => $_SESSION['emp_id'],
                    ":update_first_name" => $fname,
                    ":update_middle_name" => $mname,
                    ":update_last_name" => $lname,
                    ":update_date_of_birth" => $dob,
                    ":update_sex" => $sex,
                    ":update_address" => $address,
                    ":update_religion" => $religion,
                    ":update_civil_status" => $civilstatus,
                    ":update_legislature" => $legislature,
                    ":update_access_level" => $accesslvl,
                    ":update_reason" => $reason
                ]);

                $updateRequest = true;
            }

            $confirmUpdate = $pdo->commit();
            if ($confirmUpdate) {
                if ($updateRequest) {
                    echo "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'Account update under review.',
                                    text: 'Account has been updated successfully. An update request is under review.',
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href='../main/account.php';
                                });
                            });
                        </script>
                    ";
                } else {
                    echo "
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'Account update successful.',
                                    text: 'Account has been updated successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href='../main/account.php';
                                });
                            });
                        </script>
                    ";
                }
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error updating account: " . $e->getMessage());
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Account update failed.',
                            text: 'Failed to update account. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/account_update.php';
                        });
                    });
                </script>
            ";
            exit;
        }
    }
}

if (isset($_POST['cancel'])) {
    header('location:../main/account.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Update Account</title>
    <style>
        .dashboard-content p#warning {
            margin-bottom: 24px;
        }

        .account-main {
            display: flex;
            justify-content: space-between;
        }

        .account-main input,
        .account-main select {
            margin-top: 4px;
            padding: 4px;
            width: 256px;
        }

        .account-main textarea {
            padding: 8px;
            width: 354px;
            resize: vertical;
            overflow: hidden;
        }

        .employee-details,
        .account-details {
            margin: 0 16px;
        }

        .update-profile-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 16px;
        }

        .update-profile-container p,
        .update-profile-container input {
            margin: 0 8px;
        }
    </style>
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
                    <li class="active"><a href="../main/account.php">Account</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Account Page</center>
                </h1><br>
                <form method="POST" enctype="multipart/form-data">
                    <?php foreach ($empDetails as $row) { ?>
                        <img id="employee-picture" src="<?php echo $row['picture']; ?>" alt="Employee Picture"
                            title="<?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>">
                        <div class="update-profile-container">
                            <p><strong>Update Profile Picture: </strong></p>
                            <input type="file" name="update-picture" id="update-picture" accept="image/*">
                        </div>
                        <div class="account-main">
                            <div class="employee-details">
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <h2>Update Employee Details</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>UBISH Employee ID: </strong></td>
                                        <td><?php echo $row['emp_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>First Name: </strong></td>
                                        <td><input type="text" name="fname" placeholder="Update first name"
                                                value="<?php echo $row['first_name']; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Middle Name: </strong></td>
                                        <td><input type="text" name="mname" placeholder="Update middle name"
                                                value="<?php echo $row['middle_name']; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Last Name: </strong></td>
                                        <td><input type="text" name="lname" placeholder="Update last name"
                                                value="<?php echo $row['last_name']; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Date of Birth: </strong></td>
                                        <td><input type="date" name="dob" id="dobInput"
                                                value="<?php echo $row['date_of_birth']; ?>" onchange="updateAge()"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Biological Sex: </strong></td>
                                        <td>
                                            <select name="sex">
                                                <option value="" disabled>Select Biological Sex</option>
                                                <option value="M" <?php echo ($row['sex'] === 'M') ? "selected" : ''; ?>>Male
                                                </option>
                                                <option value="F" <?php echo ($row['sex'] === 'F') ? "selected" : ''; ?>>
                                                    Female</option>
                                                <option value="I" <?php echo ($row['sex'] === 'I') ? "selected" : ''; ?>>
                                                    Intersex</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Age: </strong></td>
                                        <td id="ageDisplay">
                                            <?php
                                            $birthDate = new DateTime($row['date_of_birth']);
                                            $today = new DateTime('today');
                                            $age = $birthDate->diff($today)->y;
                                            echo $age;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Address: </strong></td>
                                        <td><input type="text" name="address" placeholder="Update address"
                                                value="<?php echo $row['address'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Religion: </strong></td>
                                        <td><input type="text" name="religion" placeholder="Update religion"
                                                value="<?php echo $row['religion'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Civil Status: </strong></td>
                                        <td>
                                            <select name="civilstatus">
                                                <option value="" disabled>Update Civil Status</option>
                                                <option value="Single" <?php echo ($row['civil_status'] === "Single") ? "selected" : ''; ?>>Single</option>
                                                <option value="Married" <?php echo ($row['civil_status'] === "Married") ? "selected" : ''; ?>>Married</option>
                                                <option value="Divorced" <?php echo ($row['civil_status'] === "Divorced") ? "selected" : ''; ?>>Divorced</option>
                                                <option value="Widowed" <?php echo ($row['civil_status'] === "Widowed") ? "selected" : ''; ?>>Widowed</option>
                                                <option value="Legally Separated" <?php echo ($row['civil_status'] === "Legally Separated") ? "selected" : ''; ?>>
                                                    Legally Separated</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone Number: </strong></td>
                                        <td><input type="text" name="phonenum" placeholder="Update phone number"
                                                value="<?php echo $row['phone_no'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><span style="color: crimson">* </span><strong>Legislature: </strong></td>
                                        <td>
                                            <select name="legislature">
                                                <option value="" disabled>Update Legislature</option>
                                                <option value="Punong Barangay" <?php echo ($row['legislature'] === "Punong Barangay") ? "selected" : ''; ?>>Punong Barangay</option>
                                                <option value="Sangguniang Barangay Member" <?php echo ($row['legislature'] === "Sangguniang Barangay Member") ? "selected" : ''; ?>>Sangguniang Barangay Member</option>
                                                <option value="Sangguniang Kabataan Chairperson" <?php echo ($row['legislature'] === "Sangguniang Kabataan Chairperson") ? "selected" : ''; ?>>Sangguniang Kabataan Chairperson</option>
                                                <option value="Sangguniang Kabataan Member" <?php echo ($row['legislature'] === "Sangguniang Kabataan Member") ? "selected" : ''; ?>>Sangguniang Kabataan Member</option>
                                                <option value="Barangay Secretary" <?php echo ($row['legislature'] === "Barangay Secretary") ? "selected" : ''; ?>>
                                                    Barangay Secretary</option>
                                                <option value="Barangay Treasurer" <?php echo ($row['legislature'] === "Barangay Treasurer") ? "selected" : ''; ?>>
                                                    Barangay Treasurer</option>
                                                <option value="Other Barangay Personnel" <?php echo ($row['legislature'] === "Other Barangay Personnel") ? "selected" : ''; ?>>Other Barangay Personnel</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="account-details">
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <h2>Update Account Details</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Username: </strong></td>
                                        <td><input type="text" name="username" placeholder="Update username"
                                                value="<?php echo $row['username'] ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email: </strong></td>
                                        <td><input type="email" name="email" placeholder="Update email"
                                                value="<?php echo $row['email'] ?>"></td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <h2>Reason for Update</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><textarea name="reason" placeholder="Specify reason"></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <p id="warning"><span style="color: crimson;">* </span>Note: Some personal information will be under
                            review before it will be updated.</p>
                    <?php } ?>
                    <script src="../../assets/js/ageDisplay.js"></script>
                    <script>
                        // profile preview script for account update page
                        document.addEventListener('DOMContentLoaded', function () {
                            const input = document.getElementById('update-picture');
                            const preview = document.getElementById('employee-picture');

                            input.addEventListener('change', function (event) {
                                const file = event.target.files[0]; // first image file selected from the input
                                if (file) { // if image is selected
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        preview.src = e.target.result; // dynamically display selected image
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    preview.src = '<?php echo $row['picture']; ?>'; // else, display current image
                                }
                            });
                        });
                    </script>
                    <div class="account-actions">
                        <button type="submit" name="confirm" class="update-btn" id="updateBtn">Confirm Update</button>
                        <button type="submit" name="cancel" class="change-password" id="changePwdBtn">Cancel
                            Update</button>
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