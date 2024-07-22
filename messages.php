<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: messages.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
          
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #007BFF;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e0e0e0;
        }

        table td {
            word-break: break-word;
        }

        .delete-btn {
            color: #dc3545;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }

        .delete-btn:hover {
            text-decoration: underline;
        }

        .go-to-dashboard-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 15px 25px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
        }

        .go-to-dashboard-btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .go-to-dashboard-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>

    <div class="admin-container">
        <div class="admin-content">
            <h1>Messages</h1>

            <?php
            // Handle deletion
            if (isset($_GET['delete'])) {
                $id = intval($_GET['delete']);
                $conn = new mysqli('localhost', 'root', '', 'HDC');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    echo "<p style='color: red;'>Message deleted successfully!</p>";

                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
                $conn->close();

                // Refresh page after deletion
                
            }

            $conn = new mysqli('localhost', 'root', '', 'HDC');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $result = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");

            if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['message']); ?></td>
                                <td><?php echo htmlspecialchars($row['sent_at']); ?></td>
                                <td>
                                    <a href="?delete=<?php echo htmlspecialchars($row['id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No messages found.</p>
            <?php endif;

            $conn->close();
            ?>
        </div>
    </div>

   
</body>
</html>
