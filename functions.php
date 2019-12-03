<?php
/*  ds106 bank theme functions	
	http://github.com/cogdog/ds106bank
	
	The parent wp-bootstrap theme this was built on has been migrated
	into this theme, so itself can act as a parent to a  child theme
*/

// Segmenting that old long functions.php into more bite sized bits!

require_once( __DIR__ . '/wp-bootstrap.php'); // what was once parent theme
require_once( __DIR__ . '/includes/setup.php'); // inits and setups
require_once( __DIR__ . '/includes/post-types-tax.php'); // custom post types and taxonomies
require_once( __DIR__ . '/includes/assignments.php'); // assignment helpers
require_once( __DIR__ . '/includes/licenses.php'); // cc license functions
require_once( __DIR__ . '/includes/customizer.php'); // set up panels to mod appearance
require_once( __DIR__ . '/includes/login.php'); // for allowing wp-logins
require_once( __DIR__ . '/includes/forms.php'); // add form specifics
require_once( __DIR__ . '/includes/shortcodes.php'); // shortcodes
require_once( __DIR__ . '/includes/tools.php'); //  general spanners and utils
?>