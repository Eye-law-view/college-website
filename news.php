<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'HDC');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all news
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
    <title>News - College Website</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .news-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        li:hover {
            background-color: #f9f9f9;
        }

        li:last-child {
            border-bottom: none;
        }

        p {
            margin: 0;
            color: #555;
        }

        small {
            color: #888;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="news-container">
        <h1>Latest News</h1>
        <?php if (!empty($news)): ?>
            <ul>
                <?php foreach ($news as $item): ?>
                    <li>
                        <p><?php echo htmlspecialchars($item['content']); ?></p>
                        <p><small>Published on: <?php echo htmlspecialchars($item['publish_date']); ?></small></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No news available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
