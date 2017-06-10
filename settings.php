<?php
    require 'connect.php';
    require 'rpccalls.php';

    $customLocationArr = array();
    populateLocationArray();

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

    if(isset($_GET["cust_submit"])) {
        addCustomLocation($_GET["cust_name"], $_GET["cust_loc"]);
        header("location: settings.php");
    }

    if(isset($_POST["remove_locations"])) {
        global $customLocationArr;
        foreach($_POST['checkbox'] as $item) {
            unset($customLocationArr[$item]);
        }
        unset($item);
        repopulateArray();
        header("location: settings.php");
    }

    function repopulateArray() {
        $toWrite = serialize($GLOBALS['customLocationArr']);
        file_put_contents("locations.ser", $toWrite);
    }

    function populateLocationArray() {
        if(file_exists("locations.ser")) {
            $file = file_get_contents("locations.ser");
            $arr = unserialize($file);
            $GLOBALS['customLocationArr'] = array_merge($GLOBALS['customLocationArr'], $arr);
        }
    }

    function getCustomLocations() {
        return $GLOBALS['customLocationArr'];
    }

    function addCustomLocation($name, $location) {
        global $customLocationArr;
        if(array_key_exists($name, $customLocationArr)) {
            echo "Name already exists!";
        }
        else {
            $customLocationArr[$name] = $location;
        }
        $toWrite = serialize($customLocationArr);
        file_put_contents("locations.ser", $toWrite);
    }
?>
<html>
<head>
    <title>rTfront</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
        <div id="rate-limits" style="width: 49%;float:left;min-height: 300px;margin-bottom:25px">
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
        <div id="location" style="width: 49%;float:right;min-height: 300px;margin-bottom:25px">
            <h4><strong>Default Download Location</strong></h4>
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
        <script>
            function toggleLocations(source) {
                var checkboxes = document.getElementsByName('checkbox[]');
                for(var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
        <div id="customlocations" style="width:49%;margin-bottom:25px">
            <div style="margin-bottom:25px">
                <h4><strong>Custom Locations</strong></h4>
                <p></p>
                <form>
                    <label>
                        <span style="display:inline-block">
                            Enter Custom Name: <input type="text" name="cust_name" class="form-control" required />
                        </span>
                        <span style="display:inline-block">
                            Enter Download Location: <input type="text" name="cust_loc" class="form-control" required/>
                        </span>
                    </label>
                    <input type="submit" name="cust_submit" class="btn btn-success" style="display:inline-block" value="Add">
                </form>
            </div>
            <p></p>
            <h4><strong>Current Custom Locations</strong></h4>
            <p></p>
            <form id="locations-form-wrap" method="post">
            <table id="custom-location-table" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" onclick="toggleLocations(this)" /></th>
                        <th>Name</th>
                        <th>Custom Location</th>
                    </tr>
                </thead>
                <?php
                    foreach($customLocationArr as $key => $value) {
                        echo '<tr>';
                        echo '<td>' . "<input type='checkbox' name='checkbox[]' value='$key' />" . '</td>';
                        echo '<td>' . $key . '</td>';
                        echo '<td>' . $value . '</td>';
                        echo '</tr>';
                    }
                    unset($key);
                    unset($value);
                ?>
            </table>
            <input type="submit" name="remove_locations" class="btn btn-success" value="Remove">
            </form>
        </div>
    </div>
</body>
</html>
