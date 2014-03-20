<?php

/**
 * XML-RPC methods to support scimaker clubs
 *  issues: the category filter is by id
 *  		so 
 * 
 * @param unknown $cat
 * @return string
 */
function scimakers_getPostsByCategory($cat) {
	try {
		
		return scimakers_package_post_info ( scimaker_list_category ( $cat ) );
	} catch ( Exception $e ) {
		return "error " . e;
	}
}

function scimakers_getProjects($args) {
	$cat = 3; // 'project'
	
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_getResources($args) {
	$cat = 2; // 'resource';
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_getChallenges($args) {
	$cat = 5; //'challenge';
	
	return scimakers_getPostsByCategory ( $cat );
}

function scimakers_getEvents($args) {
	$cat = 6;//'event';	
	return scimakers_getPostsByCategory ( $cat );
}
function scimakers_package_post_info($plist) {
	$out = array ();
	foreach ( $plist as $v ) {
		array_push ( $out, $v->post_title );
	}
	return $out;
}

function mynamespace_new_xmlrpc_methods($methods) {

	$methods ['scimakers.getProjects'] = 'scimakers_getProjects';
	$methods ['scimakers.getEvents'] = 'scimakers_getEvents';
	$methods ['scimakers.getChallenges'] = 'scimakers_getChallenges';
	$methods ['scimakers.getResources'] = 'scimakers_getResources';
	return $methods;
}

add_filter ( 'xmlrpc_methods', 'mynamespace_new_xmlrpc_methods' );

?>