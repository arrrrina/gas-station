<?php

include 'database.php'; 
include 'session_check.php';
// session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $columnId = $_POST['columnId'];
    if (!$columnId) {
        echo json_encode(['success' => false, 'error' => 'Column ID is required']);
        exit;
    }

    $query = "UPDATE `column` 
    SET status = 'free'
    WHERE id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $columnId);
    

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update column status']);
    }


    $link->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>