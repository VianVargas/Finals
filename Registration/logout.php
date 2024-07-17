<?php
    require_once 'authorize.php';
    logout();
    header("Location: ../Registration/login.php");
    exit;
?>