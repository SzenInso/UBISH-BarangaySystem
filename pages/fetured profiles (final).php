<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ubish";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM employee_details";  // adjust table name if needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Featured Profiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    nav { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 0.5vw 5vw; 
        background: white; 
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); 
    }

    nav img { 
        width: 70px; 
        margin: 5px 0; 
    }

    nav .navigation ul { 
        display: flex; 
        gap: 15px; 
        list-style: none; 
        padding: 0; 
        margin: 0; 
    }

    nav .navigation ul li a { 
        text-decoration: none; 
        color: #151564; 
        font-size: 14px; 
        font-weight: 500; 
        transition: color 0.3s; 
    }

    nav .navigation ul li a:hover { 
        color: #FDC93B; 
    }

    @media (max-width: 576px) {
        nav { 
            flex-direction: column; 
            padding: 10px; 
        }

        nav .navigation ul { 
            justify-content: center; 
        }
    }

    .card-img-top {
        height: 300px;
        object-fit: cover;
    }

    .card {
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: scale(1.02);
    }

    .modal-content {
        cursor: pointer;
    }
    .custom-modal-size {
        max-width: 900px; 
    }

    .custom-modal-size .modal-content {
        height: 80vh;  
        overflow-y: auto;
    }
    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-body img {
        object-fit: cover;
        border-radius: 8px;
    }
    </style>
</head>
<nav>
    <img src="static pictures/greenwater logo.png" alt="Green Water Logo" />
    <div class="navigation">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Featured Profiles</a></li>
        <li><a href="#">Login</a></li>
      </ul>
    </div>
  </nav>

<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">Featured Profiles</h2>
    <div class="row g-4">

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($employee = $result->fetch_assoc()): 
                $empId = htmlspecialchars($employee['emp_id']);
                $first = htmlspecialchars($employee['first_name']);
                $middle = htmlspecialchars($employee['middle_name']);
                $last = htmlspecialchars($employee['last_name']);
                $fullName = trim("$first $middle $last");
                $imageFile = htmlspecialchars($employee['picture']);  // image filename stored here
            ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#employeeModal<?= $empId ?>">
                        <img src="picture/<?= $imageFile ?>" class="card-img-top" alt="Employee Photo">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $first . ' ' . $last ?></h5>
                            <p class="card-text"><?= htmlspecialchars($employee['legislature']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Modal -->

                <div class="modal fade" id="employeeModal<?= $empId ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg custom-modal-size">
                        <div class="modal-content" onclick="closeModal('employeeModal<?= $empId ?>')">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= $fullName ?></h5>
                            </div>
                            <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                <p><strong>Position:</strong> <?= htmlspecialchars($employee['legislature']) ?></p>
                                <p><strong>Committee: </strong> <?= htmlspecialchars($employee['committee']) ?></p>
                                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($employee['date_of_birth']) ?></p>
                                <p><strong>Civil Status:</strong> <?= htmlspecialchars($employee['civil_status']) ?></p>
                                <p><strong>Phone No : </strong> <?= htmlspecialchars($employee['phone_no']) ?></p>
                                </div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                <img src="picture/<?= $imageFile ?>" alt="Employee Photo" class="img-fluid rounded" style="max-height: 250px;">
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>


            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">No employee records found.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function closeModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    if (modal) {
        modal.hide();
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div id="footer"></div>

<script>
document.getElementById('footer').innerHTML = `
<footer class="bg-light text-muted pt-5 pb-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5 col-md-12">
        <div class="d-flex align-items-center mb-3">
          <img src="static pictures/greenwater logo.png" alt="Logo" style="height: 40px;">
          <h5 class="ms-2 mb-0">GREEN WATER</h5>
        </div>
        <p class="mb-2">Public servants should be focused on serving the public — not any special interest group,
           and good governance should be an expectation — not an exception.</p>
        <p class="mb-0">— Abigail Spanberger</p>
        <p>(pwede designer details or developer)</p>
      </div>

      <div class="col-lg-2 col-md-4 col-6">
        <h6 class="text-uppercase fw-bold mb-3">Links</h6>
        <ul class="list-unstyled">
          <li><a href="#" class="text-reset text-decoration-none">Home</a></li>
          <li><a href="#" class="text-reset text-decoration-none">About</a></li>
          <li><a href="#" class="text-reset text-decoration-none">Report an Issue</a></li>
          <li><a href="#" class="text-reset text-decoration-none">Request Barangay Certificate</a></li>
          <li><a href="#" class="text-reset text-decoration-none">Community Feedback</a></li>
        </ul>
      </div>

      <div class="col-lg-3 col-md-4 col-6">
        <h6 class="text-uppercase fw-bold mb-3">Address</h6>
        <p>Barangay Green Water</p>
        <p class="mb-1">CJ24+CX9, Green Water Vill. Rd,<br>Camp John Hay, Baguio, Benguet</p>
        <p class="mb-0">Open: 8 AM - 5 PM<br>Monday - Saturday</p>
      </div>

      <div class="col-lg-2 col-md-4 col-12">
        <h6 class="text-uppercase fw-bold mb-3">Contact Us</h6>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="bi bi-telephone me-2"></i>Phone</li>
          <li class="mb-2"><i class="bi bi-facebook me-2"></i>Facebook</li>
          <li class="mb-2"><i class="bi bi-chat-dots me-2"></i>Chat</li>
          <li class="mb-2"><i class="bi bi-envelope me-2"></i>Email</li>
        </ul>
      </div>
    </div>

    <div class="text-center pt-4 border-top mt-4">
      <small>© 2025 Barangay Green Water. All rights reserved.</small>
    </div>
  </div>
</footer>
`;
</script>
</body>
</html>
