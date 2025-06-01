<?php 
    include '../../../config/dbfetch.php';

    function generateCustomID($pdo, $table, $prefix, $id_column, $pad = 5) {
        $stmt = $pdo->query("SELECT $id_column FROM $table ORDER BY $id_column DESC LIMIT 1");
        $lastId = $stmt->fetchColumn();
        if ($lastId) {
            $num = intval(substr($lastId, strlen($prefix))) + 1;
        } else {
            $num = 1;
        }
        return $prefix . str_pad($num, $pad, '0', STR_PAD_LEFT);
    }

    if (isset($_POST['confirm-household'])) {        
        try {
            $pdo->beginTransaction();

            // household address
            $ha_id = generateCustomID($pdo, 'household_addresses', 'HA', 'household_address_id');
            $ha_query = "INSERT INTO household_addresses (household_address_id, house_number, purok, street, district, barangay) VALUES (?, ?, ?, ?, ?, ?)";
            $ha_stmt = $pdo->prepare($ha_query);
            $ha_stmt->execute([
                $ha_id,
                $_POST['household_number'] ?? '',
                $_POST['household_purok'] ?? '',
                $_POST['household_street'] ?? '',
                $_POST['household_district'] ?? '',
                $_POST['household_barangay'] ?? ''
            ]);

            // household respondent
            $hr_id = generateCustomID($pdo, 'household_respondents', 'HR', 'household_respondent_id');
            $hr_query = "INSERT INTO household_respondents (household_respondent_id, first_name, middle_initial, last_name, suffix) VALUES (?, ?, ?, ?, ?)";
            $hr_stmt = $pdo->prepare($hr_query);
            $hr_stmt->execute([
                $hr_id,
                $_POST['household_first_name'] ?? '',
                strtoupper($_POST['household_middle_initial'] ?? ''),
                $_POST['household_last_name'] ?? '',
                $_POST['household_suffix'] ?? ''
            ]);

            // household
            $hh_id = generateCustomID($pdo, 'households', 'HH', 'household_id');
            $hh_query = "INSERT INTO households (household_id, household_address_id, household_respondent_id, created_at) VALUES (?, ?, ?, NOW())";
            $hh_stmt = $pdo->prepare($hh_query);
            $hh_stmt->execute([
                $hh_id,
                $ha_id,
                $hr_id
            ]);

            // family
            $fa_id = generateCustomID($pdo, 'families', 'FA', 'family_id');
            $fa_query = "INSERT INTO families (family_id, household_id, created_at) VALUES (?, ?, NOW())";
            $fa_stmt = $pdo->prepare($fa_query);
            $fa_stmt->execute([
                $fa_id,
                $hh_id
            ]);

            // individual family members
            if (isset($_POST['member_index']) && is_array($_POST['member_index'])) {
                foreach ($_POST['member_index'] as $index) {
                    $i = (int)$index;
                    $fm_id = generateCustomID($pdo, 'family_members', 'FM', 'member_id');
                    $fm_query = "INSERT INTO family_members (
                        member_id, family_id, first_name, middle_initial, last_name, suffix, relation, sex, birthdate, civil_status, religion, schooling, attainment, occupation, emp_status, emp_category, income_cash, income_kind, livelihood_training, is_senior_citizen, is_PWD, is_OFW, is_solo_parent, is_indigenous, remarks
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    )";

                    // check if member is a senior citizen
                    $is_senior_citizen = 0;
                    if (!empty($_POST['birthdate'][$i])) {
                        $birthdate = new DateTime($_POST['birthdate'][$i]);
                        $today = new DateTime();
                        $age = $today->diff($birthdate)->y;
                        if ($age >= 60) { $is_senior_citizen = 1; }
                    }

                    $fm_stmt = $pdo->prepare($fm_query);
                    $fm_stmt->execute([
                        $fm_id,
                        $fa_id,
                        $_POST['fname'][$i] ?? '',
                        strtoupper($_POST['mname'][$i] ?? ''),
                        $_POST['lname'][$i] ?? '',
                        $_POST['suffix'][$i] ?? '',
                        $_POST['relation'][$i] ?? '',
                        $_POST['sex'][$i] ?? '',
                        $_POST['birthdate'][$i] ?? '',
                        $_POST['civilstatus'][$i] ?? '',
                        $_POST['religion'][$i] ?? '',
                        $_POST['schooling'][$i] ?? '',
                        $_POST['attainment'][$i] ?? '',
                        $_POST['occupation'][$i] ?? '',
                        $_POST['emp_status'][$i] ?? '',
                        $_POST['emp_category'][$i] ?? '',
                        $_POST['income_cash'][$i] ?? 0,
                        $_POST['income_type'][$i] ?? '',
                        $_POST['livelihood_training'][$i] ?? '',
                        $is_senior_citizen,
                        $_POST['is_PWD'][$i] ?? 0,
                        $_POST['is_OFW'][$i] ?? 0,
                        $_POST['is_solo_parent'][$i] ?? 0,
                        $_POST['is_indigenous'][$i] ?? 0,
                        $_POST['remarks'][$i] ?? ''
                    ]);
                }
            }

            $committed = $pdo->commit();
            if ($committed) {
                echo "
                    <link rel='stylesheet' href='../../../assets/css/style.css'>
                    <script src='js/sweetalert2.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Details have been added.',
                                text: 'Household and family details have been successfully added in the database.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href='add_household.php';
                            });
                        });
                    </script>
                ";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error: " . $e->getMessage());
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error occurred.',
                            text: 'An error occurred while adding the household and family details. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href='add_household.php';
                        });
                    });
                </script>
            ";
        }
    }
?>