<?php
    function call($method, $args) {
        return sendRequest(array('method'=>$method, 'args'=>$args));
    }

    function sizeToString($size) {
        $dec = "";
        if($size >= 1000000000000) {
            $tb = intval($size / 1000000000000);
            $offset = (string)($size % 1000000000000);
            $dec .=  (string)$tb . "." . $offset[0] . $offset[1];
            return $dec . " TB";
        }
        if($size >= 1000000000) {
            $gb = intval($size / 1000000000);
            $offset = (string)($size % 1000000000);
            $dec .=  (string)$gb . "." . $offset[0] . $offset[1];
            return $dec . " GB";
        }
        if($size >= 1000000) {
            $mb = intval($size / 1000000);
            $offset = (string)($size % 1000000);
            $dec .=  (string)$mb . "." . $offset[0] . $offset[1];
            return $dec . " MB";
        }
        if($size >= 1000) {
            $kb = intval($size / 1000);
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
        return call('d.ratio', $hash)[0];
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