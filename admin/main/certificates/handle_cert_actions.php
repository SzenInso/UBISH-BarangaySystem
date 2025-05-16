<?php
header('Content-Type: application/json');
include '../../../config/dbfetch.php';

// Sanitize inputs
$request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Validate inputs
if ($request_id <= 0 || !in_array($action, ['approve', 'reject'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request parameters.'
    ]);
    exit;
}

$status = $action === 'approve' ? 'approved' : 'rejected';

try {
    // Update status and updated_at timestamp
    $stmt = $pdo->prepare("UPDATE residencycertreq SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$status, $request_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Request has been ' . $status . '.'
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database update failed: ' . $e->getMessage()
    ]);
    exit;
}
