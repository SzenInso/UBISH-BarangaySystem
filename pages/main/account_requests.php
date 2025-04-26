<?php 
    include '../../config/dbfetch.php';
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
                                                    <button name="approve">Approve</button>
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
