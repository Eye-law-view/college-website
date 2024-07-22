<?php
session_start();

if (isset($_SESSION['admin'])) {
    header('Location: admin-dashboard.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['admin'] = true;
        header('Location: admin-dashboard.php');
        exit();
    } else {
        $message = 'Invalid credentials';
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Login Container */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #007BFF; /* Bright blue */
        }

        .login-box {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-box .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        .login-box h1 {
            margin-bottom: 20px;
            color: #007BFF; /* Bright blue */
            font-size: 24px;
        }

        .login-box .alert {
            color: #dc3545; /* Red */
            margin-bottom: 15px;
        }

        .login-box .form-group {
            margin-bottom: 15px;
        }

        .login-box .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .login-box .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
            color: #333;
            autocomplete: off;
        }

        .login-box .btn {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-box .btn:hover {
            background-color: #0056b3; /* Darker blue */
        }
    </style>
</head>
<body>
    <?php include "header.php" ?>
    <div class="login-container">
        <div class="login-box">
            <img src="logo.png" alt="College Logo" class="logo">
            <h1>Admin Login</h1>
            <?php if ($message): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="admin-login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" autocomplete="off" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <br><br>
            </form>        
        </div>
        <br>
    </div>
</body>
</html>
