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
add_shortcode ( 'scimaker_list_resources_for_project', function ($atts) {
	extract ( shortcode_atts ( array (
			'project_id' => null,
			'raw' => false 
	), $atts ) );
	
	return scimaker_list_resources_for_project ( $project_id, $raw );
} );

/**
 * javascript for shortcodes
 */
wp_enqueue_script ( 'jquery-ui', '//code.jquery.com/ui/1.9.2/jquery-ui.min.js', array (), '1.9.2', true );
wp_enqueue_script ( 'scimaker-plugin', plugins_url ( '../js/scimaker.js', __FILE__ ), array (), '1.0.0', true );
// Actually this could be jsut a filter + new formatter on 
/**
 * 
 * @param int $id
 * @param boolean $raw
 * @return boolean|string
 */
function scimaker_list_resources_for_project($id = null,  $raw = false) {
	$title = "Project Resources";
	$cat_class = "scimaker_resources";
	$prop = array();
	$prop['notproject'] =  "<b>Not a Project!</b>"; 
	$checkPT = function ($id, $type) {
		$post = get_post ( $id );
		return $post->post_type == $type ? true : false;
	};

	// if on a project page && id not supplied, then use post id
	
	$id = $id ?: get_the_ID();
	
	if (! $checkPT ( $id, 'scimaker_project' )) {
		return format_shortcode_list ($prop['notproject'], $title, $cat_class );
	}
	// list of resource ids assoc. with project $id
	$meta = get_post_meta ( $id, 'hasResource', false );
	
	$out = array (); // the output
	// list item formatter
	$f = function ($v) {
		return '<li><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	// list formatter
	$f_big = function ($meta, $f) use(&$out) {
		array_push ( $out, "<ul>" );
		if (! empty ( $meta )) {
			
			foreach ( $meta as $v ) {
				// get the post title + guid
				$p = get_post ( $v );
				array_push ( $out, $f ( $p ) );
			}
			unset ( $v );
		}
		array_push ( $out, "</ul>" );
	};
	if ($raw == true) {
		
	} else {
		$f_big ( $meta, $f );
		return format_shortcode_list ( join(" ",$out), $title, $cat_class );
	}
}

// http://code.jquery.com/ui/1.9.2/jquery-ui.min.js
function scimaker_list_shortcode($atts, $title, $cat) {
	// TODO attributes
	$format = function ($v) {
		return '<li data-scimaker-id="'.$v->ID.'"><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
		
	};
	return format_shortcode_list ( scimaker_list_category_shortcode ( $cat, $format ), $title, $cat );
}
function format_shortcode_list($d, $title, $cat_class) {
	$out = array ();
	array_push ( $out, '<div class="scimaker_list_container ' . $cat_class . '">' );
	array_push ( $out, '<div class="scimaker_title">' . $title . '</div>' );
	array_push ( $out, $d );
	array_push ( $out, '</div>' );
	return join ( " ", $out );
}

// wrapper for shortcodes
function scimaker_list_category_shortcode($category_id, $formatter, $filter) {
	return scimaker_list_category_widget ( $category_id, $formatter, $filter );
}
/* widget data + formatting */
// these are ONLY called by the widgets.
// strings for readability for now...
/**
 * Add basic list html for display in widget
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
		return '<li data-scimaker-id="'.$v->ID.'"><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	// wrap in ul
	$formatter_f = function ($posts_array) use($formatter,$category_id) {
		$out = array ();
		
		array_push ( $out, "<ul class=\"".$category_id."_list\">" );
		foreach ( $posts_array as $v ) {
			array_push ( $out, $formatter ( $v ) );
		}
		array_push ( $out, "</ul>" );
		return join ( " ", $out );
	};
	
	return scimaker_list_category_widget_format ( $category_id, $formatter_f, $filter );
}
/**
 * Run the list of category posts through any formatter.
 *
 *
 * @param unknown $category_id        	
 * @param callable $formatter        	
 * @param callable $filter        	
 * @return boolean string
 */
function scimaker_list_category_widget_format($category_id, $formatter = null, $filter = null) {
	$posts_array = scimaker_list_category ( $category_id );
	$filter = is_callable ( $filter ) ? $filter : $filter = function ($d) {
		return $d & 1;
	};
	// register a default formatter -- takes the posts array as argument
	$formatter = is_callable ( $formatter ) ? $formatter : $formatter = function ($posts_array) {
		$out = array ();
		$f = function ($v) {
			return '<li><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
		};
		array_push ( $out, "<ul>" );
		foreach ( $posts_array as $v ) {
			array_push ( $out, $f ( $v ) );
		}
		array_push ( $out, "</ul>" );
		return join ( " ", $out );
	};
	return $formatter ( array_filter ( $posts_array, $filter ) );
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