<?php
include '../../config/dbfetch.php';

if (isset($_GET['id'])) {
    $documentId = $_GET['id'];

    $fetch = "SELECT document_path FROM documents WHERE document_id = :document_id";
    $stmt = $pdo->prepare($fetch);
    $stmt->execute([':document_id' => $documentId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        $filePath = $document['document_path'];

        // database delete
        $delete = "DELETE FROM documents WHERE document_id = :document_id";
        $stmtDelete = $pdo->prepare($delete);
        $stmtDelete->execute([':document_id' => $documentId]);

        // server delete
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        header("Location: documents.php?message=Document deleted successfully.");
        exit;
    } else {
        echo "<p style='color: red;'>Document not found.</p>";
    }
} else {
    echo "<p style='color: red;'>No document ID.</p>";
}
?>
