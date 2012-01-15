var ecoder=(function(){
	var info;
	var status={info:false,trans:false,templ:false,splash:false};

	function init() {
		if (!testCompat()) {
			$("body").html("").css("background","#000000");
			alert("Sorry, your browser does not support some of the features needed for ecoder. Please update your browser");
		}

		$("body").on("contextmenu",false);
		getInfo();
		templ.load();
		$(document).on("ecoder.infoloaded",function() {
			trans.load();
		});

		$(document).on("ecoder.transtemplready",function() {
			initTabs();
			openSplash();
		});

		delete status;
	}

	function openSplash() {
		$.ajax({
			url:"code/base/loader.php",
			success:function(html) {
				var welcomeTab=new Tab("{{splash}}","Welcome",html);
			}
		});
	}

	function testCompat() {
		return (window.File && window.FileReader && window.FileList && window.Blob);
	}

	function getInfo() {
		$.ajax({
			url:"api.php?controller=env",
			datatype:'json',
			success:function(json) {
				info=(JSON.parse(json));
				status.info=true;
				$(document).trigger("ecoder.infoloaded");
			}
		});
	}

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

	/***************** DIALOG ********************************/
	function BaseDialog(typeClass,title,content) {
		function init() {
			$("body").append(templ.get("dialog",{
				title:title,
				content:content
			}));
			$("#dialog").center().addClass(typeClass);
			setEvents();
		}

		function setEvents() {
			$("#dialogoverlay").on("click",close)
			$("#dialog #closedialog").on("click",close);
		}

		function close() {
			$("#dialogcontainer").remove();
			$("#dialog").removeClass(typeClass);
			return false;
		}
		init();
		return {
			close:close
		};
	}

	function InfoDialog(content,timeout) {
		var doTimeout=true;
		if (timeout==-1) {
			doTimeout=false;
		}
		if (typeof timeout == "undefined" || timeout < 5) {
			timeout=5;
		}
		var dialog=new BaseDialog("info",trans.get("infodialog.info",{}),content);
		if (doTimeout) {
			setTimeout(function() {dialog.close(); },timeout*1000);
		}
	}

	function ConfirmDialog(title,content,yesfn,cancelfn) {
		var contentres=templ.get("dialog.confirm",{content:content});
		var dialog=new BaseDialog("confirm",title,contentres);
		$("#dialog.confirm #conf_yes").on("click",function(e) {
			dialog.close();
			yesfn(e);
		});
		$("#dialog.confirm #conf_cancel").on("click",function(e) {
			dialog.close();
			cancelfn(e);
		});
	}

	/***************** TEMPLATES + TRANSLATION ***************/

	var trans,templ;
	(function() {
		var formatfn=function(str,params) {
			var datare=new RegExp("{{=([A-Za-z0-9.-_]+)}}","g");
			str=str.replace(datare,function(matched,wantedval) {
				return params[wantedval];
			});
			var langre=new RegExp("{{&([A-Za-z0-9.-_]+)}}","g");
			str=str.replace(langre,function(matched,wantedval) {
				return trans.get(wantedval,params);
			});
			return str;
		};
		trans=(function(){
			var translations=null;

			var format=formatfn;

			function get(name,params) {
				if (typeof params == "undefined") { params={}; }
				return format(translations[name],params);
			}

			function load() {
				$.ajax({
					url:"translations.json",
					datatype:'json',
					success:function(msg) {
						translations=msg[info.lang];
						status.trans=true;
						if (status.templ) {
							$(document).trigger("ecoder.transtemplready");
						}
					}
				});
			}

			return {
				get:get,
				load:load
			}
		})();
		templ=(function(){
			var templates=null;

			var format=formatfn;

			function get(name,params) {
				if (typeof params == "undefined") { params={}; }
				return format(templates[name],params);
			}

			function load() {
				$.ajax({
					url:"templates.json",
					datatype:'json',
					success:function(msg) {
						templates=msg;
						status.templ=true;
						if (status.trans) {
							$(document).trigger("ecoder.transtemplready");
						}
					}
				});
			}

			return {
				get:get,
				load:load
			}
		})();
	})();

	/***************** TABS **********************************/
	var openTabs=new Object();
	var tabId=0;
	function Tab(file,title,tabContent) {
		var myId=tabId;
		var _tab=null;

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
		}

		function focus() {
			defocusAllTabs();
			$("#tabs #panels #panel_"+myId).attr("data-status","active");
			$("#tabs ul#tablist #tab_"+myId).attr("data-status","active");
		}

		function close() {
			defocusAllTabs();
			$('div#panel_'+myId).remove();
			$('li#tab_'+myId).remove();
			delete openTabs[file];
			if ($("#tabs ul#tablist li.tab").length!=0) {
				$("#tabs ul#tablist li.tab").last().data("tab").focus();
			}
		}

		function getId() {
			return myId;
		}

		_tab={
			focus:focus,
			close:close,
			getId:getId
		};
		init();
		return _tab;
	}

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

	/***************** ACTIONS *******************************/
	var actions=(function() {
		function remove(file) {
			function init() {
				new ConfirmDialog(
					trans.get("actions.remove.title"),
					trans.get("actions.remove.confirm",{file:file}),
					confirmedYes,function() {}
				);
			}

			function confirmedYes() {
				callAction("filemanipulation","remove",{file:file},handleResponse);
			}

			function handleResponse(out) {
				if (typeof out.error == "undefined") {
					tree.refresh();
					new InfoDialog(trans.get("actions.remove."+out.result));
					return;
				}
				if (out.error=="dirnotempty") {
					new ConfirmDialog(
						trans.get("actions.remove.title"),
						trans.get("actions.remove.error.dirnotempty"),
						function() {
							callAction("filemanipulation","remove",{file:file,allowedRecursive:true},handleResponse);
						},
						function() {}
					);
				} else {
					new InfoDialog(trans.get("actions.remove.error."+out.error),-1);
				}
			}

			init();
		}

		function rename(file) {
			function init() {
				var path,name;
				var pieces=file.split(info.dirSep);
				name=pieces.pop();
				path=pieces.join(info.dirSep)+info.dirSep;
				var dialog=new BaseDialog(
					"rename",
					trans.get("actions.rename.rename"),
					templ.get("dialog.rename",{path:path,name:name})
				);
				$("#dialog.rename #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.rename #ren_cancel").on("click",function(e) {
					dialog.close();
				});
			}

			function clickedSave() {
				var newname=$("#dialog.rename #newname").val();
				callAction("filemanipulation","rename",{file:file,newname:newname},function(out) {
					if (typeof out.error=="undefined") {
						tree.refresh();
						new InfoDialog(trans.get("actions.rename."+out.result));
					} else {
						new InfoDialog(trans.get("actions.rename.error."+out.error),-1);
					}
				});
			}

			init();
		}

		function addFolder(path) {
			function init() {
				path+=info.dirSep;
				var dialog=new BaseDialog("addfolder",
					trans.get("actions.addfolder.addfolder"),
					templ.get("dialog.addfolder",{path:path})
				);
				$("#dialog.addfolder #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.addfolder #ren_cancel").on("click",function(e) {
					dialog.close();
				});
			}

			function clickedSave() {
				var name=$("#dialog.addfolder #name").val();
				callAction("filemanipulation","addFolder",{path:path,name:name},function(out) {
					if (typeof out.error == "undefined") {
						tree.refresh();
						new InfoDialog(trans.get("actions.addfolder."+out.result));
					} else {
						new InfoDialog(trans.get("actions.addfolder.error."+out.error),-1);
					}
				});
			}

			init();
		}

		function addFile(path) {
			function init() {
				path+=info.dirSep;
				var dialog=new BaseDialog("addfile",
					trans.get("actions.addfile.addfile"),
					templ.get("dialog.addfile",{path:path})
				);
				$("#dialog.addfile #ren_save").on("click",function(e) {
					clickedSave();
					dialog.close();
				});
				$("#dialog.addfile #ren_cancel").on("click",function(e) {
					dialog.close();
				});
			}

			function clickedSave() {
				var name=$("#dialog.addfile #name").val();
				callAction("filemanipulation","addFile",{path:path,name:name},function(out) {
					if (typeof out.error != "undefined") {
						new InfoDialog(trans.get("actions.addfile.error."+out.error),-1);
					} else {
						tree.refresh();
						new InfoDialog(trans.get("actions.addfile."+out.result));
					}
				});
			}

			init();
		}

		//TODO upload and edit still need some updating. For later, though
		function upload(path) {
			var id=1;

			var init=function() {
				var dialog=new BaseDialog("upload",trans.get("actions.upload.upload"),templ.get("dialog.upload"));
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
						$("#dialog_upl_list").append(templ.get("upload.listitem",_fileObject));
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
							feedback+="<p>"+fo.name+" : "+trans.get("actions.upload.error."+json.error);
						} else {
							feedback+="<p>"+fo.name+" : "+trans.get("actions.upload."+json.result);
						}

						if (uploadedTotal==id+1) {
							new InfoDialog(feedback,-1);
							tree.refresh();
						}
					});

					xhr.open("POST","api.php?controller=filemanipulation&action=upload");
					xhr.setRequestHeader("Cache-Control", "no-cache");
					xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
					xhr.setRequestHeader("X-File-Name", fo.name);
					xhr.setRequestHeader("X-File-Path", path);
					xhr.send(fo.dataurl);
				});
			};

			init();
		}

		function edit(file) {
			var codemirror=null;
			var editorTab=null;
			var navselector=null;

			function init() {
				var name=file.split(info.dirSep).lastVal();

				callAction("filemanipulation","getFileEditingInfo",{file:file},function(out) {
					var doAutosave=(info.autosave!=0 ? (out.isWritable) : false);
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
					var hlLine=null;
					var foldFunc = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
					codemirror=CodeMirror.fromTextArea(textarea,{
						mode:out.cmMime,
						indentWithTabs:true,
						lineNumbers:true,
						readOnly:!out.isWritable,
						onChange:onChange,
						onCursorActivity: function() {
							codemirror.setLineClass(hlLine, null);
							hlLine=codemirror.setLineClass(codemirror.getCursor().line, "activeline");
						},
						onGutterClick: foldFunc
					});
					hlLine=codemirror.setLineClass(0, "activeline");
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
						new InfoDialog(trans.get("actions.edit.error."+out.error),-1);
					} else {
						new InfoDialog(trans.get("actions.edit."+out.result));
					}
				});
			}

			init();
		}

		return {
			remove:remove,
			rename:rename,
			addFolder:addFolder,
			addFile:addFile,
			upload:upload,
			edit:edit
		};
	})();

	/***************** TREE **********************************/
	var tree=(function() {
		var options=null;

		function init() {
			callAction("tree","",options,function(out) {
				$("#tree ul#toplevel").remove();
				$("#tree p.error").remove();
				if (out.error) {
					$("#tree")
						.append("<p class='error'>"+out.error+"</p>")
						.css("background","#FF7373");
					return;
				}
				if (!out.tree) {return;} //Shouldn't happen
				$("#tree").append('<ul id="toplevel">'+parseNodeToHtml(out.tree)+'</ul>');
				registerEvents();
			});
		}

		function parseNodeToHtml(node) {
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
		}

		function registerHeaderEvent() {
			$("#tree h2").on("contextmenu",function(e) {
				e.stopPropagation();
				var hiddenOption=null;
				if (options.showHidden) {
					hiddenOption=new ContextMenuItem({id:"hidden_hide",name:"Hide hidden files",callback:hideHidden});
				} else {
					hiddenOption=new ContextMenuItem({id:"hidden_show",name:"Show hidden files",callback:showHidden});
				}
				var refreshOption=new ContextMenuItem({id:"refresh",name:"Refresh the tree",callback:refresh});
				var splashOption=new ContextMenuItem({id:"splashmenu",name:"Open welcome tab",callback:ecoder.openSplash});
				var buttons=new Array(hiddenOption,refreshOption,splashOption);
				new ContextMenu({buttons:buttons,pos:{x:e.pageX,y:e.pageY},origEl:$(e.target)});
				return false;
			});
		}

		function registerEvents() {
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
		}

		///////////// TREE ACTION CALLBACKS
		var toggleDir=function(li) {
			li.children("ul").toggle();
		};

		var editFile=function(li) {
			actions.edit(li.attr("data-pathname"));
		}

		var rename=function(li) {
			actions.rename(li.attr("data-pathname"));
		}

		var del=function(li) {
			actions.remove(li.attr("data-pathname"));
		}

		var addFileHere=function(li) {
			actions.addFile(li.attr("data-pathname"));
		}

		var addFolderHere=function(li) {
			actions.addFolder(li.attr("data-pathname"));
		}

		var uploadHere=function(li) {
			actions.upload(li.attr("data-pathname"));
		}

		var hideHidden=function() {
			$("#ctxtmenucontainer").remove();
			options.showHidden=false;
			init();
		}

		var showHidden=function() {
			$("#ctxtmenucontainer").remove();
			options.showHidden=true;
			init();
		}

		var refresh=function() {
			$("#ctxtmenucontainer").remove();
			init();
		}

		$(document).on("ecoder.transtemplready",function() {
			options={showHidden:info.showHidden};
			init();
			registerHeaderEvent();
		});

		return {
			refresh:refresh
		};
	})();

	/***************** CONTEXTMENU ***************************/
	function ContextMenu(options) {
		function init() {
			var html=setUpHtml(options.buttons);
			$("body").append(html);
			$("#ctxtmenu").css("top",options.pos.y).css("left",options.pos.x);
			setUpEvents(options.buttons,options.origEl);
		}

		function setUpHtml(buttons) {
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
		}

		function setUpEvents(buttons,origEl) {
			$("#ctxtmenucontainer #ctxtmenuoverlay").on("click",function() {
				$("#ctxtmenucontainer").remove();
				return false;
			});
			buttons.forEach(function(v) {
				v.addEvent(origEl);
			});
		}

		init();
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

	//TODO: Shortcuts

	$(document).on("ready",init);

	return {
		openSplash:openSplash,
		callAction:callAction,
		// DIALOG
		BaseDialog:BaseDialog,
		InfoDialog:InfoDialog,
		ConfirmDialog:ConfirmDialog,
		// TEMPLATES + TRANSLATIONS
		trans:trans,
		templ:templ,
		// TAB
		Tab:Tab,
		// ACTIONS
		actions:actions,
		// TREE
		tree:tree,
		ContextMenu:ContextMenu,
		ContextMenuItem:ContextMenuItem
	};

})();