<?php 
/** create custom post-type */

//http://codex.wordpress.org/Post_Types
// metadata for each post type is added in meta.php

add_post_types_scimaker();

function add_post_types_scimaker() {
	$post_types = array('post');
	$apt = function($a,$b,$c,$pos) use (&$post_types) {
		array_push($post_types,$a);
		add_action( 'init',  function() use($a,$b,$c,$pos) {create_post_type($a,$b,$c,$pos);} );
		
	};
	
	$apt('scimaker_resources','Resources','Resource',26);
	$apt('scimaker_event','Events','Event',27);
	$apt('scimaker_challenge','Challenges','Challenge',28);
	$apt('scimaker_club','Clubs','Club',29);
	$apt('scimaker_forum','Groups','Group',30);
	add_action( 'pre_get_posts', add_scimaker_post_types_to_query($post_types));
}

function add_scimaker_post_types_to_query( $ar ) {
	
	return function($query) use ($ar) {
	if ( is_home() && $query->is_main_query() )
		
		$query->set( 'post_type', $ar );
		return $query;
	};
}


function create_post_type($pt,$name,$sing,$menu_position) {
	//http://codex.wordpress.org/Function_Reference/register_post_type
	register_post_type( $pt,
	array(
	'labels' => array(
		'name' => __( $name ),
		'singular_name' => __( $sing ),
		'name_admin_bar'     => _x( $sing, 'add new on admin bar', 'scimaker-textdomain' )
	),
	'taxonomies'=>array('post_tag'),
	'public' => true,
	'menu_position'=>$menu_position,
	'menu_icon'=>'',
	'has_archive' => true,
	'supports' => array( 'title', 'editor','author', 'comments', 'excerpt',  'thumbnail','revisions' )
	)
	);
}



?>