<?php
include '../../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>UBISH Dashboard | Account</title>
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
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../main/employee_table.php">Employee Table</a></li>'; } ?>
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="#">Permit Requests</a></li>'; } ?>
                    <h3>Reports</h3>
                    <li><a href="#">Incident Reports</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1>
                    <center>Employee Table</center>
                </h1><br>
                <div id="employee-table-container">
                    <form method="POST" action="../main/employee_table.php">
                        <?php if ($accessLevel >= 3) { ?>
                            <button type="submit" id="deleteEmp" name="delete-selected"
                                style="justify-content: flex-start; cursor: pointer;">Delete Selected</button>
                        <?php } ?>
                        <table id="employee-table">
                            <tr>
                                <?php if ($accessLevel >= 3) {
                                    echo "<th>Selection</th>";
                                } ?>
                                <th>Profile Picture</th>
                                <th>Full Name</th>
                                <th>Date of Birth</th>
                                <th>Sex</th>
                                <th>Address</th>
                                <th>Religion</th>
                                <th>Civil Status</th>
                                <th>Legislature</th>
                                <?php if ($accessLevel >= 3) {
                                    echo "<th>Access Level</th>";
                                } ?>
                                <th>Phone Number</th>
                                <?php if ($accessLevel >= 3) {
                                    echo "<th>Action</th>";
                                } ?>
                            </tr>
                            <?php foreach ($empAllDetails as $row) { ?>
                                <tr>
                                    <?php if ($accessLevel >= 3) { ?>
                                        <td>
                                            <center>
                                                <input type="checkbox" name="select_employee[]"
                                                    value="<?php echo $row['emp_id']; ?>" style="cursor: pointer;">
                                            </center>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <img src="<?php echo $row['picture']; ?>"
                                            alt="<?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?>"
                                            title="<?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?>"
                                            style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?>
                                    </td>
                                    <td><?php echo $row['date_of_birth']; ?></td>
                                    <td><?php echo $row['sex']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['religion']; ?></td>
                                    <td><?php echo $row['civil_status']; ?></td>
                                    <td><?php echo $row['legislature']; ?></td>
                                    <?php
                                    if ($accessLevel >= 3) {
                                        $accessLevel = array('Limited Access', 'Standard Access', 'Full Access', 'Administrator');
                                        echo "<td>" . $accessLevel[$row['access_level'] - 1] . "</td>";
                                    }
                                    ?>
                                    <td><?php echo $row['phone_no']; ?></td>
                                    <?php if ($accessLevel >= 3) { ?>
                                        <td>
                                            <form method="POST" action="../main/employee_table.php">
                                                <input type="hidden" name="emp_id" value="<?php echo $row['emp_id']; ?>">
                                                <button type="submit" id="deleteEmp" name="delete-employee"
                                                    style="cursor: pointer;">Delete</button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>

</html>