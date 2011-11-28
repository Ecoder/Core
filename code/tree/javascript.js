$(document).ready(function() {
	$("#load_tree").remove();
	
	$(".tree_nav #home").click(function() {
		top.ecoder_tree('tree','home',tree_root);
	});
	$(".tree_nav #fileadd").click(function() {
		top.ecoder_files('main','add',ecoder_tree_path,'','file');
	});
	$(".tree_nav #folderadd").click(function() {
		top.ecoder_files('main','add',ecoder_tree_path,'','folder');
	});
	$(".tree_nav #hidden").click(function() {
		top.ecoder_tree('tree','hidden',ecoder_tree_path,ecoder_tree_hidden);
	});
	$(".tree_nav #hidden_inactive").click(function() {
		top.ecoder_tree('tree','hidden',ecoder_tree_path,ecoder_tree_hidden);
	});
	$(".tree_nav #upload").click(function() {
		top.ecoder_files('main','upload',ecoder_tree_path,'','file');
	});
	$(".tree_nav #refresh").click(function() {
		top.ecoder_tree('tree','reload');
	});
	$(".tree_nav #secure").click(function() {
		top.location=secure_louturl;
	});
	
	$(".nodes li#error").click(function() {
		top.ecoder_tree('tree','home',tree_root);
	});
	$(".nodes li#up").click(function() {
		top.ecoder_tree('tree','up',tree_pathup);
	});
	$(".nodes li.folder").click(function() {
		var name=$(this).attr("data-name");
		top.ecoder_tree('tree','open',tree_path+name);
	});
	
	//TODO Decent mouse positioning!!
	$(".nodes > li").on("contextmenu",function(ev) {
		ev.preventDefault();
		$(this).find("ul.menu").show();
		$(".menuoverlay").show();
		$(".menuoverlay").on("click",function(subev) {
			$("ul.menu").hide();
			$(this).hide();
		});
		return false;
	});
	
	
});