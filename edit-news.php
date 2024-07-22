<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin-login.php');
    exit();
}

$message = '';

$conn = new mysqli('localhost', 'root', '', 'HDC');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle news update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_news'])) {
        $newsContent = $_POST['news'];

        $stmt = $conn->prepare("INSERT INTO news (content) VALUES (?)");
        $stmt->bind_param('s', $newsContent);

        if ($stmt->execute()) {
            $message = 'News added successfully!';
        } else {
            $message = 'Error adding news.';
        }
        $stmt->close();
    }

    // Handle removing news
    if (isset($_POST['remove_news'])) {
        $news_id = $_POST['news_id'];

        $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
        $stmt->bind_param('i', $news_id);

        if ($stmt->execute()) {
            $message = 'News removed successfully!';
        } else {
            $message = 'Error removing news.';
        }
        $stmt->close();
    }

    // Handle updating news
    if (isset($_POST['edit_news'])) {
        $news_id = $_POST['news_id'];
        $newsContent = $_POST['news'];

        $stmt = $conn->prepare("UPDATE news SET content = ? WHERE id = ?");
        $stmt->bind_param('si', $newsContent, $news_id);

        if ($stmt->execute()) {
            $message = 'News updated successfully!';
        } else {
            $message = 'Error updating news.';
        }
        $stmt->close();
    }
}

// Fetch existing news
$news = [];
$result = $conn->query("SELECT * FROM news ORDER BY publish_date DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
     
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .news-actions form {
            display: inline;
        }
        .news-edit-input {
            width: calc(100% - 120px);
            display: inline-block;
        }
        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>Edit News</h1>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Add News Section -->
        <h2>Add News</h2>
        <form action="edit-news.php" method="post">
            <label for="news">News Content:</label>
            <textarea id="news" name="news" rows="10" cols="50"></textarea>
            <button type="submit" name="update_news" class="btn-primary">Add News</button>
        </form>

        <!-- Manage News Section -->
        <h2>Manage News</h2>
        <table>
            <thead>
                <tr>
                    <th>News Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $news_item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($news_item['content']); ?></td>
                        <td class="news-actions">
                            <div class="btn-group">
                                <form action="edit-news.php" method="post">
                                    <input type="hidden" name="news_id" value="<?php echo $news_item['id']; ?>">
                                    <button type="submit" name="remove_news" class="btn-danger">Remove</button>
                                </form>
                                <form action="edit-news.php" method="post">
                                    <input type="hidden" name="news_id" value="<?php echo $news_item['id']; ?>">
                                    <input type="text" name="news" value="<?php echo htmlspecialchars($news_item['content']); ?>" required class="news-edit-input">
                                    <button type="submit" name="edit_news" class="btn-primary">Edit</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
