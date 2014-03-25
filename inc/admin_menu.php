<?php 
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
	add_menu_page( 'Scimaker', 'ScienceMakers', 'manage_options', 'custompage', 'my_custom_menu_page', plugins_url( 'scimaker/img/icon2.png' ), 500 );
}

function my_custom_menu_page(){
	?>
	<style>
	
	div.scimaker_shortcodes img {
	height: 130px;
	}
	</style>
<div class="scimaker_shortcodes">
<h2>Shortcodes</h2>
<p>Note:  colours can be changed in the theme customisation settings in the scimakers theme section of the admin page.
<h3>scimaker_list_projects</h3>
<p>Display a list of projects</p>
<img src="<?php echo
plugins_url( '../img/scimaker_list_projects.png' , __FILE__ ) ?> "/>
<h3>scimaker_list_clubs</h3>
<p>Display a list of clubs</p>
<img  src="<?php echo
plugins_url( '../img/scimaker_list_clubs.png' , __FILE__ ) ?> "/>
<h3>scimaker_list_challenges</h3>
<p>Display a list of challenges</p>
<img  style="height:100px" src="<?php echo
plugins_url( '../img/scimaker_list_challenges.png' , __FILE__ ) ?> "/>
<h3>scimaker_list_resources</h3>
<p>Display a list of resources</p>
<img src="<?php echo
plugins_url( '../img/scimaker_list_resources.png' , __FILE__ ) ?> "/>

<h3>scimaker_list_events</h3>
<p>Display a list of events</p>
<img style="height:100px" src="<?php echo
plugins_url( '../img/scimaker_list_events.png' , __FILE__ ) ?> "/>

</div>	
	<?php 
}

?>