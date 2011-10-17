<?php

/* 
block screen if javascript disabled ## 
*/

        echo '
        <div id="load_base" onclick="ecoder_loaded_base();" title="click anywhere to enter ecoder">
            <div class="loading">';
            
                // google ads ##
                if ( $_SESSION['google'] == 1 ) { // show ads ## 
                
                    include "google.ads.php";
                
                }
                
                echo '    
                <div class="one">
                    
                    <div class="logo">
                        <a href="#" title="click anywhere to enter ecoder">
                            <img src="'.$code['skin_path'].'design/logo_loader.png" alt="click anywhere to enter ecoder" border="0">
                        </a>
                    </div>
                    
                    <div class="about">
                        <h1>'.$code['name'].' '.$code['version'].'</h1>
                        <p>ecoder is a <a href="http://www.gmeditor.com" title="planet friendly websites" target="_blank">gmeditor.com</a> product</p>
                    </div>
                    
                    <div class="enter">
                        <a href="http://sourceforge.net/donate/index.php?group_id=251009">
                            <img src="http://images.sourceforge.net/images/project-support.jpg" style="float: left; margin: 7px -42px 0px 39px; border: 0px; width: 88px; height: 32px;" alt="Support This Project" />
                        </a>
                        <a href="#" title="enter ecoder">
                            <img src="'.$code['skin_path'].'design/enter.png" alt="enter ecoder" border="0" />
                        </a>
                    </div>
               
               </div>
               <div class="two">
                    
                    <div class="how">
                        <h1>what ecoder is</h1>
                        <p>ecoder is an open-source web-based code editor, with real-time colour syntax highlighting, which allows multiple documents to be edited directly online at the same time. <a href="docs/" target="_blank" title="read full instructions">more</a> | <a href="http://sourceforge.net/projects/ecoder/" target="_blank" title="download ecoder source code from sourceforge">download</a> | <a href="https://sourceforge.net/forum/?group_id=251009" title="comments and suggestions" target="_blank">forum</a></p>
                        <p>ecoder made possible thanks to <a href="https://sourceforge.net/projects/editarea/" title="javascript syntax highlighting by editarea" target="_blank">editarea</a> javascript wizardy.</p>
                    </div>         
                    
                    <div class="requires">
                        <h1>requirements</h1>
                        <p>ecoder works with most standards compliant browsers which have javascript enabled. internet explorer 6 is not supported.</p>
                    </div> 
                    
                    <div class="shortcuts">
                        <h1>keyboard shortcuts</h1>                           
                            
                            <div class="one">
                                <h2>tree</h2>
                                <p><strong>home</strong> - ctrl + 1</p>
                                <p><strong>add file</strong> - ctrl + 2</p>
                                <p><strong>add folder</strong> - ctrl + 3</p>
                                <p><strong>hidden files</strong> - ctrl + 4</p>
                                <p><strong>file upload</strong> - ctrl + 5</p>
                                <p><strong>refresh</strong> - ctrl + 6</p>
                            </div>
                            
                            <div class="two">
                                <h2>editor</h2>
                                <p><strong>save</strong> - ctrl + s</p>
                                <p><strong>undo</strong> - ctrl + z</p>
                                <p><strong>redo</strong> - ctrl + y</p>
                                <p><strong>select all</strong> - ctrl + a</p>
                                <p><strong>find</strong> - ctrl + f</p>
                                <p><strong>replace</strong> - ctrl + r</p>
                            </div>

                    </div>                 
                    
                </div>
            </div>
        </div>';

?>
