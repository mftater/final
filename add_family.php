<?php
    session_start();
    include 'db_connect.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $family_member_id = $_POST['family_member_id'];
        $relationship = $_POST['relationship'];
        $user_id = $_SESSION['user_id'];

        $stmt = $link->prepare("INSERT INTO family_connections (user_id, family_member_id, relationship, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iis", $user_id, $family_member_id, $relationship);

        if($stmt->execute()){
            header("Location: ./showcalendar_inJS.php?message=request_sent");
            exit();
        } else {
            echo "Error" . $stmt->error;
        }
        $stmt->close();
    } else {
      
        exit();
    }

?>