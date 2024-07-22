<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin-login.php');
    exit();
}

$message = '';

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'HDC');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $advertisementText = $_POST['advertisement_text'];
    
    if (!empty($advertisementText)) {
        // Delete the old advertisement
        $conn->query("DELETE FROM advertisements");

        // Insert the new advertisement
        $stmt = $conn->prepare("INSERT INTO advertisements (content) VALUES (?)");
        $stmt->bind_param('s', $advertisementText);

        if ($stmt->execute()) {
            $message = 'Advertisement updated successfully!';
        } else {
            $message = 'Error updating advertisement.';
        }

        $stmt->close();
    } else {
        $message = 'Advertisement text cannot be empty.';
    }
}

// Fetch the current advertisement
$result = $conn->query("SELECT content FROM advertisements LIMIT 1");
$advertisement = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Advertisement</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .advertisement-container {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .advertisement-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .advertisement-container textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .advertisement-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .advertisement-container button:hover {
            background-color: #0056b3;
        }

        .message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="admin-container">
        <div class="advertisement-container">
            <h1>Manage Advertisement</h1>
            <?php if ($message): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form action="advertisement.php" method="post">
                <textarea name="advertisement_text"><?php echo htmlspecialchars($advertisement['content'] ?? ''); ?></textarea>
                <button type="submit">Save Advertisement</button>
            </form>
        </div>
    </div>
</body>
</html>
