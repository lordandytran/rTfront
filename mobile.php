<?php
if(!empty($downloads)) {
    foreach($download_list as $hash) { ?>
    <div class="card-panel">
        <a href="stats.php?hash=<?php echo $hash['hash'] ?>" class="truncate"><?php echo $hash['name']?></a>
        <div class="valign-wrapper">
            <input type="checkbox" class="filled-in mobile-check" name='checkbox[]' onclick="$('#<?php echo $hash['hash'] ?>').click()" id="c<?php echo $hash['hash'] ?>">
            <label for="c<?php echo $hash['hash'] ?>"></label>
            <span class="status<?php echo $hash['hash'] ?>"><?php echo $hash['status'] ?></span>
            <span><strong>&emsp;Done: </strong><span class="percent<?php echo $hash['hash'] ?>"><?php printf("%.2f%%", $hash['percent']) ?></span></span>
            <span><strong>&emsp;ETA: </strong><span class="eta<?php echo $hash['hash'] ?>">∞</span></span>
        </div>
    </div>
<?php }} else { ?>
    <div class="card-panel">
        No Current Downloads
    </div>
<?php } ?>