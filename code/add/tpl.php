<!DOCTYPE html>
<html>
	<head>
		<title>e<?php echo $html['title']; ?></title>
		<meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
		<script src="http://code.jquery.com/jquery-<?php echo $code['jQuery']; ?>.min.js"></script>
		<script src="code/base/shortcuts.js"></script>
    <script src="code/add/add.js"></script>
		<link href="code/add/add.css" rel="stylesheet" />
		
		<script>
			var ecoder_autosave="<?php echo $code['autosave']; ?>";
			var ecoder_iframe="<?php echo $main['frame_clean']; ?>";
			var ecoder_path="<?php echo $main['path']; ?>";
			var ecoder_file="<?php echo $main['file']; ?>";
			var ecoder_type="<?php echo $main['type']; ?>";
			var ecoder_save_on="0";
			top.ecoder_save_type="add";
		</script>
	</head>
	<body>
<?php
echo ' 
<div class="edit_nav">        
    <div class="options">       
        <div id="save_1">';
            echo '
            <a href="javascript:void(0);" 
            onclick="ecoder_save();" 
            title="save '.$html['title'].'">SAVE</a>';     
        echo '           
        </div>';    
        echo ' <div id="close_1">';
            echo '
            <a href="javascript:void(0);"             
            onclick="top.ecoder_files( \''.$main['frame_clean'].'\', \'close\', \''.$main['path'].'\', \''.$main['file'].'\', \'\', content_changed );"
            title="close '.$html['title'].'">CLOSE</a>';
        echo '
        </div>
        
        <div class="details">
            '.$main['path'].'<strong>'.$html['title'].'</strong>
        </div>
				
				<div id="save">enter a '.$main['type'].' name</div>
					
				<div id="save_changes"></div>';
	    echo '     
    </div>    
</div>';			
			
			    echo '
			<div class="content_wrapper">
    <div class="edit_area">
					<div class="edit_form">
    <form name="add" action="code/add/save.php" method="post">
		<input type="hidden" name="path" value="'.$main['path'].'">
      <h1>create a new '.$main['type'].'</h1>';
		if ( $main['type'] == 'file' ) { // add file ##
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>enter a name, select a file type and press save.</p>';
        
        }
        
        echo '    
        <div class="filename">
            <input type="text" class="text" name="file" value="new_file"></input>
        </div>    

        <div class="dot">.</div>  

        <div class="type">
            <select name="type" class="select">';
                
            // loop all allowed file types ##    
            foreach ( $array_types as $t => $value ) {                
                echo '<option value="'.$array_types[$t].'" />'.$_SESSION['tree_file_types'][$t].'';                
            }
            
            echo '
            </select>
        </div>';
		}	 elseif ( $main['type'] == 'folder' ) { // add folder ## 
        if ( $main['report_code'] != 1 ) { // not done yet ##
        
        echo '
        <p>enter a name and press save</p>';
        
        }

        echo '
        <div class="filename">
            <input type="text" class="text" name="file" value="new_folder"></input>
        </div>';

		}
					
					echo '    </form> 
</div>
    </div></div>';
?>
	</body>
</html>