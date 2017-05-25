<?php
    include '../connect.php';
    include '../rpccalls.php';
    $hash = $_GET['hash'];

    /*while(isActive($hash)) {
        $down = getDownRate($hash);
        echo $down;
        ob_flush();
        flush();
        sleep(1);
    }*/

    echo getDownRate($hash);

?>