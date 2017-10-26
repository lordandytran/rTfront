<?php
    function call($method, $args) {
        return encodeRequest(array('method'=>$method, 'args'=>$args));
    }

    function ratesStaticMultiCall() {
        return call('d.multicall', array("main",
            "d.hash=",
            "d.name=",
            "d.is_active=",
            "d.complete=",
            "d.connection_current=",
            "d.hashing=",
            "d.size_bytes=",
            "d.completed_bytes=",
            "d.ratio=",
            "d.priority_str="
        ));
    }

    function ratesMultiCall() {
        return call('d.multicall', array("started",
            "d.hash=",
            "d.is_active=",
            "d.complete=",
            "d.connection_current=",
            "d.hashing=",
            "d.completed_bytes=",
            "d.size_bytes=",
            "d.down.rate=",
            "d.up.rate=",
            "d.ratio="
        ));
    }

    function filesMultiCall($hash) {
        return call('f.multicall', array($hash, "",
            "f.path=",
            "f.size_bytes=",
            "f.completed_chunks=",
            "f.size_chunks=",
            "f.priority="
        ));
    }

    function trackerMultiCall($hash) {
        return call('t.multicall', array($hash, "",
            "t.url="
        ));
    }

    function peerMultiCall($hash) {
        return call('p.multicall', array($hash, "",
            "p.id=",
            "p.address=",
            "p.port=",
            "p.client_version=",
            "p.completed_percent=",
            "p.down_rate=",
            "p.up_rate="
        ));
    }

    function statsStaticMultiCall($hash) {
        $arr = array();
        $torrents = ratesStaticMultiCall();
        for($i = 0; $i < sizeof($torrents); $i++) {
            if($torrents[$i] == $hash) {
                $arr = array(
                    "hash" => $torrents[$i],
                    "name" => $torrents[$i + 1],
                    "status" => getStatus($torrents[$i + 2], $torrents[$i + 3], $torrents[$i + 4], $torrents[$i + 5]),
                    "size" => sizeToString($torrents[$i + 6]),
                    "percent" =>  ($torrents[$i + 7] / $torrents[$i + 6]) * 100,
                    "ratio" => $torrents[$i + 8] / 1000,
                    "priority" => $torrents[$i + 9]
                );
                break;
            }
        }
        return $arr;
    }

    function getFilesStatic($hash) {
        $arr = array();
        $files = filesMultiCall($hash);
        for($i = 0; $i < sizeof($files) / 5; $i++) {
            array_push($arr, array(
                $files[0 + 5 * $i],
                sizeToString($files[1 + 5 * $i]),
                ($files[2 + 5 * $i] / $files[3 + 5 * $i]) * 100,
                priorityToString($files[4 + 5 * $i])
            ));
        }
        return $arr;
    }

    function getRatesStatic() {
        $torrents = ratesStaticMultiCall();
        $arr = array();
        for($i = 0; $i < sizeof($torrents) / 10; $i++) {
            array_push($arr, array(
                "hash" => $torrents[0 + 10 * $i],
                "name" => $torrents[1 + 10 * $i],
                "status" => getStatus($torrents[2 + 10 * $i], $torrents[3 + 10 * $i], $torrents[4 + 10 * $i], $torrents[5 + 10 * $i]),
                "size" => sizeToString($torrents[6 + 10 * $i]),
                "percent" =>  ($torrents[7 + 10 * $i] / $torrents[6 + 10 * $i]) * 100,
                "ratio" => $torrents[8 + 10 * $i] / 1000
            ));
        }
        return $arr;
    }

    function sizeToString($size) {
        $dec = "";
        if($size >= 1099511627776) {
            $tb = intval($size / 1099511627776);
            $offset = (string)($size % 1099511627776);
            $dec .=  (string)$tb . "." . $offset[0];
            return $dec . " TB";
        }
        if($size >= 1073741824) {
            $gb = intval($size / 1073741824);
            $offset = (string)($size % 1073741824);
            $dec .=  (string)$gb . "." . $offset[0];
            return $dec . " GB";
        }
        if($size >= 1048576) {
            $mb = intval($size / 1048576);
            $offset = (string)($size % 1048576);
            $dec .=  (string)$mb . "." . $offset[0];
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

    //loads torrent
    function loadTorrent($link) {
        call('load', $link);
    }

    //returns percentage done of torrent
    function getPercentDone($hash) {
        $done = call('d.completed_bytes', $hash)[0];
        $total = call('d.size_bytes', $hash)[0];
        return ($done / $total) * 100;
    }

    function getTorrentDirectory($hash) {
        return call('d.directory_base', $hash)[0];
    }

    function getETA($done, $total, $rate) {
        if($rate == 0 || $done >= $total) {
            return "∞";
        }
        $sec = intval(abs($total - $done) / $rate);

        $ret = "";
        $years = intval($sec / (31536000));

        if($years > 0) {
            $sec = intval($sec % (31536000));
            $ret .= "{$years}y ";
        }
        $months = intval($sec / (2592000));
        if($months > 0) {
            $sec = intval($sec % (2592000));
            $ret .= "{$months}mo ";
        }
        $weeks = intval($sec / (604800));
        if($weeks > 0) {
            $sec = intval($sec % (604800));
            $ret .= "{$weeks}w ";
        }
        $days = intval($sec / (86400));
        if($days > 0) {
            $sec = intval($sec % (86400));
            $ret .= "{$days}d ";
        }
        $hours = intval($sec / (3600));
        if($hours > 0) {
            $sec = intval($sec % (3600));
            $ret .= "{$hours}h ";
        }
        $minutes = intval($sec / 60);
        if($minutes > 0) {
            $sec = intval($sec % 60);
            $ret .= "{$minutes}m ";
        }
        $ret .= "{$sec}s";
        return $ret;
    }

    function isActive($hash) {
        return call('d.is_active', $hash)[0];
    }

    function isHashing($hash) {
        return call('d.hashing', $hash)[0];
    }

    function closeTorrent($hash) {
        return call('d.close', $hash)[0];
    }

    function getStatus($active, $complete, $current, $hashing) {
        if($active == 0) {
            return "Stopped";
        }
        if($complete == 1) {
            return "Complete";
        }
        if($active == 1 && $complete == 1) {
            return "Seeding";
        }

        if($active == 1 && $current == "leech") {
            return "Leeching";
        }
        if($hashing > 0) {
            return "Hashing";
        }
        return "NA";
    }

    function boolActive($hash) {
        $active = isActive($hash);
        if($active == 1) {
            return true;
        }
        return false;
    }

    function getMaxUpRate() {
        $min = call('throttle.global_up.max_rate', "")[0];
        if($min == 0) {
            return "No limit";
        }
        else {
            return ($min / 1024) . " KB/s";
        }
    }

    function getMaxDownRate() {
        $max = call('throttle.global_down.max_rate', "")[0];
        if($max == 0) {
            return "No limit";
        }
        else {
            return ($max / 1024) . " KB/s";
        }
    }

    function getMaxRatio() {
        return call('ratio.max', "")[0] / 1000;
    }

    function getDefaultDirectory() {
        return call('directory.default', "")[0];
    }

    function setDefaultDirectory($dir) {
        call('directory.default.set', array('', $dir));
    }

    function setMaxUpRate($rate) {
        call('throttle.global_up.max_rate.set_kb', array('', $rate));
    }

    function setMaxDownRate($rate) {
        call('throttle.global_down.max_rate.set_kb', array('', $rate));
    }

    function setMaxRatio($ratio) {
        $ratio = intval($ratio * 1000);
        call('ratio.max.set', $ratio);
    }

    function getFileCount($hash) {
        return call('d.size_files', $hash)[0];
    }

    function priorityToString($priority) {
        if($priority == 1) {
            return "normal";
        }
        if($priority == 2) {
            return "high";
        }
        return "Do not download";
    }

    function getPeersConnected($hash) {
        return call('d.peers_connected', $hash)[0];
    }

    function getPeersNotConnected($hash) {
        return call('d.get_peers_not_connected', $hash)[0];
    }

    function getNumLeechers($hash) {
        return call('d.get_peers_accounted', $hash)[0];
    }

    function getNumSeeders($hash) {
        return call('d.get_peers_complete', $hash)[0];
    }

    function getPeerAddress($hash) {
        return call('p.address', $hash)[0];
    }

    function getPeerPercentCompleted($hash) {
        return call('p.completed_percent', $hash)[0];
    }

    function addTracker($hash, $num, $url) {
        call('d.tracker.insert', array($hash, $num, $url));
    }

    function getNumTrackers($hash) {
        return call('d.tracker_size', $hash)[0];
    }

    function activeList() {
        $arr = array_filter(getDownloadList());
        $ret = array();
        foreach($arr as $i) {
            if(boolActive($i)) {
                array_push($ret, $i);
            }
        }
        unset($i);
        return $ret;
    }

    function fullActive() {
        $arr = array_filter(getDownloadList());
        foreach($arr as $i) {
            if(boolActive($i)) {
                return true;
            }
        }
        unset($i);
        return false;
    }
?>