<?php
// Подключаемся к базе данных
include 'database.php';
include 'session_check.php';
// session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['client_id'])) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit();
}

$user_id = $_SESSION['client_id']; // Получаем user_id из сессии

$email = $_SESSION['client_email'];

// Получаем историю покупок пользователя
$query = "SELECT price, number_of_liter, sale.date, ft.name AS fuel_type, firm.name AS firm
FROM sale 
JOIN fuel_column fc ON fc.id = sale.id_fuel_column
JOIN fuel ON fc.id_fuel=fuel.id
JOIN fuel_type ft ON ft.id=fuel.id_type
JOIN station s ON s.id = fuel.id_station
JOIN firm ON firm.id = s.id_firm
WHERE sale.id_client = ? ORDER BY date DESC";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$purchases_result = $stmt->get_result();



$purchases = [];
while ($purchase = $purchases_result->fetch_assoc()) {
    $purchases[] = [
        'date' => $purchase['date'],
        'fuel_type' => $purchase['fuel_type'],
        'number_of_liter' => $purchase['number_of_liter'],
        'price' => $purchase['price'],
        'firm' => $purchase['firm']
    ];
}

// Закрытие соединения с базой данных
$link->close();

// Возвращаем данные в формате JSON
echo json_encode([
    'email' => $email,
    'purchases' => $purchases
]);
?>