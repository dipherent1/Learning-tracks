<?php

    $success_message = "";
    $error_message = "";

    if (isset($_GET["status"]) && $_GET["status"] === "success"){
        $success_message = "Thank you";
    }

    if (isset($_GET["status"]) && $_GET["status"] === "error"){
        $error_message = "Please fill out all the fields";
    }
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Contact Us</title>
        <style>
        body { font-family: sans-serif; color: #333; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .form-group { margin-bottom: 15px; } label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .success { color: #28a745; border: 1px solid #28a745; padding: 10px; border-radius: 4px; }
        .error { color: #dc3545; border: 1px solid #dc3545; padding: 10px; border-radius: 4px; }
    </style>
    </head>
    <body>
        <div>
            <h1>Contact Us</h1>
            <?php if($success_message) :?>
                <div class="success"><?= $success_message?></div>
            
            <?php elseif($error_message) :?>
                <div class="error"><?= $error_message?></div>
            <?php endif?>


            <form action="process-form.php" method="POST">
                <div class="form-group"><label for="n">Your Name</label><input id="n" type="text" name="full_name"></div>
                <div class="form-group"><label for="e">Your Email</label><input id="e" type="text" name="email_address"></div>
                <div class="form-group"><label for="m">Your Message</label><input id="m" type="text" rows="5" name="message"></div>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </body>
</html>