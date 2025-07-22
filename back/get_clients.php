<?php
// Подключение к базе данных
include "database.php";
include 'session_check.php';
// Запрос для получения фирм и суммы их продаж
$client_query = "
    SELECT client.email As email, COUNT(sale.id) AS purchase_count, SUM(sale.price) AS price
    FROM sale
    JOIN client ON client.id = sale.id_client
    GROUP BY client.id
    ORDER BY purchase_count DESC
";

$result = $link->query($client_query);

// Массив для хранения данных о фирмах
if ($result->num_rows > 0) {
    $clients = [];
    
    // Получаем данные и сохраняем их в массив
    while ($row = $result->fetch_assoc()) {
        $clients[] = [
            'email' => $row['email'],
            'purchase_count' => $row['purchase_count'],
            'price' => $row['price']
        ];
    }
    
    // Возвращаем данные в формате JSON
    echo json_encode($clients);
} else {
    echo json_encode([]);
}

$link->close();
?>
