<?php
session_start()
include "database.php"
include 'session_check.php';
// session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $client_type = $_POST['client_type'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO client (name, surname, phone_number, email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $login, $hashedPassword);
    if ($stmt->execute()) {
    echo "Пользователь зарегистрирован успешно!";
    }    
    else {
    echo "Ошибка: " . $stmt->error;
    }
$stmt->close();
$conn->close();
}