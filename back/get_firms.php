<?php
// Подключение к базе данных
include "database.php";
include 'session_check.php';
// Запрос для получения фирм и суммы их продаж
$firms_query = "
    SELECT firm.id AS firm_id, firm.name AS firm_name, COUNT(sale.id) AS total_sales, SUM(sale.price) AS price
    FROM sale 
    JOIN fuel_column fc ON fc.id = sale.id_fuel_column
    JOIN fuel ON fc.id_fuel=fuel.id
    JOIN station s ON s.id = fuel.id_station
    JOIN firm ON firm.id = s.id_firm
    GROUP BY firm.id
    ORDER BY total_sales DESC
";

$firms_result = $link->query($firms_query);


$firms = [];
while ($firm = $firms_result->fetch_assoc()) {
    $firms[] = $firm;
}


$link->close();

// Отправка данных в формате JSON
header('Content-Type: application/json');
echo json_encode($firms);
?>
