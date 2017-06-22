<?php
/*
Template Name: Submit Response/Tutorial Form

Lets visitors add a new response or tutorial/resource for an assignment via a web form.
Needs quite a bit of error checking to catch all the crazy things people might
pop into a form. Now with reCaptcha.
*/


// enqueue jquery for tutorial types
add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_add_ex_scripts' );
	
// type of submissions (in query string)
$typ = $wp_query->query_vars['typ']; 

// flag to NOT use rich text editor and full preview features (default)	
$use_full_editor = false;

// holder for boo-boos
$errors = array();

// keep track of the kind of submission		
if 	($typ == 'tut') {
	// enqueue jquery for simple text editor and preview
	add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_simpletext_scripts' );

	$sub_type = strtolower( ds106bank_option('helpthingname') );
	$feedback_msg = '<div class="alert alert-info" role="alert">Enter all of information below to describe a' . $sub_type . ' that would be helpful to someone else who responds to this '  . THINGNAME . '. It must be something available on the web at a public URL. ';


} else {

	// enqueue jquery for responses
	add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_richtext_scripts' );

	$sub_type = 'response';
	
	if ( ds106bank_option('link_examples') == 1 ) {

		// enqueue jquery for simple text editor and preview
		add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_simpletext_scripts' );

		// simple editor and instructions
		$feedback_msg = '<div class="alert alert-info" role="alert">Enter all information in the form below to share your response to this ' . THINGNAME . '. Then <a href="#" class="btn btn-primary btn-xs disabled">update</a> the information to  verify that is entered correctly. Once entered, <a href="#" class="btn btn-success btn-xs disabled">submit</a> so it will be saved as an example for others to see.';

	} else {
		
		// enqueue jquery for rich text editor and preview
		add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_richtext_scripts' );

		// flag to use rich text editor and full preview features
		$use_full_editor = true;
	
		$feedback_msg = '<div class="alert alert-info" role="alert">Enter all information in the form below and <a href="#" class="btn btn-primary btn-xs disabled">update</a> to verify that is entered correctly. Then you can modify and <a href="#" class="btn btn-warning btn-xs disabled">preview</a> as much as necessary to finalize the entry. When satisfied, <a href="#" class="btn btn-success btn-xs disabled">submit</a> the form  and it will be saved to this site.';
		
	}
}



// disable buttons
$previewBtnState = ' disabled';
$submitBtnState = ' disabled';

// assignment id
$aid = $wp_query->query_vars['aid']; 

// start with nothing, honey
$exampleURL = '';

if ( is_user_logged_in() ) {
	//bypass captcha for logged in users
	$use_captcha = false;
	
	$current_user = wp_get_current_user();
	
	$feedback_msg .= '<br /><br />All responses submitted will be associated with your current login in as <strong>' . $current_user->display_name . '</strong>.' ;

	
} else {
	// set up captcha if set as option;
	$use_captcha = ds106bank_option('use_captcha');
	
	
	if ( ds106bank_option('use_wp_login') == 1) {
		// WP login optional
		
		
		$feedback_msg .= '<br /><br />Logging in to this site is not required, but if you wish to have all your responses associated with your name ' . sprintf( '<a href="%s">%s</a>', wp_login_url( get_permalink( $aid ) ), __( 'sign in now' ) );  
	
	// WP login requred
	} elseif ( ds106bank_option('use_wp_login') == 2 ) {
		$must_login = true;
		
		// feedback message now an alert
		$feedback_msg = '<div class="alert alert-danger" role="alert"> You must sign in to ' . bloginfo( 'name' ) . ' to add your response to this ' . THINGNAME . '. Return to <a href="' . get_permalink( $aid ) . '">' . get_the_title( $aid ) . '</a> to login so you can add your response here.';
	}
		
}

// close dat div
$feedback_msg .=  '</div>';

// include captch lib if we need to
if ($use_captcha) require_once( get_stylesheet_directory() . '/includes/recaptchalib.php');

// status for new submissions
$my_new_status = ds106bank_option( 'new_example_status' );

// flag for using/requring twitter on form
$use_twitter_name = ds106bank_option( 'use_twitter_name' );
$submitterTwitter = $_COOKIE["bank106twitter"];

// more cookies
$submitterName = $_COOKIE["bank106name"];
$submitterEmail = $_COOKIE["bank106email"];

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_example_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_example_submitted'], 'bank106_form_add_example' ) ) {
 
 		// grab the variables from the form
 		$exampleTitle = 			stripslashes( sanitize_text_field( $_POST['exampleTitle'] ) );		
 		$submitterName = 			stripslashes( sanitize_text_field( $_POST['submitterName'] ) ); 
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$exampleDescription = 		stripslashes($_POST['exampleDescription']);
 		$exampleURL = 				esc_url( trim($_POST['exampleURL']), array('http', 'https') ); 
 		$exampleTags = 				cleanTags( sanitize_text_field( $_POST['exampleTags'] ) );

 		if ($use_twitter_name) {
 		
 			$submitterTwitter = sanitize_text_field( trim( $_POST['submitterTwitter'] ) );  
 			// set a cookie
 			setcookie( "bank106twitter", $submitterTwitter, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */
 		}
 		
 		// more cookies to store
 		setcookie( "bank106name", $submitterName, strtotime( '+14 days' ), '/' );  /* expire in 14 days */
 		setcookie( "bank106email", $submitterEmail, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */

 		
 		$my_assignment_tag = THINGNAME . $aid;
		$my_tutorial_tag = 'Tutorial' . $aid;
			
 		// let's do some validation, story an error message for each problem found

 		
 		if ( $exampleTitle == '' ) $errors['exampleTitle'] = '<span class="label label-danger">Title Missing</span> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
 		if ( $use_twitter_name ) {
 			if ( $submitterTwitter == '' and  $use_twitter_name == 2) {
 				$errors['submitterTwitter'] = '<span class="label label-danger">Twitter Name Missing</span> - please enter your twitter user name, it is required.';
 			}
 			
 			if ( strpos( $submitterTwitter, '@') === false  ) {
 				$errors['submitterTwitter'] = '<span class="label label-danger">Missing @ in Twitter Name</span> - your twitter name must start with a "@"';
 			}
 			
			if ( strpos( $submitterTwitter, ',') !== false or  strpos( $submitterTwitter, ';') !== false or strpos( $submitterTwitter, ' ') !== false ) {
				$errors['submitterTwitter'] = '<span class="label label-danger">Multiple Twitter Names not Allowed</span> - Put the name of the person authoring this ' . $sub_type . ' in the Twitter Name field; if you wish to credit other people add their twitter names in the "Tags" field.';
 			} 	
 			
 			 if ( strpos( $submitterTwitter, '#') !== false  ) {
 				$errors['submitterTwitter'] = '<span class="label label-danger">Hashtags Not Allowed in Twitter Name Field</span> - only Twitter account names (they start with @) are allowed in this field';
 			}
		
 		
 		}
 		if ( $submitterEmail == '' ) {
 			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}
 		
 		// arbitrary string length to be considered a reasonable descriptions
 		if ( strlen( $exampleDescription ) < 10 )  $errors['exampleDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that describes this ' . $sub_type. '.';
 		 		
 		if ($exampleURL == '' AND $exampleURL != 'n/a' ) {
 				$errors['exampleURL'] = '<span class="label label-danger">URL Missing or not Entered Correctly</span>-  please enter the full URL where this ' . $sub_type . ' can be found- it must start with "http://"';	 
 		} // end url CHECK
 		
 		// check captcha
		if ( $use_captcha and isset( $_POST["recaptcha_response_field"]) ) {
				$resp = recaptcha_check_answer ( ds106bank_option('captcha_pri'),
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
				
				if ( !( $resp->is_valid ) ) {
					# set the error code so that we can display it
					$captcha_error = $resp->error;
					$errors[] = '<span class="label label-danger">Captcha Error</span>- please retry the captcha field.';
				}
		}
 				
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = '<div class="alert alert-danger" role="alert">Sorry, but there are a few errors in your entry. Please correct and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
 		
 			// set up stuff if we are just doing a preview
 			$previewBtnState = '';
 			$submitBtnState = '';
 			$feedback_msg = '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-thumbs-up""></span> The form information looks good! Now you can <a href="#" class="btn btn-warning btn-xs disabled">preview</a> your entry and continue to edit and then <a href="#" class="btn btn-success btn-xs disabled">submit</a> when you are ready.</div>';
 			
 			
 			$exampleDescription = oembed_filter($exampleDescription);
 			
 			// Now process form only if submit button used
 			if ( isset ( $_POST['submitexample'] ) ) {
 			
				// good enough, let's make a post! Or a custom post type
			
				$add_tags = explode("," , $exampleTags); // holder for tags
			
				// add a tag for twitter name, if provided
				if ( $use_twitter_name ) $add_tags[] = $submitterTwitter;
			
				$example_information = array(
					'post_title' => esc_attr( strip_tags( $_POST['exampleTitle'] ) ),
					'post_content' => $exampleDescription,
					'post_type' => 'examples',
					'post_status' => $my_new_status,			
				);

				// insert as a post type
				$post_id = wp_insert_post( $example_information );
		
				// check for success
				if ( $post_id ) {
				
					// set metadata, will use same custom fields as FWP
					update_post_meta( $post_id, 'syndication_source', esc_attr( $submitterName  ) );
					update_post_meta( $post_id, 'submitter_email', esc_attr( $submitterEmail  ) );
					
					// set the example tags
					if ( count($add_tags) ) wp_set_object_terms( $post_id, $add_tags, 'exampletags'); 
					
					if ( $submitterTwitter ) update_post_meta( $post_id, 'submitter_twitter', esc_attr( $submitterTwitter ) );
					
					
					update_post_meta( $post_id, 'example_url', $exampleURL ); // example url
					
					
						
					// add as syndication_permalink if we are linking directly to example URL or this is a tutorial
					if ( ds106bank_option('link_examples') == 1 OR $typ == 'tut' ) update_post_meta( $post_id, 'syndication_permalink', esc_url_raw( $_POST['exampleURL'] ) );
				
					if ($sub_type == 'response') {
						// set the tags for example
						wp_set_object_terms( $post_id, $my_assignment_tag, 'assignmenttags');
						
					} else {
						// set the tags for tutorial
						wp_set_object_terms( $post_id, $my_tutorial_tag, 'tutorialtags');
					}
									
					// create feedback success messages
				
					if ($use_full_editor) {
						// feedback includes a link to the full featured entry for a response
						
						// notice of new examples are put into draft or not
						$pending_msg = ( ds106bank_option('new_example_status') == 'draft' ) ? ', pending moderation, will be available at ' : ' is available now at ';
						 
						$feedback_msg = '<div class="alert alert-success" role="alert">Your new ' . $sub_type . ' response has been added' . $pending_msg .  '<a href="' . get_permalink(  $post_id ) . '" class="alert-link">' . get_permalink( $post_id ) . '</a>. Or you can return now to the <a href="' . get_permalink( $aid ) . '" class="alert-link">' . get_the_title( $aid ) . '</a> ' . THINGNAME . '.</div>'; 
					} else {
						// feedback link returns to the assignment
						
						// notice of new examples are put into draft or not
						$pending_msg = ( ds106bank_option('new_example_status') == 'draft' ) ? ', pending moderation, will be added ' : ' has been added ';
						
						$feedback_msg = '<div class="alert alert-success" role="alert">Your new ' . $sub_type . ' response ' . $pending_msg .  ' to the <a href="' . get_permalink( $aid ) . '" class="alert-link">' . get_the_title( $aid ) . '</a> ' . THINGNAME . '.</div>';  
					
					}
 
				} else {
			
					// generic error of post creation failed
					$feedback_msg = '<div class="alert alert-danger" role="alert">ERROR: the new ' .  $sub_type . ' could not be created. We are not sure why, but let someone know.</div>';
				} // end if ($post_id)		
			} // end if isset submit button
		} // end count errors
}	
?>

<?php get_header(); ?>				

<div id="content" class="clearfix row">

	<div id="main" class="col-sm-12 clearfix" role="main">				
		
		<?php if ( ( isset($aid) and isset($typ) ) OR isset ( $_POST['bank106_form_add_example_submitted'] ) ):?>					
										
				<?php // query to get the thing this form is associated with
				$the_query = new WP_Query( "p=$aid&post_type=assignments" );?>
			
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
				
					<header>
					<div class="page-header"><h1 class="page-title" itemprop="headline">Add a <?php echo ucfirst($sub_type)?> for the "<?php the_title(); ?>" <?php echo THINGNAME?></h1></div>
					</header> <!-- end article header -->
					</article>


				<div class="row clearfix">
				<!-- display the icon and a snippet of the thing -->
					<div class="col-sm-2">
			
						<div class="thing-icon-single">
						<!-- insert/embed assignmet icon -->
						<a href="<?php echo get_permalink( $aid);?>"><?php echo get_thing_icon ( $aid ,'thumbnail')?></a>
						</div>
					</div>

					<div class="col-sm-10">
					
						<p><em><?php the_excerpt(); ?></em></p>
					
						<p class="lead">Use the form below to add a <?php echo $sub_type?> to the <strong><a href="<?php echo get_permalink( $aid );?>"><?php the_title(); ?></a></strong> <?php echo lcfirst(THINGNAME)?>.</p>
					</div>
				</div>
				
				<div class="col-sm-offset-2 col-sm-8">	
				
				<?php echo $feedback_msg?>	
				
				</div>

			
		
      	<?php if (!$post_id and !$must_login) : //hide form if we had success ?>
				<form action="" id="bank106form" class="abank106form" method="post" action="" autocomplete="on">
				
				<div class="clearfix row">
		
				<div class="col-sm-6">
					<div class="form-group<?php if (array_key_exists("exampleTitle",$errors)) echo ' has-error ';?>">
						<label for="exampleTitle"><?php _e(  'Title for this ' . $sub_type, 'wpbootstrap' ) ?></label>
						<input type="text" name="exampleTitle" id="exampleTitle" value="<?php  echo $exampleTitle; ?>" class="form-control" tabindex="1" placeholder="Enter the title" aria-describedby="titleHelpBlock" />
						<span id="titleHelpBlock" class="help-block">Enter a title that describes that will make it stand out in a list of other responses.</span>
					</div>
				</div>

				<div class="col-sm-6">
				
					<div class="form-group<?php if (array_key_exists("exampleURL",$errors)) echo ' has-error ';?>">
							<label for="exampleURL"><?php _e( 'Web address for this ' . $sub_type, 'wpbootstrap' )?> <a href="<?php echo $exampleURL?>" class="btn btn-xs btn-warning" id="testURL" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Test Link</a></label>
							<input type="text" name="exampleURL" id="exampleURL" class="form-control" value="<?php echo $exampleURL; ?>" tabindex="2" placeholder="http://" aria-describedby="urlHelpBlock"/> 
							<span id="urlHelpBlock" class="help-block">Enter the URL for the <?php echo $sub_type?> you are describing and test the link to make sure it works. <?php if ( $use_full_editor ) echo 'If there is no relevant site to link to then enter <strong>#</strong> to indicate a link is not appropriate'?></span>
					</div>				
				</div>
				
				<?php if ( $use_full_editor )  :?>
				
					<div class="col-sm-12">
					
						<div class="form-group<?php if (array_key_exists("exampleDescription",$errors)) echo ' has-error ';?>">
						<label for="exampleDescription"><?php _e( ' Full description for this ' . $sub_type, 'wpbootstrap') ?></label>
						<span id="exampleHelpBlock" class="help-block">Use the rich text editor to compose a detailed entry that describes this <?php echo $sub_type;?>. To embed media from YouTube, vimeo, instagram, SoundCloud, Twitter, or flickr, simply put the URL for its source page on a blank line. When published url will be replaced by the embeded media for that link. </span>
						<?php
							// set up for inserting the WP post editor
							$settings = array( 'textarea_name' => 'exampleDescription',  'tabindex'  => "3", 'media_buttons' => false, 'textarea_rows' => 8);
							wp_editor(  $exampleDescription, 'exampleDescriptionHTML', $settings );
						?>
					
						</div>				
						
				<?php else:?>
					<div class="col-sm-12">
			
						<div class="form-group<?php if (array_key_exists("exampleDescription",$errors)) echo ' has-error ';?>">
							<label for="exampleDescription"><?php _e(  'Brief description for this ' . $sub_type, 'wpbootstrap') ?></label><br />
							<span id="exampleHelpBlock" class="help-block"><strong><span id="wCount">0</span></strong> words used of <strong><?php echo ds106bank_option('exlen')?></strong> word limit</span>		
							<textarea name="exampleDescription" class="form-control" id="exampleDescription" rows="4"  tabindex="3"><?php echo stripslashes( $exampleDescription );?></textarea>
							
						</div>	
				<?php endif?>
				
				
					<div class="form-group<?php if (array_key_exists("exampleTags",$errors)) echo ' has-error ';?>">
					
						<label for="exampleTags"><?php _e( 'Tags that describe this ' . $sub_type .  ' (optional)', 'wpbootstrap') ?></label>
						<input type="text" name="exampleTags" class="form-control" id="exampleTags" value="<?php echo $exampleTags; ?>" tabindex="4" aria-describedby="tagHelpBlock" />
						<span id="tagHelpBlock" class="help-block">Separate each tag a comma</span>
					</div>

				</div> 
			
				<div class="col-sm-6">
				
					<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
						<label for="submitterName"><?php _e( 'Your name  (required)', 'wpbootstrap' ) ?></label>
						<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" tabindex="5" aria-describedby="nameHelpBlock" />
						<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this <?php echo $sub_type?></span>
					</div>
				
					<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
						<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'wpbootstrap' ) ?></label>
						<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>" tabindex="6" placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
						<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
					</div>				
						
					<?php if ( $use_twitter_name ): ?>

					<div class="form-group<?php if (array_key_exists("submitterTwitter",$errors)) echo ' has-error ';?>">
						<label for="submitterTwitter"><?php _e( 'Your Twitter username ', 'wpbootstrap' ) ?> <?php if ($use_twitter_name == 1) { echo '(optional)'; } else { echo '(required)';}?></label>
						<input type="text" name="submitterTwitter" class="form-control" id="submitterTwitter" value="<?php echo $submitterTwitter; ?>" tabindex="7" placeholder="@"  aria-describedby="twitterHelpBlock" />
						<span id="twitterHelpBlock" class="help-block">Enter the twitter name (including the "@" symbol) of the person authoring this form so the site can keep track of all your shared <?php echo $sub_type?>s. To credit other people add their twitter name to the "Tags" field. </span>
					</div>	
			
					<?php endif?>		
				
				</div>
				
				<div class="col-sm-6">

				<?php if ($use_captcha):?>		
					 <script type="text/javascript">
					 var RecaptchaOptions = {
						theme : '<?php echo ds106bank_option("captcha_style");?>'
					 };
					 </script>
			
					<div class="form-group">
						<label for="recaptcha"><?php _e( 'Spam protection', 'wpbootstrap' )?></label>
						<span id="recaptchaHelpBlock" class="help-block">Unfortunately, this test is necessary to keep this site safe from spammers. Please enter the code!</span>
						<?php echo recaptcha_get_html( ds106bank_option('captcha_pub'), $captcha_error );?>
						
					</div>
				<?php endif?>

					<!-- gotta nonce -->
					<?php wp_nonce_field( 'bank106_form_add_example', 'bank106_form_add_example_submitted' ); ?>
					
					<!-- hidden data shhhhhh -->
					<input type="hidden" id="aid" value="<?php echo $aid;?>" />
					<input type="hidden" id="typ" value="<?php echo $typ;?>" />
					<input type="hidden" id="thingName" value="<?php echo THINGNAME?>" />
					<input type="hidden" id="subType" value="<?php echo ucfirst( $sub_type );?>" />
					
					<?php if ( $use_full_editor ) :?>
						<!-- hidden data stored for preview use -->
						<input type="hidden" id="assignmentURL" value="<?php echo get_permalink( $aid );?>" />
						<input type="hidden" id="assignmentTitle" value="<?php echo get_the_title( $aid );?>" />
						<input type="hidden" id="embedMedia" value="<?php echo htmlentities( get_media_embedded ( $exampleURL ))?>" />		
					<?php endif?>
		
				<div class="form-group">
					<label for="submitexample"><?php _e( 'Review and Submit this ' . ucfirst($sub_type), 'wpbootstrap' )?></label>
					
					
					<div class="row">
						<div class="col-xs-4 col-md-3">
							<button type="submit" class="btn btn-primary" id="updateassignment" name="updateassignment">
  								<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update
							</button>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
						</div>
						<div class="col-xs-4 col-md-3">
								<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your <?php echo $sub_type?>; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Optionally generate a preview. If the body content does not change after edits, try clicking "Update" agan.</span>
						</div>	
						
						<div class="col-xs-4 col-md-3">					
					
							<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitexample" name="submitexample">
  								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
							</button>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Once every thing looks perfect, submit this <?php echo $sub_type?> to the site.</span>
						</div>
					</div>										

				</div>

			</div>
				
		</div>
			
		</form>
			

	<div id="preview" style="display:none;"></div>
	<?php endif?>
	
	<?php endwhile; ?>	
	
	
	<?php endif?>			
	
	
</div><!-- end #content -->
<?php get_footer(); ?>