<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin-login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'HDC');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alertText = $_POST['alert_text'];
    $imagePath = null;

    // Handle file upload
    if (isset($_FILES['alert_image']) && $_FILES['alert_image']['error'] == 0) {
        // Check if the file is an image
        $check = getimagesize($_FILES['alert_image']['tmp_name']);
        if ($check !== false) {
            // Validate file size
            if ($_FILES['alert_image']['size'] > 500000) { // 500KB
                $message = 'File is too large.';
            } else {
                // Validate file extension
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $imageFileType = strtolower(pathinfo($_FILES['alert_image']['name'], PATHINFO_EXTENSION));
                if (!in_array($imageFileType, $allowedExtensions)) {
                    $message = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
                } else {
                    // Use the file name as the path
                    $imagePath = 'alert-images/' . basename($_FILES['alert_image']['name']);
                }
            }
        } else {
            $message = 'File is not an image.';
        }
    }

    if (!empty($alertText)) {
        // Prepare and execute the statement
        $stmt = $conn->prepare("INSERT INTO alerts (content, image_path, show_alert) VALUES (?, ?, FALSE)");
        if ($stmt === false) {
            $message = 'Error preparing statement: ' . $conn->error;
        } else {
            // Bind parameters and execute
            $stmt->bind_param('ss', $alertText, $imagePath); // 's' for string

            if ($stmt->execute()) {
                $message = 'Alert added successfully!';
            } else {
                $message = 'Error adding alert: ' . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = 'Alert text cannot be empty.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Alert</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="container">
    <h1>Add Alert</h1>
    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form action="add-alert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="alert_text">Alert Text</label>
            <textarea id="alert_text" name="alert_text" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="alert_image">Alert Image</label>
            <input type="file" id="alert_image" name="alert_image" class="form-control">
        </div>
        <button type="submit" class="btn">Add Alert</button>
    </form>
</div>
</body>
</html>
