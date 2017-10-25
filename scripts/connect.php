<?php
    $HOST = 'localhost';
    $PORT = 80;
    $RPC = '/RPC2';

    $error = "";

    function encodeRequest($req) {
        $method = null;
        $args = null;
        extract($req);
        $xml = xmlrpc_encode_request($method, $args);
        $response = sendRequest($GLOBALS['HOST'], $GLOBALS['PORT'], $GLOBALS['RPC'], $xml);
        return decodeResponse($response);
    }

    function sendRequest($host, $port, $rpc, $request) {
        $errno = 0;
        $errstr = "";
        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if(!$fp) {
            echo("Error $errno: $errstr");
        }
        $length = strlen($request);
        fwrite($fp, "POST $rpc HTTP/1.0\r\n");
        fwrite($fp, "User-Agent: rTfront\r\n");
        fwrite($fp, "Host: $host:$port\r\n");
        fwrite($fp, "Content-Type: text/xml\r\n");
        fwrite($fp, "Content-Length: $length\r\n");
        fwrite($fp, "\r\n");
        fwrite($fp, $request);
        $response = "";
        $line = fgets($fp);
        while ($line) {
            $response .= $line;
            $line = fgets($fp);
        }
        fclose($fp);
        return $response;
    }

    function decodeResponse($toDecode) {
        $substring = substr($toDecode, strpos($toDecode, "<?xml"));
        $substring = strip_tags($substring);
        $substring = trim($substring);
        $arr = preg_split("/\s\s+/", $substring);
        return $arr;
    }
?>