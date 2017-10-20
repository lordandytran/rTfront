<?php
    require 'scripts/connect.php';
    require 'scripts/rpccalls.php';

    $customLocationArr = array();
    populateLocationArray();

    if(isset($_POST['global_rates'])) {
        setMaxDownRate($_POST['down_rate']);
        setMaxUpRate($_POST['up_rate']);
        if($_POST['ratio'] == "") {

        }
        else {
            setMaxRatio($_POST['ratio']);
        }
    }

    if(isset($_POST['set_directory'])) {
        setDefaultDirectory($_POST['directory']);
    }

    if(isset($_POST["custom_location"])) {
        addCustomLocation($_POST["custom_name"], $_POST["location"]);
    }

    if(isset($_POST["remove_locations"])) {
        global $customLocationArr;
        foreach($_POST['checkbox'] as $item) {
            unset($customLocationArr[$item]);
        }
        unset($item);
        repopulateArray();
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
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body>
    <script>
        $(document).ready(function(){
            $(".button-collapse").sideNav();
        });
    </script>
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper">
                <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="left hide-on-med-and-down">
                    <li><a href="index.php">Home</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <ul class="side-nav" id="mobile">
        <li><a href="index.php">Home</a></li>
    </ul>
    <p></p>
    <div class="row">
        <div class="col s12 m6">
            <div class="card">
                <form name="global_rates" method="post">
                <div class="card-content">
                    <span class="card-title">Global Rate Limits</span>
                    <p></p>
                    <label for="down_rate"><strong>Set Max Down Rate (KB/s):</strong></label>
                    <input placeholder="Current: <?php echo getMaxDownRate() ?>" id="down_rate" name="down_rate" type="text" class="validate">
                    <p></p>
                    <label for="up_rate"><strong>Set Max Up Rate (KB/s):</strong></label>
                    <input placeholder="Current: <?php echo getMaxUpRate() ?>" id="up_rate" name="up_rate" type="text" class="validate">
                    <p></p>
                    <label for="ratio"><strong>Set Max Ratio:</strong></label>
                    <input placeholder="Current: <?php echo getMaxRatio() ?>" id="ratio" name="ratio" type="text" class="validate">
                </div>
                <div class="card-action">
                    <button type="submit" name="global_rates" class="btn waves-effect waves-light">Apply</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card">
                <form name="set_directory" method="post">
                <div class="card-content">
                    <span class="card-title">Default Download Location</span>
                    <p></p>
                    <label for="directory"><strong>Input Default Directory:</strong></label>
                    <input placeholder="Current: <?php echo getDefaultDirectory() ?>" id="directory" name="directory" type="text" class="validate" required>
                </div>
                <div class="card-action">
                    <button type="submit" name="set_directory" class="btn waves-effect waves-light">Apply</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <p></p>
    <script>
        function toggleLocations(source) {
            var checkboxes = document.getElementsByName('checkbox[]');
            for(var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    <div class="row">
        <div class="col s12 m6">
            <div class="card">
                <form name="custom_location" method="post">
                <div class="card-content">
                    <span class="card-title">Custom Locations</span>
                    <p></p>
                    <label for="custom_name"><strong>Enter Custom Name:</strong></label>
                    <input id="custom_name" name="custom_name" type="text" class="validate" required>
                    <p></p>
                    <label for="location"><strong>Enter Download Location:</strong></label>
                    <input id="location" name="location" type="text" class="validate" required>

                </div>
                <div class="card-action">
                    <button type="submit" name="custom_location" class="btn waves-effect waves-light">Add</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card">
                <form name="remove_locations" method="post">
                <div class="card-content">
                    <span class="card-title">Current Custom Locations</span>
                    <table class="bordered highlight">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" onclick="toggleLocations(this)" class="filled-in" id="check-all"/>
                                    <label for="check-all"></label>
                                </th>
                                <th>Name</th>
                                <th>Custom Locations</th>
                            </tr>
                        </thead>
                        <?php
                        foreach($customLocationArr as $key => $value) { ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="checkbox[]" value="<?php echo $key ?>" id="<?php echo $key ?>"/>
                                    <label for="<?php echo $key?>"></label>
                                </td>
                                <td><?php echo $key ?></td>
                                <td><?php echo $value ?></td>
                            </tr>
                        <?php }
                        unset($key);
                        unset($value); ?>
                    </table>
                </div>
                <div class="card-action">
                    <button type="submit" name="remove_locations" class="btn waves-effect waves-light">Remove</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>