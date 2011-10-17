<?php

/* switch autosave on / off ## */

// compile path for editor swap ##
$editor_swap = 'mode='.$main['mode'].'&file='.$main['file'].'&path='.$main['path'].'&type='.$main['type'].'&shut='.$main['shut'];

// work out default autosave settings ##
$code['autosave_class'] = 'off'; // switched off class ##
$code['autosave_title'] = 'turn on autosave feature'; // advice ##

if ( $code['autosave'] == 1 ) { // code setttings say it's turned on ##
    $code['autosave_class'] = 'on'; // switched on class ##
    $code['autosave_title'] = 'turn off autosave feature'; // advice ##
    
}
                
        echo '        
        <div class="autosave">';

        if ( $main['auto_save'] == 0 || $main['nav']['save'] == 0 ) { // no auto save option ## 

            echo '
            <div class="disabled">
                <span class="note" title="autosave not available">AUTO</span>
            </div>';

        } elseif ( $main['active'] == 1 ) { // file open and active ##
            
            echo '
            <div class="swap_'.$code['autosave_class'].'" id="swap">
                <a href="javascript:void(0);" 
                onclick="ecoder_autosave_switch();" 
                title="'.$code['autosave_title'].'">AUTO</a>
            </div>';
            
        } // options ##
        
        echo '    
        </div>';

        // javascript swapper ##
        echo '
        <script type="text/javascript">

        // swap autosave status ##
        function ecoder_autosave_switch () {
            
            //alert ( \'status = \'+ ecoder_autosave );
            
            // default ##
            var ecoder_autosave_class = \'swap_on\'; // class on ## 
            var ecoder_autosave_new = 1; // new value ##
            var ecoder_autosave_title = \'turn on autosave feature\';
            
            if ( ecoder_autosave == 1 ) { // already on ##
                var ecoder_autosave_class = \'swap_off\'; // class off ##     
                var ecoder_autosave_new = 0; // new value ##
                var ecoder_autosave_title = \'turn off autosave feature\';
                clearTimeout ( autosave ); // stop autosave script ##
                
            } else { // switching on, so call save function ##
                var autosave = setTimeout( "ecoder_save()", 0 );
                
            }
            
            // switch variable 
            ecoder_autosave = ecoder_autosave_new;
            
            // update button ##
            var ecoder_autosave_id; // declare ##
            ecoder_autosave_id = document.getElementById ( \'swap\' ); // get element ##
            ecoder_autosave_id.className = ecoder_autosave_class; // swap class ##
            ecoder_autosave_id.childNodes[0].title = ecoder_autosave_title; // TODO -- set a title tag ##
            
            //alert ( \'new status = \'+ ecoder_autosave + \' | class = \' +ecoder_autosave_class );
            
        }

        </script>';

?>
