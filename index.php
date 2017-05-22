<?php
    require 'rpccalls.php';
?>
<html>
    <head>
    </head>
    <body>
    <?php
        $arr = getDownloadList();
        print_r($arr);
        foreach($arr as $val) {
            //print($val . " ". getName($val) . " " . getPriority($val) . " " . getSize($val) . " " . getState($val));
            print_r(getSize($val));
        }
        unset($val);
    ?>

    </body>
</html>