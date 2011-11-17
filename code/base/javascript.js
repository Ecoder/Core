// main ecoder javascript ##

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
			top.ecoder_note('note',ecoder.translations.rename.alreadyEditing.format({name:this.file}),'5','block');
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
			top.ecoder_note('note',ecoder.translations.del.alreadyEditing.format({name:this.file}),'5','block');
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
			if (xhr.readyState != 4)  { return; }
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
                top.ecoder_note ( 'note', e_note, '5', 'block' );                
                
            } else { // ok to add tab ##     
            
                if ( ecoder_mode == "read" ) { // read only notice ##
                    var e_note = "<p><strong>"+ file +"</strong> is read-only, so you can view but not edit this document.</p>";
                    top.ecoder_note ( 'note', e_note, '5', 'block' );      
                }   
                   
                top.ecoder_tabs_add ( ecoder_file_clean, file, ecoder_file, path ); // add new tab -- iframe name/id ,label, iframe url, path ##
                
            }
        }
        ecoder_html_title ( file ); // set title ## // path+file           
       
    }
    return false; // no return ##
}

// ----------------------------------------------------------------------------------------------------------

// tree functions ##
function ecoder_tree ( frame, mode, path, hidden ) {
    
    var ecoder_mode = mode; // get mode ##
     
    if ( ecoder_mode == 'reload' ) { // reload ##
        top.frames[frame].location.reload(true);
        
    } else if ( ecoder_mode == 'home' ) { // tree home ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##

    } else if ( ecoder_mode == 'up' ) { // tree up ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##
        
    } else if ( ecoder_mode == 'open' ) { // tree open folder ##
        top.frames[frame].location=''+ frame +'.php?path='+ path; // call ##

    } else if ( ecoder_mode == 'hidden' ) { // show / hide hidden files ##
        top.frames[frame].location=''+ frame +'.php?path='+ path +'&hidden='+ hidden; // call ##
        
    }
    return false; // no return ##
}

// ----------------------------------------------------------------------------------------------------------

// notes, errors and messages ##
var ecoder_count_time = 0; // set to zero ##
var ecoder_note_open = 0; // timeout ID ##
//var ecoder_note_fade = 1; // timeout ID ##
function ecoder_note ( n_div, n_msg, n_delay, n_display ) {
    
    clearTimeout ( ecoder_note_open ); // clear all open note timeouts ##
      
    // set time if not passed ##
    if ( typeof ( n_delay ) == "undefined" || n_delay < 5 ) n_delay = 5;
    
    // convert n_delay to miliseconds ##
    note_delay = ( n_delay * 1000 );
    
    // show hide ##
    var elem = document.getElementById( n_div );
    if ( n_display == "none" ) { 
        elem.style.display = "none"; 
    } else {
        elem.style.display = "block"; 
    }

    // add close and clock ##
    var n_close = '<div id="note_close"><div id="note_x"><a href="#" onclick="ecoder_note_reset( \'note\', \'none\' )" title="close note"><img src="skin/one/design/icon_close.png" alt="close note" border="0" /></a></div><div id="note_clock">'+n_delay+'</div></div>';

    // alter content ##
    if ( n_msg ) { // change innerHTML of save div ##
        elem.innerHTML = n_close + n_msg; // change message ##
    }
    
    // start clock and set hide timeout ##
    ecoder_note_open = setTimeout( "ecoder_note_reset ( '"+ n_div +"', 'none' )", note_delay ); // hide after a delay ##
    var note_clock = setTimeout( "ecoder_count ( 'note_clock', '"+ n_delay +"' )", 0 ); // count it ##
    
    // swallow return ##
    return false; 
}

// hide note ##
function ecoder_note_reset ( r_div, r_display ) {
    document.getElementById( r_div ).style.display = r_display; // just hide ##
}

// ----------------------------------------------------------------------------------------------------------

// countdown ( div, seconds )
function ecoder_count ( c_div, c_time ){
     if ( typeof ( note_clock ) != "undefined" ) {clearTimeout ( note_clock );} // clear if counting already ##
     document.getElementById( c_div ).innerHTML = c_time; // return value to div ##
     if ( c_time > 0 ){ // continue ##
        ecoder_count_time = c_time; // update ##   
        note_clock = setTimeout ( "top.ecoder_count( '"+c_div+"', '"+( c_time - 1 )+"' )", 1000 ); // each second ##
     }
}

// ----------------------------------------------------------------------------------------------------------

// fade out boxes ##
function ecoder_fade ( id, opacStart, opacEnd, millisec ) { 
   
    var speed = Math.round(millisec / 100); //speed for each frame
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("ecoder_fade_do(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("ecoder_fade_do(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

// change the opacity for different browsers
function ecoder_fade_do(opacity, id) {

    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
    
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

function ecoder() {
	var _self=this;
	this.translations=null; //var later
	this.info=null;
	
	this.init=function() {
		if (!testCompat()) {
			$("body").html("").css("background","#000000");
			alert("Sorry, your browser does not support some of the features needed for ecoder. Please update your browser");
		}

		getTranslations();
		getInfo();
		return _self;
	}
	
	var testCompat=function() {
		return !(typeof FileReader == "undefined");
	}
	
	var getTranslations=function() {
		$.ajax({
			url:"translations.json",
			datatype:'json',
			success:function(msg) { 
				_self.translations=msg[$("body").attr("data-lang")]; 
			}
		});
	}
	
	var getInfo=function() {
		$.ajax({
			url:"info.php",
			datatype:'json',
			success:function(json) {
				_self.info=JSON.parse(json);
			}
		})
	}
}

// ----------------------------------------------------------------------------------------------------------
var translations, ecoder;
$(document).ready(function() {
	ecoder=new ecoder().init(); //temp
	setLiveEvents();
	
	translations=ecoder.translations;
});