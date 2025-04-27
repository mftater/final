<?php
session_start();
require_once 'db_connect.php'; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use mysqli prepared statement
    $stmt = $link->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ./showcalendar_inJS.php");
        exit();
    } else {
        echo "Invalid login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="./darkmodescript/dark.css">
</head>
<body>
    
<div id="navbar"></div>
    <h2>Login</h2>
    <button id="darkModeToggle">Toggle Dark Mode</button>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
        <button onclick="window.location.href='./newUser.html'">Register</button>
    </form>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <script src="../homepage/script.js" ></script>
    <script src="./darkmodescript/dark.js"></script>
</body>
</html>