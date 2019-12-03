<?php
# -----------------------------------------------------------------
# Customizer Stuff
# -----------------------------------------------------------------

add_action( 'customize_register', 'bank106_register_theme_customizer' );


function bank106_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'customize_bank106', array(
		'priority'       => 25,
		'theme_supports' => '',
		'title'          => __(  bank106_option( 'thingname' )  . ' Bank', 'wp-bootstrap'),
		'description'    => __( 'Customize this site', 'wp-bootstrap'),
	) );
	

	// Add section for the add thing form
	$wp_customize->add_section( 'add_thing_form' , array(
		'title'    => __('New ' .  bank106_option( 'thingname' ) .  ' Form  Prompts','wp-bootstrap'),
		'panel'    => 'customize_bank106',
		'priority' => 10
	) );
	

	// Add section for the add thing form
	$wp_customize->add_section( 'add_response_form' , array(
		'title'    => __('Response/' .  bank106_option( 'helpthingname' ) .  '  Form Prompts','wp-bootstrap'),
		'panel'    => 'customize_bank106',
		'priority' => 20
	) );
	
	// --------- Add a Thing Customizer ------------------------------------------------

	// setting for title label
	$wp_customize->add_setting( 'thing_title', array(
		 'default'           => __( 'Title for this ' . bank106_option( 'thingname' ) , 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control fortitle label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_title',
		    array(
		        'label'    => __( 'Title Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_title',
		        'type'     => 'text'
		    )
	    )
	);
	
	// setting for title description
	$wp_customize->add_setting( 'thing_title_prompt', array(
		 'default'           => __( 'Enter a title that describes this ' . lcfirst(bank106_option( 'thingname' ))  . ' so that it might make a curious visitor want to read more about it.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for title description
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_title_prompt',
		    array(
		        'label'    => __( 'Title Prompt', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_title_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	
	// setting for writing field  label
	$wp_customize->add_setting( 'thing_writing_area', array(
		 'default'           => __( 'Full Description for this ' . bank106_option( 'thingname' ), 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_writing_area',
		    array(
		        'label'    => __( 'Description Area Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_writing_area',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for description  label prompt
	$wp_customize->add_setting( 'thing_writing_area_prompt', array(
		 'default'           => __( 'Use the editor below to compose everything someone might need to complete this ' . lcfirst(bank106_option( 'thingname' )) . '. ', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_writing_area_prompt',
		    array(
		        'label'    => __( 'Description Area Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for the description field' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_writing_area_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for footer  label
	$wp_customize->add_setting( 'thing_instructions', array(
		 'default'           => __( bank106_option( 'thingname' ) . ' Specific Instructions', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_instructions',
		    array(
		        'label'    => __( bank106_option( 'thingname' ) .  ' Specific Instructions Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_instructions',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for description  label prompt
	$wp_customize->add_setting( 'thing_instructions_prompt', array(
		 'default'           => __( 'Insert any instructions or prompts that are individualized for this ' . lcfirst(bank106_option( 'thingname' )) . '.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_instructions_prompt',
		    array(
		        'label'    => __( bank106_option( 'thingname' ) . ' Specific Instruction Prompt', 'wp-bootstrap'),
		        'description' => __( 'Explain what might be entered here' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_instructions_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
	
	
	// setting for header image caption label
	$wp_customize->add_setting( 'thing_end_notes', array(
		 'default'           => __( 'End Notes', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for header image caption   label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_end_notes',
		    array(
		        'label'    => __( 'End Notes Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_end_notes',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for header image caption   label prompt
	$wp_customize->add_setting( 'thing_end_notes_prompt', array(
		 'default'           => __( 'Enter additional notes for this Thing such as an attribution for the image below or other credits.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for header image caption   label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_end_notes_prompt',
		    array(
		        'label'    => __( 'End Notes Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for entering end notes' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_end_notes_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);	
	

	// setting for things  label
	$wp_customize->add_setting( 'thing_types', array(
		 'default'           => __(  bank106_option( 'thingname' ) . ' ' . bank106_option( 'type_name' ), 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for things  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_types',
		    array(
		        'label'    => __( bank106_option( 'thingname' ) . ' ' . bank106_option( 'type_name' ) . ' Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_types',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for categories  prompt
	$wp_customize->add_setting( 'thing_types_prompt', array(
		 'default'           => __( 'Choose at least one.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	
	
	if ( bank106_option('use_thing_cats') == 1 ) {	
		// setting for thing categories  label
		$wp_customize->add_setting( 'thing_categories', array(
			 'default'           => __(  bank106_option( 'thingname' ) . ' ' . bank106_option( 'thing_cat_name' ), 'wp-bootstrap'),
			 'type' => 'theme_mod',
			 'sanitize_callback' => 'sanitize_text'
		) );
	
		// Control for things  label
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'thing_categories',
				array(
					'label'    => __( bank106_option( 'thingname' ) . ' ' . bank106_option( 'thing_cat_name') . ' Label', 'wp-bootstrap'),
					'description' => __( '' ),
					'section'  => 'add_thing_form',
					'settings' => 'thing_categories',
					'type'     => 'text'
				)
			)
		);

		// setting for categories  prompt
		$wp_customize->add_setting( 'thing_categories_prompt', array(
			 'default'           => __( 'Choose any/all that apply.', 'wp-bootstrap'),
			 'type' => 'theme_mod',
			 'sanitize_callback' => 'sanitize_text'
		) );	
		
	}
	
	
	// Control for categories prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_types_prompt',
		    array(
		        'label'    => __(  bank106_option( 'thingname' ) . ' ' . bank106_option( 'type_name' ) .' Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for the category selection' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_types_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
		
	// setting for tags  label
	$wp_customize->add_setting( 'thing_tags', array(
		 'default'           => __( 'Tags That Describe This ' .  bank106_option( 'thingname' ), 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_tags',
		    array(
		        'label'    => __( 'Tags Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_tags',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for tags  prompt
	$wp_customize->add_setting( 'thing_tags_prompt', array(
		 'default'           => __( 'All tags must be a single word; separate each tag with a comma or a space.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_tags_prompt',
		    array(
		        'label'    => __( 'Tags Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for tags entry' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_tags_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);	
		
	// setting for thing difficulty  label
	$wp_customize->add_setting( 'thing_difficulty', array(
		 'default'           => __( 'Difficulty', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for thing difficulty  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_difficulty',
		    array(
		        'label'    => __( 'Difficulty Rating Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_difficulty',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for thing difficulty  prompt
	$wp_customize->add_setting( 'thing_difficulty_prompt', array(
		 'default'           => __( 'As the creator of this ' . lcfirst(bank106_option( 'thingname' )) . ' you can assign a difficulty rating that is displayed when it is viewed.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for thing difficulty prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_difficulty_prompt',
		    array(
		        'label'    => __( 'Difficulty Rating Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for the prompt' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_difficulty_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);	
	
	
	if ( function_exists( 'the_ratings' ) ) {
		// setting for thing user  label
		$wp_customize->add_setting( 'thing_rating', array(
			 'default'           => __( 'Initial Public Rating', 'wp-bootstrap'),
			 'type' => 'theme_mod',
			 'sanitize_callback' => 'sanitize_text'
		) );
	
		// Control for thing user  label
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'thing_rating',
				array(
					'label'    => __( 'User Rating Label', 'wp-bootstrap'),
					'description' => __( '' ),
					'section'  => 'add_thing_form',
					'settings' => 'thing_rating',
					'type'     => 'text'
				)
			)
		);

		// setting for thing user  prompt
		$wp_customize->add_setting( 'thing_rating_prompt', array(
			 'default'           => __( 'Any visitor can rate this ' . lcfirst(bank106_option( 'thingname' )) . ' on the scale shown below. Give it an initial seed value.', 'wp-bootstrap'),
			 'type' => 'theme_mod',
			 'sanitize_callback' => 'sanitize_text'
		) );
	
		// Control for thing user prompt
		$wp_customize->add_control( new WP_Customize_Control(
			$wp_customize,
			'thing_rating_prompt',
				array(
					'label'    => __( 'User Rating Prompt', 'wp-bootstrap'),
					'description' => __( 'Directions for the rating prompt' ),
					'section'  => 'add_thing_form',
					'settings' => 'thing_rating_prompt',
					'type'     => 'textarea'
				)
			)
		);
	}
		
	
	// setting for editor notes  label
	$wp_customize->add_setting( 'thing_thumbnail', array(
		 'default'           => __( 'Upload Thumbnail Image', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for editor notes  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_thumbnail',
		    array(
		        'label'    => __( 'Image Upload Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_thumbnail',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for editor notes  prompt
	$wp_customize->add_setting( 'thing_thumbnail_prompt', array(
		 'default'           => __( 'Upload an image to represent your ' . lcfirst(bank106_option( 'thingname' )), 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for editor notes prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'thing_thumbnail_prompt',
		    array(
		        'label'    => __( 'Image Upload Prompt', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_thing_form',
		        'settings' => 'thing_thumbnail_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);	
	
	// --------- Add a Response Customizer ------------------------------------------------

	// setting for title label
	$wp_customize->add_setting( 'response_title', array(
		 'default'           => __( 'Title', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control fortitle label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'response_title',
		    array(
		        'label'    => __( 'Title Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_response_form',
		        'settings' => 'response_title',
		        'type'     => 'text'
		    )
	    )
	);
	
	// setting for title description
	$wp_customize->add_setting( 'response_title_prompt', array(
		 'default'           => __( 'Enter a descriptive title', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for title description
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'response_title_prompt',
		    array(
		        'label'    => __( 'Title Field Default', 'wp-bootstrap'),
		        'description' => __( 'This is what will go inside the form field as a prompt.' ),
		        'section'  => 'add_response_form',
		        'settings' => 'response_title_prompt',
		        'type'     => 'text'
		    )
	    )
	);
	


	// setting for source/credit field  label
	$wp_customize->add_setting( 'tutorial_source', array(
		 'default'           => __( 'Source/Site Name', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for source/credit  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'tutorial_source',
		    array(
		        'label'    => __( bank106_option( 'helpthingname' ) . ' Source/Site Name Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_response_form',
		        'settings' => 'tutorial_source',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for source/credit  label prompt
	$wp_customize->add_setting( 'tutorial_source_prompt', array(
		 'default'           => __( 'Enter name of a site/source to credit', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for source/credit  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'tutorial_source_prompt',
		    array(
		        'label'    => __( bank106_option( 'helpthingname' ) . ' Placeholder Text', 'wp-bootstrap'),
		        'description' => __( 'Placeholder text in the input field' ),
		        'section'  => 'add_response_form',
		        'settings' => 'tutorial_source_prompt',
		        'type'     => 'text'
		    )
	    )
	);
	
	
	

	// setting for response field  label
	$wp_customize->add_setting( 'response_writing_area', array(
		 'default'           => __( 'Description', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for response  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'response_writing_area',
		    array(
		        'label'    => __( 'Response Description Area Label', 'wp-bootstrap'),
		        'description' => __( 'Note that this needs to work for ' . lcfirst(bank106_option( 'thingname' )) . ' responses as well as ' . lcfirst(bank106_option( 'helpthingname' )) . 's.' ),
		        'section'  => 'add_response_form',
		        'settings' => 'response_writing_area',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for response  label prompt
	$wp_customize->add_setting( 'response_writing_area_prompt', array(
		 'default'           => __( 'use the text editor to compose a descriptive entry.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for response  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'response_writing_area_prompt',
		    array(
		        'label'    => __( 'Description Area Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for the description field' ),
		        'section'  => 'add_response_form',
		        'settings' => 'response_writing_area_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
	


	// setting for tags  label
	$wp_customize->add_setting( 'example_tags', array(
		 'default'           => __( 'Tags', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'example_tags',
		    array(
		        'label'    => __( 'Tags Label', 'wp-bootstrap'),
		        'description' => __( '' ),
		        'section'  => 'add_response_form',
		        'settings' => 'example_tags',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for tags  prompt
	$wp_customize->add_setting( 'example_tags_prompt', array(
		 'default'           => __( 'Separate each tag with a comma.', 'wp-bootstrap'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'example_tags_prompt',
		    array(
		        'label'    => __( 'Tags Prompt', 'wp-bootstrap'),
		        'description' => __( 'Directions for tags entry' ),
		        'section'  => 'add_response_form',
		        'settings' => 'example_tags_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);	
	
			
 	// Sanitize text
	function sanitize_text( $text ) {
	    return sanitize_text_field( $text );
	}
}


// ---------- Add a Thing Form display -------------------------------

function bank106_form_thing_title() {
	 if ( get_theme_mod( 'thing_title') != "" ) {
	 	echo get_theme_mod( 'thing_title');
	 }	else {
	 	echo 'Title for this ' . bank106_option( 'thingname' );
	 }
}

function bank106_form_thing_title_prompt() {
	 if ( get_theme_mod( 'thing_title_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_title_prompt');
	 }	else {
	 	echo 'Enter a title that describes this ' . lcfirst(bank106_option( 'thingname' ))  . ' so that it might make a curious visitor want to read more about it.';
	 }
}




function bank106_form_thing_end_notes() {
	 if ( get_theme_mod( 'thing_end_notes') != "" ) {
	 	echo get_theme_mod( 'thing_end_notes');
	 }	else {
	 	echo 'End Notes';
	 }
}

function bank106_form_thing_end_notes_prompt() {
	 if ( get_theme_mod( 'thing_end_notes_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_end_notes_prompt');
	 }	else {
	 	echo 'Enter additional notes for this Thing such as an attribution for the image below or other credits.';
	 }
}


function bank106_form_thing_writing_area() {
	 if ( get_theme_mod( 'thing_writing_area') != "" ) {
	 	echo get_theme_mod( 'thing_writing_area');
	 }	else {
	 	echo 'Full Description for this ' . bank106_option( 'thingname' );
	 }
}

function bank106_form_thing_writing_area_prompt() {
	 if ( get_theme_mod( 'thing_writing_area_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_writing_area_prompt');
	 }	else {
	 	echo 'Use the editor below to compose everything someone might need to complete this ' . lcfirst(bank106_option( 'thingname' )) . '. ';
	 }
}

function bank106_form_thing_instructions() {
	 if ( get_theme_mod( 'thing_instructions') != "" ) {
	 	echo get_theme_mod( 'thing_instructions');
	 }	else {
	 	echo bank106_option( 'thingname' ) . ' Specific Instructions';
	 }
}

function bank106_form_thing_instructions_prompt() {
	 if ( get_theme_mod( 'thing_instructions_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_instructions_prompt');
	 }	else {
	 	echo 'Insert any instructions or prompts that are individualized for this ' . lcfirst(bank106_option( 'thingname' )) . '.';
	 }
}


function bank106_form_thing_types() {
	 if ( get_theme_mod( 'thing_types') != "" ) {
	 	echo get_theme_mod( 'thing_types');
	 }	else {
	 	echo bank106_option( 'thingname' ) . ' ' . bank106_option( 'type_name' );
	 }
}

function bank106_form_thing_types_prompt() {
	 if ( get_theme_mod( 'thing_types_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_types_prompt');
	 }	else {
	 	echo 'Choose at least one.';
	 }
}


function bank106_form_thing_categories() {
	 if ( get_theme_mod( 'thing_categories') != "" ) {
	 	echo get_theme_mod( 'thing_categories');
	 }	else {
	 	echo bank106_option( 'thingname' ) . ' ' . bank106_option( 'thing_cat_name' );
	 }
}

function bank106_form_thing_categories_prompt() {
	 if ( get_theme_mod( 'thing_categories_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_categories_prompt');
	 }	else {
	 	echo 'Choose any/all that apply.';
	 }
}


function bank106_form_thing_tags() {
	 if ( get_theme_mod( 'thing_tags') != "" ) {
	 	echo get_theme_mod( 'thing_tags');
	 }	else {
	 	echo 'Tags That Describe This ' .  bank106_option( 'thingname' );
	 }
}

function bank106_form_thing_tags_prompt() {
	 if ( get_theme_mod( 'thing_tags_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_tags_prompt');
	 }	else {
	 	echo 'All tags must be a single word; separate each tag with a comma or a space.';
	 }
}

function bank106_form_thing_difficulty() {
	 if ( get_theme_mod( 'thing_difficulty') != "" ) {
	 	echo get_theme_mod( 'thing_difficulty');
	 }	else {
	 	echo 'Difficulty';
	 }
}

function bank106_form_thing_difficulty_prompt() {
	 if ( get_theme_mod( 'thing_difficulty_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_difficulty_prompt');
	 }	else {
	 	echo 'As the creator of this ' . lcfirst(bank106_option( 'thingname' )) . ' you can assign a difficulty rating that is displayed when it is viewed.';
	 }
}

function bank106_form_thing_user_rating() {
	 if ( get_theme_mod( 'thing_rating') != "" ) {
	 	echo get_theme_mod( 'thing_rating');
	 }	else {
	 	echo 'Initial Public Rating';
	 }
}

function bank106_form_thing_user_rating_prompt() {
	 if ( get_theme_mod( 'thing_rating_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_rating_prompt');
	 }	else {
	 	echo 'Any visitor can rate this ' . lcfirst(bank106_option( 'thingname' )) . ' on the scale shown below. Give it an initial seed value.';
	 }
}

function bank106_form_thing_thumbnail() {
	 if ( get_theme_mod( 'thing_thumbnail') != "" ) {
	 	echo get_theme_mod( 'thing_thumbnail');
	 }	else {
	 	echo 'Upload Thumbnail Image';
	 }
}

function bank106_form_thing_thumbnail_prompt() {
	 if ( get_theme_mod( 'thing_thumbnail_prompt') != "" ) {
	 	echo get_theme_mod( 'thing_thumbnail_prompt');
	 }	else {
	 	echo 'Upload an image to represent your ' . lcfirst(bank106_option( 'thingname' ));
	 }
}


// ---------- Add Example/Tutorial Form display -------------------------------

function bank106_form_response_title() {
	 if ( get_theme_mod( 'response_title') != "" ) {
	 	echo get_theme_mod( 'response_title');
	 }	else {
	 	echo 'Title for this ' . bank106_option( 'thingname' );
	 }
}

function bank106_form_response_title_prompt() {
	 if ( get_theme_mod( 'response_title_prompt') != "" ) {
	 	echo get_theme_mod( 'response_title_prompt');
	 }	else {
	 	echo 'Enter a descriptive title';
	 }
}


function bank106_form_tutorial_source() {
	 if ( get_theme_mod( 'tutorial_source') != "" ) {
	 	echo get_theme_mod( 'tutorial_source');
	 }	else {
	 	echo 'Source/Site Name';
	 }
}

function bank106_form_tutorial_source_prompt() {
	 if ( get_theme_mod( 'tutorial_source_prompt') != "" ) {
	 	echo get_theme_mod( 'tutorial_source_prompt');
	 }	else {
	 	echo 'Enter name of a site/source to credit';
	 }
}

function bank106_form_response_writing_area() {
	 if ( get_theme_mod( 'response_writing_area') != "" ) {
	 	echo get_theme_mod( 'response_writing_area');
	 }	else {
	 	echo 'Description';
	 }
}

function bank106_form_response_writing_area_prompt() {
	 if ( get_theme_mod( 'response_writing_area_prompt') != "" ) {
	 	echo get_theme_mod( 'response_writing_area_prompt');
	 }	else {
	 	echo 'use the text editor to compose a descriptive entry.';
	 }
}


function bank106_form_response_example_tags() {
	 if ( get_theme_mod( 'response_example_tags') != "" ) {
	 	echo get_theme_mod( 'response_example_tags');
	 }	else {
	 	echo 'Tags';
	 }
}

function bank106_form_response_example_tags_prompt() {
	 if ( get_theme_mod( 'response_example_tags_prompt') != "" ) {
	 	echo get_theme_mod( 'response_example_tags_prompt');
	 }	else {
	 	echo 'Separate each tag with a comma.';
	 }
}

?>