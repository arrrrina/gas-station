<?php
include "database.php";
include 'session_check.php';
// session_start();
header('Content-Type: application/json');
if (isset($_GET['columnId'])) {
    $columnId = $_GET['columnId'];

    // Подготовка запроса с параметром
    $stmt = $link->prepare("
        SELECT f.id, ft.name
        FROM fuel_column fc
        JOIN fuel f ON fc.id_fuel = f.id
        JOIN fuel_type ft ON f.id_type = ft.id
        WHERE fc.id_column = ?
    ");

    // Привязка параметра
    $stmt->bind_param('i', $columnId);

    // Выполнение запроса
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result) {
        $fuel = [];
        while ($row = $result->fetch_assoc()) {
            $fuel[] = $row;
        }
        echo json_encode(['success' => true, 'fuel' => $fuel]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка выполнения запроса']);
    }

    // Закрытие подготовленного запроса
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Неверный columnId']);
}

$link->close();
?>
