<?php
include "database.php";
include 'session_check.php';
// session_start();
if (isset($_GET['fuelId'])) {
    $fuelId = $_GET['fuelId'];

    // Подготовка запроса с параметром
    $stmt = $link->prepare("
        SELECT price_per_liter 
        FROM fuel 
        WHERE id = ?
    ");
    $stmt->bind_param('i', $fuelId); // Привязка параметра
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'price_of_liter' => $row['price_per_liter']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Топливо не найдено']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'fuelId не указан']);
}

$link->close();
?>
