<?php
    require 'connect.php';
    require 'rpccalls.php';

    if(isset($_POST["submit_link"])) {
        $link = $_POST["link_sub"];
        createTorrent($link);
        sleep(5);
        header("location: index.php");
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <button type="button" class="btn btn-info btn-default" data-toggle="modal" data-target="#myModal">Add</button>
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        Add magnet link or path to torrent
                    </div>
                    <div class="modal-body">
                        <div class="submit-wrap">
                            <form id="sub-link" method="post">
                                <div class="submit-form">
                                    <label>
                                        <input type="text" name="link_sub" class="form-control" placeholder="$link" required/>
                                    </label>
                                    <input type="submit" name="submit_link" class="btn btn-success btn" value="Add">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <p></p>
        <table id="tab1" style="width:100%">
            <tr>
                <th>Name</th>
                <th>Size</th>
                <th>Done</th>
                <!--<th>Seeds</th>
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

                    echo '<td>';
                        printf("%.2f%%", getPercentDone($val));
                    echo '</td>';

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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>