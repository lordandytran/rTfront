<?php
    function call($method, $args) {
        return sendRequest(array('method'=>$method, 'args'=>$args));
    }

    function sizeToString($size) {
        $dec = "";
        if($size >= 1099511627776) {
            $tb = intval($size / 1099511627776);
            $offset = (string)($size % 1099511627776);
            $dec .=  (string)$tb . "." . $offset[0]; //. $offset[1];
            return $dec . " TB";
        }
        if($size >= 1073741824) {
            $gb = intval($size / 1073741824);
            $offset = (string)($size % 1073741824);
            $dec .=  (string)$gb . "." . $offset[0]; //. $offset[1];
            return $dec . " GB";
        }
        if($size >= 1048576) {
            $mb = intval($size / 1048576);
            $offset = (string)($size % 1048576);
            $dec .=  (string)$mb . "." . $offset[0]; //. $offset[1];
            return $dec . " MB";
        }
        if($size >= 1024) {
            $kb = intval($size / 1024);
            return $kb . " KB";
        }
        return $size . " bytes";
    }

    //returns download list as array
    function getDownloadList() {
        return call('download_list', "");
    }

    //returns name of torrent
    function getName($hash) {
        return call('d.get_name', $hash)[0];
    }

    //returns the priority of the torrent
    function getPriority($hash) {
        return call('d.priority_str', $hash)[0];
    }

    //returns size of the torrent
    function getSize($hash) {
        $size = call('d.size_bytes', $hash)[0];
        return sizeToString($size);

    }

    //returns the current upload rate of torrent
    function getUpRate($hash) {
        $rate = call('d.up.rate', $hash)[0];
        return sizeToString($rate) . "/s";
    }

    //returns the current download rate of torrent
    function getDownRate($hash) {
        $rate = call('d.down.rate', $hash)[0];
        return sizeToString($rate) . "/s";
    }

    //returns seed ratio of torrent
    function getRatio($hash) {
        $ratio = call('d.ratio', $hash)[0];
        return $ratio / 1000;
    }

    //stops given torrent
    function stopTorrent($hash) {
        call('d.stop', $hash);
    }

    //starts given torrent
    function startTorrent($hash) {
        call('d.start', $hash);
    }

    //erases given torrent
    function eraseTorrent($hash) {
        call('d.erase', $hash);
    }

    //loads and starts torrent
    function createTorrent($link) {
        call('load_start', $link);
    }

    //returns percentage done of torrent
    function getPercentDone($hash) {
        $done = call('d.completed_bytes', $hash)[0];
        $total = call('d.size_bytes', $hash)[0];
        return ($done / $total) * 100;
    }

    function getDirectory($hash) {
        return call('d.directory_base', $hash)[0];
    }

    function getETA($hash) {
        return "";
    }

    function isActive($hash) {
        return call('d.is_active', $hash)[0];
    }

?>