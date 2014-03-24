<?php

/**
 * Scimaker
 * shortcodes and general functions
 */

/** shortcodes */

add_shortcode ( 'scimaker_list_projects', function($atts){ return scimaker_list_shortcode($atts,'Projects','scimaker_project'); } );
add_shortcode ( 'scimaker_list_events', function($atts){ return scimaker_list_shortcode($atts,'Events','scimaker_event'); }  );
add_shortcode ( 'scimaker_list_clubs', function($atts){ return scimaker_list_shortcode($atts,'Clubs','scimaker_club'); }  );
add_shortcode ( 'scimaker_list_challenges', function($atts){ return scimaker_list_shortcode($atts,'Challenges','scimaker_challenge'); }  );
add_shortcode ( 'scimaker_list_resources', function($atts){ return scimaker_list_shortcode($atts,'Resources','scimaker_resources'); }  );

/** javascript for shortcodes*/
wp_enqueue_script( 'jquery-ui', '//code.jquery.com/ui/1.9.2/jquery-ui.min.js', array(), '1.9.2', true );
wp_enqueue_script( 'scimaker-plugin', plugins_url( '../js/scimaker.js', __FILE__ ), array(), '1.0.0', true );

//http://code.jquery.com/ui/1.9.2/jquery-ui.min.js

function scimaker_list_shortcode($atts,$title,$cat) {
//TODO attributes
//NB we can add a formatter and a filter
	return format_shortcode_list(scimaker_list_category_widget($cat),$title,$cat);
}


function format_shortcode_list($d,$title,$cat_class) {
	
	$out = array();
	array_push($out,'<div class="scimaker_list_container '.$cat_class.'">');
	array_push($out,'<div class="scimaker_title">'.$title.'</div>');
	array_push($out,$d);
	array_push($out,'</div>');
	return  join(" ",$out);
	
}

/* widget data + formatting */
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
 * @param string $category_id
 * @param callable $formatter
 * @param callable $filter
 * 
 */
function scimaker_list_category_widget($category_id, $formatter = null, $filter = null) {
	
	$filter = is_callable($filter) ? $filter : $filter = function($d) {return $d & 1;};
		//basic formatting..
	$formatter =  is_callable($formatter) ? $formatter :function($v) {return '<li><a href="'.$v->guid.'">'.$v->post_title."</a></li>"; };
	// wrap formatter to get a list of titles
	// Actually this can be done with javascript!
	// $titles = array();
	// $formatter = function($v) use(&$titles,$formatter) { array_push($titles,$v->post_title);return $formatter($v);};
//	
		$posts_array = scimaker_list_category($category_id);
	
	// StringBuilder, php-style
	$out = array();
	
	array_push($out,"<ul>");
	array_filter($posts_array,$filter);
	foreach ($posts_array as $v) {	array_push($out, $formatter($v));}
	unset ($v);
	array_push($out,"</ul>");
	
	// array_push($out,"<script>var scimaker_titles= ['".join("','",$titles)."'];</script>");
	return join(" ",$out);
}
/**
 * 
 * @param string $category_id
 * @return string
 */
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