<?php foreach($download_list as $hash) { ?>
    <div class="card-panel">
        <a href="stats.php?hash=<?php echo $hash?>" class="truncate"><?php echo getName($hash)?></a>
        <div class="valign-wrapper">
            <input type="checkbox" class="filled-in mobile-check" name='checkbox[]' onclick="$('#<?php echo $hash?>').click()" id="c<?php echo $hash?>">
            <label for="c<?php echo $hash?>"></label>
            <span class="status<?php echo $hash ?>"><?php echo getStatus($hash) ?></span>
            <span><strong>&emsp;Done: </strong><span class="percent<?php echo $hash ?>"><?php printf("%.2f%%", getPercentDone($hash)) ?></span></span>
            <span><strong>&emsp;ETA: </strong><span class="eta<?php echo $hash ?>">∞</span></span>
        </div>
    </div>
<?php } unset($hash); ?>