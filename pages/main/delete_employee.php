<?php
    include '../../config/dbfetch.php';

    $emp_id = $_SESSION['del_id'];
    echo "
        <script>
            alert('" . $emp_id . "');
            window.location.href = '../main/employee_table.php';
        </script>
    ";
?>