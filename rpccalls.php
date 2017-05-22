<?php
    require 'connect.php';

    function call($method, $args) {
        return sendRequest(array('method'=>$method, 'args'=>$args));
    }

    //Returns the download list (hashed torrent name) as an array.
    function getDownloadList() {
        return call('download_list', "");
    }

    //Returns the name of torrent given the hashed torrent name.
    function getName($hash) {
        return call('d.get_name', $hash);
    }

    function getPriority($hash) {
        return call('d.priority_str', $hash);
    }

    function getSize($hash) {
        return call('d.size_bytes', $hash);
    }

    function getState($hash) {
        return call('d.state', $hash);
    }
?>