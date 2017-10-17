<?php
    include 'connect.php';
    include 'rpccalls.php';

    echo $_GET['method']();

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
            while(isHashing($value) != 0) {
                continue;
            }
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
        //Sleep for 1 second to allow successful time to remove
        sleep(1);
    }

    function getRates() {
        $arr = activeList();
        $rates = array();
        foreach($arr as $hash) {
            array_push($rates, array($hash,
                getStatus($hash),
                getPercentDone($hash),
                getDownRate($hash),
                getUpRate($hash),
                getETA($hash),
                getRatio($hash)));
        }
        unset($hash);
        echo json_encode($rates);
    }

    function getStats() {
        $hash = $_GET['hash'];
        $files = getFileCount($hash);
        $stats = array(
            getStatus($hash),
            getPercentDone($hash),
            getDownRate($hash),
            getUpRate($hash),
            getETA($hash),
            getRatio($hash)
        );
        for($i = 0; $i < $files; $i++) {
            $str = $hash . ":f" . $i;
            array_push($stats, getFilePercentDone($str));
        }
        echo json_encode($stats);
    }
?>