<?php
    include '../config/dbfetch.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>UBISH Dashboard | Create Announcement</title>
</head>
<body>
    <header>
        <div class="navigation">
            <div class="logo">
                <img src="../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
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
                    <li><a href="../pages/dashboard.php">Home</a></li>
                    <li><a href="../pages/account.php">Account</a></li>
                    <?php
                        // placeholder access control pages
                        if ($accessLevel >= 1) {
                            echo '<li><a href="#">Documents</a></li>';
                            echo '<li class="active"><a href="../pages/announcements.php">Post Announcement</a></li>';
                        }
                        if ($accessLevel >= 2) {
                            echo '<li><a href="../pages/employee_table.php">Employee Table</a></li>';
                        }
                        if ($accessLevel >= 3) {
                            echo '<li><a href="#">Profile Change Request</a></li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="dashboard-content">
                <h1><center>Post an Announcement</center></h1><br>
                <style>
                    .announcement-posting-form {
                        text-align: left; 
                        margin: 0; 
                        padding: 16px; 
                        width: 100%; 
                    }

                    .announcement-posting-form form {
                        display: block; 
                        width: 100%; 
                    }

                    .announcement-credentials {
                        margin-bottom: 16px; 
                    }

                    .announcement-credentials input,
                    .announcement-credentials textarea {
                        width: 100%; 
                        padding: 8px; 
                        box-sizing: border-box;
                    }

                    .privacy-options, 
                    .category-options {
                        display: flex; 
                        gap: 32px; 
                        align-items: center; 
                        white-space: nowrap;
                    }

                    .privacy-options label,
                    .category-options label {
                        display: flex;
                        align-items: center;
                        gap: 8px; 
                        font-size: 14px; 
                        cursor: pointer; 
                    }
                </style>
                <div class="announcement-posting-form">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="announcement-credentials">
                            <p>Announcement Title</p>
                            <input type="text" name="title" placeholder="Enter title" required>
                        </div>
                        <div class="announcement-credentials">
                            <p>Type of Privacy</p>
                            <div class="privacy-options">
                                <label>
                                    <input type="radio" id="privacy" name="privacy" value="Public" required>
                                    Public
                                </label>
                                <label>
                                    <input type="radio" id="privacy" name="privacy" value="Private" required>
                                    Private
                                </label>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <p>Category</p>
                            <div class="category-options">
                                <label>
                                    <input type="radio" id="category" name="category" value="Public Notice" required>
                                    Public Notice
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Report" required>
                                    Report
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Event" required>
                                    Event
                                </label>
                                <label>
                                    <input type="radio" id="category" name="category" value="Emergency" required>
                                    Emergency
                                </label>
                                <label>
                                    <input type="radio" id="categoryOthers" name="category" value="Others" required>
                                    Others
                                </label>
                                <!-- custom category input 
                                to include:
                                if ($category === "Others") {
                                    $category = $_POST['custom-category'];
                                }
                                -->
                                <input type="text" id="customCategory" name="custom-category" placeholder="Enter custom category" style="display: none;">
                                <script src="../assets/js/customCategory.js"></script>
                            </div>
                        </div>
                        <div class="announcement-credentials">
                            <p>Announcement Description</p>
                            <textarea name="description" rows="5" placeholder="Enter description" required></textarea>
                        </div>
                        <div class="announcement-credentials">
                            <p>Upload File (optional)</p>
                            <input type="file" name="file">
                        </div>
                        <button name="post">Post Announcement</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <hr>
        <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
    </footer>
</body>
</html>
