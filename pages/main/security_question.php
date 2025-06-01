<?php
    include '../../config/dbfetch.php';

    if (isset($_POST['cancel'])) {
        header('location:../main/account.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>UBISH Dashboard | Password Reset Settings</title>
    </style>
</head>

<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li><a href="../main/dashboard.php">Home</a></li>
                    <li class="active"><a href="../main/account.php">Account</a></li>
                    
                    <h3>Documents & Disclosure</h3>
                    <li><a href="../main/documents.php">Documents</a></li>
                    <li><a href="../main/announcements.php">Post Announcement</a></li>
                    
                    <h3>Tables & Requests</h3>
                    <li><a href="../main/employee_table.php">Employee Table</a></li>

                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/residency_management.php">Residency Management</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/certificates.php">Certificate Requests</a></li>'; } ?>
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/permits.php">Permit Requests</a></li>'; } ?>
                    <!-- STANDARD -->
                    
                    <!-- FULL -->
                    <?php if ($accessLevel >= 3) { echo '<li><a href="../main/account_requests.php">Account Requests</a></li>'; } ?>
                    <!-- FULL -->
                    
                    <h3>Reports</h3>
                    <!-- STANDARD -->
                    <?php if ($accessLevel >= 2) { echo '<li><a href="../main/incidents.php">Incident Reports</a></li>'; }  ?>
                    <!-- STANDARD -->
                    
                    <li><a href="../main/incident_table.php">Incident History</a></li>
                    <li><a href="../main/reports.php">Analytics</a></li>
                </ul>
            </div>
            <style>
                .security-question-main select,
                .security-question-main input#answerField {
                    padding: 8px;
                }
                .security-question-main input#answerField {
                    width: 330px;
                }
            </style>
            <div class="dashboard-content">
                <h1>
                    <center>Security Question</center>
                </h1><br>
                <p>Clease choose a security question should you forget your password.</p>
                <form method="POST">
                    <div class="security-question-main">
                        <table>
                            <tr><td>Security Question:</td></tr>
                            <tr>
                                <td>
                                    <select name="security-question" id="securityQuestion">
                                        <option value="" selected disabled>Select a security question</option>
                                        <option value="What is your mother's maiden name?">
                                            What is your mother's maiden name?
                                        </option>
                                        <option value="In what city or town did your parents meet?">
                                            In what city or town did your parents meet?
                                        </option>
                                        <option value="What city were you born in?">
                                            What city were you born in?
                                        </option>
                                        <option value="What was your childhood best friend’s nickname?">
                                            What was your childhood best friend’s nickname?
                                        </option>
                                        <option value="What was the name of your first pet?">
                                            What was the name of your first pet?
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="security-question-response">
                                        <p style="margin-bottom: 8px;">Your Answer:</p>
                                        <input 
                                            type="text" 
                                            name="security-answer" 
                                            id="answerField" 
                                            placeholder="Enter the answer to your picked security question"
                                            autocomplete="off"    
                                        >
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const securityQuestion = document.getElementById('securityQuestion'); 
                            const answerFieldDiv = document.querySelector('.security-question-response'); 
                            const confirmButton = document.getElementById('confirmBtn'); 

                            // Function to toggle visibility and button state
                            function toggleSecurityQuestionResponse() {
                                if (securityQuestion.value) {
                                    answerFieldDiv.style.display = 'block'; 
                                    confirmButton.disabled = false; 
                                } else {
                                    answerFieldDiv.style.display = 'none'; 
                                    confirmButton.disabled = true;
                                }
                            }

                            securityQuestion.addEventListener('change', toggleSecurityQuestionResponse);

                            toggleSecurityQuestionResponse();
                        });
                    </script>
                    <div class="account-actions">
                        <button type="submit" name="confirm-question" id="confirmBtn">Confirm Security Question</button>
                        <button type="submit" name="cancel" id="changePwdBtn">Cancel</button>
                    </div>
                    <br>
                    <?php 
                        if (isset($_POST['confirm-question'])) {
                            $question = $_POST['security-question'];
                            $answer = (!empty($_POST['security-answer'])) ? $_POST['security-answer'] : null;
                    
                            if (empty($answer)) {
                                echo "<p>Please enter an answer to your chosen security question.</p>";
                            } else {
                                try {
                                    $answerHash = password_hash($answer, PASSWORD_DEFAULT);
                                    $pdo->beginTransaction();

                                    $flag = false;
                                    $checkQuery = "SELECT * FROM security_questions WHERE emp_id = :emp_id";
                                    $checkStmt = $pdo->prepare($checkQuery);
                                    $checkStmt->execute([':emp_id' => $_SESSION['emp_id']]);
                                    $existingRecord = $checkStmt->fetch();

                                    if ($existingRecord) {
                                        $updateQuery = "UPDATE security_questions SET question = :question, answer = :answer WHERE emp_id = :emp_id";
                                        $updateStmt = $pdo->prepare($updateQuery);
                                        $updateStmt->execute([
                                            ':question' => $question,
                                            ':answer' => $answerHash,
                                            ':emp_id' => $_SESSION['emp_id']
                                        ]);
                                        $pdo->commit();
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    title: 'Security question updated successfully.',
                                                    icon: 'success'
                                                }).then((result) => {
                                                    window.location.href = '../main/account.php'
                                                });
                                            </script>
                                        ";
                                    } else {
                                        $insertQuery = "INSERT INTO security_questions (emp_id, question, answer) VALUES (:emp_id, :question, :answer)";
                                        $insertStmt = $pdo->prepare($insertQuery);
                                        $insertStmt->execute([
                                            ':emp_id' => $_SESSION['emp_id'],
                                            ':question' => $question,
                                            ':answer' => $answerHash
                                        ]);
                                        $pdo->commit();
                                        echo "
                                            <script>
                                                Swal.fire({
                                                    title: 'Security question set successfully.',
                                                    icon: 'success'
                                                }).then((result) => {
                                                    window.location.href = '../main/account.php'
                                                });
                                            </script>
                                        ";
                                    }
                                } catch (Exception $e) {
                                    $pdo->rollBack();
                                    error_log("An error occurred:" . $e->getMessage());
                                    echo "
                                        <script>
                                            Swal.fire({
                                                title: 'Failed to set.',
                                                text: 'Unable to set security question. Please try again.',
                                                icon: 'error'
                                            }).then((result) => {
                                                window.location.href = '../main/account.php'
                                            });
                                        </script>
                                    ";
                                }
                            }
                        }
                    ?>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>