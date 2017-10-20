<?php
    require 'scripts/connect.php';
    require 'scripts/rpccalls.php';

    $error = "";
    $track = false;

    function addTrackers() {
        $file = fopen("uploads/trackers.txt", "r");
        $list = array_reverse(array_filter(getDownloadList()));
        while(!feof($file)) {
            $num = getNumTrackers($list[0]);
            addTracker($list[0], $num++, fgets($file));
        }
        fclose($file);
        $GLOBALS['track'] = true;
    }

    function process() {
        $list = array_reverse(array_filter(getDownloadList()));
        $hash = $list[0];
        $time_start = $time_end = time();
        while($time_end - $time_start < 10) {
            if(getName($hash) != "" . $hash . ".meta") {
                return;
            }
            $time_end = time();
        }
        if(!$GLOBALS['track']) {
            addTrackers();
            process();
        }
        $GLOBALS['error'] = "Cannot track torrent";
        stopTorrent($hash);
        return;
    }

    if(isset($_POST["submit_link"])) {
        $link = "";
        if(isset($_FILES['torrent_file'])) {
            $dir = "uploads/" . $_FILES['torrent_file']['name'];
            if(move_uploaded_file($_FILES['torrent_file']['tmp_name'], $dir)) {
                $link = "/var/www/html/rTfront/" . $dir;
            }
        }
        if($link == '') {
            $link = $_POST["magnet_link"];
        }
        if(isset($_POST['locationRadio'])) {
            $val = $_POST['locationRadio'];
            $default = getDefaultDirectory();
            setDefaultDirectory($val);
            createTorrent($link);
            process();
            setDefaultDirectory($default);
        }
        else {
            createTorrent($link);
            process();
        }
        header("location: index.php");
    }

    $download_list = array_reverse(array_filter(getDownloadList()));

?>
<html>
    <head>
        <title>rTfront</title>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>
        <script>
            $(document).ready(function(){
                $(".button-collapse").sideNav();
                $('.modal').modal();
                $('#addModal').modal('open');
            });
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
                window.setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        </script>
        <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper">
                <a href="#" data-activates="menu" class="left button-collapse"><i class="material-icons">menu</i></a>
                <ul class="left hide-on-med-and-down">
                    <li><a href="settings.php">Settings</a></li>
                </ul>
                <ul class="right">
                    <li><a data-activates="add" class="dropdown-button"><i class="material-icons">more_vert</i></a></li>
                </ul>
                <ul class="dropdown-content" id="add">
                    <li><a class="modal-trigger" href="#addmodal">Add</a></li>
                    <li><a onclick="$('#check-all').click()">Select All</a></li>
                </ul>
                <ul class="right">
                    <li><a class="menu-icons" onclick="display('start')"><i class="material-icons">play_circle_outline</i></a></li>
                    <li><a class="menu-icons" onclick="display('stop')"><i class="material-icons">stop</i></a></li>
                    <li><a class="menu-icons" onclick="display('remove')"><i class="material-icons">delete</i></a></li>
                </ul>
            </div>
        </nav>
        </div>
        <ul class="side-nav" id="menu">
            <li><a href="settings.php">Settings</a></li>
        </ul>
        <?php include 'addmodal.php'?>
        <?php echo $error;?>
        <script>
            function rates() {
                $.get('scripts/phpcalls.php?method=getRates', function(data) {
                    data = jQuery.parseJSON(data);
                    for(var i = 0; i < data.length; i++) {
                        if(data[i][1] === "Stopped") {
                            location.reload();
                        }
                        $(".status" + data[i][0]).html(data[i][1]);
                        $(".percent" + data[i][0]).html(Number(data[i][2]).toFixed(2) + '%');
                        $(".down" + data[i][0]).html(data[i][3]);
                        $(".up" + data[i][0]).html(data[i][4]);
                        $(".eta" + data[i][0]).html(data[i][5]);
                        $(".ratio" + data[i][0]).html(Number(data[i][6]).toFixed(2));
                    }
                });
            }
            var isActive = <?php echo fullActive() ?>;
            if(isActive) {
                setInterval(function(){rates()}, 1000);
            }
        </script>
        <script>
            function toggle(source) {
                var checkboxes = document.getElementsByName('checkbox[]');
                for(var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
        <div class="hide-on-large-only" id="mobile-display">
        <?php include 'mobile.php'?>
        </div>
        <table class="bordered highlight hide-on-med-and-down">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" onclick="toggle(this)" class="filled-in" id="check-all"/>
                        <label for="check-all"></label>
                    </th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Size</th>
                    <th>Done</th>
                    <th>Down Speed</th>
                    <th>Up Speed</th>
                    <th style="width: 150px;">ETA</th>
                    <th>Ratio</th>
                </tr>
            </thead>
        <?php
            foreach($download_list as $val) { ?>
                <tr>
                    <td>
                        <input type="checkbox" class="filled-in desktop-check" name='checkbox[]' onclick="$('#c<?php echo $val?>').click()" value="<?php echo $val?>" id="<?php echo $val?>" />
                        <label for="<?php echo $val?>"></label>
                    </td>
                    <td><a href="stats.php?hash=<?php echo $val?>"><?php echo getName($val)?></a></td>
                    <td><div class="status<?php echo $val ?>"><?php echo getStatus($val) ?></div></td>
                    <td><?php echo getSize($val) ?></td>
                    <td><div class="percent<?php echo $val ?>"><?php printf("%.2f%%", getPercentDone($val)) ?></div></td>
                    <td><div class="down<?php echo $val ?>"></div></td>
                    <td><div class="up<?php echo $val ?>"></div></td>
                    <td style="width: 150px;"><div class="eta<?php echo $val ?>">∞</div></td>
                    <td><div class="ratio<?php echo $val ?>"><?php printf("%.2f", getRatio($val)) ?></div></td>
                </tr>
        <?php } unset($val); ?>
        </table>
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large modal-trigger red" href="#addmodal">
                <i class="large material-icons">add</i>
            </a>
        </div>
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>