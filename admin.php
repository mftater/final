<?php
require_once 'db_connect.php';
session_start();

$user_id = $_SESSION['user_id'];
$stmt = $link->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['is_admin']) {
    echo "You are an admin!";
} else {
    echo "Access denied.";
}
?>