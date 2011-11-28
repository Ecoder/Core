<?php
/*
 * Temporary file to clean out /tree.php
 */
include "code/tree/logic.php";
include "code/tree/tpl.php";

// check path exists ##
if ( $tree['error'] == 1 ) { // folder not found ## 
   
        echo '
        <li id="error">
            <a title="go home: '.$tree['root'].'">
                error! check settings.
            </a>
        </li>';
    
} else { // path OK ##
    
    if ($tree['path_up_ok']) { // link back up ##    
    
        echo '
        <li id="up">
            <a title="go back up a level: '.$tree['path_up'].'">
                up
            </a>
        </li>';
    
    } // up ##
    
    // dir loop ##
		$nodes=tempRefactorOutput(ecoder_tree($code['root'].$tree['path'], 1, $_SESSION['tree_hidden']), true);
		
		foreach ($nodes as $node) {
			//Later store all this info in js...
			?>
			<li 
				class="folder" 
				data-name="<?php echo $node->name; ?>"
				data-type="<?php echo $node->type; ?>"
				data-action="<?php echo $node->action; ?>"
				data-delete="<?php echo $node->delete; ?>"
				data-rename="<?php echo $node->rename; ?>"
			><?php echo ecoder_short($node->name,24); ?><?php include "code/tree/menu.php"; ?></li>
			<?php
		}
    
    // file loop ##
		$nodes=tempRefactorOutput(ecoder_tree($code['root'].$tree['path'], 0, $_SESSION['tree_hidden']), false);
		
		foreach ($nodes as $node) {
			if ($node->blocked) {
				echo '<li class="unknown">
            '.ecoder_short($node->name, 20).' [UNKNOWN]
        </li>'; 
			} else {
				echo '
        <li class="file '.$node->class_li.'">    
                
            <div class="file">
                <a 
                style="background-image: url('.$code['skin_path'].'design/icon_'.$node->icon.'.png);"
                href="javascript:void(0);" 
                title="'.$node->action.' '.$node->name.'"            
                onclick="top.ecoder_files( \'main\', \''.$node->editable.'\', \''.$tree['path'].'\', \''.$node->name.'\', \''.$node->extc.'\' );">
                    '.ecoder_short($node->name, 22).'
                </a>
            </div>';
                        
            // pop out menu ##  
            include "code/tree/menu.php";
                    
        echo '                   
        </li>';  
			}
		}
}

    echo '
    </ul>';

		  echo '	    
    </body>
</html>';