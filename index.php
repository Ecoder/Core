<?php

/*
main page - include all items, in iframes ##
*/

include "code.php"; // single included settings file ##
include "code/base/header.php"; // html header, branding ##

    // build main structure ##
    echo '</head>
    <body>';

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
echo '</body>
</html>';