<?php
include('db_connect.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT events.id, events.title, events.description, events.event_date, events.event_time, users.username AS added_by
    FROM events
    JOIN users ON events.added_by = users.id
    WHERE events.user_id = ?

    UNION

    SELECT events.id, events.title, events.description, events.event_date, events.event_time, users.username AS added_by
    FROM events
    JOIN shared_events ON events.id = shared_events.event_id
    JOIN users ON events.added_by = users.id
    WHERE shared_events.user_id = ?
";
if ($stmt = $link->prepare($query)) {
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);
} else {
    echo json_encode(['error' => 'Query failed']);
}
?>