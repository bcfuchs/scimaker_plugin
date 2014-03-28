
jQuery(document).ready(function($) {
	// update handler
	var updater = function(response) {

		// here we'd update the display
		console.log('Got this from the server: ' + response);
		$('#tester1').css({'background-color':"green"});
		$('#tester1').html("Added this resource!");
	}
// here we'd set a click
	$('#welcome-panel').append('<div id="tester1" style="width: 14%;background-color:gray;color:white;border:  dotted blue;cursor:pointer;">add to project test</div>');
	
	$('#tester1').click(function() {
		$('#tester1').css({'background-color':"blue"});
		$('#tester1').html("Adding this resource...");
		addResourceToProject(105,91,updater);
	});
   function addResourceToProject(pid,rid,handler) {
	var data = {
		action: ajax_object.action,
		project_id:pid,
		resource_id:rid
	};
	console.log('off we go addResourceToProject');

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	
	$.post(ajax_object.ajax_url, data, handler);
   }
});
