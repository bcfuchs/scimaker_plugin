<?php
/*
Plugin Name: ScienceMaker by Mobile Collective
Plugin URI: http://mobilecollective.co.uk
Description: Add a sciencemaker widget to your wordpress
Version: 0.1
Author: Brian Fuchs
Author URI: http://mobilecollective.co.uk
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
*/

include plugin_dir_path(__FILE__).'inc/functions.php';
include plugin_dir_path(__FILE__).'inc/widgets.php';
include plugin_dir_path(__FILE__).'inc/xmlrpc.php';
include plugin_dir_path(__FILE__).'inc/post_types.php';
include plugin_dir_path(__FILE__).'inc/meta.php';

function t3_try($atts) {
echo "t3 ok</br>";
}

add_shortcode('t3','t3_try');
?>