<?php
    include '../connect.php';
    include '../rpccalls.php';

    echo $_GET['method']();

    function getRates() {
        $hash = $_GET['hash'];
        $arr = array('percent' => getPercentDone($hash),
            'down' => getDownRate($hash),
            'up' => getUpRate($hash),
            'ratio' => getRatio($hash));
        echo json_encode($arr);
    }