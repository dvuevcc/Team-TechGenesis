<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page if not logged in
    header('Location: /html/login.php');
    exit;
}

// Display the user's name
echo 'Hello, ' . $_SESSION['user_name'] . '!';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
</head>
<body>
    <p>Welcome to the home page!</p>
    <a href="logout.php">Logout</a>
</body>
</html>