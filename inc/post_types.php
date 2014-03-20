<?php 
/** create custom post-type */

//http://codex.wordpress.org/Post_Types
// metadata for each post type in meta.php

function add_post_types_scimaker() {
	
	add_action( 'init', 'create_post_type_project' );
	add_action( 'init', 'create_post_type_resources' );
	add_action( 'init', 'create_post_type_event' );
	add_action( 'init', 'create_post_type_challenge' );
	add_action( 'init', 'create_post_type_club' );
	add_action( 'pre_get_posts', 'add_scimaker_post_types_to_query' );
}
// Show posts of 'post', 'page' and 'movie' post types on home page

// TODO -- very risky for a plugin--overrides other post-types in query  set by other themes...
function add_scimaker_post_types_to_query( $query ) {
	if ( is_home() && $query->is_main_query() )
		$query->set( 'post_type', array( 'post',
				'scimaker_project', 
				'scimaker_resources',
				'scimaker_event',
				'scimaker_club',
				'scimaker_challenge') );
	return $query;
}


function create_post_type_project() {
	create_post_type('scimaker_project','Projects','Project');
}
function create_post_type_resources() {
	create_post_type('scimaker_resources','Resources','Resource');
}

function create_post_type_event() {
	create_post_type('scimaker_event','Events','Event');
}

function create_post_type_club() {
	create_post_type('scimaker_club','Clubs','club');
}
function create_post_type_test() {
	create_post_type('scimaker_test','Tests','Test');
}
function create_post_type_challenge() {
	create_post_type('scimaker_challenge','Challenges','Challenge');
}

function create_post_type($pt,$name,$sing) {
	//http://codex.wordpress.org/Function_Reference/register_post_type
	register_post_type( $pt,
	array(
	'labels' => array(
		'name' => __( $name ),
		'singular_name' => __( $sing ),
		'name_admin_bar'     => _x( $sing, 'add new on admin bar', 'your-plugin-textdomain' )
	),
	'taxonomies'=>array('category','post_tag'),
	'public' => true,
	'menu_position'=>2,
	'menu_icon'=>'',
	'has_archive' => true,
	'supports' => array( 'title', 'editor', 'comments', 'excerpt', 'custom-fields', 'thumbnail','revisions' )
	)
	);
}
add_post_types_scimaker();


?>