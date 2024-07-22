<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - College Website</title>
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .gallery-item {
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            /* Ensure the container does not exceed the viewport width */
            max-width: 100%;
        }
        .gallery-item img {
            display: block;
            width: 100%;
            height: auto; /* Maintain aspect ratio */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <h1 class="text-center">College Gallery</h1>
        <div class="gallery">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'HDC');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch all college photos
            $result = $conn->query("SELECT photo_path FROM college_photos ORDER BY upload_date DESC");

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="gallery-item">';
                    echo '<img src="' . htmlspecialchars($row['photo_path']) . '" alt="College Photo">';
                    echo '</div>';
                }
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
