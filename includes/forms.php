<?php

// ----------- enqueuing scripts and styles --------------------------------------------

// scripts and styles needed for the page to add a new THING
function bank106_enqueue_add_thing_scripts() {

	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );


	if (! is_admin() ) wp_enqueue_media();
	

	// Autoembed functionality in rich text editor
	// needs dependency on tiny_mce
	// h/t https://wordpress.stackexchange.com/a/287623
	
	wp_enqueue_script( 'mce-view', '', array('tiny_mce'), '', true );		

	// tinymce mods
	add_filter("mce_external_plugins", "bank106_register_buttons");
	add_filter('mce_buttons','bank106_tinymce_buttons');
	add_filter('mce_buttons_2','bank106_tinymce_2_buttons');

    // custom jquery for the add thing/assignment form
	wp_register_script( 'bank106_add_thing_js' , get_template_directory_uri() . '/js/jquery.add-thing.js', array( 'jquery' ), '1.0', TRUE );
	
	// add a local variable for the URL for the default thumbnail image
	wp_localize_script(
	  'bank106_add_thing_js',
	  'bankObject',
	  array(
		'default_thumb' => bank106_option('def_thumb' ),
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'uploadMax' => wp_max_upload_size() / 1000000.0
	  )
	);	

	wp_enqueue_script( 'bank106_add_thing_js' );

	// add scripts for fancybox (used for previews of submitted things) 
	bank106_enqueue_fancybox();

	// Lightbox formatting for preview screated with rich text editor
	wp_register_script( 'lightbox_thing', get_template_directory_uri() . '/includes/lightbox/js/lightbox_thing.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_thing' );
	
	
	// Bootstrap filestyle for nice uploads of files
	wp_register_script( 'filestyle', get_template_directory_uri() . '/js/bootstrap-filestyle.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'filestyle' );

	// used to display formatted dates
	wp_register_script( 'moment' , get_template_directory_uri() . '/js/moment.js', null, '1.0', TRUE );
	wp_enqueue_script( 'moment' );
	

	// Bootstrap filestyle for nice uploads of files
	wp_register_script( 'filestyle', get_template_directory_uri() . '/js/bootstrap-filestyle.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'filestyle' );
	
	// google captcha script
	if ( bank106_option('use_captcha') ) {	
		wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js');
		wp_enqueue_script( 'recaptcha' );
	}
}


// enqueues for form for adding an example/tutorial
function bank106_enqueue_add_ex_scripts() {


	if (! is_admin() ) wp_enqueue_media();
	
	
	// Build in tag auto complete script
	wp_enqueue_script( 'suggest' );
	
	// Autoembed functionality in rich text editor
	// needs dependency on tiny_mce
	// h/t https://wordpress.stackexchange.com/a/287623
	
	wp_enqueue_script( 'mce-view', '', array('tiny_mce'), '', true );	

	// tinymce mods
	
	add_filter("mce_external_plugins", "bank106_register_buttons");
	add_filter('mce_buttons','bank106_tinymce_buttons');
	add_filter('mce_buttons_2','bank106_tinymce_2_buttons');


    // custom jquery for the example form
	wp_register_script( 'bank106_add_example_js' , get_template_directory_uri() . '/js/jquery.add-example.js', array( 'jquery' ), '1.1', TRUE );
	
	// add a local variable for the URL for the default thumbnail image
	wp_localize_script(
	  'bank106_add_example_js',
	  'bankObject',
	  array(
		'uploadMax' => bank106_option('upload_max' ),
		'ajaxUrl' => admin_url('admin-ajax.php'),
	  )
	);	
	
	wp_enqueue_script( 'bank106_add_example_js' );
	
	bank106_enqueue_fancybox();

	// google captcha script
	if ( bank106_option('use_captcha') ) {	
		wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js');
		wp_enqueue_script( 'recaptcha' );
	}

}


function bank106_enqueue_fancybox() {
	// add scripts for fancybox (used for previews of submitted examples) 
	//-- h/t http://code.tutsplus.com/tutorials/add-a-responsive-lightbox-to-your-wordpress-theme--wp-28100
	wp_register_script( 'fancybox', get_template_directory_uri() . '/includes/lightbox/js/jquery.fancybox.pack.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'fancybox' );
	
	// fancybox styles
	wp_register_style( 'lightbox-style', get_template_directory_uri() . '/includes/lightbox/css/jquery.fancybox.css' );
	wp_enqueue_style( 'lightbox-style' );
	
}

// rich media text editor scripts
function bank106_enqueue_richtext_scripts() {

	// Lightbox formatting for previews of examples created with rich text editor
	wp_register_script( 'lightbox_richtext', get_template_directory_uri() . '/includes/lightbox/js/lightbox_richtext.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_richtext' );

    // used to display formatted dates in previews
	wp_register_script( 'moment' , get_template_directory_uri() . '/js/moment.js', null, '1.0', TRUE );
	wp_enqueue_script( 'moment' );

}

// simple text editor scripts
function bank106_enqueue_simpletext_scripts() {

	//  Lightbox formatting for previews of responses/tutorials linked externally (simple text editor)
	wp_register_script( 'lightbox_simpletext', get_template_directory_uri() . '/includes/lightbox/js/lightbox_simpletext.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
	wp_enqueue_script( 'lightbox_simpletext' );

}

// ----------- Uploading Stuff  ----------------------------------------------------=----


// uploading images from the add assignment submission forms
function bank106_insert_attachment( $file_handler, $post_id) {
	
	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) return (false);

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	$attach_id = media_handle_upload( $file_handler, $post_id );
	
	return ($attach_id);
	
}



?>