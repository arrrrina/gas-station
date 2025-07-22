<?php
include "database.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha256', $password);
    // Проверка логина и пароля в базе данных
    $request = "SELECT id, name, password, client_type 
    FROM client WHERE email = ?";
    $stmt = $link->prepare($request);

    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && $hashedPassword == $user['password']) {
        session_start();
        $_SESSION['client_id'] = $user['id'];
        $_SESSION['client_name'] = $user['name'];
        $_SESSION['client_email'] = $email;
        $_SESSION['client_type'] = $user['client_type'];
        echo "Вы успешно вошли в систему!";
        header('Location: dashboard_view.php');
        exit();
    } else {
        echo 'Неверный email или пароль';
    }
    $stmt->close();
} else {
    echo 'Заполните все поля';
}

$link->close();

?>

