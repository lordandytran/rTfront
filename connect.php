<?php
    $HOST = 'localhost';
    $PORT = 80;
    $RPC = '/RPC2';

    function sendRequest($req) {
        $method = null;
        $args = null;
        extract($req);
        $xml = xmlrpc_encode_request($method, $args);
        $response = getResponse($GLOBALS['HOST'], $GLOBALS['PORT'], $GLOBALS['RPC'], $xml);
        return decode($response);
    }

    function getResponse($host, $port, $rpc, $request) {
        $errno = 0;
        $errstr = "";
        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if($fp == false) {
            exit("Error $errno: $errstr");
        }
        $length = strlen($request);
        $header =
            "POST $rpc HTTP/1.0\r\n" .
            "User-Agent: xmlrpc-epi-php\r\n" .
            "Host: $host:$port\r\n" .
            "Content-Type: text/xml\r\n" .
            "Content-Length: $length\r\n" .
            "\r\n" .
            $request;

        $response = "";
        fwrite($fp, $header, strlen($header));
        $line = fgets($fp);
        $line = fgets($fp);
        while($line) {
            $response .= $line;
            $line = fgets($fp);
        }
        fclose($fp);
        return $response;
    }

    function decode($toDecode) {
        $substring = substr($toDecode, strpos($toDecode, "<?xml"));
        return xmlrpc_decode($substring);
    }

?>