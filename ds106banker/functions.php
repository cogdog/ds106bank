<?php
/* 
This are functions calls specific to the ds106
assignment bank for use in the WP-Boostrap Theme.

Developed by: Alan Levine @cogdog
URL: http://cogdog.info/

*/

// Exit if accessed directly outside of WP
if ( !defined('ABSPATH')) exit;


/*************** SET UP *****************/	

add_action( 'init', 'bank106_create_tax' );
add_action( 'init', 'bank106_make_post_types' );
add_action( 'init', 'bank106_load_theme_options' );


/* 
	Tell WordPress to run ds106bank_setup() when the 'after_setup_theme' hook is run.
	Note that this function is hooked into the after_setup_theme hook, which runs
	before the init hook. There was a good reason for this which now eludes me
*/
add_action( 'init', 'ds106bank_setup' );

function ds106bank_setup() { 
/* Sets up theme defaults used widely  */ 

	// dimensions for thumbnails
	define('THUMBW', get_option( 'thumbnail_size_w' ) );
	define('THUMBH', get_option( 'thumbnail_size_h' ) );
	
	// width for single page media
	define('MEDIAW', get_option( 'medium_size_w' ) );
	
	// loaded from theme options()
	define('THINGNAME', ds106bank_option('thingname') ); // the kind of things here, should be singular
	
	
	
} // function ds106bank_setup

// -----  add allowable url parameter
add_filter('query_vars', 'bank106_queryvars' );


function bank106_queryvars( $qvars ) {
	$qvars[] = 'srt'; // sort parameters for things
	$qvars[] = 'aid'; // thing id for add forms
	$qvars[] = 'typ'; // glag for adding example or tutorial
	
	return $qvars;
}   

// ----- run re-writes on theme switch
add_action( 'after_switch_theme', 'bank106_rewrite_flush' );

function bank106_rewrite_flush() {
    flush_rewrite_rules();  
}

// ----- set up author type queries for responses
add_action( 'pre_get_posts', 'bank106_author_responses' );
function bank106_author_responses( $query ) {

    if ( $query->is_author() && $query->is_main_query() ) {
        $query->set( 'post_type', 'responses' );
    }
    
    if ( $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', 'things' );
    }
}

// -----  Add admin menu link for Assignment Bank Options
add_action( 'wp_before_admin_bar_render', 'bank106_options_to_admin' );

function bank106_options_to_admin() {
    global $wp_admin_bar;
    
    // we can add a submenu item too
    $wp_admin_bar->add_menu( array(
        'parent' => '',
        'id' => 'bank-options',
        'title' => __('Assignment Bank Options'),
        'href' => admin_url( 'themes.php?page=ds106bank-options')
    ) );
}


// ----- Allow changes to excerpt length (and option entered in admin)
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function custom_excerpt_length( $length ) {
	return ds106bank_option('exlen');
}

// -----  customize the "more..." link
add_filter( 'excerpt_more', 'bank106_excerpt_more' );
function bank106_excerpt_more( $more ) {

	// get link
	if (get_post_meta( get_the_ID(), 'syndication_permalink') ) {
	  $the_real_permalink = get_post_meta( get_the_ID(), 'syndication_permalink', true );
	} else {
	  $the_real_permalink = get_permalink( get_the_ID() );
	} 

	return ' <a class="read-more" href="'. $the_real_permalink . '">read more &raquo;</a>';
}

/*************************** OPTIONS STUFF ************************************/	

function ds106bank_enqueue_options_scripts() {
	// Set up javascript for the theme options interface
	
	// media scripts needed for wordpress media uploaders
	wp_enqueue_media();
	
	// custom jquery for the options admin screen
	wp_register_script( 'bank106_options_js' , get_stylesheet_directory_uri() . '/js/jquery.options.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'bank106_options_js' );
}

function bank106_load_theme_options() {
	// load theme options Settings

	if ( file_exists( get_stylesheet_directory()  . '/class.ds106bank-theme-options.php' ) ) {
		include_once( get_stylesheet_directory()  . '/class.ds106bank-theme-options.php' );
	}
}

/*************************** CONTENT TYPES ***********************************/		

function bank106_make_post_types() {
	// create post type for things to do
	
	register_post_type(
		'things', 
		array(
				'labels' => array(
							'name' => __( 'Things to Do'),
							'singular_name' => __('Thing to Do'),
							'add_new' => 'Add New',
							'add_new_item' => 'Add New Thing to Do',
							'edit_item' => 'Edit Thing to Do',
							'new_item' => 'New Thing to Do',
							'all_items' => 'All Things to Do',
							'view_item' => 'View Thing to Do',
							'search_items' => 'Search Things to Do',
							'not_found' =>  'No things to do found',
							'not_found_in_trash' => 'No things to do found in Trash', 
						),
						'description' => __('The things in your bank'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'custom-fields',
									'revisions',
									'thumbnail',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite',
						'taxonomies' => array(
							'thingtypes',
							'thingtags',
							'tutorialtags',
							//'category',
							'post_tag',
						),
							
		)
	);
	
	// create post type for responses- what people to in response to things
	
	register_post_type(
		'responses', 
		array(
				'labels' => array(
						'name' => __( 'Responses Done'),
						'singular_name' => __('Response Done'),
						'add_new' => 'Add New',
						'add_new_item' => 'Add New Response Done',
						'edit_item' => 'Edit Response Done',
						'new_item' => 'New Response Done',
						'all_items' => 'All Responses Done',
						'view_item' => 'View Response Done',
						'search_items' => 'Search Responses Done',
						'not_found' =>  'No responses done to do found',
						'not_found_in_trash' => 'No responses done found in Trash', 

						),
						'description' => __('Participant responses to things'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'custom-fields',
									'revisions',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite' => true,
						'taxonomies' => array(
							'thingtags',
							'tutorialtags',
							'category',
							'post-tag',
						),							
		)
	);
}


// modify the listings to include custom columns
add_filter( 'manage_edit-responses_columns', 'bank106_set_custom_edit_responses_columns' );
add_action( 'manage_responses_posts_custom_column' , 'bank106_custom_responses_column', 10, 2 );
 

function bank106_set_custom_edit_responses_columns( $columns ) {
	// modify the admin listing for responses
    unset($columns['categories']); //remove categories
    
    // add column for the THINGNAMEs
    $columns['thing'] = __( THINGNAME, 'bonestheme' );
    return $columns;
}

function bank106_custom_responses_column( $column, $post_id ) {
	switch ( $column ) {
        case 'thing' :
        	// get the ID for the thing
        	$aid = get_thing_id_from_terms( $post_id );
        	
        	if ($aid) {
        		echo '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>';
        	} else {
        		echo '--';
        	}
        	 break;
    }
        
}

// ----- set unique tags on saving an thing 
add_action( 'save_post', 'set_thing_tag');

function set_thing_tag( $post_id ) {
	// on saving an thing make sure it is assigned  unique tags 
	// based on type of thing and post ID
	// code from http://codex.wordpress.org/Plugin_API/Action_Reference/save_post

	// skip if not an thing post type or it is a revision
	if  ( $_POST['post_type'] != 'things' or wp_is_post_revision( $post_id ) ) return;
	
    /* Request passes all checks; update the things's taxonomy */
	update_thing_tags( $post_id );
	
}
    
function update_thing_tags( $post_id ) {
    // helper function to update the bank assignmed tags 
    
    // get terms for type of thing
	$thingtype_terms = wp_get_object_terms($post_id, 'thingtypes');
		
	// make a tag for the type of thing, assign to both taxonomies
	// for thing responses and tutorials
	if ( count( $thingtype_terms ) ) {
		$thing_type = $thingtype_terms[0]->name . THINGNAME;
		wp_set_object_terms( $post_id, $thing_type , 'thingtags');
		wp_set_object_terms( $post_id, $thing_type , 'tutorialtags');
	}	
    
    // create unique tag names based on post ids
    $thing_tag = THINGNAME . $post_id; 
    $tutorial_tag =  'Tutorial' . $post_id;
     
    if ( term_exists(  $thing_tag, 'things') == 0) {
    	// check if term does not exist, then add to thing tags
    	wp_insert_term( $thing_tag, 'thingtags' );
    }
    
    if ( term_exists(  $tutorial_tag , 'things') == 0) {
    	// check if term does not exist, then add to tutorial tags
    	wp_insert_term( $tutorial_tag , 'tutorialtags' );
    }

    // now assign tags, append to other terms
    wp_set_object_terms( $post_id, $thing_tag, 'thingtags', true);
    wp_set_object_terms( $post_id, $tutorial_tag, 'tutorialtags', true );
}

/************************** CUSTOM TAXONOMIIES *******************************/

function bank106_create_tax() {

	// singular name
	$singularThing = 'Thing';
	
	// create taxonomy for thing types
	register_taxonomy(
		'thingtypes', // Taxonomy name
		array( 'things' ), // Post Types applied ro
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' Types'),
						'singular_name' => __( $singularThing .' Type'),
						'search_items'               => __( 'Search ' . $singularThing . ' Types' ),
						'all_items'                  => __( 'All ' . $singularThing . ' Types' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' Type' ),
						'update_item'                => __( 'Update ' . $singularThing . ' Type' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' Type' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' Type' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' types with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' types' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' types' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' types found.' ),
						),
			'rewrite' => array('slug' => 'type'),
			'query_var' => 'type',
			'show_ui' => true,
			'show_tagcloud' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
		)
	);
			
	// taxonomy for thing tags that uniquely identify them matched to responses
	register_taxonomy(
		'thingtags', // Taxonomy name
		array( 'things', 'responses' ), // Post Types
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' IDs'),
						'singular_name' => __( $singularThing . 'ID'),
						'search_items'               => __( 'Search ' . $singularThing . ' IDs' ),
						'all_items'                  => __( 'All ' . $singularThing . ' IDs' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' ID' ),
						'update_item'                => __( 'Update ' . $singularThing . ' ID' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' ID' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' ID' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' ids with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' ids' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' ids' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' ids found.' ),
						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
		)
	);
	
	// taxonomy for tutorial tags
	register_taxonomy(
		'tutorialtags', // Taxonomy name
		array( 'things', 'responses') , // Post Types
		array( 
			'labels' => array(
						'name' => __( 'Tutorial IDs'),
						'singular_name' => __('Tutorial ID'),
						'search_items'               => __( 'Search Tutorial IDs' ),
						'all_items'                  => __( 'All Tutorial IDs' ),
						'edit_item'                  => __( 'Edit Tutorial ID' ),
						'update_item'                => __( 'Update Tutorial ID' ),
						'add_new_item'               => __( 'Add New Tutorial ID' ),
						'new_item_name'              => __( 'New Tutorial ID' ),
						'separate_items_with_commas' => __( 'Separate tutorial ids with commas' ),
						'add_or_remove_items'        => __( 'Add or remove tutorial ids' ),
						'choose_from_most_used'      => __( 'Choose from the most used tutorial ids' ),
						'not_found'                  => __( 'No tutorial ids found.' ),

						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
		)
	);
}


function bank106_update_tax ( $oldthingname, $newthingname ) {
	// Updates the taxonomies if the name of the Things changes...

	// first process the thing tags
	$allterms = get_terms( 'thingtags', 'hide_empty=0');
			
	// check each term		
	foreach ($allterms as $term) {
	
		// try a string replacement to convert the old term to new
		$newtag = str_replace( $oldthingname, $newthingname, $term->name, $count);
				
		if ($count > 0 ) {
			// update the terms if we find 
			wp_update_term( $term->term_id, 'thingtags', array( 'name' => $newtag, 'slug' => sanitize_title( $newtag ) ) );			
		}
	}
	
	// next process the tutorial tags
	$allterms = get_terms( 'tutorialtags', 'hide_empty=0');
	
	// check each term	
	foreach ($allterms as $term) {
	
		// try a string replacement to convert the old term to new
		$newtag = str_replace( $oldthingname, $newthingname, $term->name, $count);
		
		if ($count > 0 ) {
			// update the terms if we foind 
			wp_update_term( $term->id, 'tutorialtags', array( 'name' => $newtag ) );
		}
		
	}
}

/************************** FOR THINGS  *********************************/	

function is_url_embeddable( $url ) {
// test if URL matches the ones that Wordpress can do oembed on
// test by by string matching
	
	$allowed_embeds = array(
					'youtube.com/watch?',
					'youtu.be',
					'flickr.com/photos',
					'vimeo.com', 
					'soundcloud.com',
	);
	
	// walk the array til we get a match
	foreach( $allowed_embeds as $fragment ) {
  		if  (strpos( $url, $fragment ) !== false ) {
			return ( true );
		}
	}	
	
	// no matches, no embeds for you
	return ( false );
}

function url_is_img ($url) {
// tests urls to see if they point to an image type

	$fileExtention 	= pathinfo ( $url, PATHINFO_EXTENSION ); 	// get file extension for url_is_img	
	$allowables 	= 	array( 'jpg', 'jpeg', 'png', 'gif' ); 	// allowable file extensions
	
	// check the url file extension to ones we will allow
	return ( in_array( strtolower( $fileExtention) ,  $allowables  ) );
}

function url_is_video ($url) {

	$allowed_videos = array(
					'youtube.com/watch?',
					'youtu.be',
					'vimeo.com'
	);


	// walk the array til we get a match
	foreach( $allowed_videos as $fragment ) {
  		if  (strpos( $url, $fragment ) !== false ) {
			return ( true );
		}
	}	
	
	// no matches, no videos for you
	return ( false );
}




function get_thing_icon ($pid, $imgsize, $is_menu=false) {
// Display the thumbnail for a thing; add a link to it if we are on 
// an archive page
	
	// get clean string for alt attribute
	$title_str =  trim(strip_tags( get_the_title( $pid ) ) );
	
	
	// Do we have a thumbnail defined?
		
	if ( '' != get_the_post_thumbnail($pid) ) {
		the_post_thumbnail( $imgsize, array ('class'=> "thing-pic",
	'alt'	=> $title_str) );
	} else {
		// Otherwise use the default image
		echo '<img src="' . ds106bank_option('def_thumb' ) . '" class="thing-pic" alt="' . esc_attr( $title_str) . '" />';
	} 
} 

function get_example_media ( $pid ) {
// output link to example, display media or embeded media if example is embeddable

	if ( get_post_meta( $pid, 'fwp_url', true ) ) {
		// url for example of thing
		
		$thingURL = get_post_meta( $pid, 'fwp_url', true );
		
		echo '<p class="example-url"><strong>Response for "' . get_the_title($pid) . '":</strong><br /><a href="' . $thingURL . '">' . $thingURL  . ' </a></p>';
			
		// try and get embed code for the linke (e.g. youtube, flickr, soundcloid)
		$embedcode = ( is_url_embeddable($thingURL) ) ? wp_oembed_get( $thingURL ) : false;
		
		// if example is an image, we will use that as a display media
		if ( url_is_img( $thingURL ) ) $imgcode .= '<img src="' . $thingURL . '" alt="" />';
		
		// if we are not embedding the image
		if (!$embedcode) {
			echo $imgcode;

		} else {
		
			if ( url_is_video ($thingURL) ) {
				echo '<div class="videoWrapper">' . $embedcode . '</div>';
			} else {
				echo $embedcode;
			}
		}

	}
		
} 



function update_thing_meta($id, $example_count, $tutorial_count) {
// update custom post meta to track the views and the number of responses done for each thing
// called on each view of an thing

	// get current value, if it does nto exist, then 0
	$visit_count = ( get_post_meta($id, 'thing_visits', true) ) ? get_post_meta($id, 'thing_visits', true) : 0; 
	$visit_count++;
	
	//update visit counts
	update_post_meta($id,  'thing_visits', $visit_count);
	
	// now update the number of responses
	update_post_meta($id,  'thing_responses', $example_count);
	
	// now update the number of tutorials
	update_post_meta($id,  'thing_tutorials', $tutorial_count);
}


/****************** FOR CREATIVE COMMONS LICENSING  **************************/	
function cc_license_html ($license, $author='', $yr='') {
	// outputs the proper license for a THINGNAME
	// $license is abbeviation. author is from post metadatae, Yr is from post date
	
	if ( !isset( $license ) or $license == '' ) return '';
	
	
	if ($license == 'copyright') {
		// boo copyrighted! sigh, slap on the copyright text
		return 'This work by ' . $author . ' is &copy;' . $yr . ' All Rights Reserved';
	} 
	
	// names of creative commons licenses
	$commons = array (
		'by' => 'Attribution',
		'by-sa' => 'Attribution-ShareAlike',
		'by-nd' => 'Attribution-NoDerivs',
		'by-nc' => 'Attribution-NonCommercial',
		'by-nc-sa' => 'Attribution-NonCommercial-ShareAlike',
		'by-nc-nd' => 'Attribution-NonCommercial-NoDerivs',
	);
	
	// do we have an author?
	$credit = ($author == '' OR  $author == 'Anonymous') ? '' : ' by ' . $author;
	
	return '<a rel="license" href="http://creativecommons.org/licenses/' . $license . '/4.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/' . $license . '/4.0/88x31.png" /></a><br />This work' . $credit . ' is licensed under a <a rel="license" href="http://creativecommons.org/licenses/' . $license . '/4.0/">Creative Commons ' . $commons[$license] . ' 4.0 International License</a>.';            
}


function cc_license_select_options ($curr) {
	// output for select form options for use in forms

	$str = '';
	
	// to restrict the list of options, comment out lines you do not want
	// to make available (HACK HACK HACK)
	$licenses = array (
		'by' => 'Creative Commons Attribution',
		'by-sa' => 'Creative Commons Attribution-ShareAlike',
		'by-nd' => 'Creative Commons Attribution-NoDerivs',
		'by-nc' => 'Creative Commons Attribution-NonCommercial',
		'by-nc-sa' => 'Creative Commons Attribution-NonCommercial-ShareAlike',
		'by-nc-nd' => 'Creative Commons Attribution-NonCommercial-NoDerivs',
		'copyright' => 'Copyrighted All Rights Reserved',
	);
	
	foreach ($licenses as $key => $value) {
		// build the striing of select options
		$selected = ( $key == $curr ) ? ' selected' : '';
		$str .= '<option value="' . $key . '"' . $selected  . '>' . $value . '</option>';
	}
	
	return ($str);
}
	

/************************ GENERAL USEFUL STUFF *******************************/	

function get_id_from_tag( $input ) {
	// gets a post id from a tag, in form of Assiognment114, Tutorial12
	//  e.g. use pattern matching to find numeric part of string
	// from http://stackoverflow.com/a/13538212/2418186
	
	$input = preg_replace('/[^0-9]/', '', $input);

	return $input == '' ? '1' : $input;
}

function get_thing_id_from_terms($postid) {
	// for a given example post ID, get the terms from either the thing tag or tutorial tag
	$all_the_terms = wp_get_object_terms( $postid, array('thingtags', 'tutorialtags') );

	if ( !empty( $all_the_terms ) ) {	  
		if ( !is_wp_error( $all_the_terms ) ) {

				foreach( $all_the_terms as $term ) {
					$tid =  get_id_from_tag( $term->name );
					if ( $tid ) return ( $tid );
				}		
				return (0);
		}
	}
	return (0);
}

function get_thing_meta( $pid, $metadname, $default=0 ) {
// return post metadata
	return( ( get_post_meta($pid, $metadname, true ) ) ? get_post_meta( $pid,  $metadname, true)  : $default );
}


function get_thing_types( $orderby='name', $order ='ASC' ) {
// get all thing types from terms in the taxonomy, basically all the types of THINGS
	$atypes = get_terms( 'thingtypes', 
		array(
			'orderby'	=>  $orderby,
			'order'		=>  $order,
			'hide_empty'=> 0,
			)
	);

	return( $atypes );
}


if ( false === function_exists( 'lcfirst' ) ) {
/*
 * Make a string's first character lowercase need for older PHP versions w/o function
 * hat tip Matt Croslin 
*/
 
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}



/*************************** PLUGIN DETECTORS ********************************/	

function bank106_wp_ratings_installed() {
	// return status for WP-POSTRATINGS
	if ( function_exists('the_ratings' ) ) {
		return ('WP-PostRatings <strong>is installed</strong> and will be applied to all ' . lcfirst(THINGNAME) . 's. Check the documentation tab for details on options. Edit <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-options.php') .'">ratings options</a> or <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-templates.php') .'">ratings display templates</a>.'); 
	} else {
		return ('WP-PostRatings <strong>is not installed</strong>. To enable difficulty ranking of ' . lcfirst(THINGNAME) . 's install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a> via the Add New Plugin interface. Check the documentation tab for details on options andssettings for display of the ratings.'); 
	}
}

function bank106_fwp_installed() {
	// Status check for FeedWordPress
	if ( function_exists('is_syndicated' ) ) {
		return ('Feed Wordpress <strong>is installed</strong>.'); 
	} else {
		return ('Feed Wordpress <strong>is NOT installed</strong>. To enable syndication of responses to this site, install the <a href="http://wordpress.org/plugins/feedwordpress/" target="_blank">Feed Wordpress plugin</a> via the Add New Plugin interface. Check the documentation tab for proper Feed Wordpress settings.'); 
	}
}


/***************************** FORM STUFF ************************************/	

function ds106bank_enqueue_add_scripts() {

	// Build in tag auto complete script
    wp_enqueue_script( 'suggest' );

	// custom jquery for the add thing form
	wp_register_script( 'bank106_add_thing_js' , get_stylesheet_directory_uri() . '/js/jquery.add-thing.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'bank106_add_thing_js' );
	
}

function bank106_add_new_types( $new_types ) {
	// convert text area input into array, based on new line breaks (remove CR)
	// and add each items as a new taxonomy type
	
	$new_types = explode( "\n", str_replace( "\r", "", $new_types ) );
	
	foreach ( $new_types as $item) {
		if ( $item != '' AND term_exists(  $item, 'thingtypes') == 0) {
		
    		// check if term does not exist (or is blank), then add to thing type teaxonomy
    		wp_insert_term( $item, 'thingtypes' );
    	}
    }
}


function bank106_insert_attachment( $file_handler, $post_id) {
	// used for uploading images from  the add thing submission forms
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	$attach_id = media_handle_upload( $file_handler, $post_id );
	
	return ($attach_id);
	
}




/****************************** SHORT CODES **********************************/	

// ----- short code for number of things in the bank
add_shortcode('thingcount', 'getThingCount');

function getThingCount() {
	return wp_count_posts('things')->publish  . ' ' . THINGNAME . 's';
}

// ----- short code for number of responses in the bank
add_shortcode('examplecount', 'getResponseCount');

function getResponseCount() {
	return wp_count_posts('responses')->publish . ' responses';
}

// ----- short code to create a list of Feedwordpress subscribed feeds
add_shortcode("feedroll", "bank106_feedroll");  

function bank106_feedroll( $atts ) {  
	global $wpdb;
	
	// get the value of  attributes to shortcode, name of tag
	// set default to roken for testing
 	extract( shortcode_atts( array( "tag" => '*!*' ), $atts, 'feedroll' ) );  
 	
 	// create mySQL query condition to find tags if provided
 	$tag_cond = ( $tag ==  '*!*' ) ? '' :  " AND wpl.link_notes LIKE '%%$tag%%'";
 	
 	// custom mySQL query to get subscribed blogs from the links table
	$feedblogs = $wpdb->get_results( 
		"
		SELECT DISTINCT      
					wpl.link_name, wpl.link_url
		FROM        $wpdb->links wpl,  $wpdb->postmeta wpm
		WHERE       wpm.meta_key='syndication_feed_id' AND 
					wpm.meta_value = wpl.link_id $tag_cond
		ORDER BY    wpl.link_name ASC
		"
	);

	// bail if we got nothing
	if (count($feedblogs) == 0 ) {
		
		$content = ( $tag ==  '*!*' ) ? 'No feeds available or no posts syndicated yet from feeds' :  "No feeds found for $tag (or no posts syndicated yet from feeds)";
		
	// we got feeds!
	} else {
	
		//start the output
		$content = "<ol class=\"feedroll\">\n";
		
 
		// output each item as a list item, title of blog linked to URL 
		foreach ( $feedblogs as $item ) {
 			$content  .=  '<li><a href="' . htmlspecialchars($item->link_url)   . '">' . htmlspecialchars_decode($item->link_name)  . '</a></li>' . "\n";              
		}
		
		// clean up after your lists
		$content .= '</ol>';
	}		
	
	// here comes the short code output  
    return $content;  
}

?>