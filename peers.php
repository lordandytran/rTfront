<div id="peers">
    <?php if(boolActive($hash) == true) {?>
        <script>
            function peers() {
                $.get("scripts/phpcalls.php?method=getPeers&hash=<?php echo $hash ?>", function(data) {
                    data = jQuery.parseJSON(data);
                    var table = $('#peerBody');
                    var mobleTable = $('');
                    $('peer_table tbody').remove();
                    for(var i = 0; i < data.length; i++) {
                        table.append("<tr>" +
                            "<td>" + data[i][1] + "</td>" +
                            "<td>" + data[i][2] + "</td>" +
                            "<td>" + data[i][3] + "</td>" +
                            "<td>" + data[i][4] + "</td>" +
                            "<td>" + data[i][5] + "</td>" +
                            "<td>" + data[i][6] + "</td>" +
                            "</tr>"
                        );
                    }
                });
            }
            setInterval(function(){peers()}, 1000);
        </script>
        <table class="bordered highlight hide-on-med-and-down" id="peer_table">
            <thead>
            <tr>
                <th>Address</th>
                <th>Port</th>
                <th>Client</th>
                <th>Done</th>
                <th>Down Speed</th>
                <th>Up Speed</th>
            </tr>
            </thead>
            <tbody id="peerBody">
            </tbody>
        </table>
        <div id="test">

        </div>
        <div class="hide-on-large-only">

        </div>
    <?php }
    else { ?>
        <div class="card-panel">
            No Connected Peers
        </div>
    <?php } ?>
</div>