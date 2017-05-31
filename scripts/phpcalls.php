<?php
    include '../connect.php';
    include '../rpccalls.php';

    echo $_GET['method']();

    function getRates() {
        $hash = $_GET['hash'];
        $arr = array('status' => getStatus($hash),
            'percent' => getPercentDone($hash),
            'down' => getDownRate($hash),
            'up' => getUpRate($hash),
            'ratio' => getRatio($hash),
            'eta' => getETA($hash));
        echo json_encode($arr);
    }

    function stop() {
        foreach($_GET as $key => $value) {
            stopTorrent($value);
        }
        unset($key);
        unset($value);
    }

    function start() {
        foreach($_GET as $key => $value) {
            startTorrent($value);
        }
        unset($key);
        unset($value);
    }

    function remove() {
        foreach($_GET as $key => $value) {
            stopTorrent($value);
            closeTorrent($value);
            eraseTorrent($value);
        }
        unset($key);
        unset($value);
    }
?>

