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
	autosaveIntervalId:null,
	changed:false,
	filename:null,
	path:null,
	
	init:function() {
		var self=this;
		this.options={
			tArea:$("body.editor #editarea")[0],
			mime:$("body.editor").attr("data-mime"),
			readOnly:($("body.editor").attr("data-ro")=="1" ? true : false)
		};
		this.filename=$("body.editor").attr("data-filename");
		this.path=$("body.editor").attr("data-path");
		this.codemirror=CodeMirror.fromTextArea(
			this.options.tArea,
			{
				mode:this.options.mime,
				indentWithTabs:true,
				lineNumbers:true,
				readOnly:this.options.readOnly,
				onChange:function() { editor.editorChange(); }
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
	},
	editorChange:function() {
		if (!this.codemirror) {return;}
		var history=this.codemirror.historySize();
		$("body.editor ul.nav #undo").attr("data-status",(history['undo'] > 0 ? "1" : "0"));
		$("body.editor ul.nav #redo").attr("data-status",(history['redo'] > 0 ? "1" : "0"));
		this.changed=true;
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
		top.del.init(self.path,self.filename,'file',parseInt(self.changed)+1);
	},
	rename:function(self){
		top.ecoder_files('main','rename',self.path,self.filename,'file',parseInt(self.changed)+1);
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
		top.ecoder_files(ecoder_iframe,'close',self.path,self.filename,'',Integer(self.changed)+1);
	},
	reload:function(self) {
		top.ecoder_files(ecoder_iframe,'reload',self.path,self.filename,'',Integer(self.changed)+1);
	},
	autosaveEnable:function(self) {
		$('body.editor ul.nav #autosave[data-status="0"]')
			.attr("data-status",1)
			.attr("title","turn off autosave feature");
		self.autosaveIntervalId=setInterval(function() { self.save(self); },autosave_interval);
	},
	autosaveDisable:function(self) {
		$('body.editor ul.nav #autosave[data-status="1"]')
			.attr("data-status",0)
			.attr("title","turn on autosave feature");
		clearInterval(self.autosaveIntervalId);
	},
	save:function(self) {
		self.codemirror.save();
		
		//ecoder_display('save','block','saving',1);

		$.ajax({
			data:{
				ecoder_path:self.path,
				ecoder_file:self.filename,
				ecoder_content:($(self.options.tArea).val())
			},
			url:"code/save/edit.php",
			type:'POST',
			success:function() {
				//TODO Should give better feedback (like filename in bold when not saved)
				alert("saved");
				this.changed=false;
			}
		});
	}
};

var editor=null;
$(document).ready(function() {
	$(".editor #load_edit").css("display","none");
	
	editor=new CmEditor({
		tArea:$("body.editor #editarea")[0],
		mime:$("body.editor").attr("data-mime"),
		readOnly:($("body.editor").attr("data-ro")=="1" ? true : false)
	});
});