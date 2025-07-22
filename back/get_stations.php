<?php
// Подключение к базе данных
include "database.php";
include 'session_check.php';
header('Content-Type: application/json');
if (isset($_GET['firm_id'])) {
    $firm_id = $_GET['firm_id'];

// Запрос для получения фирм и суммы их продаж
    $station_query = "
        SELECT s.name, s.city, s.street, s.house, SUM(sale.price) AS price, COUNT(DISTINCT sale.id_client) AS clients
        FROM station s
        JOIN fuel ON fuel.id_station = s.id
        JOIN fuel_column fc ON fc.id_fuel = fuel.id
        JOIN sale ON sale.id_fuel_column = fc.id
        WHERE s.id_firm = ?
        GROUP BY s.id
        ORDER BY price DESC
    ";
    $stmt = $link->prepare($station_query);
    $stmt->bind_param('i', $firm_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Массив для хранения данных о фирмах
    $station = [];
    while ($row = $result->fetch_assoc()) {
        $station[] = $row;
    }

    // Закрытие соединения с базой данных
    $link->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Неверный firm_id']);
}
echo json_encode($station);
?>
