<?php
header('Content-Type: application/json');
include '../../../../config/dbconfig.php';

$data = json_decode(file_get_contents('php://input'), true);
$request_id = isset($data['id']) ? (int)$data['id'] : 0;
$action = isset($data['action']) ? $data['action'] : '';

if ($request_id <= 0 || !in_array($action, ['approve', 'reject', 'edit', 'delete'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request parameters.'
    ]);
    exit;
}

try {
    if ($action === 'approve' || $action === 'reject') {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $stmt = $pdo->prepare("UPDATE residencycertreq SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$status, $request_id]);

        $stmt2 = $pdo->prepare("SELECT contactNumber FROM residencycertreq WHERE id = ?");
        $stmt2->execute([$request_id]);
        $contactNumber = $stmt2->fetchColumn();

        echo json_encode([
            'success' => true,
            'message' => 'Request has been ' . $status . '.',
            'contactNumber' => $contactNumber ?: '',
            'id' => $request_id
        ]);
        exit;
    }

    // DELETE action must be separate
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM residencycertreq WHERE id = ?");
        $stmt->execute([$request_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Request has been removed from the database.'
        ]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}

