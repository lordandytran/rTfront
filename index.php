<?php
    require 'rpccalls.php';
?>
<html>
    <head>
    </head>
    <body>
    <?php
        $arr = getDownloadList();
        foreach($arr as $val) {
            print($val . " " . getName($val));
            echo '<p></p>';
        }
        unset($val);
    ?>
    </body>
</html>
