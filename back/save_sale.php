<?php

include 'database.php'; 
include 'session_check.php';
// session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fuelId = $_POST['fuelId'];
    $columnId = $_POST['columnId'];
    $liters = $_POST['liters'];
    $totalPrice = $_POST['totalPrice'];
    if (!$fuelId || !$liters || !$totalPrice || !$columnId) {
        echo json_encode(['success' => false, 'error' => 'Column ID is required']);
        exit;
    }
    // $stmt = $link->prepare('
    // UPDATE fuel
    // SET number_of_liters = number_of_liters - ?
    // WHERE id = ?
    // ');
    // $stmt->bind_param('ii', $liters, $fuelId);
    // $stmt->execute();

    $stmt = $link->prepare('
    SELECT id
    FROM fuel_column
    WHERE id_column = ? AND id_fuel = ?
    ');
    $stmt->bind_param('ii', $columnId, $fuelId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fuelColumn = $result->fetch_assoc();
    if ($fuelColumn) {
        $fuelColumnId = $fuelColumn['id'];
        $stmt = $link->prepare('
            INSERT INTO sale (date, price, number_of_liter, id_client, id_fuel_column)
            VALUES (NOW(), ?, ?, ?, ?)
        ');
        
        $stmt->bind_param('diii', $totalPrice, $liters, $_SESSION['client_id'], $fuelColumnId);
        $stmt->execute();

        // Ответ о том, что операция прошла успешно
        echo json_encode(['success' => true]);
    } else {
        // Если не найдено топливо для данной колонки, выводим ошибку
        echo json_encode(['success' => false, 'error' => 'Не найдено соответствующее топливо для этой колонки']);
    }
    $stmt->close();
    $link->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>