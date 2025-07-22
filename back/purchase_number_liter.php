<?php
include "database.php";
include 'session_check.php';
// session_start();
header('Content-Type: application/json');
if (isset($_GET['fuelId'])) {
    $fuelId = $_GET['fuelId'];

    // Подготовка запроса с параметром
    $stmt = $link->prepare("
        SELECT f.number_of_liters
        FROM fuel f
        WHERE f.id = ?
    ");

    // Привязка параметра
    $stmt->bind_param('i', $fuelId);

    // Выполнение запроса
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $liter = $result->fetch_assoc();
        echo json_encode(['success' => true, 'number_of_liters' => $liter['number_of_liters'], 'min' => 0]);
    }
    else {
        echo json_encode(['success' => false, 'error' => 'Ошибка выполнения запроса']);
    }
// Закрытие подготовленного запроса
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Неверный fuelId']);
}

$link->close();
?>
