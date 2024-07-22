<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - College Website</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        .contact-info p {
            margin: 10px 0;
            font-size: 16px;
        }
        
        .contact-info a {
            color: #fffff;
            text-decoration: none;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }

        .contact-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .contact-form h2 {
            margin-top: 0;
            font-size: 22px;
        }

        .contact-form .form-group {
            margin-bottom: 15px;
        }

        .contact-form .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .contact-form .form-group input,
        .contact-form .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .contact-form .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .contact-form .btn {
            background-color: #007BFF;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .contact-form .btn:hover {
            background-color: #0056b3;
        }

        .social-media {
            margin-top: 20px;
        }

        .social-media a {
            text-decoration: none;
            color: #007BFF;
            margin-right: 10px;
        }

        .social-media a:hover {
            text-decoration: underline;
        }

        .map {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="content">
        <h1>Contact Us</h1>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'HDC');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch contact information
        $contactInfo = [];
        $result = $conn->query("SELECT * FROM contact_info WHERE id = 1");

        if ($result && $row = $result->fetch_assoc()) {
            $contactInfo = $row;
        } else {
            $contactInfo = [
                'address' => 'No address available',
                'phone' => 'No phone number available',
                'email' => 'No email available',
                'social_facebook' => '',
                'social_twitter' => '',
                'social_instagram' => '',
                'map_iframe' => '' // Add a map iframe URL if available
            ];
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Message sent successfully!</p>";
            } else {
                echo '<p class="alert alert-danger">There was an error sending your message.</p>';
            }
            $stmt->close();
        }

        $conn->close();
        ?>

        <div class="contact-info">
            <p><strong>Address:</strong> <?php echo htmlspecialchars($contactInfo['address']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($contactInfo['phone']); ?></p>
            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($contactInfo['email']); ?>"><?php echo htmlspecialchars($contactInfo['email']); ?></a></p>
        </div>

        <div class="contact-form">


        <div class="container mt-4">
        <h2>Send Us a Message</h2>
        <form id="contactForm" action="contact-us.php" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name">
                <div class="error" id="nameError"></div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
                <div class="error" id="emailError"></div>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message"></textarea>
                <div class="error" id="messageError"></div>
            </div>
            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            // Prevent form submission
            event.preventDefault();

            // Clear previous error messages
            document.getElementById('nameError').textContent = '';
            document.getElementById('emailError').textContent = '';
            document.getElementById('messageError').textContent = '';

            // Get form values
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var message = document.getElementById('message').value.trim();

            // Validate form fields
            var valid = true;

            if (name === '') {
                document.getElementById('nameError').textContent = 'Name is required.';
                valid = false;
            } else if (!validateUsername(name)) {
                document.getElementById('nameError').textContent = 'Invalid username. Only letters and underscores are allowed.';
                valid = false;
            }

            if (email === '') {
                document.getElementById('emailError').textContent = 'Email is required.';
                valid = false;
            } else if (!validateEmail(email)) {
                document.getElementById('emailError').textContent = 'Invalid email format.';
                valid = false;
            }

            if (message === '') {
                document.getElementById('messageError').textContent = 'Message is required.';
                valid = false;
            }

            // If valid, submit the form
            if (valid) {
                this.submit();
            }
        });

        function validateUsername(username) {
            // name maa letter ra space matra
            var re = /^[a-zA-Z\s]+$/;
            return re.test(username);
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>


</body>
</html>
