<?php
    include '../connect.php';
    include '../rpccalls.php';
    $hash = $_GET['hash'];
    $r = getRatio($hash);
    printf("%.2f", $r);
?>