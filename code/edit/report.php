<?php

/*
save report
*/

if ( $main['report'] ) { // save report ##
        
    echo '
    <script type="text/javascript">';
        
        if ( $main['report_code'] == 1 ) { // ok ##
        
        echo '
        top.ecoder_tree ( \'tree\', \'reload\' ); // refresh tree ##
        top.ecoder_note ( \'note\', \''.$main['report'].'\', \'5\', \'block\' ); // show report ##';
        
        } elseif ( $main['report_code'] == 0 ) { // failed ##

        echo '
        ecoder_display ( \'save\', \'block\', \''.$main['report'].'\', \'0\' );';

        }
        
    echo '    
    </script>';

    #echo '<div id="save_report">'.$main['report'].'</div>';    

} // report filed ##

?>
