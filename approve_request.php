<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['confirm'])) {
    $request_id = $_POST['request_id'];
    $confirm = $_POST['confirm'];


    if ($confirm == "approve") {
        $status = "approved";
    } elseif ($confirm == "reject") {
        $status = "rejected";
    }

    $query = "UPDATE family_connections SET status = ? WHERE id = ? AND status = 'pending'";
    $stmt = $link->prepare($query);
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: ./showcalendar_inJS.php");
    exit();
} else {
    header("Location: ./showcalendar_inJS.php");
    exit();
}
?>