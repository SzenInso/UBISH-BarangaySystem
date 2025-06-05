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
    <link rel="stylesheet" href="css/dash.css">
    <link rel="icon" type="image/x-icon" href="../../assets/img/GreenwaterLogo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="../../assets/js/sweetalert2.js"></script>
    <title>Greenwater Village Dashboard | Password Reset Settings</title>
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <ul>
                <h2>
                    <div class="dashboard-greetings">
                        <?php 
                           $stmt = $pdo->prepare("SELECT * FROM employee_details WHERE emp_id = :emp_id");
                            $stmt->execute([":emp_id" => $_SESSION['emp_id']]);
                            $row = $stmt->fetch(PDO::FETCH_ASSOC); {
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
    <style>
        /* Title */
.dashboard-content h1 {
    font-size: 1.8rem;
    margin-bottom: 10px;
    color: #2e7d32;
    text-align: center;
}

/* Subtext */
.dashboard-content p {
    text-align: center;
    color: #4e734e;
    font-size: 1rem;
    margin-bottom: 20px;
}

/* Table structure */
.security-question-main table {
    width: 100%;
    border-spacing: 0 20px;
}

/* Dropdown styling */
#securityQuestion {
    width: 100%;
    padding: 10px;
    font-size: 0.95rem;
    border-radius: 6px;
    border: 1px solid #a5d6a7;
    background-color: #f1f8e9;
    transition: border-color 0.3s ease;
}
#securityQuestion:focus {
    border-color: #66bb6a;
    background-color: #e8f5e9;
    outline: none;
}

/* Answer input */
.security-question-response input[type="text"] {
    width: 100%;
    padding: 10px;
    font-size: 0.95rem;
    border-radius: 6px;
    border: 1px solid #a5d6a7;
    background-color: #f1f8e9;
    transition: border-color 0.3s ease;
}
.security-question-response input[type="text"]:focus {
    border-color: #66bb6a;
    background-color: #e8f5e9;
    outline: none;
}

/* Label for answer */
.security-question-response p {
    margin-bottom: 8px;
    font-weight: 500;
    color: #33691e;
}

/* Button styles */
.account-actions {
    text-align: center;
    margin-top: 25px;
}
#confirmBtn,
#changePwdBtn {
    background-color: #43a047;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    margin: 0 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
#confirmBtn:disabled {
    background-color: #a5d6a7;
    cursor: not-allowed;
}
#changePwdBtn {
    background-color: #81c784;
}
#confirmBtn:hover:not(:disabled) {
    background-color: #2e7d32;
}
#changePwdBtn:hover {
    background-color: #66bb6a;
}

/* Responsive */
@media (max-width: 600px) {
    .security-question-main table,
    .security-question-main td {
        display: block;
        width: 100%;
    }
    .security-question-main td {
        margin-bottom: 10px;
    }
}
    </style>
</body>
</html>