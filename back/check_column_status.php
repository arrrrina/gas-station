<?php
include 'database.php'; 
include 'session_check.php';

$columnId = $_GET['columnId'];

if (!$columnId) {
    echo json_encode(['error' => 'Column ID is missing']);
    exit;
}

$query = $link->prepare("SELECT status FROM column WHERE id = ?");
$query->bind_param('i', $columnId);
$column = $query->fetch();

if ($column) {
    echo json_encode(['status' => $column['status']]);
} else {
    echo json_encode(['error' => 'Column not found']);
}
?>