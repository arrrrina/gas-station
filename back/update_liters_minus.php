<?php

include 'database.php'; 
include 'session_check.php';

if (isset($_POST['liters']) && isset($_POST['fuelId'])) {
    $liters = (int)$_POST['liters'];  // Преобразуем в целое число для безопасности
    $fuelId = (int)$_POST['fuelId'];

    // Проверка на обязательные параметры
    if (!$liters || !$fuelId) {
        echo json_encode(['success' => false, 'error' => 'Column ID и Fuel ID обязательны']);
        exit;
    }
    $stmt = $link->prepare('
    UPDATE fuel
    SET number_of_liters = number_of_liters - ?
    WHERE id = ?
    ');
    $stmt->bind_param('ii', $liters, $fuelId);
    $stmt->execute();
    echo json_encode(['success' => true]);
    $link->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
