<?php
    include '../connect.php';
    include '../rpccalls.php';
    $hash = $_GET['hash'];
    $p = getPercentDone($hash);
    printf("%.2f%%", $p);
?>