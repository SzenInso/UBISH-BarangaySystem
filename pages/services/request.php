<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Certificate of Residency</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../services/assets/request.css">
</head>
<body>

<header>
    <div class="navigation">
        <div class="logo">
            <img src="../../assets/img/greenwater-village-logo.jpg" alt="Greenwater Village Logo">
            <h1>UBISH | Certificate of Residency Request</h1>
        </div>
        <nav>
            <ul>
               <li><a href="service_page.php">Services Offered</a></li>
               <li><a href="../account/login.php">Log In</a></li>
               <li><a href="../account/signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </div>
    <hr>
</header>

<main class="request-wrapper">
<form action="submit_request.php" method="POST" class="request-form">

<h2>Request Certificate of Residency</h2>

<div class="form-group">
    <label>First Name:</label>
    <input type="text" name="first_name" required>
</div>

<div class="form-group">
    <label>Middle Name:</label>
    <input type="text" name="middle_name" required>
</div>

<div class="form-group">
    <label>Last Name:</label>
    <input type="text" name="last_name" required>
</div>

<div class="form-group">
    <label>Current Date:</label>
    <input type="date" name="request_date" value="<?php echo date('Y-m-d'); ?>" required>
</div>
<div class="form-group">
    <label>Email Address:</label>
    <input type="email" name="email" required>
</div>

<div class="form-group">
    <label>Contact Number:</label>
    <input type="text" name="contact_number" pattern="[0-9]+" required>
</div>

<h3>Complete Address</h3>

<div class="form-group">
    <label>Street:</label>
    <input type="text" name="street" required>
</div>

<div class="form-group">
    <label>Barangay:</label>
    <input type="text" name="barangay" required>
</div>

<div class="form-group">
    <label>Municipality/City:</label>
    <input type="text" name="city" required>
</div>

<div class="form-group">
    <label>Province:</label>
    <input type="text" name="province" required>
</div>

<div class="form-group">
    <label>Zipcode:</label>
    <input type="text" name="zipcode" required>
</div>

<div class="form-group">
    <label>Age:</label>
    <input type="number" name="age" required>
</div>

<div class="form-group">
    <label>Civil Status:</label>
    <select name="civil_status" required>
        <option value="" disabled selected>Select Status</option>
        <option value="Single">Single</option>
        <option value="Married">Married</option>
        <option value="Widowed">Widowed</option>
    </select>
</div>

<div class="form-group">
    <label>Citizenship:</label>
    <input type="text" name="citizenship" required>
</div>

<div class="form-group">
    <label>Purpose of Requesting Clearance:</label>
    <textarea name="purpose" rows="4" required></textarea>
</div>

<button type="submit" class="submit-btn">Submit Request</button>

</form>

</main>

<footer>
    <hr>
    <p><?php echo "&copy; " . date('Y') . " | Unified Barangay Information Service Hub"; ?></p>
</footer>

</body>
</html>
