<?php
    include '../../../config/dbfetch.php';
    
    // access level verification
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../dashboard.php");
        exit;
    }

    if (isset($_POST['go-back'])) {
        header("Location: ../../main/residency_management.php");
        exit;
    }

    $householdId = $_GET['household_id'] ?? ($_POST['household_id'] ?? null);
    if (!$householdId) {
        error_log("Household ID not provided.");
        header("Location: ../residency_management.php");
    }

    $stmt = $pdo->prepare("SELECT household_address_id, household_respondent_id FROM households WHERE household_id = :household_id");
    $stmt->execute(['household_id' => $householdId]);
    $ids = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ids) {
        error_log("Household with ID $householdId not found.");
        header("Location: ../residency_management.php");
    }
    $addressId = $ids['household_address_id'];
    $respondentId = $ids['household_respondent_id'];

    // Always fetch address and respondent data for the form
    $addressQuery = "SELECT * FROM household_addresses WHERE household_address_id = :address_id";
    $addressStmt = $pdo->prepare($addressQuery);
    $addressStmt->execute(['address_id' => $addressId]);
    $addressData = $addressStmt->fetch(PDO::FETCH_ASSOC);

    $respondentQuery = "SELECT * FROM household_respondents WHERE household_respondent_id = :respondent_id";
    $respondentStmt = $pdo->prepare($respondentQuery);
    $respondentStmt->execute(['respondent_id' => $respondentId]);
    $respondentData = $respondentStmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['update-household'])) {
        $addressId = $_POST['address_id'] ?? $addressId;
        $houseNumber = $_POST['house_number'] ?? '';
        $purok = $_POST['purok'] ?? '';
        $street = $_POST['street'] ?? '';
        $district = $_POST['district'] ?? '';
        
        $respondentId = $_POST['respondent_id'] ?? $respondentId;
        $firstName = $_POST['first_name'] ?? '';
        $middleInitial = $_POST['middle_initial'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $suffix = $_POST['suffix'] ?? '';

        try {
            $pdo->beginTransaction();

            // update address
            $updateAddressQuery = "
                UPDATE household_addresses 
                SET house_number = :house_number, purok = :purok, street = :street, district = :district 
                WHERE household_address_id = :address_id
            ";
            $updateAddressStmt = $pdo->prepare($updateAddressQuery);
            $updateAddressStmt->execute([
                'house_number' => $houseNumber,
                'purok' => $purok,
                'street' => $street,
                'district' => $district,
                'address_id' => $addressId
            ]);

            // update respondent
            $updateRespondentQuery = "
                UPDATE household_respondents 
                SET first_name = :first_name, middle_initial = :middle_initial, last_name = :last_name, suffix = :suffix 
                WHERE household_respondent_id = :respondent_id
            ";
            $updateRespondentStmt = $pdo->prepare($updateRespondentQuery);
            $updateRespondentStmt->execute([
                'first_name' => $firstName,
                'middle_initial' => $middleInitial,
                'last_name' => $lastName,
                'suffix' => $suffix,
                'respondent_id' => $respondentId
            ]);

            $committed = $pdo->commit();
            if ($committed) {
                header("Location: edit_household.php?household_id=" . urlencode($householdId) . "&success=1");
                exit;
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error: " . $e->getMessage());
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='../household/js/sweetalert2.jss'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error occurred.',
                            text: 'An error occurred while updating the household details. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='../residency_management.php';
                        });
                    });
                </script>
            ";
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src='../household/js/sweetalert2.js'></script>
    <title>UBISH Dashboard | Update Resident</title>
</head>
<body>
    <style>
        header {
            background-color: #e1f3e2 !important;
            border-bottom: 5px solid #356859 !important;
        }
        .logout {
            background-color: #e1f3e2 !important;
            color: #356859 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }
        footer {
            background-color: #d0e9d2 !important;
            text-align: center !important;
            padding: 20px !important;
            color: #2b3d2f !important;
            border-top: 5px solid #356859 !important;
            margin-top: 60px !important;
        }
        .custom-cancel-button {
            border: 2px solid gray;
            background-color: white;
            color: black;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .custom-cancel-button:hover {
            background-color: lightgray;
        }
        .edit-residency-input {
            width: 100%;
            padding: 8px;
            font-size: medium;
            border: 1px solid gray;
            border-radius: 4px;
        }
        .member-information-actions {
            margin: 16px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .member-information-actions button {
            margin: 0 8px;
            padding: 8px 16px;
        }
    </style>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li><a href="../../main/dashboard.php">Home</a></li>
                    <li><a href="../../main/account.php">Account</a></li>
                    
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../../main/documents.php">Documents</a></li>
                    <li><a href="../../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li class="active"><a href="../../main/residency_management.php">Residency Management</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/permits.php">Permit Requests</a></li>'; } ?>
                    <!-- STANDARD -->
                    
                    <!-- FULL -->
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <!-- FULL -->
                    
                    <h3>Reports</h3>
                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <div class="dashboard-content">
                <center>
                    <h1>Update Resident Information</h1>
                </center>   
                <div class="household-information">
                    <form method="POST">
                        <div class="household-information-actions">
                            <input type="text" name="address_id" value="<?php echo htmlspecialchars($addressData['household_address_id'] ?? ''); ?>">
                            <input type="text" name="respondent_id" value="<?php echo htmlspecialchars($respondentData['household_respondent_id'] ?? ''); ?>">
                            <button type="submit" name="update-household" class="custom-cancel-button">Update Household</button>
                        </div>
                        <div class="household-details" style="border:1px solid #ccc; margin: 24px auto; border-radius:8px; padding:16px;">
                            <h2>Household Address</h2>
                            <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                                <tr>
                                    <th>House Number/Code <span style="color: crimson; cursor: help;" title="Required">*</span></th>
                                    <td>
                                        <input type="text" name="house_number" class="edit-residency-input" value="<?php echo htmlspecialchars($addressData['house_number'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Purok</th>
                                    <td>
                                        <input type="text" name="purok" class="edit-residency-input" value="<?php echo htmlspecialchars($addressData['purok'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Street</th>
                                    <td>
                                        <input type="text" name="street" class="edit-residency-input" value="<?php echo htmlspecialchars($addressData['street'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>District</th>
                                    <td>
                                        <input type="text" name="district" class="edit-residency-input" value="<?php echo htmlspecialchars($addressData['district'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Barangay</th>
                                    <td>
                                        Greenwater Village
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="respondent-details" style="border:1px solid #ccc; margin: 24px auto; border-radius:8px; padding:16px;">
                            <h2>Household Head/Respondent</h2>
                            <table border="1" cellspacing="0" style="width:100%; table-layout:fixed;">
                                <tr>
                                    <th>First Name</th>
                                    <td>
                                        <input type="text" name="first_name" class="edit-residency-input" value="<?php echo htmlspecialchars($respondentData['first_name'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Middle Initial</th>
                                    <td>
                                        <input type="text" name="middle_initial" class="edit-residency-input" value="<?php echo htmlspecialchars($respondentData['middle_initial'] ?? ''); ?>" maxlength="5">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td>
                                        <input type="text" name="last_name" class="edit-residency-input" value="<?php echo htmlspecialchars($respondentData['last_name'] ?? ''); ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Suffix</th>
                                    <td>
                                        <input type="text" name="suffix" class="edit-residency-input" value="<?php echo htmlspecialchars($respondentData['suffix'] ?? ''); ?>" maxlength="10">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.household-information form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const houseNumber = form.querySelector('input[name="house_number"]').value.trim();
                const firstName = form.querySelector('input[name="first_name"]').value.trim();
                const lastName = form.querySelector('input[name="last_name"]').value.trim();

                let errorMsg = '';
                if (!houseNumber) errorMsg += 'House Number/Code is required.<br>';
                if (!firstName) errorMsg += 'First Name is required.<br>';
                if (!lastName) errorMsg += 'Last Name is required.<br>';

                if (errorMsg) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMsg
                    });
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Household details have been successfully updated.'
                });
                // Optionally, remove the success param from the URL after showing the alert
                window.history.replaceState({}, document.title, window.location.pathname + window.location.search.replace(/([&?])success=1(&|$)/, '$1').replace(/[\?&]$/, ''));
            }
        });
    </script>
</body>
</html>