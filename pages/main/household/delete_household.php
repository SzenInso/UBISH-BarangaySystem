<?php 
    include '../../../config/dbfetch.php';

    // access level verification (optional, add if needed)
    if (!isset($_SESSION['user_id']) || $accessLevel < 2) {
        header("Location: ../dashboard.php");
        exit;
    }

    if (isset($_POST['delete-household'])) {
        $householdId = $_POST['household_id'];
        $addressId = $_POST['address_id'];
        $respondentId = $_POST['respondent_id'];

        echo "
            <link rel='stylesheet' href='../../../assets/css/style.css'>
            <script src='../household/js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you really want to delete this household? This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('delete_household.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: 'confirm-delete=true&household_id=$householdId&address_id=$addressId&respondent_id=$respondentId'
                            }).then(() => {
                                Swal.fire({
                                    title: 'Household Deleted',
                                    text: 'The household has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = '../residency_management.php';
                                });
                            });
                        } else {
                            window.history.back();
                        }
                    });
                });
            </script>
        ";
        exit;
    }

    if (isset($_POST['confirm-delete']) && isset($_POST['household_id']) && isset($_POST['address_id']) && isset($_POST['respondent_id'])) {
        $householdId = $_POST['household_id'];
        $addressId = $_POST['address_id'];
        $respondentId = $_POST['respondent_id'];

        try {
            $pdo->beginTransaction();

            $deleteHouseholdQuery = "DELETE FROM households WHERE household_id = :household_id";
            $deleteHouseholdStmt = $pdo->prepare($deleteHouseholdQuery);
            $deleteHouseholdStmt->execute([':household_id' => $householdId]);

            $deleteAddressQuery = "DELETE FROM household_addresses WHERE household_address_id = :address_id";
            $deleteAddressStmt = $pdo->prepare($deleteAddressQuery);
            $deleteAddressStmt->execute([':address_id' => $addressId]);

            $deleteRespondentQuery = "DELETE FROM household_respondents WHERE household_respondent_id = :respondent_id";
            $deleteRespondentStmt = $pdo->prepare($deleteRespondentQuery);
            $deleteRespondentStmt->execute([':respondent_id' => $respondentId]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Error deleting household: ' . $e->getMessage());
        }
        exit;
    }
?>