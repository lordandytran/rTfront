<?php
    require 'connect.php';
    require 'rpccalls.php';

    if(isset($_POST["submit_link"])) {
        $link = $_POST["link_sub"];
        createTorrent($link);
        sleep(4);
        header("location: index.php");
    }

    if(isset($_POST["remove"])) {
        foreach ($_POST["checkbox"] as $hash) {
            eraseTorrent($hash);
        }
        header("location: index.php");
    }

    if(isset($_POST["start"])) {
        foreach ($_POST["checkbox"] as $hash) {
            startTorrent($hash);
        }
        header("location: index.php");
    }

    if(isset($_POST["stop"])) {
        foreach ($_POST["checkbox"] as $hash) {
            stopTorrent($hash);
        }
        header("location: index.php");
    }

?>
<html>
    <head>
        <title>rTfront</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <p></p>
        <div style="width:90%;margin:auto">
            <input type="submit" name="start" class="btn btn-success btn" value="Start">
            <input type="submit" name="stop" class="btn btn-success btn" value="Stop">
            <input type="submit" name="remove" class="btn btn-success btn" value="Remove">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Add</button>
            <a href="settings.php" class="btn btn-success btn" style="float:right">Settings</a>
        </div>
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
        <script language="JavaScript">
            function toggle(source) {
                var checkboxes = document.getElementsByName('checkbox[]');
                for(var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
        <div id="table-wrap">
            <table class="table table-bordered table-striped" style="width:90%;margin:auto">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggle(this)" /></th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Done</th>
                        <!--<th>Seeds</th>
                        <th>Peers</th>-->
                        <th>Down Speed</th>
                        <th>Up Speed</th>
                        <th>ETA</th>
                        <th>Ratio</th>
                        <!--<th>Hash</th>-->
                    </tr>
                </thead>
                <?php
                    $arr = getDownloadList();
                    $i = 0;
                    foreach($arr as $val) {
                        echo '<tr>';

                        echo '<td>' . "<input type='checkbox' name='checkbox[]' value='$val' />" . '</td>';

                        echo '<td>' . getName($val) . '</td>';

                        echo '<td>' . getSize($val) . '</td>';

                        echo '<td>';
                ?>
                            <div id="progress<?php echo $val ?>"></div>
                            <div id="message<?php echo $val ?>"></div>
                            <script>
                                function percentRate<?php echo $val ?>() {
                                    $('#message<?php echo $val ?>').load('scripts/percent.php?hash=<?php echo $val ?>');
                                }
                                setInterval(function(){percentRate<?php echo $val ?>()}, 1000);
                            </script>
                <?php
                        echo '</td>';
                        echo '<td>';
                ?>
                            <div id='downrate<?php echo $val ?>'></div>
                            <script>
                                function loadDownRate<?php echo $val ?>() {
                                    $('#downrate<?php echo $val ?>').load('scripts/downrate.php?hash=<?php echo $val ?>');
                                }
                                setInterval(function(){loadDownRate<?php echo $val ?>()}, 1000);
                            </script>
                <?php
                        echo '</td>';
                        echo '<td>';
                ?>
                            <div id='uprate<?php echo $val ?>'></div>
                            <script>
                                function loadUpRate<?php echo $val ?>() {
                                    $('#uprate<?php echo $val ?>').load('scripts/uprate.php?hash=<?php echo $val ?>');
                                }
                                setInterval(function(){loadUpRate<?php echo $val ?>()}, 1000);
                            </script>
                <?php
                        echo '</td>';

                        echo '<td>' . getETA($val) . '</td>';

                        echo '<td>';
                ?>
                            <div id='ratio<?php echo $val ?>'></div>
                            <script>
                                function loadRatio<?php echo $val ?>() {
                                    $('#ratio<?php echo $val ?>').load('scripts/ratio.php?hash=<?php echo $val ?>');
                                }
                                setInterval(function(){loadRatio<?php echo $val ?>()}, 1000);
                            </script>
                <?php
                        echo '</td>';
                        //echo '<td>' . $val . '</td>';
                        echo '</tr>';
                    }
                    unset($val);
                ?>
            </table>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>