<?php
/**
 * Provide custom metadata fields for custom post types
 * 
 */
add_action ( 'load-post.php', 'scimaker_post_meta_boxes_setup' );
add_action ( 'load-post-new.php', 'scimaker_post_meta_boxes_setup' );
function scimaker_post_meta_boxes_setup() {
	
	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action ( 'add_meta_boxes', 'scimaker_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	scimaker_save_post_class_meta_boxes ();
}

// callback to add all the boxes
function scimaker_add_post_meta_boxes() {
	
	// http://codex.wordpress.org/Function_Reference/add_meta_box
	// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
	add_meta_box ( 'scimaker_resources-url-post-class', 	// Unique ID
	esc_html__ ( 'Resource URL', 'scimaker' ), 	// Title
	'scimaker_resources_post_class_meta_box_url', 	// Callback function to write html
	'scimaker_resources', 	// Admin page (or post type)
	'side', 	// Context
	'default' );	// Priority
	
	add_meta_box ( 'scimaker_club-url-post-class', 	// Unique ID
	esc_html__ ( 'Club Link', 'scimaker' ), 	// Title
	'scimaker_club_post_class_meta_box_url', 	// Callback function to write html
	'scimaker_club', 	// Admin page (or post type)
	'side', 	// Context
	'default' );	// Priority

}
function scimaker_save_post_class_meta_boxes() {
	add_action ( 'save_post', 'scimaker_resources_save_post_class_meta', 10, 2 );
	add_action ( 'save_post', 'scimaker_club_save_post_class_meta', 10, 2 );
}

/** Resources metadata */

// URI
function scimaker_resources_post_class_meta_box_url($object, $box) {
	$desc = "Enter a url for the resources";
	$id = "scimaker_resources_url_meta";
	$name = $id;
	$nonce = $id . "_nonce";
	scimaker_post_class_meta_box ( $object, $box, $desc, $id, $name, $nonce );
}

function scimaker_resources_save_post_class_meta($post_id, $post) {

	$klass = "scimaker_resources_url_meta";
	$nonce = $klass . "_nonce";

	scimaker_save_post_class_meta( $post_id, $post, $klass, $nonce );
}

/** Club Metadata */

// URI
function scimaker_club_post_class_meta_box_url($object, $box) {
	$desc = "Enter a link for the Club";
	$id = "scimaker_club_url_meta";
	$name = $id;
	$nonce = $id . "_nonce";
	scimaker_post_class_meta_box ( $object, $box, $desc, $id, $name, $nonce );
}

function scimaker_club_save_post_class_meta($post_id, $post) {

	$klass = "scimaker_club_url_meta";
	$nonce = $klass . "_nonce";

	scimaker_save_post_class_meta( $post_id, $post, $klass, $nonce );
}


/* Display the post meta box. */
function scimaker_post_class_meta_box($object, $box, $desc, $id, $name, $nonce) {
	?>

	<?php wp_nonce_field( basename( __FILE__ ), $nonce ); ?>
<p>
	<label for="<?php echo $name ?>"><?php _e( $desc, 'scimakers' ); ?></label> <br /> <input class="widefat" type="text"
		name="<?php echo $name ?>" id="<?php echo $id ?>"
		value="<?php echo esc_attr( get_post_meta( $object->ID, $id, true ) ); ?>" size="30" />
</p>
<?php
}


/* Save the meta box's post metadata. */
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
	//$new_meta_value = (isset ( $_POST [$klass] ) ? sanitize_html_class ( $_POST [$klass] ) : '');
	$new_meta_value = (isset ( $_POST[$klass] ) ?  $_POST[$klass]  : '');
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