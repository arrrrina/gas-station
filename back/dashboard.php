<?php
include "database.php";
include 'session_check.php';
header('Content-Type: application/json');
$isAdmin = $_SESSION['client_type'] === 'admin';
if ($_POST['action'] === 'get_user_role') {
    echo json_encode(['isAdmin' => $isAdmin]);
    exit;
}
$action = $_POST['action'] ?? '';

if ($action === 'get_firms') {
    $result = $link->query("SELECT id, name FROM firm");
    if ($result) {
        $firms = [];
        while ($row = $result->fetch_assoc()) {
            $firms[] = $row;
        }
        echo json_encode(['success' => true, 'firms' => $firms]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Ошибка получения фирм: ' . $link->error]);
    }
} elseif ($action === 'get_stations') {
    
    $firm_id = $_POST['firm_id'] ?? 0;
    if ($firm_id) {
        $stmt = $link->prepare("
            SELECT id, city, street, house 
            FROM station 
            WHERE id_firm = ?
        ");
        $stmt->bind_param('i', $firm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stations = [];
        while ($row = $result->fetch_assoc()) {
            $stations[] = $row;
        }
        $stmt->close();
        echo json_encode(['success' => true, 'stations' => $stations]);
    }
}
 else {
    echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
}

$link->close();
?>

