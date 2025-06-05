<?php
include '../../config/dbfetch.php';

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
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Update request approved.',
                            text: 'Update request has been approved successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/account_requests.php';
                        });
                    });
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
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Update request failed.',
                        text: 'Failed to approve update request.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/account_requests.php';
                    });
                });
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
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Update request denied.',
                            text: 'Update request has been denied successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/account_requests.php';
                        });
                    });
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
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Update request failed.',
                        text: 'Failed to deny update request.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/account_requests.php';
                    });
                });
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
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Selected update requests approved.',
                                text: 'Selected update requests have been approved successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href='../main/account_requests.php';
                            });
                        });
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
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Selected update requests failed.',
                        text: 'Failed to approve selected update requests.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/account_requests.php';
                    });
                });
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
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Selected update requests denied.',
                            text: 'Selected update requests have been denied successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../main/account_requests.php';
                        });
                    });
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
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Selected update requests failed.',
                        text: 'Failed to deny selected update requests.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href='../main/account_requests.php';
                    });
                });
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
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="css/account_requests.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Greenwater Village Dashboard | Account Requests</title>
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
                    <form method="POST">
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
                    </form>
                </div>
        </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        <!-- ending for the main content -->
         </div>
    <!-- ending for the class wrapper -->
    </div>
    <script src="../../assets/js/checkboxes.js"></script>
    <script src="../../assets/js/toggleProfileEditsView.js"></script>
    <script>
        function toggleProfileEditsView(id, btn) {
            const section = document.getElementById(id);
            if (section.style.display === 'block') {
                section.style.display = 'none';
                btn.textContent = 'View More';
            } else {
                section.style.display = 'block';
                btn.textContent = 'View Less';
            }
        }
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>