<?php
    require 'connect.php';
    require 'rpccalls.php';
    $hash = $_GET['hash'];
    $numfiles = getFileCount($hash);
?>
<html>
<head>
    <title>rTfront</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <script>
        function disp(method) {
            var params = "scripts/phpcalls.php?method=" + method + "&hash=<?php echo $hash ?>";
            $.get(params);
            window.setTimeout(function() {
                location.reload();
            }, 1000);

        }
    </script>
    <nav class="navbar navbar-inverse" role="navigation">
        <a href="index.php" class="navbar-brand" style="margin-left: 5%">rTfront</a>
        <ul class="nav navbar-nav">
            <li>
                <a href="settings.php">Settings</a>
            </li>
        </ul>
    </nav>
    <div style="width:90%;margin:auto">
        <div style="margin-bottom:50px">
            <h4>Detailed Statistics</h4>
            <p></p>
            <script>
                function rate() {
                    $.get("scripts/phpcalls.php?method=getRates&hash=<?php echo $hash ?>", function(data) {
                        var arr = jQuery.parseJSON(data);
                        if(arr.status === "Stopped") {
                            location.reload();
                        }
                        $('#status').html(arr.status);
                        $('#percent').html(Number(arr.percent).toFixed(2) + '%');
                        $('#down').html(arr.down);
                        $('#up').html(arr.up);
                        $('#ratio').html(Number(arr.ratio).toFixed(2));
                        $('#eta').html(arr.eta);
                    });
                }
                var isActive = <?php echo boolActive($hash) ?>;
                if(isActive) {
                    setInterval(function(){rate()}, 1000);
                }
            </script>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Size</th>
                        <th>Done</th>
                        <th>Down Speed</th>
                        <th>Up Speed</th>
                        <th style="width:120px">ETA</th>
                        <th>Ratio</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tr>
                    <td><?php echo getName($hash) ?></td>
                    <td><div id="status"><?php echo getStatus($hash) ?></div></td>
                    <td><?php echo getSize($hash) ?></td>
                    <td><div id="percent"><?php printf("%.2f%%", getPercentDone($hash)) ?></div></td>
                    <td><div id="down"></div></td>
                    <td><div id="up"></div></td>
                    <td style="width:120px"><div id="eta">∞</div></td>
                    <td><div id="ratio"><?php printf("%.2f", getRatio($hash)) ?></div></td>
                    <td><?php echo getPriority($hash) ?></td>
                </tr>
            </table>
            <div style="float:right">
                <input type="submit" name="start" class="btn btn-default" onclick="disp('start')" value="Start">
                <input type="submit" name="stop" class="btn btn-default" onclick="disp('stop')" value="Stop">
            </div>
        </div>
        <p></p>
        <div id="content" style="width:60%;margin-bottom:50px">
            <h4>Content</h4>
            <div id="data"></div>
            <p></p>
            <script>
                function files() {
                    $.get("scripts/phpcalls.php?method=getFileStats&hash=<?php echo $hash ?>", function(data) {
                        var arr = jQuery.parseJSON(data);
                        if(arr.status === "Stopped") {
                            location.reload();
                        }
                    <?php for($i = 0; $i < $numfiles; $i++) { ?>
                        $('#file<?php echo $i ?>').html(Number(arr.<?php echo "file" . $i ?>).toFixed(2) + '%');
                    <?php }?>
                    });
                }
                var isActive = <?php echo boolActive($hash) ?>;
                if(isActive) {
                    setInterval(function(){files()}, 1000);
                }
            </script>
            <table class="table table-bordered table-condensed table-striped">
                <thead>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Done</th>
                    <th>Priority</th>
                </thead>
            <?php
                for($i = 0; $i < $numfiles; $i++) {
                    $str = $hash . ":f" . $i; ?>
                <tr>
                    <th scope="row"><?php echo ($i + 1) ?></th>
                    <td><?php echo getFilePath($str) ?></td>
                    <td><?php echo getFileSize($str) ?></td>
                    <td><div id="file<?php echo $i ?>"><?php printf("%.2f%%", getFilePercentDone($str)) ?></div></td>
                    <td><?php echo getFilePriority($str) ?></td>
                </tr>
            <?php } ?>
            </table>
        </div>
        <p></p>
        <div id="general" style="width:90%;margin-bottom:25px">
            <h4>General</h4>
            <p></p>
            <form>
                <label>
                    <span style="display:inline-block">
                        Max Down Rate (KB/s): <input type="text" name="max_down" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php //echo getMaxDownRate() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <label>
                    <span style="display:inline-block">
                        Max Up Rate (KB/s): <input type="text" name="max_up" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php //echo getMaxUpRate() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <label>
                    <span style="display:inline-block">
                        Max Ratio: <input type="text" name="max_ratio" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php //echo getMaxRatio() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <input type="submit" name="submit_torrent_rates" class="btn btn-success" value="Apply">
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
