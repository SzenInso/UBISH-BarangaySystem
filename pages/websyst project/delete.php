<?php
// Database connection using PDO
$host = 'localhost';
$db = 'homepage_db';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Validate input
if (!isset($_GET['type'], $_GET['id'])) {
    die("Invalid request.");
}

$type = $_GET['type'];
$id = intval($_GET['id']);

switch ($type) {
    case 'official':
        // Optionally delete image file
        $stmt = $pdo->prepare("SELECT image FROM officials WHERE id = ?");
        $stmt->execute([$id]);
        $official = $stmt->fetch();
        if ($official && file_exists("uploads/" . $official['image'])) {
            unlink("uploads/" . $official['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM officials WHERE id = ?");
        $stmt->execute([$id]);
        break;

    case 'image':
        $stmt = $pdo->prepare("SELECT image_path FROM images WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetch();
        if ($img && file_exists($img['image_path'])) {
            unlink($img['image_path']);
        }

        $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
        $stmt->execute([$id]);
        break;

    case 'event':
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$id]);
        break;

    case 'team':
        $stmt = $pdo->prepare("SELECT image FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        if ($member && file_exists($member['image'])) {
            unlink($member['image']);
        }

        $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        break;

    default:
        die("Unknown type.");
}

header("Location: insert.php"); // or index.php if deleting team member
exit;
?>
