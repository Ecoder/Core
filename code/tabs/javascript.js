function tabber_build() {
	var _tabber=this;
	var panels=$("#tabs #panels");
	var tabs=$("#tabs ul");

	var init=function() {
		$(document).on("click","li.tab",function(e) {
			defocus();
			var file=$(this).attr("data-file");
			_tabber.focus(file);
		});
		$(document).on("click","li.tab span.close",function(e) {
			e.stopPropagation();
			var file=$(this).parent("li.tab").attr("data-file");
			_tabber.close(file);
			return false;
		});
	};

	this.tabExists=function(file) {
		return ($('div.panel[data-file="'+file+'"]').length!=0);
	};

	this.add=function(file,name) {
	 defocus();

	 var panelHtml='<div class="panel" data-file="'+file+'" data-status="inactive"></div>';
	 var tabHtml='<li class="tab" data-file="'+file+'" data-status="inactive" data-name="'+name+'">'+name+'<span class="close"></span></li>';

	 $(panels).append(panelHtml);
	 $(tabs).append(tabHtml);

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

/* tab functions */
/*function ecoder_tabs_add ( iframe, label, url ) { // create ##
    tabber.add ( label, iframe ).innerHTML =
    '<iframe src="'+ url +'" id="'+ iframe +'" name="'+ iframe +'" frameborder="0" style="height:100%; width:100%;"></iframe>';
}

function ecoder_tabs_focus ( file, iframe, id ) { // re-focus open tab ##
	tabber.focus(id);
}

function ecoder_tabs_close ( ) { // close ##
    tabber.close ( tabber.currentHighTab );
}

function ec_isCurrentTab(path,name) {
	var cleanPath=ecoder_replace_all(path,[["/","_"]]);
	var cleanName=ecoder_replace_all(name,[[".","_"]]);
	var cleanPathName=cleanPath+cleanName;
	var parent_id=document.getElementById(cleanPathName).parentNode.id; // get id from parent ##
	parent_id=parent_id.replace(/tabber_panel_/,"");// remove 'tabber_panel_' ##
	if (parent_id!=ecoder_tab) { // It isn't the current tab
		return false;
	}
	return false;
}*/

var tabber;
var home;
$(document).ready(function() {
	tabber=new tabber_build();

	$.ajax({
		url:"code/base/loader.php",
		success:function(html) {
			tabber.add("{{splash}}","Welcome").html(html);
		}
	});

	home=tabber.add("home.txt","home");
	home.html('<iframe src="editor.php?mode=edit&path=&file=home.txt&type=text&shut=0" id="home_txt" name="home_txt" frameborder="0"></iframe>');
});