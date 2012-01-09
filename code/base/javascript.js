// main ecoder javascript ##
var translations, ecoder;

function callAction(controller,action,data,fn) {
	$.ajax({
		data:{json:JSON.stringify(data)},
		url:"api.php?controller="+controller+"&action="+action,
		type:'POST',
		datatype:'json',
		success:function(json) {
			json=JSON.parse(json);
			fn(json);
			}
	});
}

function ContextMenu(options) {

	this.init=function(options) {
		var html=setUpHtml(options.buttons);
		$("body").append(html);
		$("#ctxtmenu").css("top",options.pos.y).css("left",options.pos.x);
		setUpEvents(options.buttons,options.origEl);
	}

	var setUpHtml=function(buttons) {
		var htmlFormat='<div id="ctxtmenucontainer"><div id="ctxtmenuoverlay"></div><ul id="ctxtmenu">{items}</ul></div>';
		var html="";
		var itemsHtml="";
		buttons.forEach(function(v) {
			if (v!=null) {
				itemsHtml+=v.toString();
			}
		});
		html=htmlFormat.format({items:itemsHtml});
		return html;
	};

	var setUpEvents=function(buttons,origEl) {
		$("#ctxtmenucontainer #ctxtmenuoverlay").on("click",function() {
			$("#ctxtmenucontainer").remove();
			return false;
		});
		buttons.forEach(function(v) {
			v.addEvent(origEl);
		});
	};


	//At the end
	this.init(options);
}

function ContextMenuItem(options) {
	this.id="";
	this.name="";
	this.callback=function() {};
	this.isSep=false;

	var defaultOptions={id:"",name:"",callback:function() {},isSep:false};

	this.init=function(options) {
		options=$.extend({},defaultOptions,options);
		this.id=options.id;
		this.name=options.name;
		this.callback=options.callback;
		this.isSep=options.isSep;
	}

	this.toString=function() {
		var itemFormat='<li id="{id}" class="{xclass}">{name}</li>';
		var res=itemFormat.format({id:this.id,name:this.name,xclass:(this.isSep ? "sep": "")});
		return res;
	}

	this.addEvent=function(origEl) {
		var self=this;
		$("#ctxtmenucontainer ul#ctxtmenu li#"+this.id).on("click",function(e) {
			self.callback(origEl);
			$("#ctxtmenucontainer").remove();
			return false;
		});
	}

	this.init(options);
}

function Tree(options) {
	var _self=this;
	this.options={showHidden:false};

	this.init=function(options) {
		$.extend(this.options,options);

		$.ajax({
			url:"api.php?controller=tree",
			data:{json:JSON.stringify({showHidden:this.options.showHidden})},
			type:"POST",
			datatype:"json",
			success:function(json) {
				json=JSON.parse(json);
				$("#tree ul#toplevel").remove();
				$("#tree p.error").remove();
				if (json.error) {
					$("#tree")
						.append("<p class='error'>"+json.error+"</p>")
						.css("background","#FF7373");
					return;
				}
				if (!json.tree) {return;} //Shouldn't happen
				$("#tree").append('<ul id="toplevel">'+parseNodeToHtml(json.tree)+'</ul>');
				registerEvents();
			}
		});
	};

	var parseNodeToHtml=function(node) {
		var htmlTmpl='<li data-type="{type}" data-pathname="{pathname}" data-name="{name}" data-path="{path}" data-ext="{ext}" data-subtype="{subtype}"><span>{name}</span>{children}</li>';
		var html="";
		var subTreeHtml="";
		if (node.children != null) {
			subTreeHtml="<ul>";
			if (node.children.length==0) {
				subTreeHtml+='<li class="empty"><em>empty...</em></li>';
			}
			node.children.forEach(function(n,k,arr) {
				subTreeHtml+=parseNodeToHtml(n);
			});
			subTreeHtml+="</ul>";
		}
		html+=htmlTmpl.format({type:node.type,name:node.name,path:node.path,pathname:node.pathname,ext:node.ext,subtype:node.subtype,children:subTreeHtml});
		return html;
	};

	var registerEvents=function() {
		$("#tree h2").on("contextmenu",function(e) {
			e.stopPropagation();
			var hiddenOption=null;
			if (_self.options.showHidden) {
				hiddenOption=new ContextMenuItem({id:"hidden_hide",name:"Hide hidden files",callback:hideHidden});
			} else {
				hiddenOption=new ContextMenuItem({id:"hidden_show",name:"Show hidden files",callback:showHidden});
			}
			var refreshOption=new ContextMenuItem({id:"refresh",name:"Refresh the tree",callback:refresh});
			var splashOption=new ContextMenuItem({id:"splash",name:"Open welcome tab",callback:ecoder.openSplash});
			var buttons=new Array(hiddenOption,refreshOption,splashOption);
			new ContextMenu({buttons:buttons,pos:{x:e.pageX,y:e.pageY},origEl:$(e.target)});
			return false;
		});
		$('#tree li[data-type="dir"] span').on("click",function(e) {
			var el=null;
			if (e.target.nodeName.toLowerCase()=="li") {
				el=$(e.target);
			} else {
				el=$(e.target).parent("li");
			}
			toggleDir(el);
			return false;
		});
		$('#tree li[data-type="file"] span').on("click",function(e) {
			var el=null;
			if (e.target.nodeName.toLowerCase()=="li") {
				el=$(e.target);
			} else {
				el=$(e.target).parent("li");
			}
			editFile(el);
			return false;
		});
		$('#tree li span').on("contextmenu",function(e) {
			var el=null;
			if (e.target.nodeName.toLowerCase()=="li") {
				el=$(e.target);
			} else {
				el=$(e.target).parent("li");
			}
			var buttons=null;
			var renameOption=new ContextMenuItem({id:"rename",name:"Rename",callback:rename,isSep:true});
			var deleteOption=new ContextMenuItem({id:"delete",name:"Delete",callback:del});
			if (el.attr("data-type")=="dir") {
				var openDirOption=new ContextMenuItem({id:"opendir",name:"Open folder",callback:toggleDir});
				var addFileHereOption=new ContextMenuItem({id:"addfile",name:"Add file in this folder",callback:addFileHere,isSep:true});
				var addFolderHereOption=new ContextMenuItem({id:"addfolder",name:"Add folder in this folder",callback:addFolderHere});
				var uploadHereOption=new ContextMenuItem({id:"upload",name:"Upload in this folder",callback:uploadHere});
				buttons=new Array(openDirOption,renameOption,deleteOption,addFileHereOption,addFolderHereOption,uploadHereOption);
			} else if (el.attr("data-type")=="file") {
				var openFileOption=new ContextMenuItem({id:"openfile",name:"Open file",callback:editFile});
				buttons=new Array(openFileOption,renameOption,deleteOption);
			}
			new ContextMenu({buttons:buttons,pos:{x:e.pageX,y:e.pageY},origEl:el});
			return false;
		});
	};

	///////////// TREE ACTION CALLBACKS
	var toggleDir=function(li) {
		li.children("ul").toggle();
	};

	var editFile=function(li) {
		ecoder.actions.edit(li.attr("data-pathname"));
	}

	var rename=function(li) {
		ecoder.actions.rename(li.attr("data-pathname"));
	}

	var del=function(li) {
		ecoder.actions.remove(li.attr("data-pathname"));
	}

	var addFileHere=function(li) {
		ecoder.actions.addFile(li.attr("data-pathname"));
	}

	var addFolderHere=function(li) {
		ecoder.actions.addFolder(li.attr("data-pathname"));
	}

	var uploadHere=function(li) {
		ecoder.actions.upload(li.attr("data-pathname"));
	}

	var hideHidden=function() {
		$("#ctxtmenucontainer").remove();
		ecoder.tree=new Tree($.extend({},this.options,{showHidden:false}));
	}

	var showHidden=function() {
		$("#ctxtmenucontainer").remove();
		ecoder.tree=new Tree($.extend({},this.options,{showHidden:true}));
	}

	var refresh=function() {
		$("#ctxtmenucontainer").remove();
		ecoder.tree=new Tree(this.options);
	}

	this.init(options);
}

function Ecoder() {
	var _self=this;
	var _ecoder=this;
	this.tree=null;

	/******************** INITING *****************/
	this.info=null;

	var init=function() {
		if (!testCompat()) {
			$("body").html("").css("background","#000000");
			alert("Sorry, your browser does not support some of the features needed for ecoder. Please update your browser");
		}

		initTabs();
		getTemplates();
		getInfo();

		$(document).on("ecoder-ready",function() {
			ecoder.tree=new Tree({showHidden:ecoder.info.showHidden});
			$("body").on("contextmenu",false);
			//ecoder.infodialog("<p>testing</p>");
			//ecoder.tabs.init();
			_ecoder.openSplash();
		});
	};

	this.openSplash=function() {
		$.ajax({
			url:"code/base/loader.php",
			success:function(html) {
				var welcomeTab=new Tab("{{splash}}","Welcome",html);
				//ecoder.tabs.add("{{splash}}","Welcome",html);
			}
		});
	}

	var getInfo=function() {
		$.ajax({
			url:"api.php?controller=env",
			datatype:'json',
			success:function(json) {
				_ecoder.info=(JSON.parse(json));
				getTranslations(); //Shouldn't be here, but still searching for a cleaner way
			}
		})
	};

	var testCompat=function() {
		return (window.File && window.FileReader && window.FileList && window.Blob);
	};

	/******************* DIALOG *********************/
	//TODO Refactor into a dialog.info, dialog.confirm, dialog.empty and so on
	this.dialog=function(typeClass,title,content) {
		this.init=function() {
			$("body").append(_ecoder.getTemplate("dialog",{title:title,content:content}));
			$("#dialog").center().addClass(typeClass);
			setEvents();
			return this;
		};

		this.close=function() {
			closeDialog();
		};

		var setEvents=function() {
			$("#dialogoverlay").on("click",closeDialog);
			$("#dialog #closedialog").on("click",closeDialog);
		};

		var closeDialog=function() {
			$("#dialogcontainer").remove();
			$("#dialog").removeClass(typeClass);
			return false;
		};

		return this.init();
	};

	this.infodialog=function(content,timeout) {
		var doTimeout=true;
		if (timeout==-1) {
			doTimeout=false;
		}
		if (typeof timeout == "undefined" || timeout < 5) {
			timeout=5;
		}
		var dialog=_ecoder.dialog("info",_ecoder.getTranslation("infodialog.info",{}),content);
		if (doTimeout) {
			setTimeout(function(){ dialog.close(); },timeout*1000);
		}
	};

	this.confirmdialog=function(title,content,yesfn,cancelfn) {
		var contentres=_ecoder.getTemplate("dialog.confirm",{content:content});
		var dialog=_ecoder.dialog("confirm",title,contentres);
		$("#dialog.confirm #conf_yes").on("click",function(e) {
			dialog.close();
			yesfn(e);
		});
		$("#dialog.confirm #conf_cancel").on("click",function(e) {
			dialog.close();
			cancelfn(e);
		});
	};

	/********************** TEMPLATES + TRANSLATIONS ****************/
	this.translations=null; //var later
	this.templates=null;

	this.getTemplate=function(name,params) {
		return formatTransTempl(_ecoder.templates[name],params);
	};

	this.getTranslation=function(name,params) {
		return formatTransTempl(_ecoder.translations[name],params);
	};

	var formatTransTempl=function(str,params) {
		var datare=new RegExp("{{=([A-Za-z0-9.-_]+)}}","g");
		str=str.replace(datare,function(matched,wantedval) {
			return params[wantedval];
		});
		var langre=new RegExp("{{&([A-Za-z0-9.-_]+)}}","g");
		str=str.replace(langre,function(matched,wantedval) {
			return _ecoder.getTranslation(wantedval,params);
		});
		return str;
	};

	var getTranslations=function() {
		$.ajax({
			url:"translations.json",
			datatype:'json',
			success:function(msg) {
				_ecoder.translations=msg[_ecoder.info.lang];
				translations=_ecoder.translations; //temp TODO remove use
				//Shouldn't be here, no cleaner way found yet
				$(document).trigger("ecoder-ready");
			}
		});
	};

	var getTemplates=function() {
		$.ajax({
			url:"templates.json",
			datatype:'json',
			success:function(msg) {
				_self.templates=msg;
			}
		});
	};

	/**************************** TABS ************************/
	var openTabs=new Object();
	var tabId=0;
	function Tab(file,title,tabContent) {
		var myId=tabId;
		var _tab=this;

		function init() {
			if (typeof openTabs[file] !== "undefined") {
				var t=$("#tabs ul#tablist #tab_"+openTabs[file]);
				t.focus();
				return t;
			}
			defocusAllTabs();
			var panel='<div class="panel" id="panel_'+tabId+'" data-status="active">'+tabContent+'</div>';
			var tab='<li class="tab" id="tab_'+tabId+'" data-status="active">'+title+'<span class="close"></span></li>';
			$("#tabs #panels").append(panel);
			$("#tabs ul#tablist").append(tab);
			$("#tabs ul#tablist #tab_"+myId).data("tab",_tab);
			openTabs[file]=myId;
			tabId++;
			return _tab;
		}

		this.focus=function() {
			defocusAllTabs();
			$("#tabs #panels #panel_"+myId).attr("data-status","active");
			$("#tabs ul#tablist #tab_"+myId).attr("data-status","active");
		}

		this.close=function() {
			defocusAllTabs();
			$('div#panel_'+myId).remove();
			$('li#tab_'+myId).remove();
			delete openTabs[file];
			if ($("#tabs ul#tablist li.tab").length!=0) {
				$("#tabs ul#tablist li.tab").last().data("tab").focus();
			}
		}

		this.getId=function() {
			return myId;
		}

		init();
	}

	this.tempNewTab=function(file,title,html) {
		return new Tab(file,title,html);
	};

	function initTabs() {
		$(document).on("click","li.tab",function(e) {
			defocusAllTabs();
			$(this).data("tab").focus();
		});
		$(document).on("click","li.tab span.close",function(e) {
			e.stopPropagation();
			$(this).parent("li.tab").data("tab").close();
		});
	}

	function defocusAllTabs() {
		$('li.tab').attr("data-status","inactive");
		$('div.panel').attr("data-status","inactive");
	}

	/************************* ACTIONS ***************************/
	this.actions={
		remove:function(file) {

			var init=function() {
				_ecoder.confirmdialog(
					_ecoder.getTranslation("actions.remove.title",{}),
					_ecoder.getTranslation("actions.remove.confirm",{file:file}),
					confirmedYes,function() {}
				);
			};

			var confirmedYes=function() {
				callAction("filemanipulation","remove",{file:file},handleResponse);
			};

			var handleResponse=function(out) {
					if (typeof out.error != "undefined") {
					if (out.error=="dirnotempty") {
						_ecoder.confirmdialog(
							_ecoder.getTranslation("actions.remove.title",{}),
							_ecoder.getTranslation("actions.remove.error.dirnotempty",{}),
							removeRecursiveConfirm,function() {}
						);
					} else {
						_ecoder.infodialog(_ecoder.getTranslation("actions.remove.error."+out.error),-1);
					}
				} else {
					ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
					//TODO is this the correct way to refresh the tree?
					_ecoder.infodialog(_ecoder.getTranslation("actions.remove."+out.result));
				}
			};

			var removeRecursiveConfirm=function() {
				callAction("filemanipulation","remove",{file:file,allowedRecursive:true},handleResponse);
			};

			init();
		},
		rename:function(file) {

			var init=function() {
				var path,name;
				var pieces=file.split(ecoder.info.dirSep);
				name=pieces.pop();
				path=pieces.join(ecoder.info.dirSep);
				var dialog=_ecoder.dialog("rename",_ecoder.getTranslation("actions.rename.rename",{}),_ecoder.getTemplate("dialog.rename",{path:path+ecoder.info.dirSep,name:name}));
				$("#dialog.rename #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.rename #ren_cancel").on("click",function(e) {
					dialog.close();
				});
			};

			var clickedSave=function() {
				var newname=$("#dialog.rename #newname").val();
				callAction("filemanipulation","rename",{file:file,newname:newname},function(out) {
					if (typeof out.error != "undefined") {
						_ecoder.infodialog(_ecoder.getTranslation("actions.rename.error."+out.error),-1);
					} else {
						ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
						//TODO is this the correct way to refresh the tree?
						_ecoder.infodialog(_ecoder.getTranslation("actions.rename."+out.result));
			}
				});
			}

			init();
		},
		addFolder:function(path) {

			var init=function() {
				var dialog=_ecoder.dialog("addfolder",_ecoder.getTranslation("actions.addfolder.addfolder",{}),_ecoder.getTemplate("dialog.addfolder",{path:path+'/'}));
				$("#dialog.addfolder #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.addfolder #ren_cancel").on("click",function(e) {
					dialog.close();
				});
		}

			var clickedSave=function() {
				var name=$("#dialog.addfolder #name").val();
				callAction("filemanipulation","addFolder",{path:path,name:name},function(out) {
					if (typeof out.error != "undefined") {
						_ecoder.infodialog(_ecoder.getTranslation("actions.addfolder.error."+out.error),-1);
					} else {
						ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
						//TODO is this the correct way to refresh the tree?
						_ecoder.infodialog(_ecoder.getTranslation("actions.addfolder."+out.result));
					}
				});
			}

			init();
		},
		addFile:function(path) {

			var init=function() {
				var dialog=_ecoder.dialog("addfile",_ecoder.getTranslation("actions.addfile.addfile",{}),_ecoder.getTemplate("dialog.addfile",{path:path+'/'}));
				$("#dialog.addfile #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.addfile #ren_cancel").on("click",function(e) {
					dialog.close();
				});
			}

			var clickedSave=function() {
				var name=$("#dialog.addfile #name").val();
				callAction("filemanipulation","addFile",{path:path,name:name},function(out) {
					if (typeof out.error != "undefined") {
						_ecoder.infodialog(_ecoder.getTranslation("actions.addfile.error."+out.error),-1);
					} else {
						ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
						//TODO is this the correct way to refresh the tree?
						_ecoder.infodialog(_ecoder.getTranslation("actions.addfile."+out.result));
					}
				});
			}

			init();
		},
		//TODO needs some work to fit guidelines
		upload:function(path) {
			var id=1;

			var init=function() {
				var dialog=_ecoder.dialog("upload",_ecoder.getTranslation("actions.upload.upload",{}),_ecoder.getTemplate("dialog.upload",{}));
				$("#dialog_upl_files").on("change",change);
				$("#dialog.upload #upl_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.upload #upl_cancel").on("click",function(e) {
					dialog.close();
				});
	};

			var change=function(e) {
				for (var i=0; i<this.files.length; i++) {
					new FileObject(this.files[i]);
				}
			};

			var FileObject=function(file) {
				var fr=new FileReader();
				var _fileObject=this;
				this.name=file.name;
				this.type=file.type;
				this.rsize=file.size;
				this.fsize=(this.rsize/1024).toFixed(3);
				this.dataurl=null;

				var processMe=function() {
					if (_fileObject) {
						_fileObject.id=id;
						$("#dialog_upl_list").append(_ecoder.getTemplate("upload.listitem",_fileObject));
						$("#dialog_upl_list #item_"+_fileObject.id).data("fileObject",_fileObject);
						id++;
					}
				};

				fr.file=file;
				fr.onloadend=function(e) {
					_fileObject.dataurl=e.target.result;
					processMe();
				}
				fr.readAsDataURL(file);
			};

			var clickedSave=function() {
				var feedback="";
				var uploadedTotal=0;
				$("#dialog_upl_list li").each(function() {
					var fo=$(this).data("fileObject");
					var xhr=new XMLHttpRequest();
					var upload=xhr.upload;

					xhr.addEventListener("readystatechange",function() {
						if (xhr.readyState != 4)  {return;}
						if (xhr.status != 200) { return; }
						uploadedTotal++;
						var json=JSON.parse(xhr.responseText);
						if (typeof json.error != "undefined") {
							feedback+="<p>"+fo.name+" : "+_ecoder.getTranslation("actions.upload.error."+json.error);
						} else {
							ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
							//TODO is this the correct way to refresh the tree?
							feedback+="<p>"+fo.name+" : "+_ecoder.getTranslation("actions.upload."+json.result);
						}

						if (uploadedTotal==id+1) {
							_ecoder.infodialog(feedback,-1);
							ecoder.tree=new Tree({showHidden:_ecoder.info.showHidden});
						}
					});

					xhr.open("POST","api.php?controller=filemanipulation&action=upload");
					xhr.setRequestHeader("Cache-Control", "no-cache");
					xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
					xhr.setRequestHeader("X-File-Name", fo.name);
					xhr.setRequestHeader("X-File-Path", path);
					xhr.send(fo.dataurl);
					console.log(feedback);
				});
			};

			init();
		},
		edit:function(file) {
			var codemirror=null;
			var editorTab=null;
			var navselector=null;

			function init() {
				var name=file.split(_ecoder.info.dirSep).lastVal();
				//var html='<iframe src="editor.php?pathname='+file+'" frameborder="0"></iframe>';

				callAction("filemanipulation","getFileEditingInfo",{file:file},function(out) {
					var doAutosave=(_ecoder.info.autosave!=0 ? (out.isWritable) : false);
					var saveStatus=(out.isWritable ? "enabled" : "disabled");
					var html='<ul class="editornav">';
					html+='<li class="save" data-status="'+saveStatus+'"></li>';
					//TODO Search
					html+='<li class="undo" data-status="0" title="Undo"></li>';
					html+='<li class="redo" data-status="0" title="Redo"></li>';
					html+='<li class="jump" title="Jump to line"></li>';
					html+='<li class="reindsel" title="Reformat selection"></li>';
					html+='<li class="reinddoc" title="Reformat whole document"></li>';
					html+='</ul>';
					html+='<div class="editorwrapper"><textarea class="editor">'+out.content+'</textarea></div>';
					editorTab=new Tab(file,name,html);
					var textarea=$("#tabs #panel_"+editorTab.getId()+" .editor")[0];
					codemirror=CodeMirror.fromTextArea(textarea,{
						mode:out.cmMime,
						indentWithTabs:true,
						lineNumbers:true,
						readOnly:!out.isWritable,
						onChange:onChange
					});
					navselector="#tabs #panel_"+editorTab.getId()+" .editornav";
					$(navselector+' li.save[data-status="enabled"]').on("click",clickSave);
					$(navselector+' li.undo[data-status="1"]').on("click",undo);
					$(navselector+' li.redo[data-status="1"]').on("click",redo);
					$(navselector+' li.jump').on("click",jump);
					$(navselector+' li.reindsel').on("click",reindsel);
					$(navselector+' li.reinddoc').on("click",reinddoc);
				});
			}

			function onChange() {
				var history=codemirror.historySize();
				$(navselector+' .undo').attr("data-status",(history['undo'] > 0 ? "1" : "0"));
				$(navselector+' .redo').attr("data-status",(history['redo'] > 0 ? "1" : "0"));
			}

			function reinddoc() {
				var lineCount = codemirror.lineCount();
				for(var line = 0; line < lineCount; line++) {
					codemirror.indentLine(line);
				}
			}

			function reindsel() {
				//From codemirror-ui
				var start = codemirror.getCursor(true)["line"];
				var end = codemirror.getCursor(false)["line"];
				for(var line = start; line <= end; line++) {
					codemirror.indentLine(line);
				}
			}

			function jump() {
				//From codemirror-ui
				var line = prompt("Jump to line:", "");
				if (line && !isNaN(Number(line))) {
					codemirror.setCursor(Number(line),0);
					codemirror.setSelection({line:Number(line),ch:0},{line:Number(line)+1,ch:0});
					codemirror.focus();
				}
			}

			function redo() {
				codemirror.redo();
			}

			function undo() {
				codemirror.undo();
			}

			function clickSave() {
				codemirror.save(); //Transfers content back to textarea
				var content=$("#tabs #panel_"+editorTab.getId()+" .editor").val();
				callAction("filemanipulation","editSave",{file:file,content:content},function(out) {
					if (typeof out.error != "undefined") {
						_ecoder.infodialog(_ecoder.getTranslation("actions.edit.error."+out.error),-1);
					} else {
						_ecoder.infodialog(_ecoder.getTranslation("actions.edit."+out.result));
					}
				});
			}

			init();
		}
	};

	init();
}

$(document).ready(function() {
	ecoder=new Ecoder();
});