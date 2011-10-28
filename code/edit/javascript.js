//dirty code ahead...
//This can under no circumstances be moved to the topframe without rewriting
var thisTabCodemirror=null;

// loader & script test ##
function ecoder_loaded_edit() {
   thediv = 'load_edit';
   if ( document.removeChild ) {
       var div = document.getElementById( thediv );
           div.parentNode.removeChild( div );           
   } else if ( document.getElementById ) {
       document.getElementById( thediv ).style.display = "none";
   }
}

// onload events ##
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function initCodeMirror(tArea,mimeType,isReadOnly) {
	return CodeMirror.fromTextArea(tArea,{
			mode:mimeType,
			indentWithTabs:true,
			lineNumbers:true,
			readOnly:isReadOnly/*,
			onChange:ecoder_changed*/
		//TODO Autosaving currently disabled
		});
	/*return new CodeMirrorUI(
		tArea,
		{
			path:'../../plug/codemirror-ui/js/', 
			searchMode:'popup' 
		},
		{
			mode:mimeType,
			indentWithTabs:true,
			lineNumbers:true,
			readOnly:isReadOnly*//*,
			onChange:ecoder_changed*/
		//TODO Autosaving currently disabled
		/*}
	);*/
}

if ( top === self ) { document.location='index.php'; } // pop back in ##
var content_changed = 0; // swallow first change ( focus ) to editarea ##
var content_changed_loop = 0; // from first save, first change is good ##
var ecoder_hours; var ecoder_minutes; var ecoder_seconds;
function ecoder_changed() {
	
	// set time ##
	var ecoder_time = new Date()
	ecoder_hours = ecoder_time.getHours()
	ecoder_minutes = ecoder_time.getMinutes()
	ecoder_seconds = ecoder_time.getSeconds()
	if ( ecoder_hours < 10 ) { ecoder_hours = "0" + ecoder_hours; }
	if ( ecoder_minutes < 10 ) { ecoder_minutes = "0" + ecoder_minutes; }
	if ( ecoder_seconds < 10 ) { ecoder_seconds = "0" + ecoder_seconds; }
	ecoder_changed_time = 'edited: ' + ecoder_hours + ':' + ecoder_minutes + ':' + ecoder_seconds; // build message ##
	if ( content_changed > 0 || content_changed_loop == 1 ) { // update ##                  
			ecoder_display('save_changes','block',ecoder_changed_time,0); // show changes div ##                    
	} 
	content_changed ++; // iterate ##

} 