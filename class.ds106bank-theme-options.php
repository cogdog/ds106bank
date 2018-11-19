<?php
// manages all of the theme options
// heavy lifting via http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/
// Revision July 26, 2016 by @cogdog as jQuery update killed TAB UI

class ds106bank_Theme_Options {

	/* Array of sections for the theme options page */
	private $sections;
	private $checkboxes;
	private $settings;

	/* Initialize */
	function __construct() {

		// be styling
		$this->styles();
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		
		// go get ;em
		$this->get_settings();
		
		// Sections for the options, always have General and Reset
		$this->sections['general'] = __( 'General Settings' );
		$this->sections['types']   = __( 'Types of ' . ds106bank_option( 'pluralthings' ) );
		$this->sections['reset']   = __( 'Reset Options to Defaults' );
		

		// Create a colllection of callbacks for each section heading
		foreach ( $this->sections as $slug => $title ) {
			$this->section_callbacks[$slug] = 'display_' . $slug;
		}
		
		// enqueue scripts for media uploader, get 'em in queueu
        add_action( 'admin_enqueue_scripts', 'ds106bank_enqueue_options_scripts' );
		
		// Do the rest of the set up stuff, Clyde
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'ds106banker_options' ) )
			$this->initialize_settings();
	}
	

	/* Add page(s) to the admin menu */
	public function add_pages() {
		// options page added
		$admin_page = add_theme_page( 'Assignment Bank Options', 'Assignment Bank Options', 'manage_options', 'ds106bank-options', array( &$this, 'display_page' ) );
		
		// documents page, but don't add to menu		
		$docs_page = add_theme_page( 'Assignment Bank Documentation', '', 'manage_options', 'ds106bank-docs', array( &$this, 'display_docs' ) );

	}
	
	/* HTML to display the theme options page and it's tabs */
	public function display_page() {
	
	
		$settings_headings = array('theme_setup' => 'Theme Setup', 'thing_heading' => 'Things', 'notify_heading' => 'Notifications', 'twitter_heading' => 'Twitter', 'thumbnail_heading' => 'Media', 'cc_heading' => 'Licensing', 'ratings_heading' => 'Ratings', 'examples_heading' => 'Responses and Tutorials', 'login_heading' => 'Login', 'syndication_heading' => 'Syndication', 'captcha_heading' => 'Captcha', 'thing_type_heading' => 'Organize Types', 'type_mod_heading' => 'Edit Types', 'reset_heading' => 'Reset Settings');
		
		$nav = '';
		
		foreach ($settings_headings as $label => $heading) {
			$nav .= '<a href="#' . $label . '" class="button-primary">' . $heading . '</a>';
		}
		
	 	echo '<div class="wrap">
		<h1>Assignment Bank Options</h1><p>' . $nav . '</p>' ;
		
		
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
			echo '<div class="notice notice-success"><p>' . __( 'Theme options updated.' ) . '</p></div>';
		}
		
		echo '<form action="options.php" method="post" enctype="multipart/form-data">';
		
		// set up thr settings
		settings_fields( 'ds106banker_options' );
		
		// tabbed navigation stuff
		echo  '<h2 class="nav-tab-wrapper"><a class="nav-tab nav-tab-active" href="?page=ds106bank-options">Settings</a>
		<a class="nav-tab" href="?page=ds106bank-docs">Documentation</a></h2>';

		// generates all the form stuff, it's like MAGIC
		do_settings_sections( $_GET['page'] );
		
		// do not forget a button!
		echo  '<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>
		</form><p>'  . $nav . '</p>
		</div>';
		
		// some extra jQuery suff to make the forms even more spiffier
		echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
		});
		</script>';
    }

		/* Insert custom CSS */
		public function styles() {
			wp_register_style( 'ds106bank-admin', get_template_directory_uri() . '/ds106bank-options.css' );
			wp_enqueue_style( 'ds106bank-admin' );
		}
    
	/*  Display documentation in a tab */
	public function display_docs() {	
		// This displays on the "Documentation" tab. 
		
	 	echo '<div class="wrap">
		<h1>Assignment Bank Options</h1>
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="?page=ds106bank-options">Settings</a>
		<a class="nav-tab nav-tab-active" href="?page=ds106bank-docs">Documentation</a></h2>';
		
		// doc tab content in HTML
		include( get_stylesheet_directory() . '/includes/ds106bank-theme-options-docs.php');
		
		echo '</div>';		
	}
				
			

	/* Define all settings and their defaults */
	public function get_settings() {
		
		// ---------- tool to make the necessary pages
		$this->settings['theme_setup'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Theme Setup',
			'std'    => 'When first set up or at any time check to <a href="' . admin_url('admin-post.php?action=make_bank_pages') . '" target="_blank" class="button-secondary">Create Theme Specific Pages</a>.',
			'type'    => 'heading'
		);
		
		
		// ---------- The Things
		
		$this->settings['thing_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Things in this Bank',
			'std'    => '',
			'type'    => 'heading'
		);		

		$this->settings['thingname'] = array(
			'title'   => __( 'Name for Things in the Bank' ),
			'desc'    => __( 'What is the name for the kind of thing banked here? Assignment? Challenge? Task? Must be singular and should not contain numbers (0-9) or spaces.' ),
			'std'     => 'Assignment',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['pluralthings'] = array(
			'title'   => __( 'Plural Name for the Things' ),
			'desc'    => __( 'Usually it\'s as simple as adding an "s" but what if it ends in "y"? Or is some weird Latin word?' ),
			'std'     => 'Assignments',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['new_thing_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Things' ),
			'desc'    => __( 'Set to draft to moderate submissions via web form.' ),
			'type'    => 'radio',
			'std'     => 'publish',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);

		$this->settings['use_thing_cats'] = array(
			'section' => 'general',
			'title'   => __( 'Use Categories for ' . ds106bank_option( 'pluralthings' ) ),
			'desc'    => __( 'Offer another way to organize them across types. You can present available categories on the form to let users assign them, or do it on the back end as a task for site admins.'),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'No, do not use categories',
							'1' => 'Yes, and let ' .  ds106bank_option( 'thingname' ) . ' creators assign categories',
							'2' => 'Yes, but leave it for admins to assign categories'
					)
		);

		$this->settings['thing_cat_name'] = array(
			'title'   => __( 'Label for Category' ),
			'desc'    => __( 'You can use another label besides the default \'Category\'- it should be singular.' ),
			'std'     => 'Category',
			'type'    => 'text',
			'section' => 'general'
		);

		// ------- email notification options		
		$this->settings['notify_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Email Notifications',
			'std'    => 'If the option <strong>Status for New Things</strong> is <code>Publish Immediately</code>, checking a box below will send messages when something new is published; for  <code>Save as Draft</code> the notification will alert you to moderate the item.',
			'type'    => 'heading'
		);
		
		$this->settings['notify'] = array(
			'title'   => __( 'Addresses to Notify' ),
			'desc'    => __( 'Send notifications to all these email addresses (separate multiple ones wth commas). ' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);


		$this->settings['notify_things'] = array(
			'section' => 'general',
			'title'   => __( 'Submitted new ' . lcfirst( ds106bank_option( 'pluralthings' ) ) ),
			'desc'    => __( 'Notify if moderation required / when published' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['notify_responses'] = array(
			'section' => 'general',
			'title'   => __( 'Responses to ' . lcfirst( ds106bank_option( 'pluralthings' ) ) ),
			'desc'    => __( 'Notify if moderation required / when published' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		// ------- twitter options		
		$this->settings['twitter_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Twitter Options',
			'std'    => '',
			'type'    => 'heading'
		);
	
		$this->settings['use_twitter_name'] = array(
			'section' => 'general',
			'title'   => __( 'Use twitter name on submission forms?'),
			'desc'    => __( 'Option to use and/or require a twitter name with submission, if used will be added as a tag to entries. An individual\'s work can be tracked via <code>' . home_url() . '/exampletags/' . '&lt;twittername&gt;</code>'),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'No',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);

		$this->settings['hashtag'] = array(
			'title'   => __( 'Twitter Hashtag' ),
			'desc'    => __( 'Optional to be included on Tweet This buttons (do not include the #!)' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);
		
		// ---------- media- thumbnail sizes, default image
		$this->settings['thumbnail_heading'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'Media Settings', 
		'std'    => 'If you change any of the thumbnail settings, you may have to regenerate the image sizes to adjust existing media. We suggest installing the <a href="http://wordpress.org/plugins/regenerate-thumbnails/">Regenerate Thumbnails plugin</a>.',
		'type'    => 'heading'
		);
		
		$this->settings['thumb_w'] = array(
			'title'   => __( 'Thumbnail Images Width' ),
			'desc'    => __( 'Width of thumbnail images (in pixels)' ),
			'std'     => '640',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['thumb_h'] = array(
			'title'   => __( 'Thumbnail Images Height' ),
			'desc'    => __( 'Height of images in pixels' ),
			'std'     => '480',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['def_thumb'] = array(
			'title'   => __( 'Set default ' . lcfirst(ds106bank_option( 'thingname' )) . ' thumbnail image' ),
			'desc'    => __( 'This image will be used if none is defined.' ),
			'std'     => 'Default ' . lcfirst(ds106bank_option( 'thingname' )) . ' Thumbnail',
			'type'    => 'medialoader',
			'section' => 'general'
		);
		

		$this->settings['def_thumb_credits'] = array(
			'title'   => __( 'Thumbnail Attribution' ),
			'desc'    => __( 'Credit for default thumbnail (e.g. Creative Commons Attribution). HTML Ok.' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'general'
		);
		

		$this->settings['media_icon'] = array(
			'section' => 'general',
			'title'   => __( 'Embed Media For Icon' ),
			'desc'    => __( 'Embed example (if embeddable, e.g. YouTube/vimeo video, tweet) as icon on index and archive views' ),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array(
				'0' => 'No',
				'1' => 'Yes'
			)
		);		

		
		// ---------- creative commons options		
		$this->settings['cc_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Apply Creative Commons to each ' . ds106bank_option( 'thingname' ),
			'std'    => '',
			'type'    => 'heading'
		);
		
		$this->settings['use_cc'] = array(
			'section' => 'general',
			'title'   => __( 'Usage Mode' ),
			'desc'    => __( 'How licenses are applied' ),
			'type'    => 'radio',
			'std'     => 'site',
			'choices' => array(
				'none' => 'No Creative Commons',
				'site' => 'Apply one license to every ' . lcfirst(ds106bank_option( 'thingname' )),
				'user' => 'Enable users to choose license when submitting  a ' . lcfirst(ds106bank_option( 'thingname' ))
			)
		);
		
		$this->settings['cc_site'] = array(
			'section' => 'general',
			'title'   => __( 'License for Every ' . ds106bank_option( 'thingname' )),
			'desc'    => __( 'Choose a license that will appear sitewide' ),
			'type'    => 'select',
			'std'     => 'by',
			'choices' => array(
				'0' =>'CC0 Public Domain',
				'by' => 'CC BY Attribution',
				'by-sa' => 'CC Attribution-ShareAlike',
				'by-nd' => 'CC BY-ND Attribution-NoDerivs',
				'by-nc' => 'CC BY-NC Attribution-NonCommercial',
				'by-nc-sa' => 'CC BY-NC-SA	Attribution-NonCommercial-ShareAlike',
				'by-nc-nd' => 'CC BY-NC-ND Attribution-NonCommercial-NoDerivs',
			)
		);
		
		// ---------- rating options
		$this->settings['ratings_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => ds106bank_option( 'thingname' ) . ' Ratings',
			'std'    => bank106_wp_ratings_installed(),
			'type'    => 'heading'
		);

		$this->settings['difficulty_rating'] = array(
			'section' => 'general',
			'title'   => __( 'Allow Author Difficulty Rating' ),
			'desc'    => __( 'When a new ' . lcfirst(ds106bank_option( 'thingname' )) . ' is created, author assigns a 1-5 difficulty rating shown on the entry' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		// ---------- example setup options
		$this->settings['examples_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Settings for ' . ds106bank_option( 'thingname' ) . ' Responses and Tutorials',
			'std'    => '',
			'type'    => 'heading'
		);

		$this->settings['show_ex'] = array(
			'section' => 'general',
			'title'   => __( 'Display on Single ' . ds106bank_option( 'thingname' ) ),
			'desc'    => __( 'Enable display of associated responses and/or tutorials on the single item view' ),
			'type'    => 'radio',
			'std'     => 'both',
			'choices' => array(
				'both' => 'Responses and Tutorials',
				'ex' => 'Responses only',
				'tut' => 'Tutorials only',
				'none' => 'Neither'
			)
		);

		$this->settings['examplesperview'] = array(
			'section' => 'general',
			'title'   => __( 'Number of Responses to Display at a Time' ),
			'desc'    => __( 'How many to show per click of \'More\' button. ' . ds106bank_alm_installed() ),
			'std'     => '10',
			'type'    => 'text',
			'section' => 'general'
		);	

		

		$this->settings['example_via_form'] = array(
			'section' => 'general',
			'title'   => __( 'Submit responses directly' ),
			'desc'    => __( 'Allow visitors to submit responses via web form' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['link_examples'] = array(
			'section' => 'general',
			'title'   => __( 'Link to Form Submitted Responses'),
			'desc'    => __( 'Link to responses external or show as entry (for longer type responses)'),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'1' => 'No, links go to response URL',
							'0' => 'Yes, links go to entry on the bank site',
					)
		);

		$this->settings['example_gen'] = array(
			'title'   => __( 'Response Form Instructions' ),
			'desc'    => __( 'Any additional details that should be added to the entry form for every submitted response (' . ds106bank_option( 'thingname' ) . ' specific instructions can also be added per item).' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'general'
		);


 		// Build array to hold options for select, an array of published pages on the site
	  	$page_options = array( '--' => 'Select Page');

		 // walk pages
	  	$all_pages = get_pages(); 
		foreach ( $all_pages as $item ) {
  			$page_options[$item->post_name] =  $item->post_title;
  		}
 
		$this->settings['example_form_page'] = array(
			'section' => 'general',
			'title'   => __( 'Page for Adding Responses/Tutorials'),
			'desc'    => __( 'Existing page for form to add new responses; one using <strong>Submit Response/Tutorial Form</strong> as template' ),
			'type'    => 'select',
			'std'     => '--',
			'choices' => $page_options
		);	

		$this->settings['new_example_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Responses' ),
			'desc'    => __( 'How new responses added are processed when submitted via a form' ),
			'type'    => 'radio',
			'std'     => 'draft',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);
					
		$this->settings['helpthingname'] = array(
			'title'   => __( 'Name for Support Things' ),
			'desc'    => __( 'What is the name for the kind of support offerings for each Thing? e.g. Tutorial? Resource? Link? This must be singular, and first letter should be capitalized.' ),
			'std'     => 'Tutorial',
			'type'    => 'text',
			'section' => 'general'
		);


		// ---------- login options		
		$this->settings['login_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Login Options',
			'std'    => '',
			'type'    => 'heading'
		);
		
		$this->settings['use_wp_login'] = array(
			'section' => 'general',
			'title'   => __( 'Use Wordpress accounts for adding responses and/or items to the bank.'),
			'desc'    => __( 'Option to use and/or require a login for submissions. An individual\'s work can be tracked via <code>' . home_url() . '/author/' . '&lt;username&gt;</code>'),
			'type'    => 'radio',
			'std'     => '0',
			'choices' => array (
							'0' => 'Do not use Wordpress logins',
							'1' => 'Yes, but make it optional',
							'2' => 'Yes, and make it required'
					)
		);
		
		// ---------- syndication options
		$this->settings['syndication_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Syndication for Responses',
			'std'    => 'Choose how/if to use Feed WordPress to aggregate responses from either an internal aggregator on this site or from an external source.',
			'type'    => 'heading'
		);
			
		$this->settings['use_fwp'] = array(
			'section' => 'general',
			'title'   => __( 'Feed Wordpress Mode' ),
			'desc'    => bank106_fwp_installed() . __( 'See documentation tab for more information on these options.' ),
			'type'    => 'radio',
			'std'     => 'none',
			'choices' => array(
				'none' => 'No syndication. Responses are added only via web form (if enabled above) or only via WordPress Admin.',
				'internal' => 'Use a local install of Feed Wordpress to aggregate responses to this site.',
				'external' => 'Syndicate from an external site that is already aggregating participant content.', 
			)
		);

		// settings only if external syndication mode 	
		$this->settings['extra_tag'] = array(
			'title'   => __( 'Required Tag' ),
			'desc'    => __( 'Only used for external syndication option. Tag for responses to be externally syndicated. This will be displayed on each assignment and will also define the feed that needs to be added to local install of Feed WordPress.' ),
			'std'     => 'bank106',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['syndication_site_name'] = array(
			'title'   => __( 'External Syndication Site ' ),
			'desc'    => __( 'Only used for external syndication option.' ),
			'std'     => 'Groovy Syndication Site Name',
			'type'    => 'text',
			'section' => 'general'
		);

		$this->settings['syndication_site_url'] = array(
			'title'   => __( 'External Syndication Site URL' ),
			'desc'    => __( 'Only used for external syndication option.' ),
			'std'     => 'http://',
			'type'    => 'text',
			'section' => 'general'
		);
		
		// ---------- captcha options, if we need 'em
		
		$this->settings['captcha_heading'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'Captcha Settings', 
		'std'    => 'To reduce spam activate a captcha for submission forms',
		'type'    => 'heading'
		);		
		
		$this->settings['use_captcha'] = array(
			'section' => 'general',
			'title'   => __( 'Use reCaptcha' ),
			'desc'    => __( 'Activate a google captcha for all submission forms; <a href="https://www.google.com/recaptcha/admin/create" target="_blank">get your access keys</a>' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['captcha_style'] = array(
		'section' => 'general',
		'title'   => __( 'Captcha Style' ),
		'desc'    => __( 'Visual style for captchas, see <a href="https://developers.google.com/recaptcha/docs/customization?csw=1" target="_blank">examples of styles</a>.' ),
		'type'    => 'select',
		'std'     => 'red',
		'choices' => array(
			'red' => 'Red',
			'white' => 'White',
			'blackglass' => 'Black',
			'clean' => 'Clean',
		)
	);
	
		$this->settings['captcha_pub'] = array(
			'title'   => __( 'reCaptcha Public Key' ),
			'desc'    => __( '' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['captcha_pri'] = array(
			'title'   => __( 'reCaptcha Private Key' ),
			'desc'    => __( '' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general'
		);

		
				
		/* Types of Things Settings
		===========================================*/

		
		$this->settings['thing_type_heading'] = array(
			'section' => 'types',
			'title' 	=> '' ,// Not used for headings.
			'desc'   =>  ds106bank_option( 'type_name' ) . ' Organization', 
			'std'    => 'Identify different types of ' . lcfirst( ds106bank_option( 'pluralthings' ) ) . ' and how they are ordered in index pages',
			'type'    => 'heading'
		);		

		$this->settings['type_name'] = array(
			'title'   => __( 'Label for the Types of Things' ),
			'desc'    => __( 'Things are organized into groups defined below. What should the name for these? (singular)' ),
			'std'     => 'Type',
			'type'    => 'text',
			'section' => 'types'
		);		

		// note, this is misnamed, it ought to be "type_order" Sue me.
 		$this->settings['thing_order'] = array(
			'section' => 'types',
			'title'   => __( 'Type Display Order' ),
			'desc'    => __( 'On the front page, the order in which types of things are listed' ),
			'type'    => 'radio',
			'std'     => 'name',
			'choices' => array(
				'name' => 'Title',
				'id' => 'Date Created',
				'count' => 'Count',
			)
		);		

	// note, this is misnamed, it ought to be "type_orderny" Sue me again.
 		$this->settings['thing_orderby'] = array(
			'section' => 'types',
			'title'   => __( 'Type Order Sorting' ),
			'desc'    => __( 'On the front page, which to list first?' ),
			'type'    => 'radio',
			'std'     => 'ASC',
			'choices' => array(
				'ASC' => 'Ascending',
				'DESC' => 'Descending',
			)
		);	
		
	
		$this->settings['exlen'] = array(
			'title'   => __( 'Excerpt Length' ),
			'desc'    => __( 'Number of words to show for the descriptions of the types of things used on the front page and archives.' ),
			'std'     => '55',
			'type'    => 'text',
			'section' => 'types'
		);
				

		$this->settings['type_mod_heading'] = array(
			'section' => 'types',
			'title' 	=> '' ,// Not used for headings.
			'desc'   =>  ds106bank_option( 'type_name' ) . ' Editing', 
			'std'    => 'Edit names, desciptions, and thumbnail images',
			'type'    => 'heading'
		);		
	
		
		// lets get all the existing assignment types
		$assigntypes = get_assignment_types( ds106bank_option( 'thing_order'), ds106bank_option( 'thing_orderby') );
		
		$i = 0;

		foreach ( $assigntypes as $atype ) {
			$i++;
			$setting_name = 'thing_type_' . $atype->term_id;
				
			//  settings for each type of thing: name, delete option, description, thumbnail 			
			$this->settings["$setting_name"] = 
				array(
					'title'   => __( ds106bank_option( 'thingname' ) . ' Type #' . $i ),
					'desc'    => __( '' ),
					'std'     =>  '',
					'type'    => 'text',
					'section' => 'types'
				);
			
			$this->settings['del_' . $setting_name] = array(
				'section' => 'types',
				'title'   => __( 'Delete this Type' ),
				'desc'    => __( 'Be careful, this cannot be undone!'),
				'type'    => 'checkbox',
				'std'     => 0 
			);
				
			$this->settings[$setting_name . '_descrip' ] = 
				array(
					'title'   => __( 'Short Description of this Type' ),
					'desc'    => __( '' ),
					'std'     =>  '',
					'type'    => 'textarea',
					'section' => 'types'
				);
				
			$this->settings[$setting_name . '_thumb'] = 
				array(
					'title'   => __( ucfirst($atype->name) . ' Thumbnail' ),
					'desc'    => __( '<hr /><p>&nbsp;</p>' ),
					'std'     =>  'http://placehold.it/' . ds106bank_option('thumb_w') . 'x' . ds106bank_option('thumb_h'),
					'type'    => 'medialoader',
					'section' => 'types'
				);
			
		}
	
		// ------- field so users can add new types of things
		$this->settings['new_type_heading'] = array(
			'section' => 'types',
			'title'   => '', // Not used for headings.
			'desc'    => 'Create New Types',
			'std'	  => 'Description and thumbnails can be added after saving changes. If one of the same name exists, a repeated name will be ignored.', 
			'type'    => 'heading'
		);
		
		$this->settings['new_types'] = array(
			'title'   => __( 'Names for New Types' ),
			'desc'    => __( 'Enter new names, one per line' ),
			'std'     => 'Name of A New Type',
			'type'    => 'textarea',
			'section' => 'types'
	);
			
		/* Reset checkbox
		===========================================*/
		// ------- field so users can add new types of things
		$this->settings['reset_heading'] = array(
			'section' => 'reset',
			'title'   => '', // Not used for headings.
			'desc'    => 'With Great Power Comes...',
			'std'	  => 'Be careful with this powerful checkbox, there is no undoing.', 
			'type'    => 'heading'
		);
		
		
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset Options' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box then click "Save Changes" below to reset all options to their defaults.' )
		);
	}

	public function display_general() {
		// section heading for general setttings
		echo '<p>These settings manage the behavior and appearance of your bank. Here they may be referred to as "Things" that are organized into "Types." As you can see there are quite a few of knobs here to twiddle!</p>';		
	}


	public function display_types() {
		// section heading for assignment type setttings
		echo '<p>Add and edit the titles, icons, and descriptions for the types of things in your bank. You can also organize how they are ordered when listed and also change the name of them from "Type" to a more approporiate name for the groups of Things.</p>';

	}

	public function display_reset() {
		// section heading for reset section setttings, none needed
	}



	/* HTML output for individual settings */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'ds106banker_options' );

		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;

		$options['new_types'] = 'New Type Name'; // always reset
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
			
			
		switch ( $type ) {
		
			case 'heading':
				echo '<tr><td colspan="2" class="alternate"><h3 id="' . $id . '">' . $desc . '</h3><p>' . $std . '</p></td></tr>';
				break;

			case 'checkbox':
			
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="ds106banker_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="ds106banker_options[' . $id . ']">';

				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

				echo '</select>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;

			case 'radio':
				if ( $desc != '' )
					echo '<span class="description">' . $desc . '</span><br /><br />';
					
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="ds106banker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
					
				break;

			case 'textarea':
			
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="ds106banker_options[' . $id . ']" placeholder="' . $std . '" rows="5" style="width:80%">' . format_for_editor( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;
				
			case 'medialoader':
					
				if ( strpos ( $options[$id], 'http') !==false ) {
					echo '<img id="previewimage_' . $id . '" src="' . $options[$id] . '" width="' . (ds106bank_option('thumb_w') / 2) . '" height="' . (ds106bank_option('thumb_h') / 2) . '" alt="default thumbnail" />';
				} else {
					echo '<img id="previewimage_' . $id . '" src="http://placehold.it/' . (ds106bank_option('thumb_w') / 2) . 'x' . (ds106bank_option('thumb_h') / 2) . '" alt="default thumbnail" />';
				}

				echo '<input type="hidden" name="ds106banker_options[' . $id . ']" id="' . $id . '" value="' . esc_attr( $options[$id] ) . '" />
  <br /><input type="button" class="upload_image_button button-primary" name="_ds106banker_button' . $id .'" id="_ds106banker_button' . $id .'" data-options_id="' . $id  . '" data-uploader_title="Set ' .  ds106bank_option( 'thingname' ) . ' Thumbnail" data-uploader_button_text="Select Thumbnail" value="Set/Change Thumbnail" />
</div><!-- uploader -->';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'password':
				echo '<label for="my-text-field">' . $title . '</label><input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="ds106banker_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'text':
			default:
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '"     name="ds106banker_options[' . $id . ']"   placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
 
 
				if ( $desc != '' ) {
	
					if ($id == 'def_thumb') $desc .= '<br /><a href="' . $options[$id] . '" target="_blank"><img src="' . $options[$id] . '" style="overflow: hidden;" width="' . $options["index_thumb_w"] . '"></a>';
					
				echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;
		}
	}	

	/* Initialize settings to their default values */
	public function initialize_settings() {
	
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
	
		update_option( 'ds106banker_options', $default_settings );
	
	}


	/* Register settings via the WP Settings API */
	public function register_settings() {

		register_setting( 'ds106banker_options', 'ds106banker_options', array ( &$this, 'validate_settings' ) );

		// Add all the sections, with appropriate callback functions
		foreach ( $this->sections as $slug => $title ) {
			add_settings_section( $slug, $title, array( &$this, $this->section_callbacks[$slug] ), 'ds106bank-options' );
		}

		$this->get_settings();
	
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}

	}
	
	
	/* tool to create settings fields */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => 'Default Field',
			'desc'    => 'This is a default description.',
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);

		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
				

		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'ds106bank-options', $section, $field_args );

	}
	
	
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'ds106bank_options' );
				
			// has the thing name changed? If so we need to update the taxonmy terms
			if ( $input['thingname'] !=  ds106bank_option( 'thingname' )  ) {
				bank106_update_tax( ds106bank_option( 'thingname' ), $input['thingname'] );
			}
			
			// has thumbnail sizes changed? If so, update option for thumbnail sizes
			if ( $input['thumb_w'] !=   ds106bank_option( 'thumb_w' )  ) {
				update_option('thumbnail_size_w', $input['thumb_w']);
			}
			
			if ( $input['thumb_h'] !=  ds106bank_option( 'thumb_h' )  ) {
				update_option('thumbnail_size_h', $input['thumb_h']);
			}
			
			// if we new types list, add to assignmemttypes taxonomy
			if ( $input['new_types'] !=  'New Type Name'  ) {		
				bank106_add_new_types( $input['new_types'] );
			}
			
			// check for types to be deleted
			$assigntypes = get_assignment_types();
			
			// updates for thing types
			foreach ( $assigntypes as $atype ) {
				// name of setting
				$setting_name = 'thing_type_' . $atype->term_id;
								
				if ( $input['del_' . $setting_name] == 1 ) {
					// delete term if box is checked
					wp_delete_term( $atype->term_id, 'assignmenttypes');
				} else {
					// update terms (whether changed or not, sigh, this seems easier)
					wp_update_term( $atype->term_id, 'assignmenttypes', array(
					  'name' => $input[$setting_name],
					  'slug' => sanitize_title( $input[$setting_name]),
					  'description' => $input[$setting_name . '_descrip']
					));
				}
			}

			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) )
					unset( $options[$id] );
			}
			
			return $input;
		}
		
		return false;	
	}
 }
 
 
$theme_options = new ds106bank_Theme_Options();

function ds106bank_option( $option ) {
	$options = get_option( 'ds106banker_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}
?>