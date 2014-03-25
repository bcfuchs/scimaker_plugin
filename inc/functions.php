<?php

/**
 * Scimaker
 * shortcodes and general functions
 */

/**
 * shortcodes
 */
add_shortcode ( 'scimaker_list_projects', function ($atts) {
	return scimaker_list_shortcode ( $atts, 'Projects', 'scimaker_project' );
} );
add_shortcode ( 'scimaker_list_events', function ($atts) {
	return scimaker_list_shortcode ( $atts, 'Events', 'scimaker_event' );
} );
add_shortcode ( 'scimaker_list_clubs', function ($atts) {
	return scimaker_list_shortcode ( $atts, 'Clubs', 'scimaker_club' );
} );
add_shortcode ( 'scimaker_list_challenges', function ($atts) {
	return scimaker_list_shortcode ( $atts, 'Challenges', 'scimaker_challenge' );
} );
add_shortcode ( 'scimaker_list_resources', function ($atts) {
	return scimaker_list_shortcode ( $atts, 'Resources', 'scimaker_resources' );
} );

/**
 * javascript for shortcodes
 */
wp_enqueue_script ( 'jquery-ui', '//code.jquery.com/ui/1.9.2/jquery-ui.min.js', array (), '1.9.2', true );
wp_enqueue_script ( 'scimaker-plugin', plugins_url ( '../js/scimaker.js', __FILE__ ), array (), '1.0.0', true );

// http://code.jquery.com/ui/1.9.2/jquery-ui.min.js
function scimaker_list_shortcode($atts, $title, $cat) {
	// TODO attributes
	// NB we can add a formatter and a filter
	return format_shortcode_list ( scimaker_list_category_widget ( $cat ), $title, $cat );
}
function format_shortcode_list($d, $title, $cat_class) {
	$out = array ();
	array_push ( $out, '<div class="scimaker_list_container ' . $cat_class . '">' );
	array_push ( $out, '<div class="scimaker_title">' . $title . '</div>' );
	array_push ( $out, $d );
	array_push ( $out, '</div>' );
	return join ( " ", $out );
}

/* widget data + formatting */
// these are ONLY called by the widgets.
// strings for readability for now...



/**
 * Add  basic list html for display in widget
 *
 * @param string $category_id        	
 * @param callable $formatter        	
 * @param callable $filter        	
 *
 */
function scimaker_list_category_widget($category_id, $formatter = null, $filter = null) {
	$filter = is_callable ( $filter ) ? $filter : $filter = function ($d) {
		return $d & 1;
	};
	
	// default formatter..
	$formatter = is_callable ( $formatter ) ? $formatter : function ($v) {
		return '<li><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	
	$formatter_f = function ($posts_array) use ($formatter) {
		$out = array ();
		
		array_push ( $out, "<ul>" );
		foreach ( $posts_array as $v ) {
			array_push ( $out, $formatter ( $v ) );
		}
		array_push ( $out, "</ul>" );
		return join ( " ", $out );
	};
	
	return scimaker_list_category_widget_format($category_id,$formatter_f,$filter);
}
/**
 * Run the list of category posts through any formatter. 
 * 
 * @param unknown $category_id
 * @param callable $formatter
 * @param callable $filter
 * @return boolean|string
 */
function scimaker_list_category_widget_format($category_id, $formatter = null, $filter = null) {
	$posts_array = scimaker_list_category ( $category_id );
	$filter = is_callable ( $filter ) ? $filter : $filter = function ($d) {
		return $d & 1;
	};
	// register a default formatter -- takes the posts array as argument
	$formatter = is_callable ( $formatter ) ? $formatter : $formatter = function ($posts_array) {
		$out = array ();
		$f = function($v){return '<li><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";};
		array_push ( $out, "<ul>" );
		foreach ( $posts_array as $v ) {
			array_push ( $out, $f ( $v ) );
		}
		array_push ( $out, "</ul>" );
		return join ( " ", $out );
	};
	return $formatter (array_filter ( $posts_array, $filter ));
	
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
		
		$posts_array = get_posts ( array (
				'post_type' => $category_id 
		) );
		
		// array_push($out,"</ul>");
	} catch ( Exception $e ) {
		return array (
				$e 
		);
	}
	
	return $posts_array;
}
?>