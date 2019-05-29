<?php

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$page = file_get_contents($_GET['link'], false, stream_context_create($arrContextOptions));
echo $page;

?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script>
    $(window).ready(function() {
        $('#footer').remove();
        $('#content').eq(0).children().eq(1).find(".row:not(.box-others-infos)").remove();
    });
</script>