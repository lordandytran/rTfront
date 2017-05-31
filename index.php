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
        <title>rTfront</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <script>
            function display(method) {
                var arr =[];
                $('input[type=checkbox]:checked').each(function() {
                    arr.push($(this).val());
                });
                var params = "scripts/phpcalls.php?method=" + method + "&";
                for(var i = 0; i < arr.length; i++) {
                    params += "hash" + i + "=" + arr[i] + "&";
                }
                $.get(params);
                location.reload();
            }
        </script>
        <nav class="navbar navbar-inverse" role="navigation">
            <span class="navbar-brand" style="margin-left: 5%">rTfront</span>
            <ul class="nav navbar-nav">
                <li>
                    <a href="#settingsModal" data-toggle="modal" data-target="#settingsModal">Settings</a>
                </li>
            </ul>
        </nav>
        <?php include 'settings.php'?>
        <p></p>
        <div style="width:90%;margin:auto">
            <input type="submit" name="start" onclick="display('start')" class="btn btn-default" value="Start">
            <input type="submit" name="stop" onclick="display('stop')" class="btn btn-default" value="Stop">
            <input type="submit" name="remove" onclick="display('remove')" class="btn btn-default" value="Remove">
            <!--<input type="submit" name="stat" class="btn btn-success btn" value="Stats">-->
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#addModal">Add</button>
        </div>
        <?php include 'addmod.php'?>
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
            <table class="table table-bordered table-striped table-hover" data-spy="scroll" style="width:90%;margin:auto">
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
                        <th style="width:120px">ETA</th>
                        <th>Ratio</th>
                        <!--<th>Hash</th>-->
                    </tr>
                </thead>
                <?php
                    $arr = array_filter(getDownloadList());
                    $arr = array_reverse($arr);
                    foreach($arr as $val) { ?>
                        <script>
                            function rate<?php echo $val ?>() {
                                $.get("scripts/phpcalls.php?method=getRates&hash=<?php echo $val ?>", function(data) {
                                    var arr = jQuery.parseJSON(data);
                                    $('#status<?php echo $val ?>').html(arr.status);
                                    $('#percent<?php echo $val ?>').html(Number(arr.percent).toFixed(2) + '%');
                                    $('#down<?php echo $val ?>').html(arr.down);
                                    $('#up<?php echo $val ?>').html(arr.up);
                                    $('#ratio<?php echo $val ?>').html(Number(arr.ratio).toFixed(2));
                                    $('#eta<?php echo $val ?>').html(arr.eta);
                                });
                            }
                            var active = <?php boolActive($val) ?>;
                            if(active) {
                                setInterval(function(){rate<?php echo $val ?>()}, 1000);
                            }
                        </script>
                        <tr id="<?php echo $val ?>" onclick="statDisplay('<?php echo $val ?>')">
                <?php
                        echo '<td>' . "<input type='checkbox' name='checkbox[]' value='$val' />" . '</td>';

                        echo '<td>' . getName($val) . '</td>';

                        echo '<td>'; ?>
                        <div id="status<?php echo $val ?>"><?php echo getStatus($val) ?></div>
                <?php
                        echo '</td>';

                        echo '<td>' . getSize($val) . '</td>';

                        echo '<td>' ?>
                        <div id="percent<?php echo $val ?>"><?php printf("%.2f%%", getPercentDone($val)) ?></div>
                <?php
                        echo '</td>';

                        echo '<td>'; ?>
                        <div id="down<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

                        echo '<td>'; ?>
                        <div id="up<?php echo $val ?>"></div>
                <?php
                        echo '</td>';

                        echo '<td>'; ?>
                        <div id="eta<?php echo $val ?>" style="width:120px">∞</div>
                <?php
                        echo '</td>';

                        echo '<td>'; ?>
                        <div id="ratio<?php echo $val ?>"><?php printf("%.2f", getRatio($val)) ?></div>
                <?php
                        echo '</td>';

                        echo '</tr>';
                    }
                    unset($val);
                ?>
            </table>
        </div>
        <p></p>
        <?php include 'stats.php'?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>