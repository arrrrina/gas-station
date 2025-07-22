<?php
include "database.php";
include 'session_check.php';

    $viewedStations = isset($_COOKIE['viewedStations']) ? json_decode($_COOKIE['viewedStations'], true) : [];
    if (!empty($viewedStations)) {
        $escapedIds = array_map('intval', $viewedStations);
        $placeholders = implode(',', $escapedIds); 
        $query = "SELECT id, name, city, street, house FROM station WHERE id IN ($placeholders)";
        $result = $link->query($query);
        
        if ($result) {
            $stations = [];
            while ($row = $result->fetch_assoc()) {
                $stations[] = $row;
            }
            echo json_encode(['success' => true, 'viewedStations' => $stations]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Ошибка выполнения запроса: ' . $link->error]);
        }
    } else {
        echo json_encode(['success' => true, 'viewedStations' => []]);
    }


$link->close();
?>
