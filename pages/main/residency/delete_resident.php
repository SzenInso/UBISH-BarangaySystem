<?php
    include '../../../config/dbfetch.php';

    if (isset($_POST['delete-resident'])) {
        $residentId = $_POST['resident_id'];

        $residentQuery = "SELECT * FROM family_members WHERE member_id = :resident_id";
        $stmt = $pdo->prepare($residentQuery);
        $stmt->execute([":resident_id" => $residentId]);
        $resident = $stmt->fetch();

        if ($resident) {
            $residentName = htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']);
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Do you really want to delete $residentName? This action cannot be undone.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch('delete_resident.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: 'confirm-delete=true&resident_id=$residentId'
                                }).then(() => {
                                    Swal.fire({
                                        title: 'Resident Deleted',
                                        text: 'The resident has been successfully deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        window.location.href = '../residency_management.php';
                                    });
                                });
                            } else {
                                window.location.href = '../residency_management.php';
                            }
                        });
                    });
                </script>
            ";
        } else {
            echo "
                <link rel='stylesheet' href='../../../assets/css/style.css'>
                <script src='js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Resident not found.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../residency_management.php';
                        });
                    });
                </script>
            ";
        }
    }

    if (isset($_POST['confirm-delete']) && isset($_POST['resident_id'])) {
        $residentId = $_POST['resident_id'];
        $deleteQuery = "DELETE FROM family_members WHERE member_id = :resident_id";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->execute([":resident_id" => $residentId]);
    }
?>