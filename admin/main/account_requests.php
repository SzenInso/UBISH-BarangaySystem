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
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
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
                    <li><a href="../main/account_creation.php">Account Creation</a></li>
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>
                    <li class="active"><a href="../main/account_requests.php">Account Requests</a></li>
                    <h3>Reports</h3>
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
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