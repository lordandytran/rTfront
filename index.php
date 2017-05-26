<?php
    //header("Content-Type: application/json");
    require 'connect.php';
    require 'rpccalls.php';

    if(isset($_POST["submit_link"])) {
        $link = $_POST["link_sub"];
        createTorrent($link);
        sleep(4);
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
        <script>
            function display() {
                $('input[type=checkbox]').each(function() {
                    var values = (this.checked ? $(this).val() : "");
                    $('#hashes').html(values.toString());
                });
            }
        </script>
        <p></p>
        <div id="hashes"></div>
        <div style="width:90%;margin:auto">
            <input type="submit" name="start" onclick="display()" class="btn btn-success btn" value="Start">
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
                        <th>Status</th>
                        <th>Size</th>
                        <th>Done</th>
                        <!--<th>Peers</th>-->
                        <th>Down Speed</th>
                        <th>Up Speed</th>
                        <th>ETA</th>
                        <th>Ratio</th>
                        <!--<th>Hash</th>-->
                    </tr>
                </thead>
                <?php
                    $arr = array_filter(getDownloadList());
                    foreach($arr as $val) {
                ?>
                        <script>
                            function rate<?php echo $val ?>() {
                                $.get("scripts/phpcalls.php?method=getRates&hash=<?php echo $val ?>", function(data) {
                                    var arr = jQuery.parseJSON(data);
                                    var p = arr.percent;
                                    var down = arr.down;
                                    var up = arr.up;
                                    var ratio = arr.ratio;
                                    $('#percent<?php echo $val ?>').html(Number(p).toFixed(2) + '%');
                                    $('#down<?php echo $val ?>').html(down);
                                    $('#up<?php echo $val ?>').html(up);
                                    $('#ratio<?php echo $val ?>').html(Number(ratio).toFixed(2));
                                });
                            }
                            setInterval(function(){rate<?php echo $val ?>()}, 1000);
                        </script>
                <?php
                        echo '<tr>';

                        echo '<td>' . "<input type='checkbox' name='checkbox[]' value='$val' />" . '</td>';

                        echo '<td>' . getName($val) . '</td>';

                        echo '<td>' . "TODO" . '</td>';

                        echo '<td>' . getSize($val) . '</td>';

                        echo '<td>'
                ?>
                        <div id="percent<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

                        echo '<td>';
                ?>
                        <div id="down<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

                        echo '<td>';
                ?>
                        <div id="up<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

                        echo '<td>' . 'TODO' . '</td>';

                        echo '<td>';
                ?>
                        <div id="ratio<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

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