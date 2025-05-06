<?php
// Database connection
$host = 'localhost';
$db = 'homepage_db';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db";

$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Fetch events
$eventsQuery = $pdo->query("SELECT * FROM events");
$events = [];
while ($row = $eventsQuery->fetch(PDO::FETCH_ASSOC)) {
    $events[$row['event_date']] = $row;
}

// Fetch images
$imagesQuery = $pdo->query("SELECT * FROM images");
$images = [];
while ($row = $imagesQuery->fetch(PDO::FETCH_ASSOC)) {
    $images[] = $row['image_path'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREEN WATER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { 
            background: #f5f5f5; 
            font-family: Arial, sans-serif; }
        nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0.5vw 5vw; 
            background: white; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); }
        nav img { 
            width: 70px; 
            margin: 5px 0; }
        nav .navigation ul { 
            display: flex; 
            gap: 15px; 
            list-style: none; 
            padding: 0; 
            margin: 0; }
        nav .navigation ul li a { 
            text-decoration: none; 
            color: #151564; 
            font-size: 14px; 
            font-weight: 500; 
            transition: color 0.3s; }
        nav .navigation ul li a:hover { 
            color: #FDC93B; }
        .carousel { 
            max-width: 1000px; 
            margin: 30px auto; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .carousel-inner img { 
            width: 100%; 
            height: 500px; 
            object-fit: cover; 
            display: block; }
        .calendar-section { 
            margin: 30px auto; 
            padding: 30px 5vw; 
            max-width: 1100px; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .calendar-header { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            gap: 20px; 
            margin-bottom: 20px; 
            flex-wrap: wrap; }
        .calendar-header h2 { 
            font-size: 22px; 
            color: #151564; margin: 0; }
        .calendar-header a { 
            text-decoration: none; 
            background: rgb(81, 188, 250); 
            color: #151564; 
            font-weight: 600; 
            padding: 6px 12px; 
            border-radius: 6px; 
            font-size: 13px; }
        .calendar { 

            display: grid; 
            grid-template-columns: repeat(7, 1fr); 
            gap: 6px; 
            margin-top: 10px; 
            text-align: center; }
        .calendar .day { 
            background:rgb(81, 188, 250); 
            padding: 10px; 
            font-size: 13px; 
            font-weight: 600; 
            color: black; 
            border-radius: 5px; }
        .calendar .date { 
            background: #fff; 
            padding: 10px; 
            font-size: 13px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: 0.3s; }
        .calendar .date:hover { 
            background: rgb(95, 191, 246);}
        .calendar .empty { 
            background: transparent; 
            border: none; }
        .calendar-box, .event-details { 
            background: #fafafa; 
            border-radius: 10px; 
            padding: 20px; 
            min-height: 300px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
        .event-details h4 { 
            font-size: 18px; 
            color: #151564; 
            margin-bottom: 10px; }
        .event-details p { 
            font-size: 14px; 
            color: #555; }
        @media (max-width: 768px) {
            .calendar { gap: 5px; }
            .calendar-header { flex-direction: column; }
            .calendar-box, .event-details { padding: 15px; }
            .calendar-header h2 { font-size: 20px; }
        }
        @media (max-width: 576px) {
            nav { flex-direction: column; padding: 10px; }
            nav .navigation ul { justify-content: center; }
            .calendar-section { padding: 20px; }
            .calendar-box, .event-details { padding: 15px; }
        }

        .welcome-section {
      background-color: #f8f9fa;
      padding: 50px 0;
    }
    .official-img {
      border-radius: 15px;
      width: 100%;
      max-width: 400px;
    }
    .logo-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      width: 80px;
    }
    .img-container {
      position: relative;
      display: inline-block;
    }
    .card-img-top {
        height: 200px;
        object-fit: cover;
        cursor: pointer;
    }
    .card-body {
        height: 120px; 
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .modal-body {
        display: flex;
        flex-wrap: wrap;
    }
    .left, .right {
        width: 50%;
        padding: 10px;
    }
    @media(max-width: 768px) {
        .left, .right {
            width: 100%;
        }
    }
    </style>
    
</head>

<body>

<!-- Navigation -->
<nav>
    <img src="static pictures/greenwater logo.png" alt="Logo" />
    <div class="navigation">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Fetured Profiles</a></li>
            <li><a href="#">Login</a></li>
        </ul>
    </div>
</nav>

<!-- Image Carousel -->
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($images as $index => $image): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <img src="<?= $image ?>" class="d-block" alt="Slide <?= $index + 1 ?>">
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="container mt-5">
    <h2 class="mb-4">Barangay Officials</h2>
    <div class="row">
        <?php
        $result = $pdo->query("SELECT * FROM officials");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)):
        
        ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="uploads/<?= $row['image'] ?>" class="card-img-top" data-toggle="modal" data-target="#modal<?= $row['id'] ?>">
                <div class="card-body text-center">
                    <h5><?= htmlspecialchars($row['name']) ?></h5>
                    <p><?= htmlspecialchars($row['position']) ?></p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body" ondblclick="$('#modal<?= $row['id'] ?>').modal('hide')">
                        <div class="left">
                            <h5><?= htmlspecialchars($row['name']) ?></h5>
                            <p><strong>Position:</strong> <?= htmlspecialchars($row['position']) ?></p>
                            <p><strong>Cellphone:</strong> <?= htmlspecialchars($row['cellphone']) ?></p>
                        </div>
                        <div class="right">
                            <img src="uploads/<?= $row['image'] ?>" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<section class="calendar-section" id="calendar-section">
<h2 class="text-center mb-4" style="color: #151564;">Announcement and Event</h2>

    <?php
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    if ($month < 1) { $month = 12; $year--; }
    elseif ($month > 12) { $month = 1; $year++; }

    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $dayOfWeek = date('w', $firstDay);
    $daysInMonth = date('t', $firstDay);

    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

    $monthName = date('F', $firstDay);
    ?>

    <div class="calendar-header">
        <a href="javascript:void(0);" onclick="changeMonth(<?= $prevMonth ?>, <?= $prevYear ?>)">&laquo; Prev</a>
        <h2><?= $monthName . " " . $year ?></h2>
        <a href="javascript:void(0);" onclick="changeMonth(<?= $nextMonth ?>, <?= $nextYear ?>)">Next &raquo;</a>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
// --- Prev/Next ---
function changeMonth(month, year) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `?month=${month}&year=${year}`, true); 
    xhr.onload = function() {
        if (xhr.status === 200) {
            const parser = new DOMParser();
            const htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
            const newCalendar = htmlDoc.querySelector('#calendar-section').innerHTML;
            document.querySelector('#calendar-section').innerHTML = newCalendar;
        }
    };
    xhr.send();
}

// Event detail viewer
function showEventDetails(title, description) {
    document.getElementById('event-title').textContent = title;
    document.getElementById('event-description').textContent = description;
}
</script>

    <div class="row">
        <div class="col-md-7">
            <div class="calendar-box p-4 bg-white rounded shadow-sm">
                <div class="calendar">
                    <?php
                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    foreach ($days as $day) {
                        echo "<div class='day'>$day</div>";
                    }

                    for ($i = 0; $i < $dayOfWeek; $i++) {
                        echo "<div class='empty'></div>";
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $eventTitle = isset($events[$dateStr]) ? $events[$dateStr]['event_title'] : 'No event';
                        $eventDesc = isset($events[$dateStr]) ? $events[$dateStr]['event_description'] : '';
                        echo "<div class='date' onclick='showEventDetails(\"$eventTitle\", \"$eventDesc\")'>$day</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="event-details p-4 bg-white rounded shadow-sm" id="event-details-box">
                <h4 id="event-title">Click on a date to see event details.</h4>
                <p id="event-description"></p>
            </div>
        </div>
    </div>
</section>



<!-- Footer -->
<div id="footer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
