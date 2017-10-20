<?php
    $customLocations = array();
    if(file_exists("locations.ser")) {
        $file = file_get_contents("locations.ser");
        $customLocations = unserialize($file);
    }
?>
<div id="addmodal" class="modal modal-fixed-footer">
    <form enctype="multipart/form-data" method="post" id="add-torrent">
    <div class="modal-content">
        <div class="nav-content">
            <ul class="tabs grey lighten-5">
                <li class="tab"><a href="#magnet">Magnet Link</a></li>
                <li class="tab"><a href="#upload">Torrent File</a></li>
            </ul>
        </div>
        <div id="magnet">
            <input placeholder="Paste magnet link" id="magnet_link" name="magnet_link" type="text"/>
        </div>
        <div id="upload">
            <div class="file-field input-field">
                <div class="btn">
                    <span>File</span>
                    <input type="file" name="torrent_file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" id="file_path" placeholder="Torrent file path"/>
                </div>
            </div>
        </div>
        <p></p>
        <?php
        if(!empty($customLocations)) {
            echo '<div class="divider"></div>';
            echo '<p></p>';
            echo '<strong>' . 'Select Custom Location' . '</strong>';
            echo '<p></p>';
            foreach($customLocations as $key => $value) {
                echo '<input class="with-gap" name="locationRadio" value="' . $value. '" type="radio" id="' . $value. '" />';
                echo '<label for="' . $value. '">' . $key . " - ". $value . '</label></br>';
            }
            unset($key);
            unset($value);
        }
        ?>
        <p></p>
        <button class="hide-on-med-and-down right waves-effect waves-light btn" type="submit" name="submit_link">Add</button>
    </div>
    <div class="modal-footer">
        <button type="submit" class="hide-on-large-only waves-effect waves-light btn" name="submit_link">Add</button>
        <a class="modal-action modal-close waves-effect btn-flat">Close</a>
    </div>
    </form>
</div>