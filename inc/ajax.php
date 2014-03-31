
<?php

// http://codex.wordpress.org/AJAX_in_Plugins
// note this works only in admin pages.
add_action ( 'admin_enqueue_scripts', 'scimaker_addResourceToProject_enqueue' );
add_action ( 'wp_enqueue_scripts', 'scimaker_addResourceToProject_enqueue' );

function scimaker_addResourceToProject_enqueue($hook) {
	$checkPT = function ($id, $type) {
		$post = get_post ( $id );
		return $post->post_type == $type ? true : false;
	};
	//only load when a project
	
	wp_enqueue_script ( 'scimaker_addresources', plugins_url ( '../js/scimaker_resource.js', __FILE__ ), array (
			'jquery' 
	) );
	
	$project_id = get_the_ID();
	$meta = get_post_meta ( $project_id, 'hasResource', false );
	// in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script ( 'scimaker_addresources', 'scimaker_addresources', array (
			'url' => admin_url ( 'admin-ajax.php' ),
			'project_id'=>$project_id,
			'resources'=>$meta, // list of resources already assigned
			'action' => 'scimaker_addResourceToProject' 
	) );
}

add_action ( 'wp_ajax_scimaker_addResourceToProject', 'scimaker_addResourceToProject_ajax' );
add_action ( 'wp_ajax_nopriv_scimaker_addResourceToProject', 'scimaker_addResourceToProject_ajax' );
// TODO token system.

function scimaker_addResourceToProject_ajax() {
//	global $wpdb; // this is how you get access to the database
	$out = array ();
	// check if user has required level in all roles-fail if missing in any one. 
	$checkUser = function ($level) use (&$out) {
		$user = wp_get_current_user ();
		foreach ( $user->roles as $role ) {
			$caps = get_role ( $role )->capabilities;
			if (! $caps[$level] == true) {
				return false;
			}
		} // foreach
		unset ( $role );
		return true;
	}; // function
	
	
	if (!$checkUser('level_1')) {
		$out['status'] = 'insufficient permissions';
		echo json_encode($out);
		die();
	}
	
	$args = array ();
	$project_id = intval ( $_POST ['project_id'] );
	$resource_id = intval ( $_POST ['resource_id'] );
	array_push ( $args, $project_id );
	array_push ( $args, $resource_id );
	// in ? 
	$out = scimakers_addResourceToProject ( $args );
	echo json_encode ( $out );
	
	die (); // this is required to return a proper result
}

add_action ( 'admin_enqueue_scripts', 'scimaker_joinTeam_enqueue' );
add_action ( 'wp_enqueue_scripts', 'scimaker_joinTeam_enqueue' );

function scimaker_joinTeam_enqueue($hook) {
	
	//only load when a project

	wp_enqueue_script ( 'scimaker_jointeam', plugins_url ( '../js/scimaker_jointeam.js', __FILE__ ), array (
	'jquery'
			) );
	// assume we are on team page.
	// otherwise js must get the id from an attribute;
	
	$team_id = get_the_ID();
	$meta = get_post_meta ( $team_id, 'hasMember', false );
	// in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script ( 'scimaker_jointeam', 'scimaker_jointeam', array (
	'url' => admin_url ( 'admin-ajax.php' ),
	'team_id'=>$team_id,
	'resources'=>$meta, // list of resources already assigned
	'action' => 'scimaker_joinTeam'
			) );
}

add_action ( 'wp_ajax_scimaker_joinTeam', 'scimaker_joinTeam_ajax' );
add_action ( 'wp_ajax_nopriv_scimaker_joinTeam', 'scimaker_joinTeam_ajax' );
// TODO token system.

function scimaker_joinTeam_ajax() {
	//	global $wpdb; // this is how you get access to the database
	$out = array ();
	
	// check if user has required level in all roles-fail if missing in any one.
	$checkUser = function ($level) use (&$out) {
		$user = wp_get_current_user ();
		foreach ( $user->roles as $role ) {
			$caps = get_role ( $role )->capabilities;
			if (! $caps[$level] == true) {
				return false;
			}
		} // foreach
		unset ( $role );
		return true;
	}; // function


	if (!$checkUser('level_1')) {
		$out['status'] = 'insufficient permissions';
		echo json_encode($out);
		die();
	}

	$args = array ();
	$team_id = intval ( $_POST ['team_id'] );
	$user = get_current_user();
	$user_id = $user->ID;
	array_push ( $args, $team_id );
	array_push ( $args, $user_id );
	
	// in ?
	$out = scimakers_joinTeam ( $args );
	$out['param2'] = $args;
	echo json_encode ( $out );

	die (); // this is required to return a proper result
}

?>