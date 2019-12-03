<?php
/* set up for ds106bank theme */

// all the inits

add_action( 'init', 'bank106_custom_post_types' );
add_action( 'init', 'bank106_load_theme_options', 12 );
add_action( 'init', 'bank106_setup', 20 );


// ----- Set up the special bank theme options -----------------------------------------

function bank106_load_theme_options() {
	// load theme options all available elsewhere via bank106_option("[optionname]")

	if ( file_exists( get_template_directory()  . '/class.ds106bank-theme-options.php' ) ) {
		include_once( get_template_directory()  . '/class.ds106bank-theme-options.php' );
	}
}

// Set up javascript for the theme options interface
function bank106_enqueue_options_scripts() {
	
	// media scripts needed for wordpress media uploaders
	wp_enqueue_media();
	
	// custom jquery for the options admin screen
	wp_register_script( 'bank106_options_js' , get_template_directory_uri() . '/js/jquery.options.js', array( 'jquery' ), '1.0', TRUE );
	wp_enqueue_script( 'bank106_options_js' );
}


// -----  Add admin menu bar link for options
add_action( 'wp_before_admin_bar_render', 'bank106_options_to_admin' );

function bank106_options_to_admin() {
    global $wp_admin_bar;
    
    // we can add a submenu item too
    $wp_admin_bar->add_menu( array(
        'parent' => '',
        'id' => 'bank-options',
        'title' => __('Bank Options'),
        'href' => admin_url( 'themes.php?page=ds106bank-options')
    ) );
}

// handle request for theme specific page setup from theme options

add_action( 'admin_post_make_bank_pages', 'prefix_admin_make_bank_pages' );

function prefix_admin_make_bank_pages() {
	// look for existence of pages with the appropriate template, if not found
	// make 'em. Called from the Assignmen Options.
	
	
	if (! page_with_template_exists( 'page-add-assignment.php' ) ) {
  
		// create the add a thing page if it does not exist
		// backdate creation date 2 days just to make sure they do not end up future dated
		
		$thingname = bank106_option( 'thingname' );
		
		$page_data = array(
			'post_title' 	=> 'Add a New ' . $thingname,
			'post_content'	=> 'Use this form to add a new ' . $thingname,
			'post_name'		=> 'add-' . strtolower($thingname),
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-add-assignment.php',
		);
	
		wp_insert_post( $page_data );
		
		// for feedback
		$pages_made[] = '<a href="' . site_url('/') . 'add-' . strtolower($thingname) . '">' . 'Add a New ' . $thingname . '</a>';
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
		
		
		// for feedback
		$pages_made[] = '<a href="' . site_url('/') . 'add-example' . '">' . 'Add a New Example</a>';
		
  		wp_insert_post( $page_data );
  	}
	
	if (! page_with_template_exists( 'page-assignment-menu.php' ) ) {
  
		// create the Write page if it does not exist
		$page_data = array(
			'post_title' 	=>  $thingname . ' Bank',
			'post_content'	=> 'Insert welcome info here.',
			'post_name'		=> 'assignment-menu',
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-assignment-menu.php',
		);
	
		wp_insert_post( $page_data );
		
		// for feedback
		$pages_made[] = '<a href="' . site_url('/') . 'assignment-menu' . '">' . $thingname . ' Bank</a> (Change the Settings -&gt; Reading Options to use for a Static Page this one)';

	}
	
if (! page_with_template_exists( 'page-random.php' ) ) {
  
		// create the Write page if it does not exist
		$page_data = array(
			'post_title' 	=>  'Random',
			'post_content'	=> 'This page dsipalys nothing, all the fun is in the template. It redirects a viewer to a random thing.',
			'post_name'		=> 'random',
			'post_status'	=> 'publish',
			'post_type'		=> 'page',
			'post_author' 	=> 1,
			'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
			'page_template'	=> 'page-random.php',
		);
	
		wp_insert_post( $page_data );
		
		// for feedback
		$pages_made[] = '<a href="' . site_url('/') . 'random' . '">Random ' . bank106_option( 'thingname' ) . ' </a>';

	}
	
	if ( $pages_made ) {
		echo 'The following special pages used by this theme have been built for you. Edit the content to customize an introduction. <ul>';
		foreach ($pages_made as $new_page) {
			echo '<li>' . $new_page . '</li>';		
		}
		
		echo '<ul>';
	} else {
		echo 'Woot! All necessary theme pages are already created. <a href="' . admin_url('edit.php?post_type=page') . '">Review and edit Pages</a>';
	}
}


// ----- General Setup Tasks -------------------------------------------------------------

function bank106_setup() { 


    // custom thumbnails
    add_theme_support( 'post-thumbnails' ); 
    add_image_size( 'bank-thumb', bank106_option('thumb_w' ), bank106_option('thumb_h' ), true); 
    
    update_option( 'thumbnail_size_w', bank106_option('thumb_w' ) );
	update_option( 'thumbnail_size_h', bank106_option('thumb_h' ) );
	update_option( 'thumbnail_crop', 1 );
		
	if ( bank106_option('use_wp_login') ) {
		add_filter( 'loginout', 'bank106_login_menu_customize' );
		add_filter( 'wp_nav_menu_items', 'bank106_login_logout_link', 10, 2);
	}
		
}

// enable custom headers, a wee one across the top
add_action( 'after_setup_theme', 'bank106_custom_header_setup' );

function bank106_custom_header_setup() {
    $args = array(
        'width'              => 970,
        'height'             => 60,
    );
    
    add_theme_support( 'custom-header', $args );  

}

// add allowable url parameters
add_filter('query_vars', 'bank106_queryvars' );

function bank106_queryvars( $qvars ) {
	$qvars[] = 'srt'; // sort parameters for things
	$qvars[] = 'aid'; // assignment id for add forms
	$qvars[] = 'typ'; // flag for adding example or tutorial
	
	return $qvars;
}  


// cleaner dashboard is a happier one, remove Posts from side menu and admin bar

add_action( 'admin_menu', 'bank106_remove_menus' );
add_action( 'admin_bar_menu', 'bank106_remove_wp_nodes', 999 );

function bank106_remove_menus() { 
	  // Hide new Posts so no creating "Things" in dashboard, shuld be done on front end
	  remove_menu_page( 'edit.php' );
	  // Hide Media uploads for non admins
	  if (!current_user_can( 'manage_options' )) remove_menu_page( 'upload.php' ); 
}

add_action( 'admin_bar_menu', 'bank106_remove_admin_menus', 999 );

function bank106_remove_admin_menus() {
    global $wp_admin_bar;   
    $wp_admin_bar->remove_node( 'new-assignments' );
}


function bank106_remove_wp_nodes()  {
    global $wp_admin_bar;   
    $wp_admin_bar->remove_node( 'new-post' );
    $wp_admin_bar->remove_node( 'new-link' );
    $wp_admin_bar->remove_node( 'new-media' );
} 


// ----- run re-writes on theme switch
add_action( 'after_switch_theme', 'bank106_rewrite_flush' );

function bank106_rewrite_flush() {
	bank106_custom_post_types();
    flush_rewrite_rules();  
}

# -----------------------------------------------------------------
# Tiny-MCE mods
# -----------------------------------------------------------------


add_filter('tiny_mce_before_init', 'bank106_tinymce_settings' );

function bank106_tinymce_settings( $settings ) {

	// $settings['file_picker_types'] = 'image';
	$settings['images_upload_handler'] = 'function (blobInfo, success, failure) {
    var xhr, formData;

    xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open(\'POST\', \'' . admin_url('admin-ajax.php') . '\');

    xhr.onload = function() {
      var json;

      if (xhr.status != 200) {
        failure(\'HTTP Error: \' + xhr.status);
        return;
      }

      json = JSON.parse(xhr.responseText);

      if (!json || typeof json.location != \'string\') {
        failure(\'Invalid JSON: \' + xhr.responseText);
        return;
      }

      success(json.location);
    };

    formData = new FormData();
    formData.append(\'file\', blobInfo.blob(), blobInfo.filename());
	formData.append(\'action\', \'bank106_upload_action\');
    xhr.send(formData);
  }';
  

	return $settings;
}

function bank106_register_buttons( $plugin_array ) {

	$plugin_array['imgbutton'] = get_template_directory_uri() . '/js/image-button.js';
	return $plugin_array;
}

// remove  buttons from the visual editor
function bank106_tinymce_buttons($buttons) {
	//Remove the more button
	$remove = 'wp_more';

	// Find the array key and then unset
	if ( ( $key = array_search($remove,$buttons) ) !== false )
		unset($buttons[$key]);

	// now add the image button in
	if (! is_user_logged_in() ) {
		$buttons[] = 'image';
		$buttons[] = 'imgbutton';
	}
	
	// array_push($buttons, "splot_upload_btn");
	return $buttons;
 }

// remove  more buttons from the visual editor


function bank106_tinymce_2_buttons( $buttons)  {
	//Remove the keybord shortcut and paste text buttons
	$remove = array('wp_help','pastetext');

	return array_diff($buttons,$remove);
 }


// this is the handler used in the tiny_mce editor to manage iage upload
add_action( 'wp_ajax_nopriv_bank106_upload_action', 'bank106_upload_action' ); //allow on front-end
add_action( 'wp_ajax_bank106_upload_action', 'bank106_upload_action' );

function bank106_upload_action() {	

    $newupload = 0;

    if ( !empty($_FILES) ) {
        $files = $_FILES;
        foreach($files as $file) {
            $newfile = array (
                    'name' => $file['name'],
                    'type' => $file['type'],
                    'tmp_name' => $file['tmp_name'],
                    'error' => $file['error'],
                    'size' => $file['size']
            );

            $_FILES = array('upload'=>$newfile);
            foreach($_FILES as $file => $array) {
                $newupload = media_handle_upload( $file, 0);
            }
        }
    }
    echo json_encode( array('location' => wp_get_attachment_image_src( $newupload, 'large' )[0] ) );
    die();	
	
}


// ----- Output Tweaks ------------------------------------------------------------------

// Allow changes to excerpt length (and option entered in admin)
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function custom_excerpt_length( $length ) {
	return bank106_option('exlen');
}

// customize the "more..." link
add_filter( 'excerpt_more', 'bank106_excerpt_more' );

function bank106_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. bank106_get_response_link(get_the_id()) . '">more... &raquo;</a>';
}

?>