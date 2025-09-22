<?php
    $success_message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $name = $_POST["full_name"];
        $email = $_POST["email_address"];
        $message = $_POST["message"];
    }
    $success_message = "Thank You" . htmlspecialchars($name). "! Your message has been recieved";
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <title>Contact Us</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Important for padding and width */
        }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: #28a745; border: 1px solid #28a745; padding: 10px; border-radius: 4px; margin-top: 20px; }
    </style>
</head>

<body>
    <div class="container">
        <h1>Contact Us</h1>

        <?php if (!$success_message) : ?>
            <div class="success"><?= $success_message; ?></div>
        
        <?php else : ?>
            <form action="" method="POST">
                <div>
                    <label for="name_input">Your Name</label>
                    <input id="name_input" type="text" name="full_name" required>
                </div>
                <div>
                    <label for="email_input">Email</label>
                    <input id="email_input" type="text" name="email_address" required>
                </div>
                <div>
                    <label for="message_input">Message</label>
                    <input id="message_input" type="text" name="message_input" rows="5" required>
                </div>

                <button type="submit">Send Message</button>
            </form>
            <?php endif; ?>


    </div>
</body>
</html>