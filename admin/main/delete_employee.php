<?php
    include '../../config/dbfetch.php';

    // delete single employee
    if (isset($_SESSION['del_id'])) {
        $emp_id = $_SESSION['del_id'];
        
        try {
            $pdo->beginTransaction();

            $fetchPicQuery = "SELECT picture FROM employee_details WHERE emp_id = :emp_id";
            $fetchPic = $pdo->prepare($fetchPicQuery);
            $fetchPic->execute([":emp_id" => $emp_id]);
            $employee = $fetchPic->fetch();

            if ($employee) {
                $profilePicture = $employee['picture'];
                if ($profilePicture !== '../../uploads/default_profile.jpg') {
                    unlink($profilePicture);
                }

                $deleteQuestionQuery = "DELETE FROM security_questions WHERE emp_id = :emp_id";
                $deleteQuestion = $pdo->prepare($deleteQuestionQuery);
                $deleteQuestion->execute([":emp_id" => $emp_id]);

                $deleteEmpQuery = "DELETE FROM employee_details WHERE emp_id = :emp_id";
                $deleteEmp = $pdo->prepare($deleteEmpQuery);
                $deleteEmp->execute([":emp_id" => $emp_id]);
                
                $deleted = $pdo->commit();
                if ($deleted) {
                    unset($_SESSION['del_id']);
                    echo "
                        <link rel='stylesheet' href='../../assets/css/style.css'>
                        <script src='../../assets/js/sweetalert2.js'></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    title: 'Deleted.',
                                    text: 'The employee has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: 'green',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = '../main/employee_table.php';
                                });
                            });
                        </script>
                    ";
                }
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error deleting employee: " . $e->getMessage());
            echo "
                <link rel='stylesheet' href='../../assets/css/style.css'>
                <script src='../../assets/js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Can not delete.',
                            text: 'An error occurred while deleting the employee. Please try again.',
                            icon: 'error',
                            confirmButtonColor: 'crimson',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../main/employee_table.php';
                        });
                    });
                </script>
            ";
        }
    } elseif (isset($_SESSION['del_ids'])) {
        $selectedDelIDs = $_SESSION['del_ids'];
        try {
            $pdo->beginTransaction();

            foreach ($selectedDelIDs as $emp_id) {
                $fetchPicQuery = "SELECT picture FROM employee_details WHERE emp_id = :emp_id";
                $fetchPic = $pdo->prepare($fetchPicQuery);
                $fetchPic->execute([":emp_id" => $emp_id]);
                $employee = $fetchPic->fetch();

                if ($employee) {
                    $profilePicture = $employee['picture'];
                    if ($profilePicture !== '../../uploads/default_profile.jpg') {
                        unlink($profilePicture);
                    }

                    $deleteEmpQuery = "DELETE FROM employee_details WHERE emp_id = :emp_id";
                    $deleteEmp = $pdo->prepare($deleteEmpQuery);
                    $deleteEmp->execute([":emp_id" => $emp_id]);
                }
            }
            
            $deleted = $pdo->commit();
            if ($deleted) {
                unset($_SESSION['del_ids']);
                echo "
                    <link rel='stylesheet' href='../../assets/css/style.css'>
                    <script src='../../assets/js/sweetalert2.js'></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                title: 'Deleted.',
                                text: 'Selected employees have been successfully deleted.',
                                icon: 'success',
                                confirmButtonColor: 'green',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '../main/employee_table.php';
                            });
                        });
                    </script>
                ";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error deleting employees: " . $e->getMessage());
            echo "
                <link rel='stylesheet' href='../../assets/css/style.css'>
                <script src='../../assets/js/sweetalert2.js'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: 'Can not delete.',
                            text: 'An error occurred while deleting selected employees. Please try again.',
                            icon: 'error',
                            confirmButtonColor: 'crimson',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '../main/employee_table.php';
                        });
                    });
                </script>
            ";
        }
    } else {
        echo "
            <link rel='stylesheet' href='../../assets/css/style.css'>
            <script src='../../assets/js/sweetalert2.js'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Can not delete.',
                        text: 'No employee selected for deletion.',
                        icon: 'error',
                        confirmButtonColor: 'crimson',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '../main/employee_table.php';
                    });
                });
            </script>
        ";
    }
?>