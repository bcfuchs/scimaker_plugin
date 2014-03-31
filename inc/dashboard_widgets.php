<?php

/**
 * My Projects
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function scimaker_add_dashboard_widgets() {
	
	$aw = function ($a, $b) {
		
		wp_add_dashboard_widget ( 'my'.$a . '_dashboard_widget', 		// Widget slug.
		'My ' . $b, 		// Title.
		function() use ($a) {scimakers_my_widget ($a);} );		// Display function.
	
	};
	
	$aw ( 'project', "Projects");  // custom post type -prefix, Title
	$aw ( 'team', "Teams" );
	$aw ( 'event', "Events" );
	$aw ( 'resources', "Resources" );
	$aw ( 'challenge', "Challenges");
}
add_action ( 'wp_dashboard_setup', 'scimaker_add_dashboard_widgets' );

/**
 * Scimaker widget creator
 * 
 * TODO-- custom formatters, as with the other widgets. 
 */
function scimakers_my_widget($type) {
	
	// Display whatever it is you want to show.
	// Display whatever it is you want to show.
	$scimaker_prefix = 'scimaker_';
	$cat = $scimaker_prefix . $type;

	$user = wp_get_current_user();
	$uid = $user->ID;
	$posts = scimaker_list_category($cat);
	$formatter = function ($v) {
		return '<li data-scimaker-id="'.$v->ID.'"><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	
	?>
	

<ul>
<?php
 foreach ($posts as $p) {
  echo $formatter($p);
  }
 unset($p);
 ?>
</ul>
<?php
}

?>