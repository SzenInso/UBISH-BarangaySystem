<?php
// Database connection
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

// Message to show feedback
$message = "";

// Ensure uploads directory exists
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

// Handle image upload
if (isset($_POST['submit_image'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['image']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $message = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $stmt = $pdo->prepare("INSERT INTO images (image_path) VALUES (?)");
                $stmt->execute([$targetFile]);
                $message = "Image uploaded and saved to database.";
            } else {
                $message = "Failed to move uploaded file.";
            }
        }
    } else {
        $message = "Please select an image file.";
    }
}

// Handle event submission
if (isset($_POST['submit_event'])) {
    $event_date = $_POST['event_date'] ?? '';
    $event_title = $_POST['event_title'] ?? '';
    $event_description = $_POST['event_description'] ?? '';

    if ($event_date && $event_title) {
        $stmt = $pdo->prepare("INSERT INTO events (event_date, event_title, event_description) VALUES (?, ?, ?)");
        $stmt->execute([$event_date, $event_title, $event_description]);
        $message = "Event added successfully.";
    } else {
        $message = "Event date and title are required.";
    }
}

// Handle official insertion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['name'], $_POST['position'], $_POST['cellphone'], $_FILES['image'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $cellphone = $_POST['cellphone'];
    $image = $_FILES['image']['name'];

    if (!empty($name) && !empty($position) && !empty($cellphone) && $image) {
        $imagePath = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        $stmt = $pdo->prepare("INSERT INTO officials (name, position, cellphone, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $position, $cellphone, basename($image)]);
        $message = "Official inserted successfully.";
    }
}

// Fetch images, events, and officials
$images = $pdo->query("SELECT * FROM images")->fetchAll(PDO::FETCH_ASSOC);
$events = $pdo->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
$officials = $pdo->query("SELECT * FROM officials")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Image, Event, or Official</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-5">

<h1 class="mb-4">Insert Image, Event, or Official</h1>

<?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- Image Upload Form -->
<div class="card mb-4">
    <div class="card-header">Upload Image</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Select Image:</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <button type="submit" name="submit_image" class="btn btn-primary">Upload Image</button>
        </form>
    </div>
</div>

<!-- Event Add Form -->
<div class="card mb-4">
    <div class="card-header">Add Event</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" class="form-control" name="event_date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" class="form-control" name="event_title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea class="form-control" name="event_description" rows="4"></textarea>
            </div>
            <button type="submit" name="submit_event" class="btn btn-success">Add Event</button>
        </form>
    </div>
</div>

<!-- Insert Official -->
<div class="card mb-4">
    <div class="card-header">Add New Official</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="mb-2">
                <input type="text" name="position" class="form-control" placeholder="Position" required>
            </div>
            <div class="mb-2">
                <input type="text" name="cellphone" class="form-control" placeholder="Cellphone Number" required>
            </div>
            <div class="mb-3">
                <input type="file" name="image" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success">Insert Official</button>
        </form>
    </div>
</div>

<!-- Officials List -->
<h3>List of Officials</h3>
<table class="table table-bordered">
    <tr><th>Image</th><th>Name</th><th>Position</th><th>Cellphone</th><th>Actions</th></tr>
    <?php foreach ($officials as $row): ?>
        <tr>
            <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" width="60"></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['cellphone']) ?></td>
            <td>
                <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Uploaded Images -->
<h3>Uploaded Images</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($images as $image): ?>
            <tr>
                <td><?= $image['id'] ?></td>
                <td><img src="<?= htmlspecialchars($image['image_path']) ?>" width="100" alt=""></td>
                <td>
                    <a href="update.php?type=image&id=<?= $image['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?type=image&id=<?= $image['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this image?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Events -->
<h3>Events</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= $event['id'] ?></td>
                <td><?= $event['event_date'] ?></td>
                <td><?= htmlspecialchars($event['event_title']) ?></td>
                <td><?= htmlspecialchars($event['event_description']) ?></td>
                <td>
                    <a href="update.php?type=event&id=<?= $event['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?type=event&id=<?= $event['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php" class="btn btn-secondary mt-4">Back to Home</a>

</body>
</html>
