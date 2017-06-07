<?php
    $customLocations = array();
    if(file_exists("locations.ser")) {
        $file = file_get_contents("locations.ser");
        $customLocations = unserialize($file);
    }
?>
<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                Add magnet link or path to torrent
            </div>
            <div class="modal-body">
                <div class="submit-wrap">
                    <form id="sub-link" method="post">
                        <div class="submit-form">
                            <label>
                                <input type="text" name="link_sub" class="form-control" placeholder="magnet link" required/>
                            </label>
                            <input type="submit" name="submit_link" class="btn btn-success" value="Add">
                        </div>
                        <p></p>
                        <?php
                            if(!empty($customLocations)) {
                                echo '<strong>' . 'Select Custom Location' . '</strong>';
                                foreach($customLocations as $key => $value) {
                                    echo '<div class="radio">';
                                    echo '<label>' . '<input type="radio" name="locradio" value="' . $value. '">' . $value . '</label>';
                                    echo '</div>';
                                }
                                unset($key);
                                unset($value);
                            }
                        ?>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>