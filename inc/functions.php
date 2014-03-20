<?php

/** shortcodes */



/**
 * scimaker_list_projects
 * @param unknown $atts
 */
function scimaker_list_projects($atts) {
	try {
		
		echo "list scimaker projects<br/>";
		$posts_array = scimaker_list_category(3);
		
		echo "hi! there are " . count ( $posts_array ) . " projects<br/>";
		foreach ($posts_array as $v) {
			echo $v->post_title . "<br/>";
			echo '<a href="'.$v->guid.'">'.$v->post_title."</a><br/>";
		}
		unset ($v);
		echo "<hr/>";
		$post_1 =  $posts_array[0];
		echo $post_1->post_title . "</br>";
		echo '<a href="'.$post_1->guid.'">'.$post_1->post_title."</a><br/>";
		echo "<pre>".print_r($posts_array[0])."</pre>";
	} catch ( Exception $e ) {
	}
}
add_shortcode ( 'scimaker_projects', 'scimaker_list_projects' );


// strings for readability for now...
function scimaker_list_projects_widget() {
	$cat = 'scimaker_project'; // projects
	return scimaker_list_category_widget($cat); 		
}

function scimaker_list_challenges_widget() {
	$cat = 'scimaker_challenge'; //'challenge'; 
	return scimaker_list_category_widget($cat);
}

function scimaker_list_events_widget() {
	$cat = 'scimaker_event';//'event';
	return scimaker_list_category_widget($cat);
}

function scimaker_list_resources_widget() {
	$cat = 'scimaker_resources'; // 'resource';
	return scimaker_list_category_widget($cat);
}

/**
 * Add some basic html for display in widget
 * @param unknown $category_id
 * @param unknown $filter
 */
function scimaker_list_category_widget($category_id, $filter) {
	
	$posts_array = scimaker_list_category($category_id);
	// StringBuilder, php-style
	$out = array();
	array_push($out,"<ul>");
	foreach ($posts_array as $v) {
		//	echo $v->post_title . "<br/>";
		array_push($out, '<li><a href="'.$v->guid.'">'.$v->post_title."</a></li>");
	
	}
	unset ($v);
	array_push($out,"</ul>");
	return join(" ",$out);
}

function scimaker_list_category($category_id) {
	// http://codex.wordpress.org/Template_Tags/get_posts
	// Note: The category parameter needs to be the ID of the category, and not the category name.
	
	$posts_array;
	
	try {

		$posts_array = get_posts ( array(
		'post_type' => $category_id
		) );
		
		//array_push($out,"</ul>");

	} catch ( Exception $e ) { 
			return array($e);
	}

	return $posts_array;
}
?>