// main ecoder javascript ##
var translations, ecoder;

var dialog={
	init:function() {
		$("#dialogoverlay").live("click",function() {
			dialog.hide();
		});
		$("#dialog #closedialog").live("click",function() {
			dialog.hide();
		});
	},
	show:function(content) {
		$("#dialog #dialogcontent").html(content);
		$("#dialogoverlay").show();
		$("#dialog").center().show();
		$("#dialog #innercontent").css("padding-bottom",""+($("#dialog footer").outerHeight() + 5)+"px");
	},
	hide:function() {
		$("#dialogoverlay").hide();
		$("#dialog").hide();
		$("#dialog #dialogcontent").html("");
	}
};

function callAction(controller,action,data,fn) {
	$.ajax({
		data:{json:JSON.stringify(data)},
		url:controller+".php?controller="+controller+"&action="+action,
		type:'POST',
		datatype:'json',
		success:function(json) {
			json=JSON.parse(json);
			fn(json);
		}
	});
}
function setLiveEvents() {
	dialog.init();
	rename_v2.setLiveEvents();
	del.setLiveEvents();
	add.setLiveEvents();
}
/*
 * TODO
 *  - Replace path, name, type, ext variables by file object.
 */
var rename_v2={
	path:null,
	name:null,
	type:null,
	ext:null,
	setLiveEvents:function() {
		$('.rename .submit').live("click",function() {rename_v2.save();});
	},
	setFeedback:function(msg,type) {
		$(".rename #feedback").html(msg).removeClass("success error info").addClass(type);
	},
	init:function(path,name,type,changed) {
		this.path=path;
		this.name=name;
		this.type=type;
		this.ext=name.split('.').lastVal();

		//Very dirty code for now, thanks to tabs api.
		if (this._IsOpenEdit()) {
			if (this._IsNotCurrentOpenThenSwitch()) {
				return
			}
			if (!this.IsCurrentOpenThenAskIfClose(changed)) {
				return;
			}

		}
		callAction("rename","dialog",{
				path:this.path,
				file:this.name,
				type:this.type,
				ext:this.ext
			},function(json) {
				dialog.show(json.html);
			}
		);
	},
	//TODO: Refactor tabs engine -_-
	_IsOpenEdit:function() {
		var cleanPath=ecoder_replace_all(this.path,[["/","_"]]);
		var cleanName=ecoder_replace_all(this.name,[[".","_"]]);
		var cleanPathName=cleanPath+cleanName;
		return ecoder_check_object(cleanPathName);
	},
	_IsNotCurrentOpenThenSwitch:function() {
		var cleanPath=ecoder_replace_all(this.path,[["/","_"]]);
		var cleanName=ecoder_replace_all(this.name,[[".","_"]]);
		var cleanPathName=cleanPath+cleanName;
		var parent_id=document.getElementById(cleanPathName).parentNode.id; // get id from parent ##
		parent_id=parent_id.replace(/tabber_panel_/,"");// remove 'tabber_panel_' ##
		if (parent_id!=ecoder_tab) { // It isn't the current tab
			top.ecoder_tabs_focus(this.file,cleanPathName,parent_id);
			ecoder.infodialog(ecoder.translations.rename.alreadyEditing.format({name:this.file}));
			ecoder_html_title(this.file);
			return true;
		}
		return false
	},
	IsCurrentOpenThenAskIfClose:function(changed) {
		var close_do=false; // false ##
		//Checking for changes won't work with old change api so disabling for now
		//if (changed>1) { // changes made -- was > 1 TODO ##
			if (confirm(ecoder.translations.rename.closeConfirm.format({name:this.name}))) { // confirm ## + changed
				close_do=true; // ok ##
			}
		/*} else { // no changes made ##
			close_do=true; // ok ##
		}*/

		if (close_do) { // closed confirmed and not home tab ##
			if (top.ecoder_tab>0) { // close if not focused on home ##
				top.ecoder_tabs_close();
			}

			return true;
		}
		return false;
	},
	save:function() {
		var i=rename_v2;
		var newname=$("#filenewname").val();
		if (newname=="") {
			i.setFeedback(ecoder.translations.rename.noNameEntered.format({name:i.name}),"error");
		}
		callAction("rename","save",{
				path:i.path,
				file:i.name,
				type:i.type,
				ext:i.ext,
				file_new:newname
			},function(json) {
				if (json.code!=1) {
					i.setFeedback(json.msg,"error");
					return;
				}
				i.setFeedback(json.msg,"success");
				ecoder_tree('tree','reload');
			}
		);
	}
};

var del={
	path:null,
	name:null,
	type:null,
	ext:null,
	setLiveEvents:function() {
		$('.delete .submit').live("click",function() {del.save();});
	},
	setFeedback:function(msg,type) {
		$(".delete #feedback").html(msg).removeClass("success error info").addClass(type);
	},
	init:function(path,name,type,changed) {
		this.path=path;
		this.name=name;
		this.type=type;
		this.ext=name.split('.').lastVal();

		//Very dirty code for now, thanks to tabs api.
		if (this._IsOpenEdit()) {
			if (this._IsNotCurrentOpenThenSwitch()) {
				return
			}
			if (!this.IsCurrentOpenThenAskIfClose(changed)) {
				return;
			}

		}
		callAction("delete","dialog",{
				path:this.path,
				file:this.name,
				type:this.type,
				ext:this.ext
			},function(json) {
				dialog.show(json.html);
			}
		);
	},
	//TODO: Refactor tabs engine -_-
	_IsOpenEdit:function() {
		var cleanPath=ecoder_replace_all(this.path,[["/","_"]]);
		var cleanName=ecoder_replace_all(this.name,[[".","_"]]);
		var cleanPathName=cleanPath+cleanName;
		return ecoder_check_object(cleanPathName);
	},
	_IsNotCurrentOpenThenSwitch:function() {
		var cleanPath=ecoder_replace_all(this.path,[["/","_"]]);
		var cleanName=ecoder_replace_all(this.name,[[".","_"]]);
		var cleanPathName=cleanPath+cleanName;
		var parent_id=document.getElementById(cleanPathName).parentNode.id; // get id from parent ##
		parent_id=parent_id.replace(/tabber_panel_/,"");// remove 'tabber_panel_' ##
		if (parent_id!=ecoder_tab) { // It isn't the current tab
			top.ecoder_tabs_focus(this.file,cleanPathName,parent_id);
			ecoder.infodialog(ecoder.translations.del.alreadyEditing.format({name:this.file}));
			ecoder_html_title(this.file);
			return true;
		}
		return false
	},
	IsCurrentOpenThenAskIfClose:function(changed) {
		var close_do=false; // false ##
		//Checking for changes won't work with old change api so disabling for now
		//if (changed>1) { // changes made -- was > 1 TODO ##
			if (confirm(ecoder.translations.edit.closeConfirm.format({name:this.name}))) { // confirm ## + changed
				close_do=true; // ok ##
			}
		/*} else { // no changes made ##
			close_do=true; // ok ##
		}*/

		if (close_do) { // closed confirmed and not home tab ##
			if (top.ecoder_tab>0) { // close if not focused on home ##
				top.ecoder_tabs_close();
			}

			return true;
		}
		return false;
	},
	save:function() {
		var i=del;
		callAction("delete","save",{
				path:i.path,
				file:i.name,
				type:i.type,
				ext:i.ext
			},function(json) {
				if (json.code!=1) {
					i.setFeedback(json.msg,"error");
					return;
				}
				i.setFeedback(json.msg,"success");
				ecoder_tree('tree','reload');
			}
		);
	}
};

var add={
	path:null,
	type:null,
	setLiveEvents:function() {
		$('.add .submit').live("click",function() {add.save();});
	},
	setFeedback:function(msg,type) {
		$(".add #feedback").html(msg).removeClass("success error info").addClass(type);
	},
	init:function(path,type) {
		this.path=path;
		this.type=type;
		callAction("add","dialog",{
				path:this.path,
				type:this.type
			},function(json) {
				dialog.show(json.html);
			}
		);
	},

	save:function() {
		var i=add;
		callAction("add","save",{
				path:i.path,
				file:$(".dialogcontentwrapper.add #nodename").val(),
				ext:$(".dialogcontentwrapper.add #ext").val(),
				type:i.type
			},function(json) {
				if (json.code!=1) {
					i.setFeedback(json.msg,"error");
					return;
				}
				i.setFeedback(json.msg,"success");
				ecoder_tree('tree','reload');
			}
		);
	}
}

function upload(p) {
	var _self=this;
	var path=p;
	var MAXSIZE=ecoder.info.maxUploadSize;

	this.init=function() {
		callAction("upload","dialog",{
			path:path
		},function(json) {
			dialog.show(json.html);
			$(".dialogcontentwrapper.upload #file").change(handleUploaderChange);
			$(".dialogcontentwrapper.upload #reset").click(clearList);
			$(".dialogcontentwrapper.upload #upload").click(uploadQueue);
		});
		return _self;
	}

	var handleUploaderChange=function() {
		addToFileList(this.files);
	};

	var clearList=function(ev) {
		ev.preventDefault();
		$(".dialogcontentwrapper.upload #fileList").html("");
	};

	var uploadQueue=function(ev) {
		ev.preventDefault();
		$('.dialogcontentwrapper.upload #fileList li[data-status="0"]').each(function(k,v) {
			var el=$(v);
			var loader="<p class='loader'>Uploading...</p>";
			el.html(el.html()+loader);
			if (el.attr("data-rsize") < MAXSIZE) {
				uploadFile(el);
			} else {
				el.children(".loader").html("File to large").css("color","red");
			}
			el.attr("data-status","1");
		});
	};

	var addToFileList=function(files) {
		for (var i=0; i<files.length; i++) {
			new FileObject(files[i]);
		}
	};

	var nodeToHtml=function(fileObject) {
		var htmlTpl="<li data-status='0' data-name='{name}' data-type='{type}' data-rsize='{rsize}'><h3>{name}</h3><p>File type: ({type}) - {fsize} KB</p><div class='loadingIndicator'></div></li>";
		return htmlTpl.format({name:fileObject.name,type:fileObject.type,rsize:fileObject.rsize,fsize:fileObject.fsize});
	};

	var FileObject=function(file) {
		var fr = new FileReader();
		this.name=file.name;
		this.type=file.type;
		this.rsize=file.size;
		this.fsize=Math.round(this.rsize/1024);
		this.dataurl=null;
		var self=this;
		fr.file = file;
		fr.onloadend = function(e) {self.dataurl=e.target.result;showFileInList(self);};
		fr.readAsDataURL(file);
	}

	var showFileInList=function(fileObject) {
		if (fileObject) {
				$("#fileList").html($("#fileList").html()+nodeToHtml(fileObject));
				$.data($("#fileList li:last-child")[0],"file",fileObject);
			}
	};

	var uploadFile=function(el) {
		if (!el) {
			return;
		}
		var xhr=new XMLHttpRequest();
		var upload=xhr.upload;
		var file=$.data(el[0],"file");

		xhr.addEventListener("readystatechange",function (ev) {
			if (xhr.readyState != 4)  {return;}
			if (xhr.status == 200) {
				var json=JSON.parse(xhr.responseText);
				var x=null;
				if (json.error) {
					x="err_"+json.error;
				} else {
					x="err_uplsuccess";
					el.children(".loadingIndicator").css("width","100%").css("background-color","#0f0");
					el.children(".loader").html("Upload complete").css("color","#3DD13F");
				}

				$("#feedback").html(ecoder.translations.upload[x]).addClass("error");
			}
		},false);

		upload.addEventListener("error",function (ev) {
			console.log(ev);
		},false);

		xhr.open("POST","upload.php?controller=upload&action=save");
		xhr.setRequestHeader("Cache-Control", "no-cache");
		xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xhr.setRequestHeader("X-File-Name", file.name);
		xhr.setRequestHeader("X-File-Path", p);
		xhr.send(file.dataurl);
	};
}

// file functions ##
// frame target, action || mode, file path, file name, file extension || file/folder, change tracker ##
function ecoder_files ( frame, mode, path, file, type, changed ) {

		switch (mode) {
			case "rename":
				return rename_v2.init(path,file,type,changed);
			case "delete":
				return del.init(path,file,type,changed);
			case "add":
				return add.init(path,type);
			case "upload":
				return new upload(path).init();
		}

    var ecoder_tabs_max = 10; // max tabs ##

    // make uniquish iframe id ##
    var ecoder_file_full = file; // assign file to variable ##
    var ecoder_path_full = path; // assign path to variable ##
    var ecoder_file_clean = ''; // declare ##
    var ecoder_changed_min = 1; // number of changes to warn on ##
    ecoder_path_full = ecoder_replace_all( ecoder_path_full, [ ["/", "_"] ] ); // replace / with _ in path ##
    ecoder_file_full = ecoder_replace_all( ecoder_file_full, [ [".", "_"] ] ); // replace . with _ in file ##
    ecoder_file_clean = ecoder_path_full + ecoder_file_full; // add path & file ##

    var ecoder_frame = frame; // get frame ##
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
            if ( top.tabber.tabContainer.cells.length + 1 > ecoder_tabs_max ) { // restrict to x tabs

                // note ##
                var e_note = "<p>you already have <strong>"+ ecoder_tabs_max +"</strong> tabs open which is the maximum allowed in the configuration.</p>";
                ecoder.infodialog(e_note);

            } else { // ok to add tab ##

                if ( ecoder_mode == "read" ) { // read only notice ##
                    var e_note = "<p><strong>"+ file +"</strong> is read-only, so you can view but not edit this document.</p>";
                    ecoder.infodialog(e_note);
                }

                top.ecoder_tabs_add ( ecoder_file_clean, file, ecoder_file, path ); // add new tab -- iframe name/id ,label, iframe url, path ##

            }
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
			url:"tree2.php",
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
		var htmlTmpl='<li data-type="{type}" data-name="{name}" data-path="{path}" data-ext="{ext}" data-subtype="{subtype}"><span>{name}</span>{children}</li>';
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
		html+=htmlTmpl.format({type:node.type,name:node.name,path:node.path,ext:node.ext,subtype:node.subtype,children:subTreeHtml});
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
			var openOption=null;
			var addFileHereOption=null;
			var addFolderHereOption=null;
			var uploadHereOption=null;
			if (el.attr("data-type")=="dir") {
				openOption=new ContextMenuItem({id:"opendir",name:"Open folder",callback:toggleDir});
				addFileHereOption=new ContextMenuItem({id:"addfile",name:"Add file in this folder",callback:addFileHere,isSep:true});
				addFolderHereOption=new ContextMenuItem({id:"addfolder",name:"Add folder in this folder",callback:addFolderHere});
				uploadHereOption=new ContextMenuItem({id:"upload",name:"Upload in this folder",callback:uploadHere});
			} else if (el.attr("data-type")=="file") {
				openOption=new ContextMenuItem({id:"openfile",name:"Open file",callback:editFile});
			}
			var renameOption=new ContextMenuItem({id:"rename",name:"Rename",callback:rename,isSep:true});
			var deleteOption=new ContextMenuItem({id:"delete",name:"Delete",callback:del});
			var buttons=new Array(openOption,renameOption,deleteOption,addFileHereOption,addFolderHereOption,uploadHereOption); //later also rename, delete. can be done in same call
			new ContextMenu({buttons:buttons,pos:{x:e.pageX,y:e.pageY},origEl:el});
			return false;
		});
	};

	///////////// TREE ACTION CALLBACKS
	var toggleDir=function(li) {
		li.children("ul").toggle();
	}

	var editFile=function(li) {
		var path=li.attr("data-path");
		var name=li.attr("data-name");
		var subtype=li.attr("data-subtype");
		ecoder_files('main','edit',path,name,subtype);
	}

	var rename=function(li) {
		var path=li.attr("data-path");
		var name=li.attr("data-name");
		var type=li.attr("data-type");
		ecoder_files('main','rename',path,name,type);
	}

	var del=function(li) {
		var path=li.attr("data-path");
		var name=li.attr("data-name");
		var type=li.attr("data-type");
		ecoder_files('main','delete',path,name,type);
	}

	var addFileHere=function(li) {
		var path=li.attr("data-path");
		ecoder_files('main','add',path,'','file');
	}

	var addFolderHere=function(li) {
		var path=li.attr("data-path");
		ecoder_files('main','add',path,'','folder');
	}

	var uploadHere=function(li) {
		var path=li.attr("data-path");
		ecoder_files('main','upload',path,'','file');
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
	this.translations=null; //var later
	this.templates=null;
	this.info=null;
	this.tree=null;

	this.init=function() {
		if (!testCompat()) {
			$("body").html("").css("background","#000000");
			alert("Sorry, your browser does not support some of the features needed for ecoder. Please update your browser");
		}

		getTemplates();
		getInfo();

		return _self;
	}

	this.dialog=function(typeClass,titleTranslation,content) {
		this.init=function() {
			$("body").append(_self.getTemplate("dialog",{title:titleTranslation,content:content}));
			$("#dialog").center().addClass(typeClass);
			setEvents();
		};

		this.close=function() {
			closeDialog();
		}

		var setEvents=function() {
			$("#dialogoverlay").on("click",closeDialog);
			$("#dialog #closedialog").on("click",closeDialog);
		}

		var closeDialog=function() {
			$("#dialogcontainer").remove();
			$("#dialog").removeClass(typeClass);
			return false;
		}

		this.init();
	};

	this.infodialog=function(content,timeout) {
		if (typeof timeout == "undefined" || timeout < 5) {
			timeout=5;
		}
		var dialog=ecoder.dialog("info","infodialog.info",content);
		setTimeout(function(){
			dialog.close();
		},timeout*1000);
	};

	this.getTemplate=function(name,params) {
		return formatTransTempl(ecoder.templates[name],params);
	}

	this.getTranslation=function(name,params) {
		console.log(ecoder.translations);
		return formatTransTempl(ecoder.translations[name],params);
	}

	var testCompat=function() {
		return !(typeof FileReader == "undefined");
	}

	var formatTransTempl=function(str,params) {
		var datare=new RegExp("{{=([A-Za-z0-9.-_]+)}}","g");
		str=str.replace(datare,function(matched,wantedval) {
			return params[wantedval];
		});
		console.log(str);
		var langre=new RegExp("{{&([A-Za-z0-9.-_]+)}}","g");
		console.log(langre);
		str=str.replace(langre,function(matched,wantedval) {
			console.log(wantedval);
			return ecoder.getTranslation(wantedval,params);
		});
		return str;
	}

	var getTranslations=function() {
		$.ajax({
			url:"translations.json",
			datatype:'json',
			success:function(msg) {
				ecoder.translations=msg[ecoder.info.lang];
				translations=ecoder.translations; //temp TODO remove use
				//Shouldn't be here, no cleaner way found yet
				$(document).trigger("ecoder-ready");
			}
		});
	}

	var getTemplates=function() {
		$.ajax({
			url:"templates.json",
			datatype:'json',
			success:function(msg) {
				_self.templates=msg;
			}
		});
	}

	var getInfo=function() {
		$.ajax({
			url:"info.php",
			datatype:'json',
			success:function(json) {
				_self.info=(JSON.parse(json)).info;
				getTranslations(); //Shouldn't be here, but still searching for a cleaner way
			}
		})
	}

	this.init();
}

// ----------------------------------------------------------------------------------------------------------
$(document).ready(function() {
	ecoder=new Ecoder();
});
$(document).on("ecoder-ready",function() {
	ecoder.tree=new Tree({showHidden:ecoder.info.showHidden});
	setLiveEvents();
	$("body").on("contextmenu",false);
	ecoder.infodialog("<p>testing</p>");
})