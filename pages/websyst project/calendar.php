<?php
// Get selected month/year from GET or use current
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

// Connect to DB and fetch events
$conn = new mysqli("localhost", "root", "", "green_water");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$startDate = sprintf('%04d-%02d-01', $year, $month);
$endDate = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);

$sql = "SELECT event_date, event_title, event_description FROM events WHERE event_date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[$row['event_date']] = [
        'event_title' => $row['event_title'],
        'event_description' => $row['event_description']
    ];
}

$stmt->close();
$conn->close();
?>

<!-- Calendar UI -->
<div class="calendar-header text-center mb-4">
    <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>">&laquo; Prev</a>
    <h2><?= $monthName . " " . $year ?></h2>
    <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Next &raquo;</a>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="calendar-box p-4 bg-white rounded shadow-sm">
            <div class="calendar d-grid" style="grid-template-columns: repeat(7, 1fr);">
                <?php
                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach ($days as $day) {
                    echo "<div class='day fw-bold'>$day</div>";
                }

                for ($i = 0; $i < $dayOfWeek; $i++) {
                    echo "<div class='empty'></div>";
                }

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $eventTitle = isset($events[$dateStr]) ? $events[$dateStr]['event_title'] : 'No event';
                    $eventDesc = isset($events[$dateStr]) ? $events[$dateStr]['event_description'] : '';
                    $hasEvent = isset($events[$dateStr]) ? 'bg-success text-white' : 'bg-light';
                    echo "<div class='date p-2 border text-center $hasEvent' onclick='showEventDetails(\"" . addslashes($eventTitle) . "\", \"" . addslashes($eventDesc) . "\")'>$day</div>";
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

<!-- JavaScript for showing event details -->
<script>
function showEventDetails(title, description) {
    document.getElementById('event-title').textContent = title;
    document.getElementById('event-description').textContent = description;
}
</script>

<!-- Optional CSS for better visuals -->
<style>
.calendar .day, .calendar .date, .calendar .empty {
    padding: 10px;
    border: 1px solid #ddd;
    min-height: 60px;
}
.calendar .day {
    background-color: #f8f9fa;
    text-align: center;
}
.calendar .date {
    cursor: pointer;
}
.calendar .bg-success {
    background-color: #28a745 !important;
}
</style>
