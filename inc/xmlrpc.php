<?php

/**
 * XML-RPC methods to support scimaker clubs
 *  issues: the category filter is by id
 *  		so 
 * 
 * @param unknown $cat
 * @return string
 */
function scimakers_addResourceToProject($args) {
	$id = 1;
	$project_id = $args[0];
	$resource_id = $args[1];
	$out = array();
	// for the moment, do this w/o ref. to user.
	$res = add_metadata ( 'post', $project_id, 'hasResource', $resource_id, false );
	$out['msg'] = "added " . $resource_id . " to " . $project_id;
	$out['args'] = $args;
	$out['res'] = $res;
	return $out;
	
}
function scimakers_getPostsByCategory($cat) {
	try {
		return scimakers_package_post_info ( scimaker_list_category ( $cat ) );
	} catch ( Exception $e ) {
		return "error " . e;
	}
}
function scimakers_getProjects($args) {
	$cat = "scimaker_project"; // 'project'
	
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_getResources($args) {
	$cat = "scimaker_resources"; // 'resource';
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_getChallenges($args) {
	$cat = "scimaker_challenge"; // 'challenge';
	
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_getEvents($args) {
	$cat = "scimaker_event"; // 'event';
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_package_rpc($msg) {
	$out = array ();
	$info = array ();
	$out ['data'] = $msg;
	$info ['timestamp'] = time ();
	$out ['info'] = $info;
	return $out;
}
function scimakers_package_post_info($plist, $formatter) {
	$formatter = is_callable ( $formatter ) ? $formatter : $formatter = function ($plist) {
		$bigout = array ();
		foreach ( $plist as $v ) {
			$out = array ();
			$out ['title'] = $v->post_title;
			$out ['guid'] = $v->guid;
			$out ['id'] = $v->ID; // this is what will be used
			$out ['post'] = $v;
			$bigout [$v->ID] = $out;
		}
		return $bigout;
	};
	return scimakers_package_rpc ( $formatter ( $plist ) );
}
function mynamespace_new_xmlrpc_methods($methods) {
	$gt = function ($a) use(&$methods) {
		$methods ['scimakers.' . $a] = 'scimakers_' . $a;
	};
	$gt ( 'addResourceToProject');
	$gt ( 'getProjects' );
	$gt ( 'getEvents' );
	$gt ( 'getChallenges' );
	$gt ( 'getResources' );
	
	return $methods;
}

add_filter ( 'xmlrpc_methods', 'mynamespace_new_xmlrpc_methods' );

?>