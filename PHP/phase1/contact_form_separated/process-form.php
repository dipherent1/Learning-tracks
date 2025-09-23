<?php
var_dump(value: $_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST["full_name"];
    $email= $_POST["email_address"];
    $message = $_POST["message"];

echo"".$name."".$email."".$message."";
if (empty($name) || empty($email) || empty($message)){
    header('Location: index.php?status=error');
    exit;
}

header('Location: index.php?status=success');
exit;
} else{
    header("Location: index.php");
    exit;
}
?>