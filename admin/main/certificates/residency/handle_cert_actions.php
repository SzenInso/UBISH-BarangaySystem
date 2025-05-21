<?php
header('Content-Type: application/json');
include '../../../../config/dbconfig.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$request_id = isset($data['id']) ? (int)$data['id'] : 0;
$action = isset($data['action']) ? $data['action'] : '';

// Validate basic input
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
            'contactNumber' => $contactNumber ?: ''
        ]);
        exit;
    }

    if ($action === 'edit') {
        $purpose = trim($data['purpose'] ?? '');
        $years = is_numeric($data['years']) ? (int)$data['years'] : null;
        $months = is_numeric($data['months']) ? (int)$data['months'] : null;

        if ($purpose === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Purpose is required.'
            ]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE residencycertreq 
            SET purpose = ?, years_residency = ?, months_residency = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?");
        $stmt->execute([$purpose, $years, $months, $request_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Request successfully updated.'
        ]);
        exit;
    }

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM residencycertreq WHERE id = ?");
        $stmt->execute([$request_id]);

        echo json_encode([
            'success' => true,
            'message' => 'Request successfully deleted.'
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
