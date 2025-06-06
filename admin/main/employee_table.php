<?php
    include '../../config/dbfetch.php';

    // sort and filter logic
    if (isset($_POST['action']) && $_POST['action'] === 'fetch') {
        $sort = $_POST['sort'];
        $filterSex = $_POST['filterSex'];
        $filterAccessLvl = $_POST['filterAccessLvl'];
    
        $query = "SELECT * FROM employee_details WHERE 1=1";
    
        // add filters
        if (!empty($filterSex)) {
            $query .= " AND sex = :sex";
        }
        if (!empty($filterAccessLvl)) {
            $query .= " AND access_level = :access_level";
        }
    
        // add sorting
        if ($sort === "name_asc") {
            $query .= " ORDER BY last_name ASC, first_name ASC, middle_name ASC";
        } elseif ($sort === "name_desc") {
            $query .= " ORDER BY last_name DESC, first_name DESC, middle_name DESC";
        } elseif ($sort === "access_control_asc") {
            $query .= " ORDER BY access_level ASC";
        } elseif ($sort === "access_control_desc") {
            $query .= " ORDER BY access_level DESC";
        }
    
        $stmt = $pdo->prepare($query);
        // bindParam is needed since some filters can be empty
        if (!empty($filterSex)) { $stmt->bindParam(":sex", $filterSex); }
        if (!empty($filterAccessLvl)) { $stmt->bindParam(":access_level", $filterAccessLvl); }
        $stmt->execute();
        $employees = $stmt->fetchAll();

        // displays filtered table
        echo '
            <table id="employee-table">
            <tr>
        ';
        if ($accessLevel >= 3) { echo "<th>Selection</th>"; }
        echo '
                <th>Profile Picture</th>
                <th>Full Name</th>
                <th>Date of Birth</th>
                <th>Sex</th>
                <th>Address</th>
                <th>Religion</th>
                <th>Civil Status</th>
                <th>Legislature</th>
        ';
        if ($accessLevel >= 3) { echo "<th>Access Level</th>"; }
        echo '<th>Phone Number</th>';
        if ($accessLevel >= 3) { echo "<th>Action</th>"; }
        echo '</tr>';
        
        foreach ($employees as $row) {
            echo "<tr>";
            if ($accessLevel >= 3) {
                echo "<td><center><input type='checkbox' name='select_employee[]' value='{$row['emp_id']}' style='cursor: pointer;'></center></td>";
            }
            echo "
                <td>
                    <img 
                        src='{$row['picture']}' 
                        alt='{$row['first_name']} {$row['middle_name']} {$row['last_name']}' 
                        style='width: 75px; height: 75px; border-radius: 50%; object-fit: cover;' 
                        loading='lazy'
                    >
                </td>
                <td>{$row['first_name']} {$row['middle_name']} {$row['last_name']}</td>
                <td>{$row['date_of_birth']}</td>
                <td>{$row['sex']}</td>
                <td>{$row['address']}</td>
                <td>{$row['religion']}</td>
                <td>{$row['civil_status']}</td>
                <td>{$row['legislature']}</td>
            ";
            if ($accessLevel >= 3) {
                $accessLevels = ['Limited Access', 'Standard Access', 'Full Access', 'Administrator'];
                echo "<td>{$accessLevels[$row['access_level'] - 1]}</td>";
            }
            echo "<td>{$row['phone_no']}</td>";
            if ($accessLevel >= 3) {
                echo "
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                            <button type='submit' id='deleteEmp' name='delete-employee' style='cursor: pointer;'>Delete</button>
                        </form>
                    </td>
                ";
            }
            echo "</tr>";
        }
        exit;
    }

    // delete single employee
    if (isset($_POST['delete-employee'])) {
        $emp_id = $_POST['emp_id'];

        $fetchEmployeeQuery = "SELECT * FROM employee_details WHERE emp_id = :emp_id";
        $fetchEmployee = $pdo->prepare($fetchEmployeeQuery);
        $fetchEmployee->execute([":emp_id" => $emp_id]);
        $employee = $fetchEmployee->fetch();

        if ($employee) {
            $_SESSION['del_id'] = $emp_id;
            $employeeName = $employee['first_name'] . ' ' . $employee['middle_name'] . ' ' . $employee['last_name'];
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Are you sure?',
                            html: 'Are you sure you want to delete this employee:<br><br> $employeeName?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: 'crimson',
                            cancelButtonColor: '#28a745',
                            confirmButtonText: 'Yes, delete.',
                            cancelButtonText: 'No, cancel',
                            customClass: {
                                cancelButton: 'custom-cancel-button'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // redirect to delete employee action
                                window.location.href = '../main/delete_employee.php';
                            } else {
                                window.location.href = '../main/employee_table.php';
                            }
                        });
                    });
                </script>
            ";
        }
    }

    // delete multiple selected employees
    if (isset($_POST['delete-selected'])) {
        $selectedDelIDs = $_POST['select_employee'];
        if (!empty($selectedDelIDs)) {
            $placeholders = implode(',', array_fill(0, count($selectedDelIDs), '?'));
            $fetchNamesQuery = "SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name FROM employee_details WHERE emp_id IN ($placeholders)";
            $fetchNames = $pdo->prepare($fetchNamesQuery);
            $fetchNames->execute($selectedDelIDs);
            $employees = $fetchNames->fetchAll();

            if ($employees) {
                $_SESSION['del_ids'] = $selectedDelIDs;
                $employeeNames = array_column($employees, 'full_name');
                $employeeList = implode('<br>', $employeeNames);
                echo "
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Are you sure?',
                                html: 'Are you sure you want to delete these employees:<br><br>$employeeList',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: 'crimson',
                                cancelButtonColor: '#28a745',
                                confirmButtonText: 'Yes, delete.',
                                cancelButtonText: 'No, cancel',
                                customClass: {
                                    cancelButton: 'custom-cancel-button'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Rrdirect to delete selected employees action
                                    window.location.href = '../main/delete_employee.php';
                                } else {
                                    window.location.href = '../main/employee_table.php';
                                }
                            });
                        });
                    </script>
                ";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../../assets/css/style.css"> -->
    <link rel="stylesheet" href="css/dash.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="css/employee_table.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <script src="../../assets/js/checkboxes.js"></script>
    <title>Greenwater Village Dashboard | Account</title>
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
                    <h1>
                        <center>Employee Table</center>
                    </h1><br>
                    <div id="employee-table-container">
                        <form method="POST" action="../main/employee_table.php">
                            <!--
                            <?php if ($accessLevel >= 3) { ?>
                                <button type="submit" id="deleteSelectedEmp" name="delete-selected" style="justify-content: flex-start; cursor: pointer;">Delete Selected</button>
                            <?php } ?>
                            -->
                            <br>
                            <div class="employee-filters">
                                <div class="employee-filters-container">                                
                                    <label for="sort">Sort By: </label>
                                    <select name="sort" id="sort">
                                        <option value="" selected>None</option>
                                        <option value="name_asc">Name (Ascending)</option>
                                        <option value="name_desc">Name (Descending)</option>
                                        <option value="access_control_asc">Access Control (Ascending)</option>
                                        <option value="access_control_desc">Access Control (Descending)</option>
                                    </select>
                                </div>
                                <div class="employee-filters-container">                                
                                    <label for="filter-sex">Filter by Biological Sex: </label>
                                    <select name="filter-sex" id="filter-sex">
                                        <option value="" selected>All</option>
                                        <option value="M">Male</option>
                                        <option value="F">Female</option>
                                        <option value="I">Intersex</option>
                                    </select>
                                </div>
                                <?php if ($accessLevel >= 3) { ?>
                                    <div class="employee-filters-container">
                                        <label for="filter-access-level">Filter By Access Level: </label>
                                        <select name="filter-access-level" id="filter-access-level">
                                            <option value="" selected>All</option>
                                            <option value="1">Limited Access</option>
                                            <option value="2">Standard Access</option>
                                            <option value="3">Full Access</option>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="employee-filters-container">
                                    <button name="reset-sort-filter" id="reset-sort-filter">Reset Sort and Filters</button>
                                </div>
                            </div>
                            <script src="../../assets/js/sortAndFilter.js"></script>
                            <table id="employee-table">
                                <tr>
                                    <?php /* if ($accessLevel >= 3) { echo "<th>Selection</th>"; } */ ?>
                                    <th>Employee ID</th>
                                    <th>Profile Picture</th>
                                    <th>Full Name</th>
                                    <th>Date of Birth</th>
                                    <th>Sex</th>
                                    <th>Address</th>
                                    <th>Religion</th>
                                    <th>Civil Status</th>
                                    <th>Legislature</th>
                                    <?php if ($accessLevel >= 3) { echo "<th>Access Level</th>"; } ?>
                                    <th>Phone Number</th>
                                    <?php if ($accessLevel >= 3) { echo "<th>Action</th>"; } ?>
                                </tr>
                                <?php foreach ($empAllDetails as $row) { ?>
                                    <tr>
                                        <!--
                                        <?php if ($accessLevel >= 3) { ?>
                                            <td>
                                                <center>
                                                    <input 
                                                        type="checkbox"
                                                        class="deletion-checkbox" 
                                                        name="select_employee[]" 
                                                        value="<?php echo $row['emp_id']; ?>" 
                                                        style="cursor: pointer;"
                                                    >
                                                </center>
                                            </td>
                                        <?php } ?>
                                        -->
                                        <td>
                                            <?php echo $row['emp_id']; ?>
                                        </td>
                                        <td>
                                            <img 
                                                src="<?php echo $row['picture']; ?>"
                                                alt="<?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?>"
                                                title="<?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?>"
                                                style="width: 75px; height: 75px; border-radius: 50%; object-fit: cover;"
                                                loading="lazy"
                                            >
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
                                                <form method="POST">
                                                    <input type="hidden" name="emp_id" value="<?php echo $row['emp_id']; ?>">
                                                    <button type="submit" id="deleteEmp" name="delete-employee" style="cursor: pointer;">Delete</button>
                                                </form>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </form>
                    </div>
                </div>
        </main>
            <footer class="main-footer">
                <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
            </footer>
        <!-- ending for main content -->
         </div>
    <!-- ending for class wrapper -->
    </div>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>