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


add_action( 'init', 'create_assignmentbank_tax' );
add_action( 'init', 'post_type_assignments' );
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
	
	// look for existence of pages with the appropriate template, if not found
	// make 'em
	if (! page_with_template_exists( 'page-add-assignment.php' ) ) {
  
		// create the add a thing page if it does not exist
		// backdate creation date 2 days just to make sure they do not end up future dated
		
		$page_data = array(
			'post_title' 	=> 'Add a New ' . THINGNAME,
			'post_content'	=> 'Use this form to add a new ' . THINGNAME,
			'post_name'		=> 'add-' . strtolower(THINGNAME),
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-add-assignment.php',
		);
	
		wp_insert_post( $page_data );
	}
	
	if (! page_with_template_exists( 'page-add-example.php' ) ) {
  
		// create the add example/tutorial page if it does not exist
		$page_data = array(
			'post_title' 	=> 'Add a New Example',
			'post_content'	=> 'Insert instructions here for adding an exmaple or tutorial to the site',
			'post_name'		=> 'add-example',
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-add-example.php',
		);
  	
  		wp_insert_post( $page_data );
  	}
	
	if (! page_with_template_exists( 'page-assignment-menu.php' ) ) {
  
		// create the Write page if it does not exist
		$page_data = array(
			'post_title' 	=>  THINGNAME . ' Bank',
			'post_content'	=> 'Insert welcome info here.',
			'post_name'		=> 'assignment-menu',
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-assignment-menu.php',
		);
	
		wp_insert_post( $page_data );
	}
	
	if ( ds106bank_option('use_wp_login') ) {
		add_filter( 'loginout', 'ds106bank_login_menu_customize' );
		add_filter( 'wp_nav_menu_items', 'ds106bank_login_logout_link', 10, 2);
	}
	
} // function ds106bank_setup

// -----  add allowable url parameter
add_filter('query_vars', 'bank106_queryvars' );


function bank106_queryvars( $qvars ) {
	$qvars[] = 'srt'; // sort parameters for things
	$qvars[] = 'aid'; // assignment id for add forms
	$qvars[] = 'typ'; // flag for adding example or tutorial
	
	return $qvars;
}   

// ----- run re-writes on theme switch
add_action( 'after_switch_theme', 'bank106_rewrite_flush' );

function bank106_rewrite_flush() {
    flush_rewrite_rules();  
}

// ----- set up author type queries for exaples
add_action( 'pre_get_posts', 'bank106_author_examples' );

function bank106_author_examples( $query ) {

    if ( $query->is_author() && $query->is_main_query() ) {
        $query->set( 'post_type', 'examples' );
    }
    
    if ( $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', 'assignments' );
    }
    
     if ( $query->is_category('featured') && $query->is_main_query() ) {
        $query->set( 'post_type', 'examples' );
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

	return ' <a class="read-more" href="'. $the_real_permalink . '">more... &raquo;</a>';
}

function bank106_tinymce_buttons($buttons)
 {
	//Remove the more button
	$remove = 'wp_more';

	//Find the array key and then unset
	if ( ( $key = array_search($remove,$buttons) ) !== false )
		unset($buttons[$key]);
	$buttons[] = 'image';
	return $buttons;
 }
add_filter('mce_buttons','bank106_tinymce_buttons');



function bank106_tinymce_2_buttons($buttons)
 {
	//Remove the keybord shortcut and paste text buttons
	$remove = array('wp_help','pastetext');

	return array_diff($buttons,$remove);
 }
add_filter('mce_buttons_2','bank106_tinymce_2_buttons');

// ----- enable custom headers, a wee one across the top


function ds106bank_custom_header_setup() {
    $args = array(
        'width'              => 970,
        'height'             => 60,
    );
    
    add_theme_support( 'custom-header', $args );
}

add_action( 'after_setup_theme', 'ds106bank_custom_header_setup' );




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

function post_type_assignments() {
	// create post type for assignments- things to do
	
	register_post_type(
		'assignments', 
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
						'description' => __('Tasks, assignments, lessons in the bank'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'author',
									'custom-fields',
									'revisions',
									'thumbnail',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite',
						'taxonomies' => array(
							'assignmenttypes',
							'assignmenttags',
							'tutorialtags',
							'post_tag',
						),
							
		)
	);
	
	// create post type for examples- what people to in response to assignments
	
	register_post_type(
		'examples', 
		array(
				'labels' => array(
						'name' => __( 'Responses'),
						'singular_name' => __('Response'),
						'add_new' => 'Add New',
						'add_new_item' => 'Add New Response ',
						'edit_item' => 'Edit Response',
						'new_item' => 'New Response',
						'all_items' => 'All Responses',
						'view_item' => 'View Response',
						'search_items' => 'Search Responses',
						'not_found' =>  'No responses found',
						'not_found_in_trash' => 'No responses found in Trash', 

						),
						'description' => __('Participant responses and/or tutorials to things'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'author',
									'custom-fields',
									'revisions',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite' => true,
						'taxonomies' => array(
							'assignmenttags',
							'tutorialtags',
							'exampletags',
							'category'
						),							
		)
	);
}


// modify the listings to include custom columns
add_filter( 'manage_edit-examples_columns', 'bank106_set_custom_edit_examples_columns' );
add_action( 'manage_examples_posts_custom_column' , 'bank106_custom_examples_column', 10, 2 );
 

function bank106_set_custom_edit_examples_columns( $columns ) {
	// modify the admin listing for examples
    unset($columns['categories']); //remove categories
    
    // add column for the THINGNAMEs
    $columns['thing'] = __( THINGNAME, 'bonestheme' );
    return $columns;
}

function bank106_custom_examples_column( $column, $post_id ) {
	switch ( $column ) {
        case 'thing' :
        	// get the ID for the assignment
        	$aid = get_assignment_id_from_terms( $post_id );
        	
        	if ($aid) {
        		echo '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>';
        	} else {
        		echo '--';
        	}
        	 break;
    }
        
}

// ----- set unique tags on saving an assignment 
add_action( 'save_post', 'set_assignment_tag');

function set_assignment_tag( $post_id ) {
	// on saving an assignment make sure it is assigned  unique tags 
	// based on type of assignment and post ID
	// code from http://codex.wordpress.org/Plugin_API/Action_Reference/save_post

	// skip if not an assignment post type or it is a revision
	if  ( $_POST['post_type'] != 'assignments' or wp_is_post_revision( $post_id ) ) return;
	
    /* Request passes all checks; update the things's taxonomy */
	update_assignment_tags( $post_id );
	
}
    
function update_assignment_tags( $post_id ) {
    // helper function to update the bank assignmed tags 
    
    // get terms for type of assignment
	$assignmenttype_terms = wp_get_object_terms($post_id, 'assignmenttypes');
		
	// make a tag for the type of assignment, assign to both taxonomies
	// for assignment examples and tutorials
	if ( (! isset( $assignmenttype_terms->errors ) )
		 && count( $assignmenttype_terms ) ) {
		$assignment_type = $assignmenttype_terms[0]->name . THINGNAME;
		wp_set_object_terms( $post_id, $assignment_type , 'assignmenttags');
		wp_set_object_terms( $post_id, $assignment_type , 'tutorialtags');
	}	
    
    // create unique tag names based on post ids
    $assignment_tag = THINGNAME . $post_id; 
    $tutorial_tag =  'Tutorial' . $post_id;
     
    if ( term_exists(  $assignment_tag, 'assignments') == 0) {
    	// check if term does not exist, then add to assignment tags
    	wp_insert_term( $assignment_tag, 'assignmenttags' );
    }
    
    if ( term_exists(  $tutorial_tag , 'assignments') == 0) {
    	// check if term does not exist, then add to tutorial tags
    	wp_insert_term( $tutorial_tag , 'tutorialtags' );
    }

    // now assign tags, append to other terms
    wp_set_object_terms( $post_id, $assignment_tag, 'assignmenttags', true);
    wp_set_object_terms( $post_id, $tutorial_tag, 'tutorialtags', true );
}

/************************** CUSTOM TAXONOMIIES *******************************/

function create_assignmentbank_tax() {

	// singular name
	$singularThing = 'Thing';
	
	// create taxonomy for assignment types
	register_taxonomy(
		'assignmenttypes', // Taxonomy name
		array( 'assignments' ), // Post Types applied to
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


	// create taxonomy for assignment categories
	register_taxonomy(
		'assignmentcats', // Taxonomy name
		array( 'assignments' ), // Post Types applied to
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' Categories'),
						'singular_name' => __( $singularThing .' Category'),
						'search_items'               => __( 'Search ' . $singularThing . ' Categories' ),
						'all_items'                  => __( 'All ' . $singularThing . ' Categories' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' Category' ),
						'update_item'                => __( 'Update ' . $singularThing . ' Category' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' Category' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' Category' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' categories with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' categories' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' categories' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' categories found.' ),
						),
			'rewrite' => array('slug' => 'cats'),
			'query_var' => 'cats',
			'show_ui' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
		)
	);
			
	// taxonomy for assignment tags that uniquely identify them matched to examples
	register_taxonomy(
		'assignmenttags', // Taxonomy name
		array( 'assignments', 'examples' ), // Post Types
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
		array( 'assignments', 'examples') , // Post Types
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
	

	// taxonomy for tutorial tags
	register_taxonomy(
		'exampletags', // Taxonomy name
		array( 'examples') , // Post Types
		array( 
			'labels' => array(
						'name' => __( 'Example Tags'),
						'singular_name' => __('Example Tags'),
						'search_items'               => __( 'Search Example Tags' ),
						'all_items'                  => __( 'All Example Tags' ),
						'edit_item'                  => __( 'Edit Example Tags' ),
						'update_item'                => __( 'Update Example Tags' ),
						'add_new_item'               => __( 'Add New Example Tags' ),
						'new_item_name'              => __( 'New Example Tags' ),
						'separate_items_with_commas' => __( 'Separate example tags with commas' ),
						'add_or_remove_items'        => __( 'Add or remove example tags' ),
						'choose_from_most_used'      => __( 'Choose from the most used example tags' ),
						'not_found'                  => __( 'No example tags found.' ),

						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
		)
	);

}


function bank106_update_tax ( $oldthingname, $newthingname ) {
	// Updates the taxonomies if the name of the Things changes...

	// first process the assignment tags
	$allterms = get_terms( 'assignmenttags', 'hide_empty=0');
			
	// check each term		
	foreach ($allterms as $term) {
	
		// try a string replacement to convert the old term to new
		$newtag = str_replace( $oldthingname, $newthingname, $term->name, $count);
				
		if ($count > 0 ) {
			// update the terms if we find 
			wp_update_term( $term->term_id, 'assignmenttags', array( 'name' => $newtag, 'slug' => sanitize_title( $newtag ) ) );			
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


function get_examples_type_by_tax ( $post_id ) {
	// identifies is an examples post type is a response (internallly called "example") or a tutorial
	// by looking for taxonomis present. Needed because of the mixing of both in one content type. #hindsight
	
	// look first for assignment tags
	$myterms = wp_get_post_terms( $post_id, 'assignmenttags', array('fields' => 'ids') );
	if ( count($myterms) ) return ('Response');
	
	// now look for tutorials
	$myterms = wp_get_post_terms( $post_id, 'exampletags', array('fields' => 'ids') );
	if ( count($myterms) ) return (ds106bank_option('helpthingname'));
	
	// got nothing
	return ('');

}

/************************** FOR ASSIGNMENTS  *********************************/	

function is_url_embeddable( $url ) {
// test if URL matches the ones that Wordpress can do oembed on
// test by by string matching
	
	$allowed_embeds = array(
					'outube.com/watch?',
					'outu.be',
					'lickr.com/photos',
					'flic.kr',
					'imeo.com', 
					'oundcloud.com',
					'nstagram.com',
					'witter.com',
					'ine.co',
					'mgur.com',
					'nimoto.com'
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

function url_is_video ($url) {
// tests if URl is for a potentially media site so we can wrap with
// boostrap resppnsive tags
	$allowed_videos = array(
					'youtube.com/watch?',
					'youtu.be',
					'vimeo.com',
					'soundcloud.com',
					'vine.co',
					'instagram.com',
					'animoto.com'
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




function get_thing_icon ($pid, $imgsize, $imgclass = "thing-pic") {
// Display the thumbnail for a thing; assume by default it's for a single assignment (class= 'thing-pic')
// For an archive view ($imgclass = 'thing-archive') we will use embedded media as icon (if option set)
	
	
	if ( $imgclass == "thing-archive" AND ds106bank_option('media_icon')) {
		// try to use example media as icon if its embeddable and option set to look for embeddable icon
			
		// do we have an example URL as meta data
		if ( get_post_meta( $pid, 'fwp_url' , true ) ) {
			$check_for_embed =  get_media_embedded (  get_post_meta( $pid, 'fwp_url' , true) );
			
			// if we got some embed code, return it
			if ( $check_for_embed != '') return ( $check_for_embed );
		}
	}
	
	// Let's just to the regular thumbnail route...
	// Do we have a thumbnail defined?

	// get clean string for alt attribute
	$title_str =  trim(strip_tags( get_the_title( $pid ) ) );

	if ( '' != get_the_post_thumbnail($pid) ) {
		the_post_thumbnail( $imgsize, array ('class'=> $imgclass,
	'alt'	=> $title_str) );
	} else {
		// Otherwise use the default image
		return '<img src="' . ds106bank_option('def_thumb' ) . '" $imgclass alt="' . esc_attr( $title_str) . '" />';
	} 
} 


function get_example_media ( $pid, $metafieldname='fwp_url' ) {
// output link to example, display media or embeded media if example is embeddable

	$str = ''; // hold output
	
	
	if ( get_post_meta( $pid, $metafieldname , true ) ) {
		// url for example of assignment
				
		$assignmentURL = get_post_meta( $pid, $metafieldname, true );
		
		// case to handle an example with no URL, return empty string
		// Just check the first character because people seem to think this is a hash tag!
		if ($assignmentURL[0] == "#") return ('');
		
		
		if ( url_is_type( $assignmentURL, array( 'mp3' ) ) ) {
			// option for href to make as a download
			$download_option = ' download';
		}

		// make header
		$str .= '<p class="example-url"><strong>Example for "' . get_the_title($pid) . '":</strong><br /><a href="' . $assignmentURL . '"' . $download_option . '>' . $assignmentURL  . ' </a></p>';
		
		// add on the embedded media
		$str .= get_media_embedded ( $assignmentURL );	
		
	}
	
	return( $str );		
} 


function get_media_embedded ( $url ) {
// get the media embeds for a given URL
	
	if ($url == '' or ( strpos( $url, 'commons.wikimedia.org' ) !== false ) ) return ('');

	$str = ''; // hold output
	$display_code = ''; // hold display code
			
	if ( url_is_type( $url, array( 'mp3' ) ) ) {
		// see if the URL points to an embeddable audio type, display code set for mp3
		$display_code = '<audio controls="controls" class="audio-player"><source src="' . $url . '" /> </audio>';
	}
		
	// try and get embed code for the link (e.g. youtube, flickr, soundcloud)
	$embedcode = ( is_url_embeddable($url) ) ? wp_oembed_get( $url ) : false;
	
	// if example is an image, we will use that as a display code
	if ( url_is_type( $url ) ) $display_code = '<img src="' . $url . '" alt="" />';
	
	if (!$embedcode) {
		// if we are not showing an embeddable media type, display either an image or an mp3 player	
		$str .=  $display_code;

	} else {
		// do some embedding, wrap if needed
		if ( url_is_video ( $url ) ) {
			// wrap the video so it is responsive
			$str .=  '<div class="videoWrapper">' . $embedcode . '</div>';	
		} else {
			// just output the embed code
			$str .=  $embedcode;
		}
	}
	return( $str );		
} 


function update_assignment_meta($id, $example_count, $tutorial_count) {
// update custom post meta to track the views and the number of examples done for each assignment
// called on each view of an assignment

	// get current value, if it does nto exist, then 0
	$visit_count = ( get_post_meta( $id, 'assignment_visits', true ) ) ? get_post_meta( $id, 'assignment_visits', true ) : 0; 
	$visit_count++;
	
	//update visit counts
	update_post_meta( $id,  'assignment_visits', $visit_count );
	
	// now update the number of examples
	update_post_meta( $id,  'assignment_examples', $example_count );
	
	// now update the number of tutorials
	update_post_meta( $id,  'assignment_tutorials', $tutorial_count );
}

function update_example_meta( $id ) {
// update custom post meta to track the views called on each view of an example

	// get current value, if it does not exist, then initialize it to  0
	$visit_count = ( get_post_meta($id, 'examples_visits', true) ) ? get_post_meta($id, 'examples_visits', true) : 0; 
	$visit_count++;
	
	//update visit counts
	update_post_meta($id,  'examples_visits', $visit_count);
}


function get_assignment_meta_string( $id ) {
// get thing meta data counts for views, examples, tutorials for use in archive views

	// you gotta start somewhere
	$str = ' &bull; <strong>' . get_assignment_meta( $id, 'assignment_visits') . '</strong> views ';
	
	
	if ( ds106bank_option('show_ex' )  == 'both') {
		// display examples and tutorials counts
		$str .=  ' &bull; <strong>' . get_assignment_meta( $id, 'assignment_examples') . '</strong> responses &bull;  <strong>' .  get_assignment_meta( $id, 'assignment_tutorials') . '</strong> ' .  lcfirst( ds106bank_option('helpthingname') ) . 's';
		
	} elseif ( ds106bank_option('show_ex' )  == 'ex' ) {
		// display example counts only
		$str .=  ' &bull; <strong>' . get_assignment_meta( $id, 'assignment_examples') . '</strong> responses';
		
	} elseif ( ds106bank_option('show_ex' )  == 'tut') {
		// display tutorial counts only
		$str .=  '  &bull;  <strong>' .  get_assignment_meta( $id, 'assignment_tutorials') . '</strong> ' .  lcfirst( ds106bank_option('helpthingname') ) . 's';
		
	}
	
	return ($str);
}


# -----------------------------------------------------------------
# Plugin Detectors
# -----------------------------------------------------------------

function ds106bank_alm_installed() {
	// return status for Ajax Load More Plugin
	if ( function_exists('alm_install' ) ) {
		return ('The Ajax Load More plugin <strong>is installed</strong> and will be used to sequentially load responses (with the value entered) if there are many of them. Check documentation tab for details on setting up the custom template in the plugin.'); 
		
	} else {
		return ('Ajax Load More plugin <strong>is not installed</strong>. This means all example responses will be loaded on a single ' . THINGNAME . ' and the number entered is ignored. If you start getting many responses, you may want to install this plugin. '); 
	}
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
	
	return '<a rel="license" href="https://creativecommons.org/licenses/' . $license . '/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/' . $license . '/4.0/88x31.png" /></a><br />This work' . $credit . ' is licensed under a <a rel="license" href="https://creativecommons.org/licenses/' . $license . '/4.0/">Creative Commons ' . $commons[$license] . ' 4.0 International License</a>.';            
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

/************************************* LOGIN STUFF **************************************/	

function ds106bank_get_author_link() {
	
	
	$current_user = wp_get_current_user();
	$user_id = get_current_user_id();
	
	return '<a href="' . site_url() . '/author/' . $current_user->user_login  . '" class="btn btn-default">' . $current_user->display_name . '</a>';

}


// change the text of the login / logout link
// h/t https://core.trac.wordpress.org/ticket/34356#comment:4

function ds106bank_login_menu_customize( $link ) {

        if ( ! is_user_logged_in() ) {
        	
        	// return to current page when logged in
			return sprintf( '<a href="%s" class="btn btn-primary">%s</a>', wp_login_url( get_permalink() ), __( 'Sign In' ) );
        } else {
        
        	// send to home page on logout
			return sprintf( '<a href="%s" class="btn btn-primary">%s</a>', wp_logout_url( home_url() ), __( 'Sign Out' ) );
        }

        return $link;
}

// add a login / logout option to the menu, will work only if a menu is created in 
// Appaearances -> menus (who does not want menus?)
// h/t http://vanweerd.com/enhancing-your-wordpress-3-menus/#add_login

function ds106bank_login_logout_link( $items, $args ) {
        ob_start();
        wp_loginout();
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
        
        if (  is_user_logged_in() ) {
        	 $items .= '<li>'. ds106bank_get_author_link() .'</li>';
        }
        
    	return $items;
}
	

/************************ GENERAL USEFUL STUFF *******************************/	

function get_id_from_tag( $input ) {
	// gets a post id from a tag, in form of Assignment114, Tutorial12
	//  e.g. use pattern matching to find numeric part of string
	// from http://stackoverflow.com/a/13538212/2418186
	
	$input = preg_replace('/[^0-9]/', '', $input);

	return $input == '' ? '1' : $input;
}

function get_assignment_id_from_terms( $postid ) {
	// for a given example post ID, get the terms from either the assignment tag or tutorial tag
	$all_the_terms = wp_get_object_terms( $postid, array( 'assignmenttags' ,  'tutorialtags' ) );

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

function get_assignment_meta( $pid, $metadname, $default=0 ) {
// return post metadata
	return( ( get_post_meta($pid, $metadname, true ) ) ? get_post_meta( $pid,  $metadname, true)  : $default );
}


function get_assignment_types( $orderby='name', $order ='ASC' ) {
// get all assignment types from terms in the taxonomy, basically all the types of THINGS
	$atypes = get_terms( 'assignmenttypes', 
		array(
			'orderby'	=>  $orderby,
			'order'		=>  $order,
			'hide_empty'=> 0,
			)
	);

	return( $atypes );
}

function get_the_article ( $str ) {
	// Make grammar happy, returns "an" if #str starts with vowel
	// h/t for strcspn tip http://stackoverflow.com/a/16579680/2418186

	$my_article = ( strcspn( strtolower( $str ), "aeiou") ) ? "a " : "an ";
	return ($my_article);

}

function page_with_template_exists ($template) {
	// returns true if at least one Page exists that uses given template

	// look for pages that use the give template
	$seekpages = get_posts (array (
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => $template
			));
	 
	// did we find any?
	$pages_found = ( count ($seekpages) ) ? true : false ;
	
	// report to base
	return ($pages_found);
}

function url_is_type ($url, $allowables = array( 'jpg', 'jpeg', 'png', 'gif' ) ) {
// generalized function than original url_is_img to check file name extension on url
// against an expected array of file names (default is for images), could be called to check for
// mp3 by calling  url_is_type($url, array('mp3') )

	// get file extension for url
	$fileExtention 	= pathinfo ( $url, PATHINFO_EXTENSION ); 	
	
	// check the url file extension to ones we will allow
	return ( in_array( strtolower( $fileExtention ),  $allowables  ) );
}

function bank106_twitter_button (  $postid, $mytype  ) {
// display a tweet this button for a challenge or locally published responses

	// doth there be a twitter acct to credit?
	$mention = get_post_meta( $postid, 'submitter_twitter', 1 );	
	
	// make it proper (or not visible)
	$mention_str = 	( $mention ) ? ' by ' .  $mention : '';
	
	$urltotweet = get_site_url() . '/?p=' . $postid;

	$tweet_text = get_the_title(  $postid )  . '- ' . get_the_article ( $mytype ) . lcfirst( $mytype ) . $mention_str  ;					
	
	echo '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $urltotweet . '" data-text="' . $tweet_text . '" data-hashtags="' . ds106bank_option( 'hashtag' ) . '" data-dnt="true">Tweet</a>' . "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";

}

function bank106_twitter_credit_link ( $post_id, $prefix='', $suffix='', $path='tag' ) {
	// if a given item has a twitter credit, return an HTML credit link string
	// path = 'tag' for challenges, and  'exampletags' for examples
	
	// look for a tweeter 
	$tweeter = get_post_meta( $post_id, 'submitter_twitter', true );
	
	if ( ds106bank_option( 'use_twitter_name' ) and $tweeter ) {
		// we will try if we are even requiring twitter names and if one exixts
		return $prefix . '<a href="' . site_url() . '/' . $path . '/' . $tweeter . '">' . $tweeter . '</a>' . $suffix;

	} else {
		// otherwise, send nothing
		return ('');
	}
}

function bank106_get_page_id_by_slug( $page_slug ) {
	// pass the slug and get it's id, so we can use most basic permalink structure
	// ----- h/t https://gist.github.com/davidpaulsson/9224518
	
	// get page as object
	$page = get_page_by_path( $page_slug );
	
	if ( $page ) {
		return $page->ID;
	} else {
		return null;
	}
}


/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
   h/t http://wordpress.stackexchange.com/a/99516/14945
 */
function bank106_sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        bank106_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
    }
}


function make_links_clickable( $text ) {
//----	h/t http://stackoverflow.com/a/5341330/2418186
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}


/*************************** PLUGIN DETECTORS ********************************/	

function bank106_wp_ratings_installed() {
	// return status for WP-POSTRATINGS
	if ( function_exists('the_ratings' ) ) {
		return ('WP-PostRatings <strong>is installed</strong> and will be applied to each ' . lcfirst(THINGNAME) . ' so visitors can crowdsource it\'s rating or popularity . Check documentation tab for details. To specify the scale and prompt see <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-options.php') .'">ratings options</a> or <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-templates.php') .'">ratings display templates</a>.'); 
	} else {
		return ('WP-PostRatings <strong>is not installed</strong>. To enable public ratings of ' . lcfirst(THINGNAME) . 's install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a> via the Add New Plugin interface. Check the documentation tab for details on options for display of the ratings.'); 
	}
}

function bank106_fwp_installed() {
	// Status check for FeedWordPress
	if ( function_exists('is_syndicated' ) ) {
		return ('Feed Wordpress <strong>is installed</strong>.'); 
	} else {
		return ('Feed Wordpress <strong>is NOT installed</strong>. To enable syndication of examples to this site, install the <a href="http://wordpress.org/plugins/feedwordpress/" target="_blank">Feed Wordpress plugin</a> via the Add New Plugin interface. Check the documentation tab for proper Feed Wordpress settings.'); 
	}
}

function cleanTags( $str ) {
	// replace multiple white spaces in tags to single blanks
	$cleansed = preg_replace('!\s+!', ' ', $str);
	// now convert blanks to commas
	$cleansed = str_replace ( ' ', ',' , $cleansed );
	
	$cleansed = preg_replace('!,+!', ',', $cleansed);

	// return the cleaned string
	return ($cleansed);

}


/***************************** FORM STUFF ************************************/	

function ds106bank_enqueue_add_thing_scripts() {

	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );

    // custom jquery for the add thing/assignment form
	wp_register_script( 'bank106_add_thing_js' , get_stylesheet_directory_uri() . '/js/jquery.add-thing.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'bank106_add_thing_js' );

	// add scripts for fancybox (used for previews of submitted things) 
	//-- h/t http://code.tutsplus.com/tutorials/add-a-responsive-lightbox-to-your-wordpress-theme--wp-28100
	wp_register_script( 'fancybox', get_stylesheet_directory_uri() . '/includes/lightbox/js/jquery.fancybox.pack.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'fancybox' );

	// Lightbox formatting for preview screated with rich text editor
	wp_register_script( 'lightbox_thing', get_stylesheet_directory_uri() . '/includes/lightbox/js/lightbox_thing.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_thing' );
	
	// fancybox styles
	wp_register_style( 'lightbox-style', get_stylesheet_directory_uri() . '/includes/lightbox/css/jquery.fancybox.css' );
	wp_enqueue_style( 'lightbox-style' );
	
	// Bootstrap filestyle for nice uploads of files
	wp_register_script( 'filestyle', get_stylesheet_directory_uri() . '/js/bootstrap-filestyle.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'filestyle' );

	
	// used to display formatted dates
	wp_register_script( 'moment' , get_stylesheet_directory_uri() . '/js/moment.js', null, '1.0', TRUE );
	wp_enqueue_script( 'moment' );

}



function ds106bank_enqueue_add_ex_scripts() {

	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );

    // custom jquery for the example form
	wp_register_script( 'bank106_add_example_js' , get_stylesheet_directory_uri() . '/js/jquery.add-example.js', array( 'jquery' ), '1.1', TRUE );
	wp_enqueue_script( 'bank106_add_example_js' );

	// add scripts for fancybox (used for previews of submitted examples) 
	//-- h/t http://code.tutsplus.com/tutorials/add-a-responsive-lightbox-to-your-wordpress-theme--wp-28100
	wp_register_script( 'fancybox', get_stylesheet_directory_uri() . '/includes/lightbox/js/jquery.fancybox.pack.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'fancybox' );
	
	// fancybox styles
	wp_register_style( 'lightbox-style', get_stylesheet_directory_uri() . '/includes/lightbox/css/jquery.fancybox.css' );
	wp_enqueue_style( 'lightbox-style' );
	
}


function ds106bank_enqueue_richtext_scripts() {

	// Lightbox formatting for previews of examples created with rich text editor
	wp_register_script( 'lightbox_richtext', get_stylesheet_directory_uri() . '/includes/lightbox/js/lightbox_richtext.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_richtext' );

    // used to displaye formatted dates
	wp_register_script( 'moment' , get_stylesheet_directory_uri() . '/js/moment.js', null, '1.0', TRUE );
	wp_enqueue_script( 'moment' );

}


function ds106bank_enqueue_simpletext_scripts() {

	//  Lightbox formatting for previews of responses/tutorials linked externally (simple text editor)
	wp_register_script( 'lightbox_simpletext', get_stylesheet_directory_uri() . '/includes/lightbox/js/lightbox_simpletext.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_simpletext' );

}



function bank106_add_new_types( $new_types ) {
	// convert text area input into array, based on new line breaks (remove CR)
	// and add each items as a new taxonomy type
	
	$new_types = explode( "\n", str_replace( "\r", "", $new_types ) );
	
	foreach ( $new_types as $item) {
		if ( $item != '' AND term_exists(  $item, 'assignmenttypes') == 0) {
		
    		// check if term does not exist (or is blank), then add to assignment type teaxonomy
    		wp_insert_term( $item, 'assignmenttypes' );
    	}
    }
}


function bank106_insert_attachment( $file_handler, $post_id) {
	// used for uploading images from  the add assignment submission forms
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) return (false);

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	$attach_id = media_handle_upload( $file_handler, $post_id );
	
	return ($attach_id);
	
}




/****************************** SHORT CODES **********************************/	

// ----- short code for number of assignments in the bank
add_shortcode('thingcount', 'getThingCount');

function getThingCount() {
	return wp_count_posts('assignments')->publish  . ' ' . THINGNAME . 's';
}

// ----- short code for number of examples in the bank
add_shortcode('examplecount', 'getExampleCount');

function getExampleCount() {
	return wp_count_posts('examples')->publish . ' examples';
}

/* ----- shortcode to generate lists of top contributors -------- */
add_shortcode("bankleaders", "bank106_leaders");  

function bank106_leaders ( $atts ) {  

	// return a list of the top responders to dailies
		
	// get the value of any passed attributes to our function
	// we want a number of results we should return (0=all)
	// and an indicator if we are looking for responders (hashtag taxonony) or contributors (tag tax)
 	extract( shortcode_atts( array( "number" => 0,  "type" => 'responders' , "exclude" => ""), $atts ) );  

	// Arguments to search hashtag terms
	// search for @ in order of highest frequency
	$args = array(
		'number' => $number,
		'orderby' => 'count',
		'order' => 'DESC',
		'exclude' =>  $exclude,
		'name__like' => '@'
	);
	
	
	if ( $type == 'contributors') {
		// search for terms in the custom taxonomy for regular tags
		$terms = get_tags( $args );
		$taxpath = 'tag';
	} else {
		// search for terms in the custom taxonomy for response tags
		$terms = get_terms('exampletags',  $args );
		$taxpath = 'exampletags';
	}
	
	$out = '<ol>';
	// here come the leaders!
	foreach ( $terms as $term) {
		$out .= '<li><a href="' . site_url() . "/$taxpath/" . $term->slug  . '">' . $term->name . ' (' . $term->count . ')</a></li>';
	}
	$out .= '</ol>';
	
	// here ya go!
	return ($out);

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

function oembed_filter( $str ) {
	// filters text for URLs WP can autoembed, and returns with proper embed code
	// lifted somewhat from oembed-in-comments plugin
	global $wp_embed;

	// Automatic discovery would be a security risk, safety first
	add_filter( 'embed_oembed_discover', '__return_false', 999 );
	$str = $wp_embed->autoembed( $str );

	// ...but don't break your posts if you use it
	remove_filter( 'embed_oembed_discover', '__return_false', 999 );

	return $str;
}
?>