<?php
    require 'connect.php';
    require 'rpccalls.php';

    if(isset($_POST["submit_link"])) {
        $link = $_POST["link_sub"];
        if(isset($_POST['locationRadio'])) {
            $val = $_POST['locationRadio'];
            $default = getDefaultDirectory();
            setDefaultDirectory($val);
            createTorrent($link);
            sleep(5);
            setDefaultDirectory($default);
            header("location: index.php");
        }
        else {
            createTorrent($link);
        }
        //sleep for 5 seconds to allow rTorrent to retrieve name of new download
        sleep(5);
        header("location: index.php");
    }
?>
<html>
    <head>
        <title>rTfront</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
                    <a href="settings.php">Settings</a>
                </li>
            </ul>
        </nav>
        <p></p>
        <div style="width:90%;margin:auto">
            <input type="submit" name="start" onclick="display('start')" class="btn btn-default" value="Start">
            <input type="submit" name="stop" onclick="display('stop')" class="btn btn-default" value="Stop">
            <input type="submit" name="remove" onclick="display('remove')" class="btn btn-default" value="Remove">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#addModal">Add</button>
        </div>
        <?php include 'addmod.php'?>
        <p></p>
        <script>
            function toggle(source) {
                var checkboxes = document.getElementsByName('checkbox[]');
                for(var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
        <div id="table-wrap">
            <table id="download-list" class="table table-bordered table-striped table-hover" data-spy="scroll" style="width:90%;margin:auto">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggle(this)" /></th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Size</th>
                        <th>Done</th>
                        <th>Down Speed</th>
                        <th>Up Speed</th>
                        <th style="width:120px">ETA</th>
                        <th>Ratio</th>
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
                                    if(arr.status === "Stopped") {
                                        location.reload();
                                    }
                                    $('#status<?php echo $val ?>').html(arr.status);
                                    $('#percent<?php echo $val ?>').html(Number(arr.percent).toFixed(2) + '%');
                                    $('#down<?php echo $val ?>').html(arr.down);
                                    $('#up<?php echo $val ?>').html(arr.up);
                                    $('#ratio<?php echo $val ?>').html(Number(arr.ratio).toFixed(2));
                                    $('#eta<?php echo $val ?>').html(arr.eta);
                                });
                            }
                            var isActive = <?php boolActive($val) ?>;
                            if(isActive) {
                                setInterval(function(){rate<?php echo $val ?>()}, 1000);
                            }
                        </script>
                        <tr id="<?php echo $val ?>">
                            <td><input type='checkbox' name='checkbox[]' value='<?php echo $val ?>' /></td>
                            <td><a href="stats.php?hash=<?php echo $val ?>"><?php echo getName($val)?></a></td>
                            <td><div id="status<?php echo $val ?>"><?php echo getStatus($val) ?></div></td>
                            <td><?php echo getSize($val) ?></td>
                            <td><div id="percent<?php echo $val ?>"><?php printf("%.2f%%", getPercentDone($val)) ?></div></td>
                            <td><div id="down<?php echo $val ?>"></div></td>
                            <td><div id="up<?php echo $val ?>"></div></td>
                            <td><div id="eta<?php echo $val ?>" style="width:120px">∞</div></td>
                            <td><div id="ratio<?php echo $val ?>"><?php printf("%.2f", getRatio($val)) ?></div></td>
                        </tr>
                <?php } unset($val); ?>
            </table>
        </div>
        <p></p>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>