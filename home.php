<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'HDC');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the alerts to be shown as popups
$result = $conn->query("SELECT content, image_path FROM alerts WHERE show_alert = TRUE");
$alerts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Himalaya Darshan College</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body.blurred {
    overflow: hidden;
}

.blur-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(1px);
    z-index: 999;
}

.alert-popup {
    background-color: rgba(0, 0, 0, 0.7); /* Transparent background */
    color: white;
    padding: 20px;
    position: fixed;
    top: 50%; /* Center vertically */
    left: 50%; /* Center horizontally */
    transform: translate(-50%, -50%);
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center; /* Center content horizontally */
    max-width: 90%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-height: 80%; /* Limit the height to prevent overflow */
    box-sizing: border-box; /* Include padding and border in element's total width and height */
    overflow: hidden; /* Hide overflow */
}

.alert-popup.text-only {
    background-color: #007BFF; /* Blue background for text-only alert */
}

.alert-popup img {
    max-width: 100%; /* Ensure the image fits within the popup */
    max-height: 100%; /* Ensure the image fits within the popup */
    height: auto; /* Maintain aspect ratio */
    width: auto; /* Maintain aspect ratio */
    margin-right: 10px;
    display: block; /* Ensure image is displayed correctly */
}

.alert-popup .text-only-content {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex: 1;
}

.alert-popup .close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    margin-left: 10px;
}

.alert-popup .image-only-content {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex: 1;
    background: transparent; /* No background color for image-only alert */
}

    </style>
</head>
<body>
    <?php if (!empty($alerts)): ?>
        <div class="blur-background"></div>
        <?php foreach ($alerts as $index => $alert): ?>
            <div class="alert-popup <?php echo empty($alert['image_path']) ? 'text-only' : 'image-only'; ?>" id="alert-<?php echo $index; ?>" style="display: <?php echo $index === 0 ? 'flex' : 'none'; ?>;">
                <?php if (!empty($alert['image_path'])): ?>
                    <div class="image-only-content">
                        <img src="<?php echo htmlspecialchars($alert['image_path']); ?>" alt="Alert Image">
                    </div>
                <?php else: ?>
                    <div class="text-only-content">
                        <span><?php echo htmlspecialchars($alert['content']); ?></span>
                    </div>
                <?php endif; ?>
                <button class="close-btn" onclick="closeAlert(<?php echo $index; ?>)">✖</button>
            </div>
        <?php endforeach; ?>
        <script>
            document.body.classList.add('blurred');
        </script>
    <?php endif; ?>

    <script>
        function closeAlert(index) {
            var currentAlert = document.getElementById('alert-' + index);
            currentAlert.style.display = 'none';

            var nextAlert = document.getElementById('alert-' + (index + 1));
            if (nextAlert) {
                nextAlert.style.display = 'flex';
            } else {
                document.querySelector('.blur-background').style.display = 'none';
                document.body.classList.remove('blurred');
            }
        }
    </script>

    <?php include 'header.php'; ?>
    <?php 
    $result = $conn->query("SELECT content FROM advertisements");
    $advertisement = $result->fetch_assoc();
    ?>
    
    <div class="marquee-container">
        <marquee class="marquee"><?php echo htmlspecialchars($advertisement['content']); ?></marquee>
    </div>

    <div class="content">
        <p>Welcome to Himalaya Darshan College. Established in 2070 B.S., the college provides innovative opportunities in a highly academic environment. We aim to promote value-based quality education at the graduate level and foster personal and professional growth through our experienced faculty, experts, and professionals.</p>

        <?php
        // Fetch the latest college photo
        $result = $conn->query("SELECT photo_path FROM college_photos ORDER BY upload_date DESC LIMIT 1");

        if ($result && $row = $result->fetch_assoc()) {
            $collegePhotoPath = $row['photo_path'];
        } else {
            $collegePhotoPath = 'default-photo.jpg'; 
        }

        // Fetch the faculties
        $faculties = [];
        $result = $conn->query("SELECT * FROM faculties LIMIT 10");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $faculties[] = $row;
            }
        }

        $conn->close();
        ?>

        <img src="<?php echo htmlspecialchars($collegePhotoPath); ?>" alt="College Photo" class="college-photo">
        
        <h2>Faculty</h2>
        <div class="faculties">
            <?php foreach ($faculties as $faculty): ?>
                <div class="faculty">
                    <?php if ($faculty['photo_path']): ?>
                        <img src="<?php echo htmlspecialchars($faculty['photo_path']); ?>" alt="<?php echo htmlspecialchars($faculty['name']); ?> Photo" class="faculty-photo">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($faculty['name']); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
