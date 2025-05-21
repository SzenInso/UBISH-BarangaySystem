<?php
include '../../../config/dbfetch.php';

header('Content-Type: application/json');

try {
    // Fetch all requests ordered by created_at DESC, regardless of status
    $stmt = $pdo->prepare("SELECT * FROM residencycertreq ORDER BY created_at DESC");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $requests]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
