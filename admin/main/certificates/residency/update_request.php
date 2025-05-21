<?php
header('Content-Type: application/json');
include '../../../../config/dbfetch.php';

// Parse JSON body
$data = json_decode(file_get_contents('php://input'), true);

$request_id = isset($data['id']) ? (int)$data['id'] : 0;
$action = $data['action'] ?? '';

if ($request_id <= 0 || !in_array($action, ['edit', 'delete'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters.']);
    exit;
}

try {
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM residencycertreq WHERE id = ?");
        $stmt->execute([$request_id]);
        echo json_encode(['success' => true, 'message' => 'Request deleted successfully.']);
    } elseif ($action === 'edit') {
        $purpose = trim($data['purpose'] ?? '');
        $years = (isset($data['years']) && is_numeric($data['years']) && $data['years'] > 0) ? (int)$data['years'] : null;
        $months = (isset($data['months']) && is_numeric($data['months']) && $data['months'] > 0) ? (int)$data['months'] : null;

        $stmt = $pdo->prepare("UPDATE residencycertreq 
        SET purpose = ?, years_residency = ?, months_residency = ?, updated_at = CURRENT_TIMESTAMP 
        WHERE id = ?");
        $stmt->execute([$purpose, $years, $months, $request_id]);

        echo json_encode(['success' => true, 'message' => 'Request updated successfully.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
