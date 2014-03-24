<?php
/**
 * Provide custom metadata fields for custom post types
 * 
 */
add_action ( 'load-post.php', 'scimaker_post_meta_boxes_setup' );
add_action ( 'load-post-new.php', 'scimaker_post_meta_boxes_setup' );

// NOTE: because hook weirdness, display and save functions have to be added in
// different places.
function scimaker_post_meta_boxes_setup() {
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action ( 'add_meta_boxes', 'scimaker_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	scimaker_save_post_class_meta_boxes ();
}

// callback to add all the boxes
function scimaker_add_post_meta_boxes() {
	// getting rid of unnecessary functions...
	// closurre to add html
	$boxf = function ($desc, $type) {
		return function ($object, $box) use($desc, $type) {
			// $desc = "Enter a url for the resources";
			$id = "scimaker_" . $type . "_meta"; // ids need to be different when fields are on the same html page.
			$name = $id;
			$nonce = $id . "_nonce";
			scimaker_post_class_meta_box ( $object, $box, $desc, $id, $name, $nonce );
		};
	};
	// closure to add the box
	$metaf = function ($title, $desc, $metatype, $post_type) use($boxf) {
		add_meta_box ( 'scimaker_url-post-class-'.$metatype, 		// Unique ID
		esc_html__ ( $title, 'scimaker' ), 		// Title
		$boxf ( $desc, $metatype ), 		// Callback function to write html
		                          // 'scimaker_resources_post_class_meta_box_url',
		$post_type, 		// Admin page (or post type)
		'side', 		// Context
		'core' ); // Priority
	};
	$metaf ( 'Resource URL', "Enter a URL for the resource", 'resources_url', 'scimaker_resources' );
	$metaf ( 'Club Link', "Enter a link for the club", 'club_url', 'scimaker_club' );
	$metaf ( 'Club Location', "Enter a location for the club", 'club_geo', 'scimaker_club' );
	$metaf ( 'Event Location', "Enter a location for the event", 'event_geo', 'scimaker_event' );
	$metaf ( 'Event Link', "Enter a link for the event", 'event_url', 'scimaker_event' );

}

function scimaker_save_post_class_meta_boxes() {
	
	// closure to add save callback
	$st  = function($type) {
		return function($post_id,$post) use ($type){
			scimaker_save_post_class_meta( $post_id, $post,"scimaker_".$type."_meta",
			"scimaker_".$type."_meta_nonce" );};
	};
	add_action('save_post',$st('resources_url'),10,2);
	add_action('save_post',$st('club_url'),10,2);
	add_action('save_post',$st('event_url'),10,2);
	add_action('save_post',$st('club_geo'),10,2);
	add_action('save_post',$st('event_geo'),10,2);
}



/**
 * Display the post meta box.
 * 
 * @param Post $object        	
 * @param unknown $box        	
 * @param string $desc        	
 * @param int $id        	
 * @param string $name        	
 * @param string $nonce        	
 * @param callable $fomatter        	
 */
function scimaker_post_class_meta_box($object, $box, $desc, $id, $name, $nonce, $fomatter = null) {
	// define a default formatter
	$formatter = is_callable ( $formatter ) ? $formatter : function ($object, $box, $desc, $id, $name, $nonce) {
		wp_nonce_field ( basename ( __FILE__ ), $nonce );
		?>
<p class="scimaker_meta_box">
	<label for="<?php echo $name ?>"><?php _e( $desc, 'scimakers' ); ?></label> <br /> <input class="widefat" type="text"
		name="<?php echo $name ?>" id="<?php echo $id ?>"
		value="<?php echo esc_attr( get_post_meta( $object->ID, $id, true ) ); ?>" size="30" />
</p>
<?php
	};
	$formatter ( $object, $box, $desc, $id, $name, $nonce );
}


/**
 *  Save the meta box's post metadata
 * @param string $post_id
 * @param Post $post
 * @param string $klass
 * @param string $nonce
 *
 */
function scimaker_save_post_class_meta($post_id, $post, $klass, $nonce) {
	/* Verify the nonce before proceeding. */
	if (! isset ( $_POST [$nonce] ) || ! wp_verify_nonce ( $_POST [$nonce], basename ( __FILE__ ) ))
		return $post_id;
		/* Get the post type object. */
	$post_type = get_post_type_object ( $post->post_type );
	/* Check if the current user has permission to edit the post. */
	if (! current_user_can ( $post_type->cap->edit_post, $post_id ))
		return $post_id;
		/* Get the posted data and sanitize it for use as an HTML class. */
		// $new_meta_value = (isset ( $_POST [$klass] ) ? sanitize_html_class ( $_POST [$klass] ) : '');
	$new_meta_value = (isset ( $_POST [$klass] ) ? $_POST [$klass] : '');
	/* Get the meta key. */
	$meta_key = $klass;
	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta ( $post_id, $meta_key, true );
	/* If a new meta value was added and there was no previous value, add it. */
	if ($new_meta_value && '' == $meta_value)
		add_post_meta ( $post_id, $meta_key, $new_meta_value, true );
		/* If the new meta value does not match the old value, update it. */
	elseif ($new_meta_value && $new_meta_value != $meta_value)
		update_post_meta ( $post_id, $meta_key, $new_meta_value );
		/* If there is no new meta value but an old value exists, delete it. */
	elseif ('' == $new_meta_value && $meta_value)
		delete_post_meta ( $post_id, $meta_key, $meta_value );
}
?>