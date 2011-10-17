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

function CmEditor(options) {
	this.init(options);
}

CmEditor.prototype={
	options:{
		tArea:"",
		mime:"text/plain",
		readOnly:false
	},
	codemirror:null,
	init:function(options) {
		var self=this;
		this.options=options;
		this.codemirror=new CodeMirrorUI(
			this.options.tArea,
			{
				path:'../../plug/codemirror-ui/js/', 
				searchMode:'popup' 
			},
			{
				mode:this.options.mime,
				indentWithTabs:true,
				lineNumbers:true,
				readOnly:this.options.readOnly/*,
				onChange:ecoder_changed*/
			//TODO Autosaving currently disabled
			}
		);
		/*this.codemirror=CodeMirror.fromTextArea(this.options.tArea,{
			mode:this.options.mime,
			indentWithTabs:true,
			lineNumbers:true,
			readOnly:this.options.readOnly*//*,
			onChange:ecoder_changed*/
		//TODO Autosaving currently disabled
		//});
		$("#save_1").click(function() {self.save(self); });
		//And now we can go and add events on this thing for the buttons
		//Accessing the cminstance w/ this.codemirror
	},
	save:function(self) {
		self.codemirror.mirror.save();
		
		if (typeof(top.hide)!="undefined") { clearTimeout(top.hide); }

		ecoder_display('save','block','saving',1);

		$.ajax({
			data:{
				ecoder_path:ecoder_path,
				ecoder_file:ecoder_file,
				ecoder_content:($(self.options.tArea).val())
			},
			url:"code/save/edit.php",
			type:'POST',
			success:function() {
				$("#save").html("saved");
			}
		});

		/*if ( ecoder_autosave == 1 && ecoder_save_on == 1 ) {      
			var autosave=setTimeout("ecoder_save()",ec_autosave_time); // call function every x seconds ##
		}*/

		// reset change counter ##
		ecoder_changed_stop();
	}
};

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

$(document).ready(function() {
	//thisTabCodemirror=initCodeMirror($("#editarea")[0],$("body").attr("data-mime"),($("body").attr("data-ro")=="1" ? true : false));
	//thisTabCodemirror.getOption("readOnly");
	new CmEditor({
		tArea:$("#editarea")[0],
		mime:$("body").attr("data-mime"),
		readOnly:($("body").attr("data-ro")=="1" ? true : false)
	})
	$("html").keypress(function(e) { 
		var x=e.target; 
	});
});