<?php
include '../../config/dbfetch.php';

if (isset($_GET['id'])) {
    $documentId = $_GET['id'];

    $fetch = "SELECT file_path FROM files WHERE file_id = :file_id";
    $stmt = $pdo->prepare($fetch);
    $stmt->execute([':file_id' => $documentId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        $filePath = $document['file_path'];

        // database delete
        $delete = "DELETE FROM files WHERE file_id = :file_id";
        $stmtDelete = $pdo->prepare($delete);
        $stmtDelete->execute([':file_id' => $documentId]);

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
