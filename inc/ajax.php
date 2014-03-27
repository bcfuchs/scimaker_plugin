<?php

// http://codex.wordpress.org/AJAX_in_Plugins
// note this works only in admin pages. 

add_action( 'admin_footer', 'addResourceToProject_javascript' );

function addResourceToProject_javascript() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {
	// handler 
	var respf = function(response) {
// here we'd update the display
		console.log('Got this from the server: ' + response);
		$('#tester1').html(response);
	}
// here we'd set a click
	$('#welcome-panel').append('<div id="tester1" style="cursor:pointer;">add to project test</div>');
	$('#tester1').click(function() {
	addResourceToProject(105,91,respf);
	});
   function addResourceToProject(pid,rid,handler) {
	var data = {
		action: 'scimaker_addResourceToProject',
		project_id:pid,
		resource_id:rid
	};
	console.log('off we go addResourceToProject');

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	
	$.post(ajaxurl, data, respf);
   }
});
</script>
<?php
}

add_action( 'wp_ajax_scimaker_addResourceToProject', 'scimaker_addResourceToProject_ajax' );

function scimaker_addResourceToProject_ajax() {
	global $wpdb; // this is how you get access to the database
	$args = array();
	$project_id = intval( $_POST['project_id'] );
	$resource_id = intval( $_POST['resource_id'] );
	
	array_push($args,$project_id);
	array_push($args,$resource_id);
	$out = scimakers_addResourceToProject($args);

	echo json_encode($out);

	die(); // this is required to return a proper result
}

?>