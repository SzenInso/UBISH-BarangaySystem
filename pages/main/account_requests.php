<?php
include '../../config/dbfetch.php';

// approve individual registration request
if (isset($_POST['approve'])) {
    $selected = $_POST['approve'];

    try {
        $pdo->beginTransaction();

        $registrationQuery = "SELECT * FROM registration WHERE registration_id = :registration_id";
        $registration = $pdo->prepare($registrationQuery);
        $registration->execute([":registration_id" => $selected]);
        $registrationDetails = $registration->fetch();

        $fetchEmpApproveQuery = "SELECT * FROM employee_registration WHERE registration_emp_id = :registration_emp_id";
        $fetchEmpApprove = $pdo->prepare($fetchEmpApproveQuery);
        $fetchEmpApprove->execute([":registration_emp_id" => $registrationDetails['registration_emp_id']]);
        $empApprove = $fetchEmpApprove->fetch();

        $empInsertQuery = "
                INSERT INTO employee_details (
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
        $empInsert = $pdo->prepare($empInsertQuery);
        $empInsert->execute([
            ":fname" => $empApprove['first_name'],
            ":mname" => $empApprove['middle_name'],
            ":lname" => $empApprove['last_name'],
            ":dob" => $empApprove['date_of_birth'],
            ":sex" => $empApprove['sex'],
            ":address" => $empApprove['address'],
            ":religion" => $empApprove['religion'],
            ":civilstatus" => $empApprove['civil_status'],
            ":legislature" => $empApprove['legislature'],
            ":accesslvl" => $empApprove['access_level'],
            ":phonenum" => $empApprove['phone_no'],
            ":picture" => $empApprove['picture']
        ]);
        $empID = $pdo->lastInsertId();

        if ($empApprove['picture'] !== "../../uploads/default_profile.jpg") {
            $tempPath = $empApprove['picture'];
            $newPath = "../../uploads/profiles/" . basename($tempPath);
            if (file_exists($tempPath)) {
                $transferPath = rename($tempPath, $newPath);
                if (!$transferPath) {
                    throw new Exception("Failed to move profile picture.");
                }

                $updatePathQuery = "UPDATE employee_details SET picture = :picture WHERE emp_id = :emp_id";
                $updatePath = $pdo->prepare($updatePathQuery);
                $updatePath->execute([
                    ":picture" => $newPath,
                    ":emp_id" => $empID
                ]);
                if (!$updatePath) {
                    throw new Exception("Failed to update profile picture path.");
                }
            } else {
                throw new Exception("Profile picture file not found.");
            }
        }

        $fetchLoginApproveQuery = "SELECT * FROM login_registration WHERE registration_login_id = :registration_login_id";
        $fetchLoginApprove = $pdo->prepare($fetchLoginApproveQuery);
        $fetchLoginApprove->execute([":registration_login_id" => $registrationDetails['registration_login_id']]);
        $loginApprove = $fetchLoginApprove->fetch();

        $loginInsertQuery = "INSERT INTO login_details (emp_id, username, password, email) VALUES (:emp_id, :username, :password, :email)";
        $loginInsert = $pdo->prepare($loginInsertQuery);
        $loginInsert->execute([
            ":emp_id" => $empID,
            ":username" => $loginApprove['username'],
            ":password" => $loginApprove['password'],
            ":email" => $loginApprove['email']
        ]);

        $updateRegQuery = "UPDATE registration SET status = 'Approved' WHERE registration_id = :registration_id";
        $updateReg = $pdo->prepare($updateRegQuery);
        $updateReg->execute([":registration_id" => $selected]);

        $approved = $pdo->commit();
        if ($approved) {
            echo "
                    <script>
                        alert('Request approved successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to approve request.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to approve request: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to approve request.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// deny individual registration request
if (isset($_POST['deny'])) {
    $selected = $_POST['deny'];

    try {
        $pdo->beginTransaction();

        $registrationQuery = "SELECT * FROM registration WHERE registration_id = :registration_id";
        $registration = $pdo->prepare($registrationQuery);
        $registration->execute([":registration_id" => $selected]);
        $registrationDetails = $registration->fetch();

        $deleteTempQuery = "SELECT * FROM employee_registration WHERE registration_emp_id = :registration_emp_id";
        $deleteTemp = $pdo->prepare($deleteTempQuery);
        $deleteTemp->execute([":registration_emp_id" => $registrationDetails['registration_emp_id']]);
        $deleteTempPath = $deleteTemp->fetch();

        if ($deleteTempPath['picture'] !== "../../uploads/default_profile.jpg") {
            if (file_exists($deleteTempPath['picture'])) {
                unlink($deleteTempPath['picture']);
            } else {
                throw new Exception("Failed to delete profile picture.");
            }
        }

        $updateRegQuery = "UPDATE registration SET status = 'Denied' WHERE registration_id = :registration_id";
        $updateReg = $pdo->prepare($updateRegQuery);
        $updateReg->execute([":registration_id" => $selected]);

        $denied = $pdo->commit();
        if ($denied) {
            echo "
                    <script>
                        alert('Request denied successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to deny request.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to deny request: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to deny request.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// approve multiple registration requests
if (isset($_POST['approve-selected']) && isset($_POST['selection'])) {
    $selectedIDs = $_POST['selection'];

    try {
        $pdo->beginTransaction();

        foreach ($selectedIDs as $selected) {
            $registrationQuery = "SELECT * FROM registration WHERE registration_id = :registration_id";
            $registration = $pdo->prepare($registrationQuery);
            $registration->execute([":registration_id" => $selected]);
            $registrationDetails = $registration->fetch();

            $fetchEmpApproveQuery = "SELECT * FROM employee_registration WHERE registration_emp_id = :registration_emp_id";
            $fetchEmpApprove = $pdo->prepare($fetchEmpApproveQuery);
            $fetchEmpApprove->execute([":registration_emp_id" => $registrationDetails['registration_emp_id']]);
            $empApprove = $fetchEmpApprove->fetch();

            $empInsertQuery = "
                    INSERT INTO employee_details (
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
            $empInsert = $pdo->prepare($empInsertQuery);
            $empInsert->execute([
                ":fname" => $empApprove['first_name'],
                ":mname" => $empApprove['middle_name'],
                ":lname" => $empApprove['last_name'],
                ":dob" => $empApprove['date_of_birth'],
                ":sex" => $empApprove['sex'],
                ":address" => $empApprove['address'],
                ":religion" => $empApprove['religion'],
                ":civilstatus" => $empApprove['civil_status'],
                ":legislature" => $empApprove['legislature'],
                ":accesslvl" => $empApprove['access_level'],
                ":phonenum" => $empApprove['phone_no'],
                ":picture" => $empApprove['picture']
            ]);
            $empID = $pdo->lastInsertId();

            if ($empApprove['picture'] !== "../../uploads/default_profile.jpg") {
                $tempPath = $empApprove['picture'];
                $newPath = "../../uploads/profiles/" . basename($tempPath);
                if (file_exists($tempPath)) {
                    $transferPath = rename($tempPath, $newPath);
                    if (!$transferPath) {
                        throw new Exception("Failed to move profile picture.");
                    }

                    $updatePathQuery = "UPDATE employee_details SET picture = :picture WHERE emp_id = :emp_id";
                    $updatePath = $pdo->prepare($updatePathQuery);
                    $updatePath->execute([":picture" => $newPath, ":emp_id" => $empID]);
                    if (!$updatePath) {
                        throw new Exception("Failed to update profile picture path.");
                    }
                } else {
                    throw new Exception("Profile picture file not found.");
                }
            }

            $fetchLoginApproveQuery = "SELECT * FROM login_registration WHERE registration_login_id = :registration_login_id";
            $fetchLoginApprove = $pdo->prepare($fetchLoginApproveQuery);
            $fetchLoginApprove->execute([":registration_login_id" => $registrationDetails['registration_login_id']]);
            $loginApprove = $fetchLoginApprove->fetch();

            $loginInsertQuery = "INSERT INTO login_details (emp_id, username, password, email) VALUES (:emp_id, :username, :password, :email)";
            $loginInsert = $pdo->prepare($loginInsertQuery);
            $loginInsert->execute([
                ":emp_id" => $empID,
                ":username" => $loginApprove['username'],
                ":password" => $loginApprove['password'],
                ":email" => $loginApprove['email']
            ]);

            $updateRegQuery = "UPDATE registration SET status = 'Approved' WHERE registration_id = :registration_id";
            $updateReg = $pdo->prepare($updateRegQuery);
            $updateReg->execute([":registration_id" => $selected]);
        }

        $approved = $pdo->commit();
        if ($approved) {
            echo "
                    <script>
                        alert('Selected requests approved successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to approve requests.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to approve requests: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to approve requests.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// deny multiple registration requests
if (isset($_POST['deny-selected']) && isset($_POST['selection'])) {
    $selectedIDs = $_POST['selection'];

    try {
        $pdo->beginTransaction();

        foreach ($selectedIDs as $selected) {
            $registrationQuery = "SELECT * FROM registration WHERE registration_id = :registration_id";
            $registration = $pdo->prepare($registrationQuery);
            $registration->execute([":registration_id" => $selected]);
            $registrationDetails = $registration->fetch();

            $deleteTempQuery = "SELECT * FROM employee_registration WHERE registration_emp_id = :registration_emp_id";
            $deleteTemp = $pdo->prepare($deleteTempQuery);
            $deleteTemp->execute([":registration_emp_id" => $registrationDetails['registration_emp_id']]);
            $deleteTempPath = $deleteTemp->fetch();

            if ($deleteTempPath['picture'] !== "../../uploads/default_profile.jpg") {
                if (file_exists($deleteTempPath['picture'])) {
                    unlink($deleteTempPath['picture']);
                } else {
                    throw new Exception("Failed to delete profile picture.");
                }
            }

            $updateRegQuery = "UPDATE registration SET status = 'Denied' WHERE registration_id = :registration_id";
            $updateReg = $pdo->prepare($updateRegQuery);
            $updateReg->execute([":registration_id" => $selected]);
        }

        $denied = $pdo->commit();
        if ($denied) {
            echo "
                    <script>
                        alert('Selected requests denied successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to deny requests.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to deny requests: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to deny requests.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// approve individual profile edit request
if (isset($_POST['approve-update'])) {
    $selected = $_POST['approve-update'];

    try {
        $pdo->beginTransaction();

        $updateQuery = "SELECT * FROM employee_update WHERE update_id = :update_id";
        $update = $pdo->prepare($updateQuery);
        $update->execute([":update_id" => $selected]);
        $updateDetails = $update->fetch();

        $approveUpdateQuery = "
                UPDATE employee_details SET
                    first_name = :fname, 
                    middle_name = :mname, 
                    last_name = :lname, 
                    date_of_birth = :dob,
                    sex = :sex,
                    address = :address,
                    religion = :religion,
                    civil_status = :civilstatus,
                    legislature = :legislature,
                    access_level = :accesslvl
                WHERE emp_id = :emp_id
            ";
        $approveUpdate = $pdo->prepare($approveUpdateQuery);
        $approveUpdate->execute([
            ":fname" => $updateDetails['update_first_name'],
            ":mname" => $updateDetails['update_middle_name'],
            ":lname" => $updateDetails['update_last_name'],
            ":dob" => $updateDetails['update_date_of_birth'],
            ":sex" => $updateDetails['update_sex'],
            ":address" => $updateDetails['update_address'],
            ":religion" => $updateDetails['update_religion'],
            ":civilstatus" => $updateDetails['update_civil_status'],
            ":legislature" => $updateDetails['update_legislature'],
            ":accesslvl" => $updateDetails['update_access_level'],
            ":emp_id" => $updateDetails['emp_id']
        ]);

        $updateStatusQuery = "UPDATE employee_update SET update_status = 'Approved' WHERE update_id = :update_id";
        $updateStatus = $pdo->prepare($updateStatusQuery);
        $updateStatus->execute([":update_id" => $selected]);

        $approved = $pdo->commit();
        if ($approved) {
            echo "
                    <script>
                        alert('Update request approved successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to approve update request.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to approve update request: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to approve update request:');
                </script>
            ";
    }
}

// deny individual profile edit request
if (isset($_POST['deny-update'])) {
    $selected = $_POST['deny-update'];

    try {
        $pdo->beginTransaction();

        $updateQuery = "UPDATE employee_update SET update_status = 'Denied' WHERE update_id = :update_id";
        $update = $pdo->prepare($updateQuery);
        $update->execute([":update_id" => $selected]);

        $denied = $pdo->commit();
        if ($denied) {
            echo "
                    <script>
                        alert('Update request denied successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to deny update request.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to deny update request: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to deny update request.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// approve multiple profile edit requests
if (isset($_POST['approve-update-selected']) && isset($_POST['updates'])) {
    $selectedIDs = $_POST['updates'];

    try {
        $pdo->beginTransaction();

        foreach ($selectedIDs as $selected) {
            $updateQuery = "SELECT * FROM employee_update WHERE update_id = :update_id";
            $update = $pdo->prepare($updateQuery);
            $update->execute([":update_id" => $selected]);
            $updateDetails = $update->fetch();

            $approveUpdateQuery = "
                    UPDATE employee_details SET
                        first_name = :fname, 
                        middle_name = :mname, 
                        last_name = :lname, 
                        date_of_birth = :dob,
                        sex = :sex,
                        address = :address,
                        religion = :religion,
                        civil_status = :civilstatus,
                        legislature = :legislature,
                        access_level = :accesslvl
                    WHERE emp_id = :emp_id
                ";
            $approveUpdate = $pdo->prepare($approveUpdateQuery);
            $approveUpdate->execute([
                ":fname" => $updateDetails['update_first_name'],
                ":mname" => $updateDetails['update_middle_name'],
                ":lname" => $updateDetails['update_last_name'],
                ":dob" => $updateDetails['update_date_of_birth'],
                ":sex" => $updateDetails['update_sex'],
                ":address" => $updateDetails['update_address'],
                ":religion" => $updateDetails['update_religion'],
                ":civilstatus" => $updateDetails['update_civil_status'],
                ":legislature" => $updateDetails['update_legislature'],
                ":accesslvl" => $updateDetails['update_access_level'],
                ":emp_id" => $updateDetails['emp_id']
            ]);

            $updateStatusQuery = "UPDATE employee_update SET update_status = 'Approved' WHERE update_id = :update_id";
            $updateStatus = $pdo->prepare($updateStatusQuery);
            $updateStatus->execute([":update_id" => $selected]);

            $approved = $pdo->commit();
            if ($approved) {
                echo "
                        <script>
                            alert('Selected update requests approved successfully.');
                            window.location.href='../main/account_requests.php';
                        </script>
                    ";
            } else {
                throw new Exception("Failed to approve update request.");
            }
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to approve update requests: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to approve update requests.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}

// deny multiple profile edit requests
if (isset($_POST['deny-update-selected']) && isset($_POST['updates'])) {
    $selectedIDs = $_POST['updates'];

    try {
        $pdo->beginTransaction();

        foreach ($selectedIDs as $selected) {
            $updateQuery = "UPDATE employee_update SET update_status = 'Denied' WHERE update_id = :update_id";
            $update = $pdo->prepare($updateQuery);
            $update->execute([":update_id" => $selected]);
        }

        $denied = $pdo->commit();
        if ($denied) {
            echo "
                    <script>
                        alert('Selected update requests denied successfully.');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
        } else {
            throw new Exception("Failed to deny update requests.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Failed to deny update requests: " . $e->getMessage());
        echo "
                <script>
                    alert('Failed to deny update requests.');
                    window.location.href='../main/account_requests.php';
                </script>
            ";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Account Requests</title>
    <style>
        .update-reason {
            max-width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-edit-requests-view p#update-reason {
            max-width: 1000px;
            word-wrap: break-word;
            overflow-wrap: break-word;
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
                    <li><a href="../main/account.php">Account</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li class="active"><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="../main/incidents.php">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <form method="POST">
                    <h1>
                        <center>Registration Requests</center>
                    </h1>
                    <div class="registration-main">
                        <?php
                        if ($registration->rowCount() < 1) {
                            echo "<p>No registration requests at the moment.</p>";
                        } else {
                            ?>
                            <!-- Multiple selection actions -->
                            <div class="registration-actions-multiple">
                                <button type="button" id="viewAllBtn" onclick="toggleRegistrationViewAll(this)">View
                                    All</button>
                                <button type="submit" id="approveSelectedBtn" name="approve-selected"
                                    class="approve-selected-btn" disabled>Approve Selected</button>
                                <button type="submit" id="denySelectedBtn" name="deny-selected" class="deny-selected-btn"
                                    disabled>Deny Selected</button>
                            </div>
                            <table id="registration-requests">
                                <tr>
                                    <th>Selection</th>
                                    <th>Profile</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th colspan="3">Actions</th>
                                </tr>
                                <?php foreach ($registration as $reg) { ?>
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" class="selection-checkbox" name="selection[]"
                                                    value="<?php echo $reg['registration_id']; ?>" style="cursor: pointer;">
                                            </center>
                                        </td>
                                        <td>
                                            <img src="../../uploads/temp/<?php echo $reg['picture']; ?>"
                                                alt="<?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?>"
                                                title="<?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?>"
                                                class="profile-picture"
                                                style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover;">
                                        </td>
                                        <td><?php echo $reg['first_name']; ?></td>
                                        <td><?php echo $reg['middle_name']; ?></td>
                                        <td><?php echo $reg['last_name']; ?></td>
                                        <td><?php echo $reg['username']; ?></td>
                                        <td><?php echo $reg['status']; ?></td>
                                        <td class="action-btns">
                                            <button class="view-btn" type="button"
                                                onclick="toggleRegistrationView('registrationView_<?php echo $reg['registration_id']; ?>', this)">
                                                View More
                                            </button>
                                            <button name="approve"
                                                value="<?php echo $reg['registration_id']; ?>">Approve</button>
                                            <button name="deny" value="<?php echo $reg['registration_id']; ?>">Deny</button>
                                        </td>
                                    </tr>
                                    <tr colspan="8">
                                        <td colspan="8">
                                            <div class="registration-view"
                                                id="registrationView_<?php echo $reg['registration_id']; ?>">
                                                <h3>Employee Details</h3>
                                                <p><strong>Name:</strong>
                                                    <?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?>
                                                </p>
                                                <p><strong>Date of Birth:</strong>
                                                    <?php echo date('F j, Y', strtotime($reg['date_of_birth'])); ?></p>
                                                <p><strong>Sex:</strong>
                                                    <?php
                                                    if ($reg['sex'] === 'M')
                                                        echo "Male";
                                                    elseif ($reg['sex'] === 'F')
                                                        echo "Female";
                                                    elseif ($reg['sex'] === 'I')
                                                        echo "Intersex";
                                                    else
                                                        echo "Not Specified";
                                                    ?>
                                                </p>
                                                <p><strong>Address:</strong> <?php echo $reg['address']; ?></p>
                                                <p><strong>Religion:</strong> <?php echo $reg['religion']; ?></p>
                                                <p><strong>Civil Status:</strong> <?php echo $reg['civil_status']; ?></p>
                                                <p><strong>Legislature:</strong> <?php echo $reg['legislature']; ?></p>
                                                <p><strong>Access Level:</strong>
                                                    <?php
                                                    $accessLevel = array('Limited Access', 'Standard Access', 'Full Access', 'Administrator');
                                                    echo $accessLevel[$reg['access_level'] - 1];
                                                    ?>
                                                </p>
                                                <p><strong>Phone Number:</strong> <?php echo $reg['phone_no']; ?></p>

                                                <br>
                                                <h3>Log In Details</h3>
                                                <p><strong>Username:</strong> <?php echo $reg['username']; ?></p>
                                                <p><strong>Email:</strong> <?php echo $reg['email']; ?></p>

                                                <br>
                                                <h3>Request Details</h3>
                                                <p><strong>Registration ID:</strong> <?php echo $reg['registration_id']; ?></p>
                                                <p><strong>Registration Employee ID:</strong>
                                                    <?php echo $reg['registration_emp_id']; ?></p>
                                                <p><strong>Registration Login ID:</strong>
                                                    <?php echo $reg['registration_login_id']; ?></p>
                                                <p><strong>Registration Date:</strong> <?php echo date("M j, Y h:i:s A", strtotime($reg['request_date'])); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                    <script src="../../assets/js/toggleRegistrationViews.js"></script>
                    <br>

                    <h1>
                        <center>Profile Update Requests</center>
                    </h1>
                    <div class="profile-edit-main">
                        <?php
                        if ($empUpdate->rowCount() < 1) {
                            echo "<p>No profile edit requests at the moment.</p>";
                        } else { 
                        ?>
                            <!-- Multiple selection actions -->
                            <div class="profile-edit-actions-multiple">
                                <button type="button" id="viewAllUpdatesBtn" onclick="toggleProfileEditsViewAll(this)">View
                                    All</button>
                                <button type="submit" id="approveUpdateSelectedBtn" name="approve-update-selected"
                                    class="approve-update-selected-btn" disabled>Approve Selected</button>
                                <button type="submit" id="denyUpdateSelectedBtn" name="deny-update-selected"
                                    class="deny-update-selected-btn" disabled>Deny Selected</button>
                            </div>
                            <table id="profile-edit-requests">
                                <tr>
                                    <th>Selection</th>
                                    <th>Profile</th>
                                    <th>Full Name</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th colspan="3">Actions</th>
                                </tr>
                                <?php foreach ($empUpdate as $upd) { ?>
                                    <tr>
                                        <td>
                                            <center>
                                                <input type="checkbox" class="updates-checkbox" name="updates[]"
                                                    value="<?php echo $upd['update_id']; ?>" style="cursor: pointer;">
                                            </center>
                                        </td>
                                        <td>
                                            <img src="<?php echo $upd['picture']; ?>"
                                                alt="<?php echo $upd['first_name'] . " " . $upd['middle_name'] . " " . $upd['last_name']; ?>"
                                                title="<?php echo $upd['first_name'] . " " . $upd['middle_name'] . " " . $upd['last_name']; ?>"
                                                class="profile-picture"
                                                style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover;">
                                        </td>
                                        <td><?php echo $upd['first_name'] . " " . $upd['middle_name'] . " " . $upd['last_name']; ?></td>
                                        <td class="update-reason"><?php echo $upd['update_reason']; ?></td>
                                        <td><?php echo $upd['update_status']; ?></td>
                                        <td class="action-btns">
                                            <button class="view-update-btn" type="button"
                                                onclick="toggleProfileEditsView('updatesView_<?php echo $upd['update_id']; ?>', this)">
                                                View More
                                            </button>
                                            <button name="approve-update"
                                                value="<?php echo $upd['update_id']; ?>">Approve</button>
                                            <button name="deny-update" value="<?php echo $upd['update_id']; ?>">Deny</button>
                                        </td>
                                    </tr>
                                    <tr colspan="8">
                                        <td colspan="8">
                                            <div class="profile-edit-requests-view"
                                                id="updatesView_<?php echo $upd['update_id']; ?>">
                                                <h3>Reason</h3>
                                                <p id="update-reason"><?php echo $upd['update_reason']; ?></p>

                                                <br>
                                                <h3>Updates</h3>
                                                <?php
                                                $currentValQuery = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
                                                $currentVal = $pdo->prepare($currentValQuery);
                                                $currentVal->execute([":emp_id" => $upd['emp_id']]);
                                                $currentValRow = $currentVal->fetch();

                                                if ($upd['update_first_name'] !== $currentValRow['first_name']) {
                                                    echo '<p><strong>First Name:</strong> ' . $currentValRow['first_name'] . ' → ' . $upd['update_first_name'] . '</p>';
                                                }
                                                if ($upd['update_middle_name'] !== $currentValRow['middle_name']) {
                                                    echo '<p><strong>Middle Name:</strong> ' . $currentValRow['middle_name'] . ' → ' . $upd['update_middle_name'] . '</p>';
                                                }
                                                if ($upd['update_last_name'] !== $currentValRow['last_name']) {
                                                    echo '<p><strong>Last Name:</strong> ' . $currentValRow['last_name'] . ' → ' . $upd['update_last_name'] . '</p>';
                                                }
                                                if ($upd['update_date_of_birth'] !== $currentValRow['date_of_birth']) {
                                                    echo '<p><strong>Date of Birth:</strong> ' .
                                                        date('F j, Y', strtotime($currentValRow['date_of_birth'])) .
                                                        ' → ' .
                                                        date('F j, Y', strtotime($upd['update_date_of_birth'])) . '</p>';
                                                }
                                                if ($upd['update_sex'] !== $currentValRow['sex']) {
                                                    function formatSex($sex)
                                                    {
                                                        if ($sex == 'M')
                                                            return "Male";
                                                        elseif ($sex == 'F')
                                                            return "Female";
                                                        elseif ($sex == 'I')
                                                            return "Intersex";
                                                        else
                                                            return "Not Specified";
                                                    }
                                                    echo '<p><strong>Sex:</strong> ' . formatSex($currentValRow['sex']) . ' → ' . formatSex($upd['update_sex']) . '</p>';
                                                }
                                                if ($upd['update_address'] !== $currentValRow['address']) {
                                                    echo '<p><strong>Address:</strong> ' . $currentValRow['address'] . ' → ' . $upd['update_address'] . '</p>';
                                                }
                                                if ($upd['update_religion'] !== $currentValRow['religion']) {
                                                    echo '<p><strong>Religion:</strong> ' . $currentValRow['religion'] . ' → ' . $upd['update_religion'] . '</p>';
                                                }
                                                if ($upd['update_civil_status'] !== $currentValRow['civil_status']) {
                                                    echo '<p><strong>Civil Status:</strong> ' . $currentValRow['civil_status'] . ' → ' . $upd['update_civil_status'] . '</p>';
                                                }
                                                if ($upd['update_legislature'] !== $currentValRow['legislature']) {
                                                    echo '<p><strong>Legislature:</strong> ' . $currentValRow['legislature'] . ' → ' . $upd['update_legislature'] . '</p>';
                                                }
                                                if ($upd['update_access_level'] !== $currentValRow['access_level']) {
                                                    $accessLevel = array('Limited Access', 'Standard Access', 'Full Access', 'Administrator');
                                                    echo '<p><strong>Access Level:</strong> ' .
                                                        $accessLevel[$currentValRow['access_level'] - 1] .
                                                        ' → ' .
                                                        $accessLevel[$upd['update_access_level'] - 1] . '</p>';
                                                }
                                                ?>

                                                <br>
                                                <h3>Request Details</h3>
                                                <p><strong>Request Date:</strong> <?php echo date("M j, Y h:i:s A", strtotime($upd['update_request_date'])); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                    <script src="../../assets/js/toggleProfileEditsView.js"></script>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
    <script src="../../assets/js/checkboxes.js"></script>
</body>
</html>