<?php
    require 'connect.php';
    require 'rpccalls.php';

    if(isset($_GET["submit_location"])) {
        $link = $_GET["directory_submit"];
        setDefaultDirectory($link);
        header("location: settings.php");
    }

    if(isset($_GET["submit_global_rates"])) {
        if(isset($_GET["max_down"])) {
            setMaxDownRate($_GET["max_down"]);
        }
        if(isset($_GET["max_up"])) {
            setMaxUpRate($_GET["max_up"]);
        }
        if(isset($_GET["max_ratio"])) {
            setMaxRatio($_GET["max_ratio"]);
        }
        header("location: settings.php");
    }
?>
<html>
<head>
    <title>rTfront</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-inverse" role="navigation">
        <a href="index.php" class="navbar-brand" style="margin-left: 5%">rTfront</a>
        <ul class="nav navbar-nav">
            <li>
                <a href="#">Settings</a>
            </li>
        </ul>
    </nav>
    <p></p>
    <div id="settings-wrap" style="width:60%;margin:auto">
        <div id="rate-limits" style="width: 49%;float:left;min-height: 300px;margin-bottom:15px">
            <h4><strong>Global Rate Limits</strong></h4>
            <form>
                <label>
                    <span style="display:inline-block">
                        Max Down Rate (KB/s): <input type="text" name="max_down" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php echo getMaxDownRate() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <label>
                    <span style="display:inline-block">
                        Max Up Rate (KB/s): <input type="text" name="max_up" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php echo getMaxUpRate() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <label>
                    <span style="display:inline-block">
                        Max Ratio: <input type="text" name="max_ratio" class="form-control" />
                    </span>
                    <span style="display:inline-block">
                        Current: <input type="text" class="form-control" placeholder="<?php echo getMaxRatio() ?>" readonly/>
                    </span>
                </label>
                <p></p>
                <input type="submit" name="submit_global_rates" class="btn btn-success" value="Apply">
            </form>
        </div>
        <p></p>
        <div id="location" style="width: 49%;float:right;min-height: 300px;margin-bottom:15px">
            <h4><strong>Download Location</strong></h4>
            <p></p>
            <form>
                <label>
                    Input Default Directory: <input type="text" name="directory_submit" class="form-control" required />
                    Current: <input type="text" class="form-control" placeholder="<?php echo getDefaultDirectory() ?>" readonly/>
                </label>
                <p></p>
                <input type="submit" name="submit_location" class="btn btn-success" value="Apply">
            </form>
        </div>
        <div id="customlocations" style="width:49%;float:left;margin-bottom:15px">
            <h4><strong>Custom Locations</strong></h4>
            <p></p>
            <form>
                <!--<label>
                    Enter Custom Name: <input type="text" name="" class="form-control" required />
                    Enter Download Location: <input type="text" name="" class="form-control" />
                </label>
                <p></p>
                <input type="submit" name="" class="btn btn-success" value="Apply">-->
            </form>
        </div>
    </div>
</body>
</html>
