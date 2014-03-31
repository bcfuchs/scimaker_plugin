<?php 


function scimakers_joinTeam($args) {
	$team_id = $args [0];
	$user_id = $args [1];
	$verb = 'hasMember';
	$post_type = 'scimaker_team';
	// $post_type = 'scimaker_project';
	//TODO tests should go in as callbacks here.
	$out = scimakers_addRelation_user($post_type,'',$team_id,$verb,$user_id);
	$out ['msg'] = "added you to " . get_post_field('post_title',$team_id);
	$out ['args'] = $args;
	$out ['res'] = get_post_meta ( $team_id, 'hasMember', false );
	
	
	return scimakers_package_rpc ( $out, $status );
}
/**
 * Creates a scimaker metadata relation project hasResource $resource_id
 * @param unknown $args
 * @return array
 */
function scimakers_addResourceToProject($args) {
	$project_id = $args [0];
	$resource_id = $args [1];
	$verb = 'hasResource';
	$post_type = 'scimaker_project';
	// $post_type = 'scimaker_project';
	//TODO tests should go in as callbacks here. 
	$out = scimakers_addRelation($post_type,'scimaker_resources',$project_id,$verb,$resource_id);
// if we return here that's good. 
//TOD0 addRelation should probably return true false + error message. 
	$out ['msg'] = "added " . $resource_id . " to " . $project_id;
	$out ['args'] = $args;
	$out ['res'] = get_post_meta ( $project_id, 'hasResource', false );


	return scimakers_package_rpc ( $out, $status );
}
/**
 * 
 * @param string $post_type  
 * @param string $object_post_type
 * @param int $subject // id of subject
 * @param string $verb  // relation
 * @param int $object // id of object. 
 * @return 
 */
function scimakers_addRelation($subject_post_type,$object_post_type,$subject,$verb,$object) {

	$status = false;
	// $post_type = 'scimaker_project';
	
	$out = array ();
	// check right post type
	$checkPT = function($id,$type) use (&$out) {
		$post = get_post($id);
		return	$post->post_type == $type ?  true :  false;
	
	};
	// is project post-type ?
	$out['args2'] = array($subject_post_type,$object_post_type,$subject,$verb,$object);
	
	if (! $checkPT($subject,$subject_post_type)) {
		$status = false;
		
		$out['err'] = 'no subject for ' . $subject;
		return scimakers_package_rpc ( $out, $status );
	}
	
	// is project post-type scimaker_resources?
	if (! $checkPT($object,$object_post_type)) {
		$status = false;
		$out['err'] = 'no object';
		return scimakers_package_rpc ( $out, $status );
	}
	$meta = get_post_meta ( $subject, $verb, false );
	
	// is this subject already assigned?
	if (in_array ( $object, $meta )) {
		$status = false;
		$out ['msg'] = "relationship exists!";
	
		delete_post_meta( $subject,$verb,$object);
		return scimakers_package_rpc ( $out, $status );
		
	}
	
	$status = true;
	
	// for the moment, do this w/o ref. to user.
	$res = add_metadata ( 'post', $subject, $verb,$object, false );
	$out['status'] = $status;
	return $out;
	
	
}

/**
 *
 * @param string $post_type
 * @param string $object_post_type
 * @param int $subject // id of subject
 * @param string $verb  // relation
 * @param int $object // id of object.
 * @return
 */
function scimakers_addRelation_user($subject_post_type,$object_post_type,$subject,$verb,$object) {

	$status = false;
	// $post_type = 'scimaker_project';

	$out = array ();
	// check right post type
	$checkPT = function($id,$type) use (&$out) {
		$post = get_post($id);
		return	$post->post_type == $type ?  true :  false;

	};
	// is project post-type ?
	$out['args2'] = array($subject_post_type,$object_post_type,$subject,$verb,$object);

	if (! $checkPT($subject,$subject_post_type)) {
		$status = false;

		$out['err'] = 'no subject ' . $subject_post_type .' for ' . $subject;
		return scimakers_package_rpc ( $out, $status );
	}

	
	$meta = get_post_meta ( $subject, $verb, false );

	// is this subject already assigned?
	if (in_array ( $object, $meta )) {
		$status = false;
		$out ['msg'] = "relationship exists!";

		delete_post_meta( $subject,$verb,$object);
		return scimakers_package_rpc ( $out, $status );

	}

	$status = true;

	// for the moment, do this w/o ref. to user.
	$res = add_metadata ( 'post', $subject, $verb,$object, false );
	$out['status'] = $status;
	return $out;


}
?>