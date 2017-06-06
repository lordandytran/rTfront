<?php
    function call($method, $args) {
        return sendRequest(array('method'=>$method, 'args'=>$args));
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

    function getTorrentDirectory($hash) {
        return call('d.directory_base', $hash)[0];
    }

    function getETA($hash) {
        $done = call('d.completed_bytes', $hash)[0];
        $total = call('d.size_bytes', $hash)[0];
        $rate = call('d.down.rate', $hash)[0];
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
            $ret .= "{$months}m ";
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

    function isComplete($hash) {
        return call('d.complete', $hash)[0];
    }

    function closeTorrent($hash) {
        return call('d.close', $hash)[0];
    }

    function getCurrentConnection($hash) {
        return call('d.connection_current', $hash)[0];
    }

    function getStatus($hash) {
        $active = isActive($hash);
        $complete = isComplete($hash);
        $concurr = getCurrentConnection($hash);
        if($active == 0) {
            return "Stopped";
        }
        if($active == 1 && $complete == 1) {
            return "Seeding";
        }
        if($complete == 1) { //This condition may never be reached
            return "Complete";
        }
        if($active == 1 && $concurr == "leech") {
            return "Leeching";
        }
        return "NA";
    }

    function boolActive($hash) {
        $active = isActive($hash);
        if($active == 1) {
            echo true;
        }
        echo false;
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
        shellCall('directory.default.set', $dir);
    }

    function setMaxUpRate($rate) {
        shellCall('throttle.global_up.max_rate.set_kb', $rate);
    }

    function setMaxDownRate($rate) {
        shellCall('throttle.global_down.max_rate.set_kb', $rate);
    }

    function setMaxRatio($ratio) {
        $ratio = intval($ratio * 1000);
        call('ratio.max.set', $ratio);
    }

?>