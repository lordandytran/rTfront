<?php
require 'connect.php';
require 'rpccalls.php';
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
        <div id="rate-limits">
            <h4><strong>Global Rate Limits</strong></h4>
            <form>
                <label>
                    <span style="float:left">Max Down Rate (KB/s): <input type="text" name="max-down-rate-link" class="form-control" /></span>
                    <span style="float:right">Current: <input type="text" class="form-control" placeholder="<?php echo getMaxDownRate() ?>" disabled/></span>
                </label>
                <p></p>
                <label>
                    <span style="float:left">Max Up Rate (KB/s): <input type="text" name="max-up-rate-link" class="form-control" /></span>
                    <span style="float:right">Current: <input type="text" class="form-control" placeholder="<?php echo getMaxUpRate() ?>" disabled/></span>
                </label>
                <p></p>
                <label>
                    <span style="float:left">Max Ratio: <input type="text" name="max-ratio-rate-link" class="form-control" /></span>
                    <span style="float:right">Current: <input type="text" class="form-control" placeholder="<?php echo getMaxRatio() ?>" disabled/></span>
                </label>
                <p></p>
                <!--<input type="submit" name="submit_globalrates" class="btn btn-success btn" value="Apply" style="">-->
            </form>
        </div>
        <p></p>
        <div id="location">
            <h4><strong>Download Location</strong></h4>
            <p></p>
            <form>
                <label>
                    <span style="float:left">Input Default Directory: <input type="text" name="directory-submit" class="form-control" /></span>
                    <span style="float:right">Current: <input type="text" class="form-control" placeholder="<?php echo getDefaultDirectory() ?>" disabled/></span>
                </label>
                <p></p>
                <!--<input type="submit" name="submit_globalrates" class="btn btn-success btn" value="Apply" style="">-->
            </form>
            <p></p>
            <h4>Advanced</h4>
        </div>
    </div>
</body>
</html>
