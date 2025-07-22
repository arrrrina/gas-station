<?php

include 'database.php'; 
include 'session_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['columnId']) && isset($_POST['fuelId']) && isset($_POST['liters'])) {
    $columnId = (int)$_POST['columnId'];  // Преобразуем в целое число для безопасности
    $fuelId = (int)$_POST['fuelId'];
    $liters = (int)$_POST['liters'];

    // Проверка на обязательные параметры
    if (!$columnId || !$fuelId || !$liters) {
        echo json_encode(['success' => false, 'error' => 'Column ID и Fuel ID обязательны']);
        exit;
    }

    try {
        $link->begin_transaction();
        $query = "
        SELECT c.id AS column_id, c.status AS column_status, f.number_of_liters AS number_of_liters
        FROM `column` c
        JOIN fuel_column fc ON c.id = fc.id_column
        JOIN fuel f ON fc.id_fuel = f.id
        WHERE fc.id_column = ? AND fc.id_fuel = ? AND c.status = 'free'
        ";
        $stmt = $link->prepare($query);
        $stmt->bind_param('ii', $columnId, $fuelId);
        $stmt->execute();
        $result = $stmt->get_result();
        $column = $result->fetch_assoc();  // Получаем первую строку

        if (!$column ) {
            $link->rollback();
            echo json_encode(['success' => false, 'error' => 'Колонка не найдена или уже занята']);
            exit;
        }

        $number_of_liters = $column['number_of_liters'];

        
            // Обновление статуса колонки
            $updateQuery = "UPDATE `column` SET status = 'nofree' WHERE id = ?";
            $stmt = $link->prepare($updateQuery);
            $stmt->bind_param('i', $columnId);
            $stmt->execute();
            $stmt = $link->prepare('
            UPDATE fuel
            SET number_of_liters = number_of_liters - ?
            WHERE id = ?
            ');
            if ($number_of_liters - $liters > 0){
            $stmt->bind_param('ii', $liters, $fuelId);
            $stmt->execute();
            $link->commit();
            echo json_encode(['success' => true]);
        } else {
            $link->rollback();
            echo json_encode(['success' => false, 'error' => 'Недоступное количество литров']);
        }

    } catch (Exception $e) {
        $link->rollback();
        echo json_encode(['success' => false, 'error' => 'Ошибка сервера: ' . $e->getMessage()]);
    } finally {
        $link->close();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
