<?php
    
// http://codex.wordpress.org/AJAX_in_Plugins
// note this works only in admin pages.
add_action ( 'admin_enqueue_scripts', 'scimaker_addResourceToProject_enqueue' );
function scimaker_addResourceToProject_enqueue($hook) {
	if ('index.php' != $hook) {
		// Only applies to dashboard panel
		return;
	}
	wp_enqueue_script ( 'scimaker_addresources', plugins_url ( '../js/scimaker_resource.js', __FILE__ ), array ('jquery') );
	
	// in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'scimaker_addresources', 'ajax_object', array (
			'ajax_url' => admin_url ( 'admin-ajax.php' ),
			'action' => 'scimaker_addResourceToProject' 
	) );
}

add_action ( 'wp_ajax_scimaker_addResourceToProject', 'scimaker_addResourceToProject_ajax' );
function scimaker_addResourceToProject_ajax() {
	global $wpdb; // this is how you get access to the database
	$args = array ();
	$project_id = intval ( $_POST ['project_id'] );
	$resource_id = intval ( $_POST ['resource_id'] );
	array_push ( $args, $project_id );
	array_push ( $args, $resource_id );
	$out = scimakers_addResourceToProject ( $args );
	
	echo json_encode ( $out );
	
	die (); // this is required to return a proper result
}

?>