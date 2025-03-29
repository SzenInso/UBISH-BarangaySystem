<?php
    include '../config/dbconfig.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('location:../index.php');
        exit;
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:../index.php');   
        exit;
    }
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
                <img src="https://placehold.co/100" alt="UBISH Logo">
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
                </ul>
            </div>
            <div class="dashboard-content">
                <h1><center>Account Page</center></h1><br>
            <?php 
                $query = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
                $empDetails = $pdo->prepare($query);
                $empDetails->execute([":emp_id" => $_SESSION['emp_id']]);

                foreach ($empDetails as $row) {
            ?>
                    <img 
                        src="<?php echo "../" . $row['picture']; ?>"
                        alt="Employee Picture"
                        style="
                            width: 200px;
                            border-radius: 50%; 
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto;
                        "
                    >
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
                        <tr>
                            <td>
                                <strong>Access Level: </strong>
                            </td>
                            <td>
                                <?php
                                    $limitedAccess = array("Sangguniang Kabataan Member", "Other Barangay Personnel");
                                    $standardAccess = array("Sangguniang Barangay Member", "Sangguniang Kabataan Chairperson", "Barangay Secretary", "Barangay Treasurer");
                                    $fullAccess = array("Punong Barangay");

                                    if (in_array($row['legislature'], $limitedAccess)) {
                                        echo "Limited Access";
                                    } elseif (in_array($row['legislature'], $standardAccess)) {
                                        echo "Standard Access";
                                    } elseif (in_array($row['legislature'], $fullAccess)) {
                                        echo "Full Access";
                                    } else {
                                        echo "No/Unknown Access";
                                    }
                                ?>
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