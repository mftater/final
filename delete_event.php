<?php

include('db_connect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $event_id = intval($_POST['id']);

    $stmt = $link->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param('i', $event_id);

    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
 }


$link->close();
?>