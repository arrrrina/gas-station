<?php

$link = mysqli_connect("localhost", "root", "", "gas_station", 3306);
if ($link->connect_error){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
}
if(!$link->set_charset('utf8')) {
    die("Ошибка кодировки".$link->error);
}
?>