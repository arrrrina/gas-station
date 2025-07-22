<?php
session_start();
session_unset();
session_destroy();
header('Location: http://localhost/application/view/autorization.html'); // Перенаправляем на страницу входа
exit();
?>