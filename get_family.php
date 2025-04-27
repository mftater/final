<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]); // If not logged in, return an empty array
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch user_id of family members
$query = "SELECT user_id 
          FROM family_connections 
          WHERE family_connections.family_member_id = ? AND status = 'approved'";  // Only show approved connections
$stmt = $link->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$family_members = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($family_members);
?>