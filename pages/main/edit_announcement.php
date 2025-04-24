<?php
    include '../../config/dbfetch.php';
    if (isset($_GET['announcement_id'])) {
        $announcementID = $_GET['announcement_id'];
        echo "Editing announcement with ID: " . htmlspecialchars($announcementID);
    } else {
        echo "No announcement ID provided.";
    }
?>