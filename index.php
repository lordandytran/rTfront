<?php
    require 'connect.php';
    require 'rpccalls.php';
?>
<html>
    <head>
    </head>
    <body>
        <table style="width:100%">
            <tr>
                <th>Name</th>
                <th>Size</th>
                <!--<th>Done</th>
                <th>Status</th>
                <th>Seeds</th>
                <th>Peers</th>-->
                <th>Down Speed</th>
                <th>Up Speed</th>
                <!--<th>ETA</th>-->
                <th>Ratio</th>
                <th>Hash</th>
            </tr>
            <?php
                $arr = getDownloadList();
                foreach($arr as $val) {
                    echo '<tr>';

                    echo '<td>' . getName($val) . '</td>';

                    echo '<td>' . getSize($val) . '</td>';

                    echo '<td>' . getDownRate($val) . '</td>';

                    echo '<td>' . getUpRate($val) . '</td>';

                    echo '<td>';
                    printf("%.2f",getRatio($val));
                    echo '</td>';

                    echo '<td>' . $val . '</td>';

                    echo '</tr>';
                }
                unset($val);
            ?>
        </table>
    </body>
</html>