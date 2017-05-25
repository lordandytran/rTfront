<?php
    include '../connect.php';
    include '../rpccalls.php';
    $hash = $_GET['hash'];
    $down = getDownRate($hash);
    echo $down;
?>