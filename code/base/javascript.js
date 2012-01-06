// main ecoder javascript ##
var translations, ecoder;

//DEPRECATED
var ecoder_tab = 0; // current tab ##
var ecoder_iframe = "home_txt"; // start at home ##

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

// file functions ##
// frame target, action || mode, file path, file name, file extension || file/folder, change tracker ##
function ecoder_files ( frame, mode, path, file, type, changed ) {

		switch (mode) {
			case "upload":
				return new upload(path);
		}

    // make uniquish iframe id ##
    var ecoder_file_full = file; // assign file to variable ##
    var ecoder_path_full = path; // assign path to variable ##
    var ecoder_file_clean = ''; // declare ##
    var ecoder_changed_min = 1; // number of changes to warn on ##
    ecoder_path_full = ecoder_replace_all( ecoder_path_full, [ ["/", "_"] ] ); // replace / with _ in path ##
    ecoder_file_full = ecoder_replace_all( ecoder_file_full, [ [".", "_"] ] ); // replace . with _ in file ##
    ecoder_file_clean = ecoder_path_full + ecoder_file_full; // add path & file ##

    var ecoder_mode = mode; // get mode ##
    var ecoder_type = type; // get frame ##
    var ecoder_file = ''; // file ##

    if ( ecoder_mode == 'close' ) { // close file ##
        var close_do = 0; // false ##
        var close_confirm = 'if you close '+ file +' unsaved changes may be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1 ##
            if ( confirm ( close_confirm ) ) { // confirm ## + changed
                close_do = 1; // ok ##
            }
        } else { // delux and no changes made ##
            close_do = 1; // ok ##
        }

        if ( close_do == 1 && top.ecoder_tab > 0 ) {
            top.ecoder_tabs_close(); // close ##
            ecoder_html_title ( 'home' ); // set title ##
        }

    } else if ( ecoder_mode == 'editor' ) { // swap code editor ##
        var swap_do = 0; // false ##
        var swap_confirm = 'swap to '+ ecoder_type +' editor? unsaved changes to '+ file +' will be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1 ##
            if ( confirm( swap_confirm ) ) { // confirm ##
                swap_do = 1; // ok ##
            }
        } else { // delux and no changes made ##
            swap_do = 1; // ok ##
        }

        if ( swap_do == 1 ) {
            top.frames[frame].location='editor.php?'+ path +'&editor='+ type; // call ##
            ecoder_editor = ecoder_type; // update ecoder_editor variable if swapped ##
        }

    } else if ( ecoder_mode == 'reload' ) { // reload ##
        var reload_do = 0; // false ##
        var reload_confirm = 'if you reload '+ file +' unsaved changes will be lost.\npress OK to close or CANCEL to stop.'; // message ##
        if ( changed > ecoder_changed_min ) { // delux and changes made -- was > 1  ##
            if ( confirm( reload_confirm ) ) { // confirm ## + changed
                reload_do = 1; // ok ##
            }
        } else { // delux and no changes made ##
            reload_do = 1; // ok ##
        }
        if ( reload_do == 1 ) {
            top.frames[frame].location.reload(true); // call ##
        }

    } else if ( ecoder_mode == 'read' || ecoder_mode == 'edit' ) { // edit or read ##
			//TODO: New tree always passes mode edit.
			// Editor will have to find out writability for itself

			//Is it open?
			//	T: Focus
			//	F: Open
			//NOTE: File is different then parameter file
			var fullpath="";
			var name="";
			var html='<iframe src="editor.php?mode=edit&path='+ path +'&file='+ file +'&type='+ type +'" frameborder="0"></iframe>';
			if (ecoder.tabs.tabExists(fullpath)) {
				ecoder.tabs.focus(fullpath);
			} else {
				ecoder.tabs.add(fullpath,name,html);
			}

			var ecoder_object = ecoder_check_object( ecoder_file_clean ); // check if object/file is open ##
			var ecoder_file = 'editor.php?mode='+ mode +'&path='+ path +'&file='+ file +'&type='+ type; // url to open ##
			if ( ecoder_object ) { // tab open, so focus ##
				var parent_id; // declare ##
				parent_id = document.getElementById( ecoder_file_clean ).parentNode.id; // get id from parent ##
				parent_id = parent_id.replace( /tabber_panel_/, "" );// remove 'tabber_panel_' ##
				//alert ( parent_id + ' - ' + ecoder_tab );
				if ( parent_id != ecoder_tab ) { // focus, if not clicked on current tab ##
						top.ecoder_tabs_focus ( file, ecoder_file_clean, parent_id ); // focus tab ##
				}
			} else { // not open yet ##
					if ( ecoder_mode == "read" ) { // read only notice ##
							var e_note = "<p><strong>"+ file +"</strong> is read-only, so you can view but not edit this document.</p>";
							ecoder.infodialog(e_note);
					}
					top.ecoder_tabs_add ( ecoder_file_clean, file, ecoder_file, path ); // add new tab -- iframe name/id ,label, iframe url, path ##
			}
			ecoder_html_title ( file ); // set title ## // path+file
    }
    return false; // no return ##
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
			console.log("showhidden = "+_self.options.showHidden);
			if (_self.options.showHidden) {
				hiddenOption=new ContextMenuItem({id:"hidden_hide",name:"Hide hidden files",callback:hideHidden});
			} else {
				hiddenOption=new ContextMenuItem({id:"hidden_show",name:"Show hidden files",callback:showHidden});
			}
			//TODO hidden
			var refreshOption=new ContextMenuItem({id:"refresh",name:"Refresh the tree",callback:refresh});
			var buttons=new Array(hiddenOption,refreshOption);
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
		var path=li.attr("data-path");
		var name=li.attr("data-name");
		var subtype=li.attr("data-subtype");
		ecoder_files('main','edit',path,name,subtype);
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

// ----------------------------------------------------------------------------------------------------------

// html title function ## remove mode ##
function ecoder_html_title ( file ) {

    var ecoder_title = 'home'; // declare default ##
    var ecoder_file = file; // file name from function ##
    if ( ecoder_file ) ecoder_title = ecoder_file; // add file if set ##
    top.document.title = ecoder_name + ' | '+ ecoder_title; // apply title ##
    return false; // no return ##

}

// ----------------------------------------------------------------------------------------------------------

// track changes to editarea ##
var content_changed = 0; // swallow first change ( focus ) to editarea ##
function ecoder_changed () {
    content_changed ++; // iterate to count changes ##
}

// ----------------------------------------------------------------------------------------------------------

// check if a variable is defined ##
function ecoder_var_defined ( variable ) {
    return (!(!(document.getElementById(variable))))
}

// ----------------------------------------------------------------------------------------------------------

// track id of top tab ##
//DEPRECATED
function ecoder_track ( what, reference ) {

    // track tab ##
    if ( what == 'tab' ) {
        ecoder_tab = reference; // update variable ##
    }
    // track tab ##
    if ( what == 'iframe' ) {
        ecoder_iframe = reference; // update iframe reference ## -- document.getElementById ( reference )
    }
}

// ----------------------------------------------------------------------------------------------------------

// recursive replace ##
function ecoder_replace_all ( str, replacements ) {

    for ( i = 0; i < replacements.length; i++ ) {
        var idx = str.indexOf( replacements[i][0] );
        while ( idx > -1 ) {
            str = str.replace( replacements[i][0], replacements[i][1] );
            idx = str.indexOf( replacements[i][0] );
        }
    }
    return str;
}

// ----------------------------------------------------------------------------------------------------------

// object checker ##
//DEPRECATED
function ecoder_check_object(Id, Tag) {

  var o = document.getElementById(Id);
  if (o) { //alert ( '1 > found '+ o.id );
    if (Tag) { //alert ( '2 > found '+ o.id );
      if (o.tagName.toLowerCase() == Tag.toLowerCase()) { //alert ( '3 > found '+ o.id );
        return o;
      }
    } else {
      return o;
    }
  }
  return null;
}

// ----------------------------------------------------------------------------------------------------------

// loader & script test ##
function ecoder_loaded_base( mode ) {

    b_div = 'load_base';
    var elem = document.getElementById( b_div );
    elem.style.display = ( elem.style.display == "none" ) ? "" : "none";

}

// ----------------------------------------------------------------------------------------------------------

// onload events ##
function addLoadEvent( func ) {

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

		_ecoder.tabs=new tabs_f();
		getTemplates();
		getInfo();
	};

	var getInfo=function() {
		$.ajax({
			url:"info.php",
			datatype:'json',
			success:function(json) {
				_ecoder.info=(JSON.parse(json)).info;
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
	this.tabs=null;
	var tabs_f=function() {
		var _tabs=this;
		var panels=$("#tabs #panels");
		var tabs=$("#tabs ul");

		var init=function() {
			$(document).on("click","li.tab",function(e) {
				defocus();
				var file=$(this).attr("data-file");
				_tabs.focus(file);
			});
			$(document).on("click","li.tab span.close",function(e) {
				e.stopPropagation();
				var file=$(this).parent("li.tab").attr("data-file");
				_tabs.close(file);
				return false;
			});
		};

		this.tabExists=function(file) {
			return ($('div.panel[data-file="'+file+'"]').length!=0);
		};

		this.add=function(file,name,innerhtml) {
		 defocus();

		 var panelHtml='<div class="panel" data-file="'+file+'" data-status="inactive"></div>';
		 var tabHtml='<li class="tab" data-file="'+file+'" data-status="inactive" data-name="'+name+'">'+name+'<span class="close"></span></li>';

		 $(panels).append(panelHtml);
		 $(tabs).append(tabHtml);

		 $('div.panel[data-file="'+file+'"]').html(innerhtml);
		 $('li.tab[data-file="'+file+'"]').click();
		 return $('div.panel[data-file="'+file+'"]');
		};

		var defocus=function() {
		 $('li.tab[data-status="active"]').attr("data-status","inactive");
		 $('div.panel[data-status="active"]').attr("data-status","inactive");
		};

		this.focus=function(file) {
			var tab=$('li.tab[data-file="'+file+'"]');
			var name=tab.attr("data-name");
			tab.attr("data-status","active");
			$('div.panel[data-file="'+file+'"]').attr("data-status","active");
			ecoder_html_title(name);
		};

		this.close=function(file) {
		 if (file==null) { return; }

		 $('div.panel[data-file="'+file+'"]').remove();
		 $('li.tab[data-file="'+file+'"]').remove();

		 this.focus($(tabs).children("li.tab").last().attr("data-file"));
		};

		init();
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
		}
	};

	init();
}

// ----------------------------------------------------------------------------------------------------------
$(document).ready(function() {
	ecoder=new Ecoder();
});
$(document).on("ecoder-ready",function() {
	ecoder.tree=new Tree({showHidden:ecoder.info.showHidden});
	$("body").on("contextmenu",false);
	//ecoder.infodialog("<p>testing</p>");
	//ecoder.tabs.init();
	$.ajax({
		url:"code/base/loader.php",
		success:function(html) {
			ecoder.tabs.add("{{splash}}","Welcome",html);
		}
	});
});