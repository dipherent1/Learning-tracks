<?php
    // --- SETTINGS ---
    $is_logged_in = true; // Change this to false to see what happens!
    $username = "Admin";
    // --- END SETTINGS ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Page (Simple Server)</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f9; }
        .message { padding: 20px; border-radius: 5px; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <?php
        if ($is_logged_in == true) {
            // If the user IS logged in, show this block
            echo "<div class='message success'>";
            echo "<h1>Welcome back, " . $username . "!</h1>";
            echo "</div>";
        } else {
            // If the user IS NOT logged in, show this block
            echo "<div class='message error'>";
            echo "<h1>Welcome, Guest!</h1>";
            echo "<p>Please log in to continue.</p>";
            echo "</div>";
        }
    ?>

</body>
</html>