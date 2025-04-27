<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];


    if ($action == "approve") {
        $status = "approved";
    } elseif ($action == "reject") {
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