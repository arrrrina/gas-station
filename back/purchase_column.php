<?php
include "database.php";
include 'session_check.php';
// session_start();
header('Content-Type: application/json');
$stationId = isset($_GET['stationId']) ? intval($_GET['stationId']) : 0;


if ($stationId > 0) {
    // Запрос для получения свободных колонок
    $query = "
    SELECT id, number 
    FROM `column`
    WHERE id_station = ? AND status = 'free'";
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row;
        }
        echo json_encode(['success' => true, 'columns' => $columns]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка выполнения запроса']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Неверный stationId']);
}
$link->close();
?>

