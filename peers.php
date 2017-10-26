<div id="peers">
    <?php if(boolActive($hash) == true) {?>
        <script>
            function peers() {
                $.get("scripts/phpcalls.php?method=getPeers&hash=<?php echo $hash ?>", function(data) {
                    data = jQuery.parseJSON(data);
                    $('#peer_table .pRow').remove();
                    $('#pMobile .card-panel').remove();
                    var table = $('#peerBody');
                    var mobileTable = $('#pMobile');
                    for(var i = 0; i < data.length; i++) {
                        table.append("<tr class='pRow'>" +
                            "<td>" + data[i][1] + "</td>" +
                            "<td>" + data[i][2] + "</td>" +
                            "<td>" + data[i][3] + "</td>" +
                            "<td>" + Number(data[i][4]).toFixed(2) + "%" + "</td>" +
                            "<td>" + data[i][5] + "</td>" +
                            "<td>" + data[i][6] + "</td>" +
                            "</tr>"
                        );
                        mobileTable.append("<div class='card-panel'>" +
                            "<div class='valign-wrapper'>" +
                            "<strong>" + (i + 1) + ".&nbsp;" + "</strong>" + data[i][1] +
                            "<strong>&emsp;Port:&nbsp;</strong>" + data[i][2] +
                            "</div>" +
                            "<div class='valign-wrapper'>" +
                            "<strong>" + "Client:&nbsp;" + "</strong>" + data[i][3] +
                            "<strong>&emsp;Done:&nbsp;</strong>" + Number(data[i][4]).toFixed(2) + "%" +
                            "</div>" +
                            "<div class='valign-wrapper'>" +
                            "<strong>" + "Down:&nbsp;" + "</strong>" + data[i][5] +
                            "<strong>&emsp;Up:&nbsp;</strong>" + data[i][6] +
                            "</div>" +
                            "</div>"
                        );
                    }
                });
            }
            setInterval(function(){peers()}, 3000);
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
        <div class="hide-on-large-only">
            <div id="pMobile"></div>
        </div>
    <?php }
    else { ?>
        <div class="card-panel">
            No Connected Peers
        </div>
    <?php } ?>
</div>