<?php
session_start();
include 'db_connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT fc.id, fc.user_id, fc.family_member_id, fc.relationship, fc.status
          FROM family_connections fc
          WHERE fc.family_member_id = ? AND fc.status = 'pending'";

$stmt = $link->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pendingRequests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($pendingRequests);