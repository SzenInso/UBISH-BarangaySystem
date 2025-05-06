<?php
// Database connection (PDO)
$host = 'localhost';
$db = 'homepage_db';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = "";

if (!isset($_GET['type'], $_GET['id'])) {
    die("Invalid request.");
}

$type = $_GET['type'];
$id = intval($_GET['id']);

// Fetch record based on type
switch ($type) {
    case 'image':
        $stmt = $pdo->prepare("SELECT * FROM images WHERE id = ?");
        break;
    case 'event':
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        break;
    case 'official':
        $stmt = $pdo->prepare("SELECT * FROM officials WHERE id = ?");
        break;
    default:
        die("Unknown type.");
}
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Record not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'image') {
        $newPath = $_POST['image_path'] ?? '';
        $stmt = $pdo->prepare("UPDATE images SET image_path = ? WHERE id = ?");
        $stmt->execute([$newPath, $id]);
        header('Location: insert.php');
        exit;

    } elseif ($type === 'event') {
        $date = $_POST['event_date'] ?? '';
        $title = $_POST['event_title'] ?? '';
        $desc = $_POST['event_description'] ?? '';
        $stmt = $pdo->prepare("UPDATE events SET event_date = ?, event_title = ?, event_description = ? WHERE id = ?");
        $stmt->execute([$date, $title, $desc, $id]);
        header('Location: insert.php');
        exit;

    } elseif ($type === 'official') {
        $name = $_POST['name'];
        $position = $_POST['position'];
        $cellphone = $_POST['cellphone'];
        $image = $data['image'];

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        }

        $stmt = $pdo->prepare("UPDATE officials SET name = ?, position = ?, cellphone = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $position, $cellphone, $image, $id]);
        header("Location: insert.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?= ucfirst($type) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-5">
    <h1>Edit <?= ucfirst($type) ?></h1>

    <?php if ($type === 'image'): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Image Path:</label>
                <input type="text" class="form-control" name="image_path" value="<?= htmlspecialchars($data['image_path']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Image</button>
        </form>

    <?php elseif ($type === 'event'): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="event_date" value="<?= htmlspecialchars($data['event_date']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" class="form-control" name="event_title" value="<?= htmlspecialchars($data['event_title']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea class="form-control" name="event_description" rows="4"><?= htmlspecialchars($data['event_description']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </form>

    <?php elseif ($type === 'official'): ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Position:</label>
                <input type="text" name="position" value="<?= htmlspecialchars($data['position']) ?>" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Cellphone:</label>
                <input type="text" name="cellphone" value="<?= htmlspecialchars($data['cellphone']) ?>" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>Current Image:</label><br>
                <img src="uploads/<?= htmlspecialchars($data['image']) ?>" width="100"><br>
                <input type="file" name="image" class="form-control-file mt-2">
            </div>
            <button type="submit" class="btn btn-primary">Update Official</button>
        </form>
    <?php endif; ?>

    <a href="insert.php" class="btn btn-secondary mt-3">Back</a>
</body>
</html>
