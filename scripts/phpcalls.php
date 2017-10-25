<?php
    include 'connect.php';
    include 'rpccalls.php';

    echo $_GET['method']();

    function stop() {
        foreach($_GET as $key => $value) {
            stopTorrent($value);
        }
    }

    function start() {
        foreach($_GET as $key => $value) {
            startTorrent($value);
            while(isHashing($value) != 0) {
                continue;
            }
        }
    }

    function remove() {
        foreach($_GET as $key => $value) {
            stopTorrent($value);
            closeTorrent($value);
            eraseTorrent($value);
        }
        //Sleep for 1 second to allow successful time to remove
        sleep(1);
    }

    function getRates() {
        $arr = ratesMultiCall();
        $rates = array();
        for($i = 0; $i < sizeof($arr) / 10; $i++) {
            array_push($rates, array(
                $arr[0 + 10 * $i], //hash
                getStatus($arr[1 + 10 * $i], $arr[2 + 10 * $i], $arr[3 + 10 * $i], $arr[4 + 10 * $i]), //status
                ($arr[5 + 10 * $i] / $arr[6 + 10 * $i]) * 100, //percent
                sizeToString($arr[7 + 10 * $i]) . "/s", //down rate
                sizeToString($arr[8 + 10 * $i]) . "/s", //up rate
                getETA($arr[5 + 10 * $i], $arr[6 + 10 * $i], $arr[7 + 10 * $i]), //eta
                $arr[9 + 10 * $i] / 1000 //ratio
            ));
        }
        echo json_encode($rates);
    }

    function getStats() {
        $hash = $_GET['hash'];
        $arr = ratesMultiCall();
        $stats = array();
        for($i = 0; $i < sizeof($arr); $i++) {
            if($arr[$i] == $hash) {
                array_push($stats, array(
                    getStatus($arr[1 + $i], $arr[2 + $i], $arr[3 + $i], $arr[4 + $i]), //status
                    ($arr[5 + $i] / $arr[6 + $i]) * 100, //percent
                    sizeToString($arr[7 + $i]) . "/s", //down rate
                    sizeToString($arr[8 + $i]) . "/s", //up rate
                    getETA($arr[5 + $i], $arr[6 + $i], $arr[7 + $i]), //eta
                    $arr[9 + $i] / 1000 //ratio
                ));
            }
        }
        $files = filesMultiCall($hash);
        $filePercents = array();
        for($i = 0; $i < sizeof($files) / 5; $i++) {
            array_push($filePercents, ($files[2 + 5 * $i] / $files[3 + 5 * $i]) * 100);
        }
        array_push($stats, $filePercents);
        echo json_encode($stats);
    }

    function getPeers() {
        $hash = $_GET['hash'];
        $peers = peerMultiCall($hash);
        $arr = array();
        for($i = 0; $i < sizeof($peers) / 7; $i++) {
            array_push($arr, array(
                $peers[0 + 7 * $i], //id
                $peers[1 + 7 * $i], //address
                $peers[2 + 7 * $i], //port
                $peers[3 + 7 * $i], //client
                $peers[4 + 7 * $i], //percent
                sizeToString($peers[5 + 7 * $i]) . "/s", //down rate
                sizeToString($peers[6 + 7 * $i]) . "/s", //up rate
            ));
        }
        echo json_encode($arr);
    }
?>