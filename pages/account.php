<?php
    include '../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBISH Dashboard | Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        img#employee-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        table td {
            padding: 10px;
        }
        .bg-green {
            background-color: #28a745 !important;
        }
        .btn-green {
            background-color: #28a745 !important;
            color: white;
        }
        .nav-link-active {
            color: #28a745 !important;
        }
        .text-green {
            color: #28a745 !important;
        }
        .list-group-item-action:hover {
            background-color: #218838;
        }
    </style>
</head>
<body class="bg-light">

    <header class="bg-white shadow-sm mb-4">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo" height="60">
                <h1 class="h4 ms-3 mb-0 text-green">UBISH</h1>
            </div>
            <form method="POST">
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <button class="btn btn-green logout" style="cursor: pointer;" name="logout">Log Out</button>
                        </li>
                    </ul>
                </nav>
            </form>
        </div>
        <hr class="my-0">
    </header>

    <main class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="../pages/dashboard.php" class="list-group-item list-group-item-action">Home</a>
                    <a href="../pages/account.php" class="list-group-item list-group-item-action active text-green">Account</a>
                    <?php
                        if ($accessLevel >= 1) {
                            echo '<a href="#" class="list-group-item list-group-item-action">Documents</a>';
                            echo '<a href="../pages/announcements.php" class="list-group-item list-group-item-action">Post Announcement</a>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<a href="../pages/employee_table.php" class="list-group-item list-group-item-action">Employee Table</a>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<a href="#" class="list-group-item list-group-item-action">Profile Change Request</a>';
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-9">
                <h1 class="text-center mb-4 text-green">Account Page</h1>
                <?php 
                    foreach ($empDetails as $row) {
                ?>
                    <div class="text-center">
                        <img id="employee-picture" src="<?php echo $row['picture']; ?>" alt="Employee Picture">
                    </div>
                    <table class="table table-bordered table-striped mt-4">
                        <tr>
                            <td><strong>UBISH Employee ID:</strong></td>
                            <td><?php echo $row['emp_id']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Full Name:</strong></td>
                            <td><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Date of Birth:</strong></td>
                            <td><?php echo date('F j, Y', strtotime($row['date_of_birth'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Age:</strong></td>
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
                            <td><strong>Address:</strong></td>
                            <td><?php echo $row['address'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Religion:</strong></td>
                            <td><?php echo $row['religion'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Civil Status:</strong></td>
                            <td><?php echo $row['civil_status'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Phone Number:</strong></td>
                            <td><?php echo $row['phone_no'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Legislature:</strong></td>
                            <td><?php echo $row['legislature'] ?></td>
                        </tr>
                    </table>
                <?php
                    }
                ?>
            </div>
        </div>
    </main>

    <footer class="bg-white py-4 text-center mt-5">
        <hr>
        <p class="mb-0">&copy; <?php echo date('Y'); ?> | Unified Barangay Information Service Hub</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>