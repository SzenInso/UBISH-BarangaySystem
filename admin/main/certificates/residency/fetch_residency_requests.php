<?php
header('Content-Type: application/json');
include '../dbcon.php';

$input = json_decode(file_get_contents('php://input'), true);
$search = isset($input['search']) ? trim($input['search']) : '';

$searchSql = '';
$searchParam = [];

if ($search !== '') {
    $searchSql = " AND (firstname LIKE :search OR lastname LIKE :search OR contactNumber LIKE :search OR street LIKE :search OR barangay LIKE :search OR purpose LIKE :search)";
    $searchParam[':search'] = "%$search%";
}

// Fetch pending
$stmtPending = $pdo->prepare("SELECT * FROM residencycertreq WHERE status = :status $searchSql ORDER BY created_at DESC");
$paramsPending = array_merge([':status' => 'pending'], $searchParam);
$stmtPending->execute($paramsPending);
$pendingRequests = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

// Fetch approved
$stmtApproved = $pdo->prepare("SELECT * FROM residencycertreq WHERE status = :status $searchSql ORDER BY updated_at DESC");
$paramsApproved = array_merge([':status' => 'approved'], $searchParam);
$stmtApproved->execute($paramsApproved);
$approvedRequests = $stmtApproved->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'pending' => $pendingRequests,
    'approved' => $approvedRequests
]);
exit;
