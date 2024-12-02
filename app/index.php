<?php
    if ($_SERVER['REQUEST_URI'] == '/server-monitoring/app/') {
        header('Location: /server-monitoring/app/home/login.php');
        exit;
    } else {
        header('Location: /home/login.php');
        exit;
    }
?>
