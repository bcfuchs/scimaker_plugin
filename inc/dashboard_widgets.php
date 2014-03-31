<?php

/**
 * My Projects
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function scimaker_add_dashboard_widgets() {
	
	$aw = function ($a, $b) {
		$slug = 'my' . $a . '_1_dashboard_widget';
		wp_add_dashboard_widget ( $slug, 		// Widget slug.
		'My ' . $b, 		// Title.
		function () use($a,$slug) {
			$scimaker_prefix = 'scimaker_';
			scimakers_my_widget_posts ( $scimaker_prefix . $a,$slug);
		} ); // Display function.
	};
	$awu = function ($a, $b,$rel) {
		$slug = 'my' . $a . '_1_dashboard_widget';
		wp_add_dashboard_widget ( $slug, 		// Widget slug.
		'My ' . $b, 		// Title.
		function () use($a,$slug,$rel) {
			$scimaker_prefix = 'scimaker_';
			scimakers_my_widget_user ( $scimaker_prefix . $a,$slug,$rel);
		} ); // Display function.
	};
	
	$aw ( 'project', "Projects" ); // custom post type -prefix, Title
	 $awu ( 'team', "Teams",'hasMember' );
	$aw ( 'event', "Events" );
	$aw ( 'resources', "Resources" );
	$aw ( 'challenge', "Challenges" );
}
add_action ( 'wp_dashboard_setup', 'scimaker_add_dashboard_widgets' );

/**
 * Scimaker widget creator
 * this is the display callback
 * TODO-- custom formatters, as with the other widgets.
 */
function scimakers_my_widget_posts($cat,$slug) {
	//This shows only events, resources, etc. you have contributed
	// NOT ones you have added to a project or joined.
	//TODO a second loop is needed to show stuff you've collected, etc.

	$user = wp_get_current_user ();
	$uid = $user->ID;
	$posts = scimaker_list_category ( $cat );
	$formatter = function ($v) {
		return '<li data-scimaker-id="' . $v->ID . '"><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	$f2 = function ($v,$pa,$uid) {
		return '<li data-scimaker-id="' . $v->ID . '">Oops! ' . $v->post_title . ' ' . $pa . " " . $uid . "</li>";
	};
	$sluginfoId = $slug . "_info";
	
	echo '<div class="sm_widget_info" style="display:none"> post-type: '.$cat.'</div>';
	?>
	
	<ul>
		<?php
	foreach ( $posts as $p ) {	
		if ($p->post_author == $uid) {
			echo $formatter ( $p );
		}	
	}
	unset ( $p );
	?>
		</ul>
		<div id="<?php echo $sluginfoId?>" class="dashicons dashicons-info"></div>
		<style>
		#<?php echo $sluginfoId?> {
		cursor: pointer;
		}
		</style>
		<script>
jQuery(document).ready(function($){
	$('#<?php echo  $sluginfoId?>').click(function(e){
		e.preventDefault();
		$('#<?php echo $slug; ?> .sm_widget_info').toggle();
		});
	});
	</script>
<?php 
}
/**
 * 
 * @param string $cat // the post type
 * @param string $slug // 
 * @param string $rel // the relationship
 * @return string
 */
function scimakers_my_widget_user($cat,$slug,$rel = null) {
	

	// get the user
	
	
	$user = wp_get_current_user ();
	$uid = $user->ID;
	
	// get all teams;
	$posts = get_posts(array('post_type'=>$cat));
	// get metadata for each team. 
	$myPosts = array();
	foreach ($posts as $p) {
		$meta = get_post_meta($p->ID,$rel,false);	
	    if (in_array($uid,$meta)){
			
		}
		array_push($myPosts,$p);
	}
	unset($p);
		$formatter = function ($v) {
		return '<li data-scimaker-id="' . $v->ID . '"><a href="' . $v->guid . '">' . $v->post_title . "</a></li>";
	};
	
	$f2 = function ($v,$pa,$uid) {
		return '<li data-scimaker-id="' . $v->ID . '">Oops! ' . $v->post_title . ' ' . $pa . " " . $uid . "</li>";
	};
	$sluginfoId = $slug . "_info";

	echo '<div class="sm_widget_info" style="display:none"> post-type: '.$cat.'</div>';
	?>
	
	<ul>
		<?php
	foreach ( $myPosts as $p ) {	
	
		if ($p->post_author == $uid) {
			echo $formatter ( $p );
		}	
	}
	unset ( $p );
	?>
		</ul>
		<div id="<?php echo $sluginfoId?>" class="dashicons dashicons-info"></div>
		<style>
		#<?php echo $sluginfoId?> {
		cursor: pointer;
		}
		</style>
		<script>
jQuery(document).ready(function($){
	$('#<?php echo  $sluginfoId?>').click(function(e){
		e.preventDefault();
		$('#<?php echo $slug; ?> .sm_widget_info').toggle();
		});
  

	
});

		</script>
		<?php 
		
}
		?>

