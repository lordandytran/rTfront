<?php
    include '../connect.php';
    include '../rpccalls.php';
    $hash = $_GET['hash'];
    $up = getUpRate($hash);
    echo $up;
?>