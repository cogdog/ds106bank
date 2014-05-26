<?php
// manages all of the theme options
// heavy lifting via http://alisothegeek.com/2011/01/wordpress-settings-api-tutorial-1/

class ds106bank_Theme_Options {

	/* Array of sections for the theme options page */
	private $sections;
	private $checkboxes;
	private $settings;

	/* Initialize */
	function __construct() {

		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		
		//$this->bank106_init();
		$this->get_settings();
		
		$this->sections['general'] = __( 'General Settings' );
		$this->sections['types']   = __( ds106bank_option( 'thingname' ) . ' Types' );
		$this->sections['docs']        = __( 'Documentation' );
		$this->sections['reset']   = __( 'Reset to Defaults' );

		// enqueue scripts for media uploader
        add_action( 'admin_enqueue_scripts', 'ds106bank_enqueue_options_scripts' );
		
		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'ds106banker_options' ) )
			$this->initialize_settings();
	}

	/* Add page(s) to the admin menu */
	public function add_pages() {
		$admin_page = add_theme_page( 'Assignment Bank Options', 'Assignment Bank Options', 'manage_options', 'ds106bank-options', array( &$this, 'display_page' ) );
		
		// give us javascript for this page
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		
		// and some pretty styling
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
	}

	/* HTML to display the theme options page */
	public function display_page() {
		echo '<div class="wrap">
		<div class="icon32" id="icon-options-general"></div>
		<h2>Assignment Bank Options</h2>';
		
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
				
		echo '<form action="options.php" method="post" enctype="multipart/form-data">';

			settings_fields( 'ds106banker_options' );
			echo '<div class="ui-tabs">
				<ul class="ui-tabs-nav">';

			foreach ( $this->sections as $section_slug => $section )
				echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

			echo '</ul>';
			do_settings_sections( $_GET['page'] );

			echo '</div>
			<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>

		</form>';
		echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
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
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
			         
		
				//  via http://stackoverflow.com/a/14467706/2418186
	
				//  jQueryUI 1.10 and HTML5 ready
				//      http://jqueryui.com/upgrade-guide/1.10/#removed-cookie-option 
				//  Documentation
				//      http://api.jqueryui.com/tabs/#option-active
				//      http://api.jqueryui.com/tabs/#event-activate
				//      http://balaarjunan.wordpress.com/2010/11/10/html5-session-storage-key-things-to-consider/
				//
				//  Define friendly index name
				var index = "key";
				//  Define friendly data store name
				var dataStore = window.sessionStorage;
				//  Start magic!
				try {
					// getter: Fetch previous value
					var oldIndex = dataStore.getItem(index);
				} catch(e) {
					// getter: Always default to first tab in error state
					var oldIndex = 0;
				}
				$(".ui-tabs").tabs({
					// The zero-based index of the panel that is active (open)
					active : oldIndex,
					// Triggered after a tab has been activated
					activate : function( event, ui ){
						//  Get future value
						var newIndex = ui.newTab.parent().children().index(ui.newTab);
						//  Set future value
						dataStore.setItem( index, newIndex ) 
					}
				}); 
					 
			});
	</script>
</div>';	
	}
			
		/* Insert custom CSS */
		public function styles() {

			wp_register_style( 'ds106bank-admin', get_stylesheet_directory_uri() . '/ds106bank-options.css' );
			wp_enqueue_style( 'ds106bank-admin' );

		}

	/* Define all settings and their defaults */
	public function get_settings() {
	
		/* General Settings
		===========================================*/
	
		$this->settings['thingname'] = array(
			'title'   => __( 'Name for Things in the Bank' ),
			'desc'    => __( 'What is the name for the kind of thing banked here? Assignment? Challenge? Task? Must be singular and should not contain an numbers (0-9).' ),
			'std'     => 'Assignment',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['new_thing_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Things' ),
			'desc'    => __( 'Set to draft to moderate submissions via web form' ),
			'type'    => 'radio',
			'std'     => 'publish',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);		
 
 		$this->settings['thing_order'] = array(
			'section' => 'general',
			'title'   => __( 'Display Order' ),
			'desc'    => __( 'On the main index, the order in which ' . lcfirst(THINGNAME) . 's are listed' ),
			'type'    => 'radio',
			'std'     => 'name',
			'choices' => array(
				'name' => 'Title',
				'id' => 'Date Created',
				'count' => 'Count',
			)
		);		

 		$this->settings['thing_orderby'] = array(
			'section' => 'general',
			'title'   => __( 'Display Order Sorting' ),
			'desc'    => __( 'Which to list first?' ),
			'type'    => 'radio',
			'std'     => 'ASC',
			'choices' => array(
				'ASC' => 'Ascending',
				'DESC' => 'Descending',
			)
		);		

		$this->settings['exlen'] = array(
			'title'   => __( 'Excerpt Length' ),
			'desc'    => __( 'Number of words to show for content when displayed on an index or archive page' ),
			'std'     => '55',
			'type'    => 'text',
			'section' => 'general'
		);
		
		
		// ------- captcha options
		
		$this->settings['captcha_heading'] = array(
		'section' => 'general',
		'title' 	=> '' ,// Not used for headings.
		'desc'   => 'Captcha Settings', 
		'std'    => 'To reduce spamm activate a captcha for submission forms',
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


		// ------- media- thumbnaiil sizes, default image
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
			'std'     => '320',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['thumb_h'] = array(
			'title'   => __( 'Thumbnail Images Height' ),
			'desc'    => __( 'Height of images in pixels' ),
			'std'     => '240',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['def_thumb'] = array(
			'title'   => __( 'Set default ' . lcfirst(THINGNAME) . ' thumbnail image' ),
			'desc'    => __( 'This image will be used if none is defined.' ),
			'std'     => 'Default ' . lcfirst(THINGNAME) . ' Thumbnail',
			'type'    => 'medialoader',
			'section' => 'general'
		);
		
		
		// ------- creative commons options		
		$this->settings['cc_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Apply Creative Commons to ' . THINGNAME . 's',
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
				'site' => 'Apply one license to all ' . lcfirst(THINGNAME) . 's',
				'user' => 'Enable users to choose license when submitting  a ' . lcfirst(THINGNAME)
			)
		);
		
		$this->settings['cc_site'] = array(
			'section' => 'general',
			'title'   => __( 'License for All ' . THINGNAME . 's'),
			'desc'    => __( 'Choose a license that will appear sitewide' ),
			'type'    => 'select',
			'std'     => 'by',
			'choices' => array(
				'by' => 'CC BY Attribution',
				'by-sa' => 'CC Attribution-ShareAlike',
				'by-nd' => 'CC BY-ND Attribution-NoDerivs',
				'by-nc' => 'CC BY-NC Attribution-NonCommercial',
				'by-nc-sa' => 'CC BY-NC-SA	Attribution-NonCommercial-ShareAlike',
				'by-nc-nd' => 'CC BY-NC-ND Attribution-NonCommercial-NoDerivs',
			)
		);
		
		$this->settings['ratings_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => THINGNAME . ' Ratings',
			'std'    => bank106_wp_ratings_installed(),
			'type'    => 'heading'
		);


		// ------- example setup options
		$this->settings['examples_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Settings for ' . THINGNAME . ' Examples',
			'std'    => '',
			'type'    => 'heading'
		);
		
		$this->settings['example_via_form'] = array(
			'section' => 'general',
			'title'   => __( 'Submit examples directly' ),
			'desc'    => __( 'Allow visitors to submit examples via web form' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
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
			'title'   => __( 'Page for Adding Examples/Tutorials'),
			'desc'    => __( 'Existing page for form to add new examples; one using <strong>Submit Example/Tutorial Form</strong> as template' ),
			'type'    => 'select',
			'std'     => '--',
			'choices' => $page_options
		);	


		$this->settings['new_example_status'] = array(
			'section' => 'general',
			'title'   => __( 'Status For New Examples' ),
			'desc'    => __( 'How new examples added are processed when submitted via a form' ),
			'type'    => 'radio',
			'std'     => 'draft',
			'choices' => array(
				'publish' => 'Publish immediately',
				'draft' => 'Set to draft',
			)
		);

		// ------- syndication options
		$this->settings['syndication_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'	 => 'Syndication for Examples',
			'std'    => 'Choose how/if to use Feed WordPress to aggregate examples from either an internal aggregator on this site or from an external source.',
			'type'    => 'heading'
		);
			
		$this->settings['use_fwp'] = array(
			'section' => 'general',
			'title'   => __( 'Feed Wordpress Mode' ),
			'desc'    => bank106_fwp_installed() . __( 'See documentation tab for more information on these options.' ),
			'type'    => 'radio',
			'std'     => 'none',
			'choices' => array(
				'none' => 'No syndication. Examples are added only via web form (if enabled above) or only via WordPress Admin.',
				'internal' => 'Use a local install of Feed Wordpress to aggregate examples to this site.',
				'external' => 'Syndicate from an external site that is already aggregating participant content.', 
			)
		);

		// settings only if external syndication mode 	
		$this->settings['extra_tag'] = array(
			'title'   => __( 'Required Tag' ),
			'desc'    => __( 'Only used for external syndication option. Tag for examples to be externally syndicated. This will be displayed on each assignment and will also define the feed that needs to be added to local install of Feed WordPress.' ),
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
				
		/* Types of Things Settings
		===========================================*/
		
		// lets get all the existing assignment types
		$assigntypes = get_assignment_types( ds106bank_option( 'thing_order'), ds106bank_option( 'thing_orderby') );
		$i = 0;
		
		foreach ( $assigntypes as $atype ) {
			$i++;
			$setting_name = 'thing_type_' . $atype->term_id;
			
			
			
			// ------- settings for each type of thing: name, delete option, description, thumbnail 			
			$this->settings["$setting_name"] = 
				array(
					'title'   => __( THINGNAME . ' Type #' . $i ),
					'desc'    => __( '' ),
					'std'     =>  $atype->name,
					'type'    => 'text',
					'section' => 'types'
				);
			
			$this->settings['del_' . $setting_name] = array(
				'section' => 'types',
				'title'   => __( 'Delete this type' ),
				'desc'    => __( 'Be careful, this cannot be undone!'),
				'type'    => 'checkbox',
				'std'     => 0 
			);
				
			$this->settings[$setting_name . '_descrip' ] = 
				array(
					'title'   => __( 'Short Description'),
					'desc'    => __( '' ),
					'std'     =>  $atype->description,
					'type'    => 'textarea',
					'section' => 'types'
				);
				
			$this->settings[$setting_name . '_thumb'] = array(
			'title'   => __( ucfirst($atype->name) . ' Thumbnail' ),
			'desc'    => __( '<hr /><p>&nbsp;</p>' ),
			'std'     =>  'http://placehold.it/' . THUMBW . 'x' . THUMBH,
			'type'    => 'medialoader',
			'section' => 'types'
			);
			
		}
		
		// ------- field so users can add new types of things
		$this->settings['new_type_heading'] = array(
			'section' => 'types',
			'title'   => '', // Not used for headings.
			'desc'    => 'Create New ' . THINGNAME . ' Types',
			'std'	  => 'Description and thumbnails can be added after saving changes. If the type already exists, a repeated name will be ignored.', 
			'type'    => 'heading'
		);
		
		$this->settings['new_types'] = array(
			'title'   => __( 'Names for new type(s)' ),
			'desc'    => __( 'Enter new names, one per line' ),
			'std'     => 'New Type Name',
			'type'    => 'textarea',
			'section' => 'types'
	);
			
		/* Reset
		===========================================*/
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset Options' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset assignment bank options to their defaults.' )
		);

		
	}
	
	/* Description for section */
	public function display_section() {
		// code
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
				echo '</td></tr><tr valign="top"><td colspan="2"><h4 style="margin-bottom:0;">' . $desc . '</h4><p style="margin-top:0">' . $std . '</p>';
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
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="ds106banker_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="ds106banker_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;
				
			case 'medialoader':
			
			
				echo '<div id="uploader_' . $id . '">';
				
				if ( strpos ( $options[$id], 'http') !==false ) {
					echo '<img id="previewimage_' . $id . '" src="' . $options[$id] . '" width="' . THUMBW . '" height="' . THUMBH . '" alt="default thumbnail" />';
				} else {
					echo '<img id="previewimage_' . $id . '" src="http://placehold.it/' . THUMBW . 'x' . THUMBH . '" alt="default thumbnail" />';
				}

				echo '<input type="hidden" name="ds106banker_options[' . $id . ']" id="' . $id . '" value="' . esc_attr( $options[$id] ) . '" />
  <br /><input type="button" class="upload_image_button button-primary" name="_ds106banker_button' . $id .'" id="_ds106banker_button' . $id .'" data-options_id="' . $id  . '" data-uploader_title="Set ' .  THINGNAME . ' Thumbnail" data-uploader_button_text="Select Thumbnail" value="Set/Change Thumbnail" />
</div><!-- uploader -->';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="ds106banker_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';

				break;

			case 'text':
			default:
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="ds106banker_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';

				if ( $desc != '' ) {
				
					if ($id == 'def_thumb') $desc .= '<br /><a href="' . $options[$id] . '" target="_blank"><img src="' . $options[$id] . '" style="overflow: hidden;" width="' . $options["index_thumb_w"] . '"></a>';
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;
		}
	}	
			


	/**
	 * Description for Docs section
	 *
	 * @since 1.0
	 */
	public function display_docs_section() {
		
		// This displays on the "Documentation" tab. 
		
		include( get_stylesheet_directory() . '/includes/ds106bank-theme-options-docs.php');
		
		
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
		//register_setting( 'ds106banker_options', 'ds106banker_options' );

		foreach ( $this->sections as $slug => $title )
		
			if ( $slug == 'docs' ) {
				add_settings_section( $slug, $title, array( &$this, 'display_docs_section' ), 'ds106bank-options' );
			} else {
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'ds106bank-options' );
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
	
	
	/* jQuery Tabs */
	public function scripts() {
		wp_print_scripts( 'jquery-ui-tabs' );
	}
	
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'ds106bank_options' );
				
			// has the thing name changed? If so we need to update the taxonmy terms
			if ( $input['thingname'] !=  THINGNAME  ) {
						
			// return ( substr( $thing, 0, -1 ) );
				bank106_update_tax( THINGNAME, $input['thingname'] );
			}
			
			// has thumbnail sizes changed? If so, update option for thumbnail sizes
			if ( $input['thumb_w'] !=  THUMBW  ) {
				update_option('thumbnail_size_w', $input['thumb_w']);
			}
			
			if ( $input['thumb_h'] !=  THUMBH  ) {
				update_option('thumbnail_size_h', $input['thumb_h']);
			}
			
			// has page media width changed? if so, update options for medium sized images
			if ( $input['page_media_width'] !=  MEDIAW  ) {
				update_option('medium_size_w', $input['page_media_width']);
				update_option('medium_size_h', $input['page_media_width']);
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