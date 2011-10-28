 // swap autosave status ##
/*function ecoder_autosave_switch () {
	// default ##
	var ecoder_autosave_class = 'swap_on'; // class on ## 
	var ecoder_autosave_new = 1; // new value ##
	var ecoder_autosave_title = 'turn on autosave feature';

	if ( ecoder_autosave == 1 ) { // already on ##
		ecoder_autosave_class = 'swap_off'; // class off ##     
		ecoder_autosave_new = 0; // new value ##
		ecoder_autosave_title = 'turn off autosave feature';
		clearTimeout(autosave); // stop autosave script ##
	} else { // switching on, so call save function ##
		var autosave = setTimeout( "ecoder_save()", 0 );
	}

	// switch variable 
	ecoder_autosave = ecoder_autosave_new;

	// update button ##
	var ecoder_autosave_id; // declare ##
	ecoder_autosave_id = document.getElementById ('swap'); // get element ##
	ecoder_autosave_id.className = ecoder_autosave_class; // swap class ##
	ecoder_autosave_id.childNodes[0].title = ecoder_autosave_title; // TODO -- set a title tag ##
}*/

function CmEditor() {
	this.init();
}

CmEditor.prototype={
	options:{
		tArea:"",
		mime:"text/plain",
		readOnly:false
	},
	codemirror:null,
	init:function() {
		var self=this;
		this.options={
			tArea:$("body.editor #editarea")[0],
			mime:$("body.editor").attr("data-mime"),
			readOnly:($("body.editor").attr("data-ro")=="1" ? true : false)
		};
		this.codemirror=CodeMirror.fromTextArea(
			this.options.tArea,
			{
				mode:this.options.mime,
				indentWithTabs:true,
				lineNumbers:true,
				readOnly:this.options.readOnly,
				onChange:this.editorChange()
			//TODO Autosaving currently disabled
			}
		);
		
		self._addButtonEvent('#save[data-status="1"]',self.save);
		self._addButtonEvent('#autosave[data-status="0"]',self.autosaveEnable);
		self._addButtonEvent('#autosave[data-status="1"]',self.autosaveDisable);
		self._addButtonEvent('#reload',self.reload);
		self._addButtonEvent('#close[data-status="1"]',self.close);
		self._addButtonEvent('#info',self.info);
		self._addButtonEvent('#synhl[data-status="0"]',self.synhlEnable);
		self._addButtonEvent('#synhl[data-status="1"]',self.synhlDisable);
		self._addButtonEvent('#delete[data-status="1"]',self.del);
		self._addButtonEvent('#rename[data-status="1"]',self.rename);
		//Search later
		self._addButtonEvent('#undo[data-status="1"]',self.undo);
		self._addButtonEvent('#redo[data-status="1"]',self.redo);
		self._addButtonEvent('#jump',self.jump);
		self._addButtonEvent('#reindsel',self.reindsel);
		self._addButtonEvent('#reinddoc',self.reinddoc);
		//And now we can go and add events on this thing for the buttons
		//Accessing the cminstance w/ this.codemirror
	},
	editorChange:function() {
		if (!this.codemirror) {return;}
		var history=this.codemirror.historySize();
		if (history['undo'] > 0) {
			$("body.editor ul.nav #undo").attr("data-status","1");
		} else {
			$("body.editor ul.nav #undo").attr("data-status","0");
		}
		
		if (history['redo'] > 0) {
			$("body.editor ul.nav #redo").attr("data-status","1");
		} else {
			$("body.editor ul.nav #redo").attr("data-status","0");
		}
	},
	_addButtonEvent:function(selector, fn) {
		var self=this;
		$('body.editor ul.nav '+selector).live("click",function() {fn(self);});
	},
	search:function(self) {
		
	},
	undo:function(self) {
		self.codemirror.undo();
	},
	redo:function(self) {
		self.codemirror.redo();
	},
	jump:function(self) {
		//From codemirror-ui
		var line = prompt("Jump to line:", "");
    if (line && !isNaN(Number(line))) {
      self.codemirror.setCursor(Number(line),0);
      self.codemirror.setSelection({line:Number(line),ch:0},{line:Number(line)+1,ch:0});
      self.codemirror.focus();
    }
	},
	reindsel:function(self) {
		//From codemirror-ui
		var cur = self.codemirror.getCursor()
    var start = self.codemirror.getCursor(true)["line"]
    var end = self.codemirror.getCursor(false)["line"]
    for(var line = start; line <= end; line++) {
      self.codemirror.indentLine(line);
    }
	},
	reinddoc:function(self) {
		var lineCount = self.codemirror.lineCount();
    for(var line = 0; line < lineCount; line++) {
      self.codemirror.indentLine(line);
    }
	},
	del:function(self) {
		top.ecoder_files('main','delete',ecoder_path,ecoder_file,'file',content_changed);
	},
	rename:function(self){
		top.ecoder_files('main','rename',ecoder_path,ecoder_file,'file',content_changed);
	},
	synhlEnable:function(self) {
		self.codemirror.setOption("mode",self.options.mime);
		$(".editor ul.nav #synhl").attr("data-status","1");
	},
	synhlDisable:function(self) {
		self.codemirror.setOption("mode","text/plain");
		$(".editor ul.nav #synhl").attr("data-status","0");
	},
	info:function(self) {
		top.ecoder_loaded_base('block');
	},
	close:function(self) {
		top.ecoder_files(ecoder_iframe,'close',ecoder_path,ecoder_file,'',content_changed);
	},
	reload:function(self) {
		top.ecoder_files(ecoder_iframe,'reload',ec_mode,ecoder_file,'',content_changed);
	},
	autosaveEnable:function(self) {
		$('body.editor ul.nav #autosave[data-status="0"]')
			.attr("data-status",1)
			.attr("title","turn off autosave feature");
		//TODO: actually enable it :)
	},
	autosaveDisable:function(self) {
		$('body.editor ul.nav #autosave[data-status="1"]')
			.attr("data-status",0)
			.attr("title","turn on autosave feature");
	},
	save:function(self) {
		self.codemirror.mirror.save();
		
		if (typeof(top.hide)!="undefined") {clearTimeout(top.hide);}

		//ecoder_display('save','block','saving',1);

		$.ajax({
			data:{
				ecoder_path:ecoder_path,
				ecoder_file:ecoder_file,
				ecoder_content:($(self.options.tArea).val())
			},
			url:"code/save/edit.php",
			type:'POST',
			success:function() {
				//Should give better feedback (like filename in bold when not saved)
				alert("saved");
			}
		});

		/*if ( ecoder_autosave == 1 && ecoder_save_on == 1 ) {      
			var autosave=setTimeout("ecoder_save()",ec_autosave_time); // call function every x seconds ##
		}*/

		// reset change counter ##
		ecoder_changed_stop();
	}
};

$(document).ready(function() {
	new CmEditor({
		tArea:$("body.editor #editarea")[0],
		mime:$("body.editor").attr("data-mime"),
		readOnly:($("body.editor").attr("data-ro")=="1" ? true : false)
	})
});