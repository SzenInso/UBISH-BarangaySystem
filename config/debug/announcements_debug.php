<br><?php 
/* FOR DEBUGGING PURPOSES ONLY -Mark */
include '../../config/dbconfig.php';

if (isset($_POST['post'])) {
    $title = $_POST['title'];
    $privacy = $_POST['privacy'];
    $category = $_POST['category'];
    if ($category === "Others") {
        $category = $_POST['custom-category'];
    }
    $description = $_POST['description'];
    $author = $_SESSION['user_id'];
    $post_date = date('Y-m-d H:i:s');
    
    echo "<h3>Form Data:</h3>";
    echo "Title: $title<br>";
    echo "Privacy: $privacy<br>";
    echo "Category: $category<br>";
    echo "Description:<br>" . nl2br(htmlspecialchars($description)) . "<br>";
    echo "Author ID: $author<br>";
    echo "Post Date: $post_date<br><br>";

    // for upload directory
    $uploadDir = '../../uploads/attachments/';
    $maxSize = 10 * 1024 * 1024; // 10MB
    $fileTimestamp = time();
    $errors = [];
    
    // thumbnail upload validation
    $thumbnail = null;
    $thumbnailPath = null;
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
        $thumbnailTmpName = $_FILES['thumbnail']['tmp_name'];
        $thumbnailError = $_FILES['thumbnail']['error'];
        $thumbnailSize = $_FILES['thumbnail']['size'];

        echo "<h3>Thumbnail Info:</h3>";
        echo "Original Name: " . htmlspecialchars($_FILES['thumbnail']['name']) . "<br>";
        echo "Size: $thumbnailSize bytes<br>";
        echo "- Temp Name: $thumbnailTmpName<br>";
        
        $allowedMIMETypes = array(
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/tiff',     
            'image/vnd.microsoft.icon', 'image/svg+xml', 'image/heif', 'image/heic', 'image/jp2', 'image/avif',     
            'image/x-icon', 'image/apng'
        );

        if ($thumbnailError !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading thumbnail.";
        } elseif ($thumbnailSize > $maxSize) {
            $errors[] = "Thumbnail " . basename($_FILES['thumbnail']['name']) . " exceeds 10MB size limit.";
        } else {
            $fileMIMEType = $_FILES['thumbnail']['type'];
            echo "- Detected MIME Type: $fileMIMEType<br>";
            
            if (!in_array($fileMIMEType, $allowedMIMETypes)) {
                $errors[] = "Invalid file type for thumbnail. Only image files are allowed.";
            } else {
                $thumbnailPath = $uploadDir . "thumbnail_" . $fileTimestamp . '_' . basename($_FILES['thumbnail']['name']);
            }
        }
    }

    // attachment upload validation
    $validAttachments = [];
    if (!empty($_FILES['attachments']['name'][0])) {
        echo "<h3>Attachments:</h3>";
        foreach ($_FILES['attachments']['name'] as $index => $name) {
            $tmpName = $_FILES['attachments']['tmp_name'][$index];
            $size = $_FILES['attachments']['size'][$index];
            $error = $_FILES['attachments']['error'][$index];

            echo "Attachment: $name<br>";
            echo "- Size: $size bytes<br>";
            echo "- Temp Name: $tmpName<br>";

            if ($error !== UPLOAD_ERR_OK) {
                $errors[] = "Error uploading attachment: $name.";
            } elseif ($size > $maxSize) {
                $errors[] = "Attachment $name exceeds 10MB size limit.";
            } else {
                $uniqueName = "attachment_" . time() . '_' . basename($name);
                $filePath = $uploadDir . $uniqueName;

                $validAttachments[] = [
                    "originalName" => $name,
                    "tmpName" => $tmpName,
                    "filePath" => $filePath,
                    "uniqueName" => $uniqueName
                ];

                echo "- Unique Name: $uniqueName<br>";
                echo "- Save Path: $filePath<br><br>";
            }
        }
    }

    // checks for errors
    if (empty($errors)) {
        echo "<h3 style='color:green;'>All data validated. Ready for database upload.</h3><br>";
        echo "<h3>Uploads Ready</h3>";

        if (isset($thumbnailPath)) {
            echo "Thumbnail would be moved from: " . $_FILES['thumbnail']['tmp_name'] . " to $thumbnailPath<br>";
        }

        foreach ($validAttachments as $validAttachment) {
            echo "Attachment would be moved from: " . $validAttachment['tmpName'] . " to " . $validAttachment['filePath'] . "<br>";
        }
    }

    if (empty($errors)) {
        echo "<h3>DB Insertion Preview</h3>";
        echo "Inserting announcement with:<br>";
        echo "- Title: $title<br>";
        echo "- Body: $description<br>";
        echo "- Privacy: $privacy<br>";
        echo "- Category: $category<br>";
        echo "- Author: $author<br>";
        echo "- Post Date: $post_date<br>";
        echo "- Thumbnail: $thumbnailPath<br><br>";
        echo "<h3>Attachment Insertions:</h3>";
        
        foreach ($validAttachments as $validAttachment) {
            echo "Inserting attachment:<br>";
            echo "- Announcement ID: (after insert)<br>";
            echo "- File Path: " . $validAttachment['filePath'] . "<br>";
            echo "- File Name: " . $validAttachment['uniqueName'] . "<br>";
            echo "- Upload Date: $post_date<br><br>";
        }
    } else {
        echo "<h3 style='color:red;'>Upload to database stopped due to errors.</h3><br>";
        echo "<h3>Errors:</h3>";
        foreach ($errors as $err) {
            echo "<p style='color:red;'>$err</p><br>";
        }
    }
}
?>