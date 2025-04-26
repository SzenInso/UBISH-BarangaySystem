<?php 
    include '../../config/dbfetch.php';

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

            $tempPath = $empApprove['picture'];
            $newPath = "../../uploads/profiles/" . basename($tempPath);
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
                        alert('Request approved successfully!');
                        window.location.href='../main/account_requests.php';
                    </script>
                ";
            } else {
                throw new Exception("Failed to approve request.");
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "
                <script>
                    alert('Failed to approve request: " . $e->getMessage() . "');
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
        .registration-view {
            display: none;
        }
        .registration-view.active {
            display: block;
        }
        .registration-main button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px;
            border-radius: 4px;
            cursor: pointer;
        }
        .registration-main button:hover {
            background-color: lightgray;
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
                    <li><a href="../main/dashboard.php">Home</a></li>
                    <li><a href="../main/account.php">Account</a></li>
                    <?php
                        // placeholder access control pages
                        if ($accessLevel >= 1) {
                            echo '<li><a href="#">Documents</a></li>';
                            echo '<li><a href="../main/announcements.php">Post Announcement</a></li>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<li><a href="../main/employee_table.php">Employee Table</a></li>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<li class="active"><a href="../main/account_requests.php">Account Requests</a></li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="dashboard-content">
                <form method="POST">
                    <h1><center>Registration Requests</center></h1>
                    <div class="registration-main">
                            <?php 
                            if ($registration->rowCount() < 1) {
                                echo "<p>No registration requests at the moment.</p>";
                            } else {
                        ?>
                                <!-- Multiple selection actions -->
                                <button type="button" id="viewAllBtn" onclick="toggleRegistrationViewAll(this)">View All</button>
                                <table border="1">
                                    <tr>
                                        <th>Selection</th>
                                        <th>Profile</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th colspan="3">Action</th>
                                    </tr>
                                    <?php 
                                        foreach ($registration as $reg) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <input type="checkbox" name="selection[]" value="<?php echo $reg['registration_id']; ?>">
                                                    </center>
                                                </td>
                                                <td>
                                                    <img 
                                                        src="../../uploads/temp/<?php echo $reg['picture']; ?>" 
                                                        alt="<?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?>"
                                                        title="<?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?>"
                                                        class="profile-picture"
                                                        style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover;"
                                                    >
                                                </td>
                                                <td><?php echo $reg['first_name']; ?></td>
                                                <td><?php echo $reg['middle_name']; ?></td>
                                                <td><?php echo $reg['last_name']; ?></td>
                                                <td><?php echo $reg['username']; ?></td>
                                                <td><?php echo $reg['status']; ?></td>
                                                <td>
                                                    <button 
                                                        class="view-btn" 
                                                        type="button" 
                                                        onclick="toggleRegistrationView('registrationView_<?php echo $reg['registration_id']; ?>', this)"
                                                    >
                                                        View More
                                                    </button>
                                                    <button name="approve" value="<?php echo $reg['registration_id']; ?>">Approve</button>
                                                    <button name="deny">Deny</button>
                                                </td>
                                            </tr>
                                            <tr colspan="8">
                                                <td colspan="8">
                                                    <div class="registration-view" id="registrationView_<?php echo $reg['registration_id']; ?>">
                                                        <h3>Employee Details</h3>
                                                        <p><strong>Name:</strong> <?php echo $reg['first_name'] . " " . $reg['middle_name'] . " " . $reg['last_name']; ?></p>
                                                        <p><strong>Date of Birth:</strong> <?php echo date('F j, Y', strtotime($reg['date_of_birth'])); ?></p>
                                                        <p><strong>Sex:</strong> 
                                                            <?php 
                                                                if ($reg['sex'] == 'M') { echo "Male"; }
                                                                elseif ($reg['sex'] == 'F') { echo "Female"; }
                                                                else { echo "Intersex"; }
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
                                                        
                                                        <br><h3>Log In Details</h3>
                                                        <p><strong>Username:</strong> <?php echo $reg['username']; ?></p>
                                                        <p><strong>Email:</strong> <?php echo $reg['email']; ?></p>
                                                        
                                                        <br><h3>Request Details</h3>
                                                        <p><strong>Registration ID:</strong> <?php echo $reg['registration_id']; ?></p>
                                                        <p><strong>Registration Employee ID:</strong> <?php echo $reg['registration_emp_id']; ?></p>
                                                        <p><strong>Registration Login ID:</strong> <?php echo $reg['registration_login_id']; ?></p>
                                                        <p><strong>Registration Date:</strong> <?php echo $reg['request_date']; ?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php } ?>
                                </table>
                        <?php } ?>
                    </div>
                    <script src="../../assets/js/toggleRegistrationViews.js"></script>

                    <h1><center>Profile Edit Requests</center></h1><br>
                    <div class="profile-edit-main">
                        <p>No account edit requests at the moment.</p>
                    </div>

                    <h1><center>Password Reset Requests</center></h1><br>
                    <div class="password-reset-main">
                        <p>No password reset requests at the moment.</p>
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
