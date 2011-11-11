<?php
@ecoder_request( $_GET['path'], $main['path'], '' ); // path to file to process ##
@ecoder_request( $_GET['report'], $main['report'], '' ); // report / confirm ##
@ecoder_request( $_GET['report_code'], $main['report_code'], '' ); // report / confirm ##

$main['frame_clean'] = ecoder_iframe_clean ( $main['path'].'upload file' ); // for close and refresh

include "code/upload/tpl.php";