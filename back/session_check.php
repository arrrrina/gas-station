<?php

session_start();

$user_inactive = 120;  
$admin_inactive = 1800;  

if (!isset($_SESSION['client_id'])) {
    header("Location: autorization.html");
    exit;
}

if (isset($_SESSION['last_activity'])) {
    $session_lifetime = time() - $_SESSION['last_activity'];

    $inactive_limit = ($_SESSION['client_type'] === 'admin') ? $admin_inactive : $user_inactive;

    if ($session_lifetime > $inactive_limit) {
        session_unset();
        session_destroy();
        header("Location: session_expired.php");
        exit;
    }
}

$_SESSION['last_activity'] = time();
?>
