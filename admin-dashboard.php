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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    // Handle updating contact info
    if (isset($_POST['update_contact_info'])) {
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("INSERT INTO contact_info (id, address, phone, email) VALUES (1, ?, ?, ?) ON DUPLICATE KEY UPDATE address = VALUES(address), phone = VALUES(phone), email = VALUES(email)");
        $stmt->bind_param('sss', $address, $phone, $email);

        if ($stmt->execute()) {
            $message = 'Contact information updated successfully!';
        } else {
            $message = 'Error updating contact information.';
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

    // Handle college photo upload
    if (isset($_FILES['college_photo']) && $_FILES['college_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['college_photo']['name']);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['college_photo']['tmp_name'], $uploadFile)) {
            $stmt = $conn->prepare("INSERT INTO college_photos (photo_path) VALUES (?)");
            $stmt->bind_param('s', $uploadFile);
            if ($stmt->execute()) {
                $message = 'College photo updated successfully!';
            } else {
                $message = 'Error saving photo to database.';
            }
            $stmt->close();
        } else {
            $message = 'Error uploading photo.';
        }
    }

    // Handle adding faculty
    if (isset($_POST['add_faculty'])) {
        $name = $_POST['name'];
        $photoPath = '';

        if (isset($_FILES['faculty_photo']) && $_FILES['faculty_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['faculty_photo']['name']);

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['faculty_photo']['tmp_name'], $uploadFile)) {
                $photoPath = $uploadFile;
            } else {
                $message = 'Error uploading faculty photo.';
            }
        }

        $stmt = $conn->prepare("INSERT INTO faculties (name, photo_path) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $photoPath);

        if ($stmt->execute()) {
            $message = 'Faculty added successfully!';
        } else {
            $message = 'Error adding faculty.';
        }
        $stmt->close();
    }

    // Handle removing faculty
    if (isset($_POST['remove_faculty'])) {
        $faculty_id = $_POST['faculty_id'];

        $stmt = $conn->prepare("DELETE FROM faculties WHERE id = ?");
        $stmt->bind_param('i', $faculty_id);

        if ($stmt->execute()) {
            $message = 'Faculty removed successfully!';
        } else {
            $message = 'Error removing faculty.';
        }
        $stmt->close();
    }
}

// Fetch existing faculties for removal
$faculties = [];
$result = $conn->query("SELECT * FROM faculties");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $faculties[] = $row;
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

// Fetch existing contact info
$contactInfo = [];
$result = $conn->query("SELECT * FROM contact_info WHERE id = 1");

if ($result && $row = $result->fetch_assoc()) {
    $contactInfo = $row;
} else {
    $contactInfo = [
        'address' => '',
        'phone' => '',
        'email' => ''
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - College Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php';?>
        <div class="admin-content">
            <h1>Welcome to the Admin Dashboard</h1>
            <p>Here you can manage the website content.</p>
            <?php if ($message): ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php endif; ?>
        <form action="admin-dashboard.php" method="post" style="display:inline;">
        </form>
    </td>
</tr>
                  
                </tbody>
            </table>

            <h2>Upload College Photo</h2>
            <form action="admin-dashboard.php" method="post" enctype="multipart/form-data">
                <label for="college_photo">Upload College Photo:</label>
                <input type="file" id="college_photo" name="college_photo" accept="image/*">
                <button type="submit">Upload Photo</button>
            </form>

            <h2>Add Faculty</h2>
            <form action="admin-dashboard.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="faculty_photo">Upload Photo:</label>
                <input type="file" id="faculty_photo" name="faculty_photo" accept="image/*">
                <button type="submit" name="add_faculty">Add Faculty</button>
            </form>

            <h2>Remove Faculty</h2>
            <form action="admin-dashboard.php" method="post">
                <label for="faculty_id">Select Faculty to Remove:</label>
                <select id="faculty_id" name="faculty_id">
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?php echo $faculty['id']; ?>"><?php echo htmlspecialchars($faculty['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="remove_faculty">Remove Faculty</button>
            </form>

          
        </div>
    </div>
</body>
</html>
<script>console.log($username);</script> 