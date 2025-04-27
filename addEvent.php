<?php

include('db_connect.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./loginPage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  
    $event_title = $_POST['event_title'];
    $event_desc = $_POST['event_desc'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_share = isset($_POST['share_event']) ? $_POST['share_event'] : false;  
    $family_members = isset($_POST['family_members']) ? $_POST['family_members'] : [];  

    if (!empty($event_title) && !empty($event_date) && !empty($event_time)) {
        // Insert event into the database
        $query = "INSERT INTO events (user_id, title, description, event_date, event_time, added_by) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $link->prepare($query)) {
            $stmt->bind_param("issssi", $user_id, $event_title, $event_desc, $event_date, $event_time, $user_id);
            
            
            if ($stmt->execute()) {
                $event_id = $stmt->insert_id;  
                if ($event_share && !empty($family_members)) {
                    foreach ($family_members as $family_member_id) {
                        
                        $query = "INSERT INTO shared_events (event_id, user_id) VALUES (?, ?)";
                        $stmt = $link->prepare($query);
                        $stmt->bind_param("ii", $event_id, $family_member_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                }

             
                echo "<script>
                        alert('New Event Added');
                        window.location.href = './showcalendar_inJS.php';
                      </script>";
                exit();
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Error preparing the SQL query.</p>";
        }
    } else {
        echo "<p>All fields are required.</p>";
    }
}
?>