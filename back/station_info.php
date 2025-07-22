<?php
include "database.php";
include 'session_check.php';
// session_start();
if (isset($_GET['id'])) {
    $station_id = $_GET['id'];  // Получаем id из параметра URL
 

    // Проверяем успешность подключения
    if ($link->connect_error) {
        die("Ошибка подключения: " . $link->connect_error);
    }

    // Защищаем от SQL инъекций
    $station_id = $link->real_escape_string($station_id);

    // Выполняем запрос к базе данных для получения информации о станции
    $result = $link->query("SELECT * FROM station WHERE id = '$station_id'");
    
    // Проверяем, нашли ли станцию по переданному id
    if ($result->num_rows > 0) {
        $station = $result->fetch_assoc();
        
        // Отправляем данные о станции в формате JSON
        echo json_encode([
            'success' => true,
            'station' => [
                'name' => $station['name'],
                'city' => $station['city'],
                'street' => $station['street'],
                'house' => $station['house'],
                'openning_time' => $station['openning_time'],
                'ending_time' => $station['ending_time'],
                'phone_number'=> $station['phone_number']
            ]
        ]);
        $viewedStations = isset($_COOKIE['viewedStations']) ? json_decode($_COOKIE['viewedStations'], true) : [];
        $viewedStations[] = $station_id;
        $viewedStations = array_unique($viewedStations);
        setcookie('viewedStations', json_encode($viewedStations), time() + (86400 * 30), '/');
    } else {
        echo json_encode(['success' => false, 'error' => 'Станция не найдена.']);
    }

    
    $link->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Не передан id станции.']);
}
?>