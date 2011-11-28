<?php

/*
main page - include all items, in iframes ##
*/

include "code.php"; // single included settings file ##
include "code/base/header.php"; // html header, branding ##

    // build main structure ##
    echo '
    </head>
    <body>
		<div id="dialogoverlay"></div> <!--Also used for compatability-->
	<div id="dialog"><span id="closedialog">&nbsp;</span><div id="dialogcontent"></div></div>
	';

    // test if javascript enabled ##
    include "code/base/loader.php";
        ?>
<div id="tree">
	<h2>Ecoder</h2>
</div>
        <?php echo '<div id="content">';

            // include required tabs mark-up ##
            include "code/tabs/build.php";

        echo '
        </div>';

    // notes and messages ##
     echo '<div id="note">no messages from ecoder.</div>';

echo '</body>
</html>';