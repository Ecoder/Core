//dirty code ahead...
//This can under no circumstances be moved to the topframe without rewriting
if ( top === self ) { document.location='index.php'; }

$(document).ready(function() {
	$("#load_edit").hide();
	
	$(".edit_nav #savebtn span").click(function() {
		var newname=$("#filenewname").val();
		if (newname=="") { // validate ##
			var e_note = "<p>you have not entered a new name for <strong>"+ec_html_title+"</strong>, please complete the box and then press SAVE.</p>";
			top.ecoder_note('note',e_note,'5','block');
		}
		$.ajax({
			data:{
				path:ecoder_path,
				file:ec_html_title,
				type:ecoder_type,
				ext:ec_ext,
				file_new:newname
			},
			url:"code/save/rename.php",
			type:'POST',
			datatype:'json',
			success:function(json) {
				json=$.parseJSON(json);
				if (json.code!=1) {
					$("#feedback").removeClass("success").addClass("error").html(json.msg);
				} else {
					$("#feedback").removeClass("error").addClass("success").html("this tab will close itself now.");
					top.ecoder_tree('tree','reload'); // refresh tree ##
					top.ecoder_note('note',json.msg,'5','block'); // show report ##
					var fn=function() { top.ecoder_files(ecoder_type+'_file','close','',ecoder_type+'_file')};
					var close_tab=setTimeout(fn,1000);
				}
			}
		});
	});
	$(".edit_nav #close span").click(function() {
		top.ecoder_files(ecoder_iframe,'close',ecoder_path,ecoder_file,'',0);
	});
	$(".edit_nav .logo span").click(function() {
		top.ecoder_loaded_base('block');
	});
});