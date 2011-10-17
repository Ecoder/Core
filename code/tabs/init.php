<?php

/*
build tabs ##
*/

        echo '
        <script type="text/javascript">

        /* init etabs */ 
        var tabber = new tabber_build ( \'tabber_\', document.getElementById(\'mainTabArea\'), document.getElementById(\'mainPanelArea\') );

        /* build home tab */
        var home = tabber.add( \''.$tabs['home'].'\', \''.$tabs['home'].'_txt\' );
        home.innerHTML = \'<iframe src="edit.php?mode=edit&path=&file='.$tabs['home'].'.txt&type=text&shut=0" id="'.$tabs['home'].'_txt" name="'.$tabs['home'].'_txt" frameborder="0" style="height:100%; width:100%;"></iframe>\';

        </script>';

?>
