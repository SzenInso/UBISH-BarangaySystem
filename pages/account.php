<?php
    include '../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>UBISH Dashboard | Account</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li><a href="../pages/dashboard.php">Home</a></li>
                    <li class="active"><a href="../pages/account.php">Account</a></li>
                    <?php
                        // placeholder access control pages
                        if ($accessLevel >= 1) {
                            echo '<li><a href="#">Documents</a></li>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<li><a href="../pages/employee_table.php">Employee Table</a></li>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<li><a href="#">Profile Change Request</a></li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1><center>Account Page</center></h1><br>
            <?php 
                foreach ($empDetails as $row) {
            ?>
                    <img id="employee-picture" src="<?php echo $row['picture']; ?>" alt="Employee Picture">
                    <table>
                        <tr>
                            <td>
                                <strong>UBISH Employee ID: </strong>
                            </td>
                            <td>
                                <?php echo $row['emp_id']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Full Name: </strong>
                            </td>
                            <td>
                                <?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Date of Birth: </strong>
                            </td>
                            <td>
                                <?php echo date('F j, Y', strtotime($row['date_of_birth'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Age: </strong>
                            </td>
                            <td>
                                <?php
                                    $birthDate = new DateTime($row['date_of_birth']);
                                    $today = new DateTime('today');
                                    $age = $birthDate->diff($today)->y;
                                    echo $age; 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Address: </strong>
                            </td>
                            <td>
                                <?php echo $row['address'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Religion: </strong>
                            </td>
                            <td>
                                <?php echo $row['religion'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Civil Status: </strong>
                            </td>
                            <td>
                                <?php echo $row['civil_status'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Phone Number: </strong>
                            </td>
                            <td>
                                <?php echo $row['phone_no'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Legislature: </strong>
                            </td>
                            <td>
                                <?php echo $row['legislature'] ?>
                            </td>
                        </tr>
                    </table>
            <?php
                }
            ?>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>