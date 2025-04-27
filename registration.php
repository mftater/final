<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        die('Please fill in all fields.');
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format.');
    }

    // Hash the password before storing it
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $checkStmt = $link->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email); // Binding variables
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        die('Username or email already exists.');
    }

    // Insert new user
    try {
        $stmt = $link->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $passwordHash); // Binding variables
        $stmt->execute();
        $stmt->close();

        // Redirect to login page after successful registration
        echo "<script>
                alert('User registered!');
                window.location.href = './loginPage.php'; // Redirect to login page
              </script>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>