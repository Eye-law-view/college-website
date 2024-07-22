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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_alert_id'])) {
        $alertId = $_POST['delete_alert_id'];

        // Delete the alert from the database
        $stmt = $conn->prepare("DELETE FROM alerts WHERE id = ?");
        $stmt->bind_param('i', $alertId);

        if ($stmt->execute()) {
            $message = 'Alert deleted successfully!';
        } else {
            $message = 'Error deleting alert.';
        }

        $stmt->close();
    }

    if (isset($_POST['update_alerts'])) {
        // Reset all alerts to not show
        $conn->query("UPDATE alerts SET show_alert = 0");

        // Update alerts based on checkbox selections
        if (isset($_POST['alerts'])) {
            foreach ($_POST['alerts'] as $alertId => $showAlert) {
                $showAlert = $showAlert == 'on' ? 1 : 0;
                $stmt = $conn->prepare("UPDATE alerts SET show_alert = ? WHERE id = ?");
                $stmt->bind_param('ii', $showAlert, $alertId);
                $stmt->execute();
                $stmt->close();
            }

            $message = 'Alerts updated successfully!';
        } else {
            $message = 'No alerts selected for update.';
        }
    }
}

// Fetch all alerts
$result = $conn->query("SELECT * FROM alerts");
$alerts = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Alerts - Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-apply {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-apply:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .alert-image {
            max-width: 150px; /* Adjust as needed */
            max-height: 100px; /* Adjust as needed */
            object-fit: cover;
            display: block;
            margin: 0 auto; /* Center the image */
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>
      
        <div class="admin-content">
            <h1>Manage Alerts</h1>
            <?php if (isset($message)): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="manage-alerts.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Alert Text</th>
                            <th>Image</th>
                            <th>Show Alert</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alerts as $alert): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alert['content']); ?></td>
                                <td>
                                    <?php if ($alert['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($alert['image_path']); ?>" class="alert-image" alt="Alert Image">
                                    <?php else: ?>
                                        <span>null</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="alerts[<?php echo $alert['id']; ?>]" <?php echo $alert['show_alert'] ? 'checked' : ''; ?>>
                                </td>
                                <td>
                                    <button type="submit" name="delete_alert_id" value="<?php echo $alert['id']; ?>" class="btn-delete">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="update_alerts" class="btn-apply">Apply Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
