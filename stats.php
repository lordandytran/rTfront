<?php
    require 'scripts/connect.php';
    require 'scripts/rpccalls.php';
    $hash = $_GET['hash'];
    $numfiles = getFileCount($hash);
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
            $('select').material_select();
        });
        function display(method) {
            var params = "scripts/phpcalls.php?method=" + method + "&hash=<?php echo $hash ?>";
            $.get(params);
            window.setTimeout(function() {
                location.reload();
            }, 1000);
        }
    </script>
    <nav class="nav-extended">
        <div class="nav-wrapper">
            <a href="#" data-activates="menu" class="left button-collapse"><i class="material-icons">menu</i></a>
            <ul class="left hide-on-med-and-down">
                <li><a href="index.php">Home</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
            <ul class="right">
                <li><a data-activates="add" class="dropdown-button"><i class="material-icons">more_vert</i></a></li>
            </ul>
            <ul class="dropdown-content" id="add">
                <li><a href="#">Add Trackers</a></li>
            </ul>
            <ul class="right">
                <li><a class="menu-icons" onclick="display('start')"><i class="material-icons">play_circle_outline</i></a></li>
                <li><a class="menu-icons" onclick="display('stop')"><i class="material-icons">stop</i></a></li>
            </ul>
        </div>
        <div class="nav-content">
            <ul class="tabs tabs-transparent">
                <li class="tab"><a class="active" href="#main">Main</a></li>
                <li class="tab"><a href="#files">Files</a></li>
                <li class="tab"><a href="#trackers">Trackers</a></li>
            </ul>
        </div>
    </nav>
    <ul class="side-nav" id="menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="settings.php">Settings</a></li>
    </ul>
    <?php include 'trackers.php' ?>
    <script>
        function stats() {
            $.get("scripts/phpcalls.php?method=getStats&hash=<?php echo $hash ?>", function(data) {
                data = jQuery.parseJSON(data);
                if(data[0][0] === "Stopped") {
                    location.reload();
                }
                $(".status").html(data[0][0]);
                $(".percent").html(Number(data[0][1]).toFixed(2) + '%');
                $(".down").html(data[0][2]);
                $(".up" ).html(data[0][3]);
                $(".eta").html(data[0][4]);
                $(".ratio").html(Number(data[0][5]).toFixed(2));
                for(var i = 0; i < <?php echo $numfiles?>; i++) {
                    $(".file" + i).html(Number(data[1][i]).toFixed(2) + '%');
                }
            });
        }
        var isActive = <?php echo boolActive($hash) ?>;
        if(isActive) {
            setInterval(function(){stats()}, 1000);
        }
    </script>
    <div id="main">
        <table class="bordered highlight hide-on-med-and-down">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Size</th>
                    <th>Done</th>
                    <th>Down Speed</th>
                    <th>Up Speed</th>
                    <th style="width: 150px;">ETA</th>
                    <th>Ratio</th>
                    <th>Priority</th>
                </tr>
            </thead>
                <tr>
                    <td><?php echo getName($hash)?></td>
                    <td><div class="status"><?php echo getStatus($hash) ?></div></td>
                    <td><?php echo getSize($hash) ?></td>
                    <td><div class="percent"><?php printf("%.2f%%", getPercentDone($hash)) ?></div></td>
                    <td><div class="down"></div></td>
                    <td><div class="up"></div></td>
                    <td style="width: 150px;"><div class="eta">∞</div></td>
                    <td><div class="ratio"><?php printf("%.2f", getRatio($hash)) ?></div></td>
                    <td><?php echo getPriority($hash) ?></td>
                </tr>
        </table>
        <div class="hide-on-large-only">
            <ul class="collection">
                <li class="collection-item truncate"><?php echo getName($hash)?></li>
                <li class="collection-item"><strong>Status: </strong><span class="status"><?php echo getStatus($hash) ?></span></li>
                <li class="collection-item"><strong>Size: </strong><?php echo getSize($hash) ?></li>
                <li class="collection-item"><strong>Done: </strong><span class="percent"><?php printf("%.2f%%", getPercentDone($hash)) ?></span></li>
                <li class="collection-item"><strong>Down Speed: </strong><span class="down"></span></li>
                <li class="collection-item"><strong>Up Speed: </strong><span class="up"></span></li>
                <li class="collection-item"><strong>ETA: </strong><span class="eta">∞</span></li>
                <li class="collection-item"><strong>Ratio: </strong><span class="ratio"><?php printf("%.2f", getRatio($hash)) ?></span></li>
                <li class="collection-item"><strong>Priority: </strong><?php echo getPriority($hash) ?></li>
            </ul>
        </div>
    </div>
    <div id="files">
        <table class="bordered highlight hide-on-med-and-down">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th style="width: 150px;">Done</th>
                    <th>Priority</th>
                </tr>
            </thead>
            <?php
            for($i = 0; $i < $numfiles; $i++) {
                $str = $hash . ":f" . $i; ?>
                <tr>
                    <th scope="row"><?php echo ($i + 1) ?></th>
                    <td><?php echo getFilePath($str) ?></td>
                    <td><?php echo getFileSize($str) ?></td>
                    <td style="width: 150px;"><div class="file<?php echo $i ?>"><?php printf("%.2f%%", getFilePercentDone($str)) ?></div></td>
                    <td><?php echo getFilePriority($str) ?></td>
                </tr>
            <?php }?>
        </table>
        <div class="hide-on-large-only">
            <?php for($i = 0; $i < $numfiles; $i++) {
                $str = $hash . ":f" . $i; ?>
                <div class="card-panel">
                    <div class="truncate"><?php echo "<strong>" . ($i + 1) . ".</strong> ". getFilePath($str)?></div>
                    <div class="valign-wrapper">
                        <span><strong>Size: </strong><span><?php echo getFileSize($str) ?></span></span>
                        <span><strong>&emsp;Done: </strong><span class="file<?php echo $i ?>"><?php printf("%.2f%%", getPercentDone($hash)) ?></span></span>
                        <span><strong>&emsp;Priority: </strong><?php echo getFilePriority($str) ?></span>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <div id="trackers">
        <ul class="collection with-header">
            <li class="collection-header"><h5>Trackers</h5></li>
            <?php
            $trackers = getNumTrackers($hash);
            for($i = 0; $i < $trackers; $i++) {
                $temp = $hash . ":t" . $i; ?>
                <li class="collection-item"><?php echo getTracker($temp) ?></li>
            <?php }?>
        </ul>
    </div>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>
