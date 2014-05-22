<?php
/*
Template Name: Submit Assignment Form

Creates a new assignment for a ds106 bank like site form user input via web form.
Needs quite a bit of error checking to catch all the crazy things people might
pop into a form. Now with reCaptcha.
*/

// enqueue jquery for this form
add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_add_scripts' );

// set af default values
$assignmentRating = 1;
$assignmentExampleOpts = 1;
$feedback_msg = '';

// creative commons usage mode
$my_cc_mode = ds106bank_option( 'use_cc' ); 


if ( is_user_logged_in() ) {
	//bypass captcha for logged in users
	$use_captcha = false;
	
	// set default name and email based on user profile
	global $user_identity, $user_email;
	get_currentuserinfo();
	
	$submitterName 	= $user_identity;
	$submitterEmail = $user_email;
	
} else {
	// set up captcha if set as option;
	$use_captcha = ds106bank_option('use_captcha');
}

// include captch lib if we need to
if ($use_captcha) require_once( get_stylesheet_directory() . '/includes/recaptchalib.php');

// status for new submissions
$my_new_status = ds106bank_option( 'new_thing_status' );

// a little mojo to get current page ID so we can build a link back here
$post = $wp_query->post;
$current_ID = $post->ID;

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_assignment_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_assignment_submitted'], 'bank106_form_add_assignment' ) ) {
 
 		// grab the variables from the form
 		$assignmentTitle = 			sanitize_text_field( $_POST['assignmentTitle'] );		
 		$submitterName = 			sanitize_text_field( $_POST['submitterName'] ); 
 		$assignmentTags = 			sanitize_text_field( $_POST['assignmentTags'] );	
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$assignmentDescription = 	esc_textarea( trim($_POST['assignmentDescription']) );
 		$assignmentType = 			$_POST['assignmentType'];
 		$assignmentRating = 		$_POST['assignmentRating'];		
 		$assignmentExampleOpts = 	$_POST['assignmentExampleOpts'];
 		$assignmentURL = 			esc_url( trim($_POST['assignmentURL']), array('http', 'https') ); 
 		$assignmentCC = 			$_POST['assignmentCC'];
 		
			
 		// let's do some validation, story an error message for each problem found
 		$errors = array();
 		
 		if ( $assignmentTitle == '' ) $errors[] = '<strong>' . THINGNAME . ' Title Missing</strong> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors[] = '<strong>Name Missing</strong>- enter your name so we can give you credit';
 		if ( $submitterEmail == '' ) {
 			$errors[] = '<strong>Email Address Missing</strong>- Enter your email in case we have to contact you. If it is one associated with <a href="http://gravatar.com/" target="_blank">gravatar</a> we can list your icon as well.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors[] = '<strong>Invalid Email Address</strong>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}
 		
 		// arbitrary string length to be considered a reasonable descriptions
 		if ( strlen( $assignmentDescription ) < 50 )  $errors[] = '<strong>Description Missing or Too Short</strong>- please provide a full description that will help someone complete this ' . lcfirst(THINGNAME) . '.';
 		
 		if ( $assignmentType == -1 ) $errors[] = '<strong>Type Not Selected</strong>- select the type of ' . lcfirst(THINGNAME);
 		
 		// check entered URLs
 		if ( $assignmentExampleOpts < 3 ) {
 			if ($assignmentURL == '') {
 				$errors[] = '<strong>Example URL Missing or not Entered Correctly</strong>- if you have an example, please enter the full URL where it can be found- it must start with "http://"';	 
 			}
 		} // end url CHECK
 		
 		// check selection of license option
 		if ($assignmentCC == '--')  {
 			$errors[] = '<strong>License Not Selected</strong>- Choose the license you wish to attach to thie ' . lcfirst(THINGNAME);	 
 		}
 		
 		
 		// check captcha
		if ( $use_captcha and isset( $_POST["recaptcha_response_field"] ) ) {
				$resp = recaptcha_check_answer ( ds106bank_option('captcha_pri'),
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
				
				if ( !( $resp->is_valid ) ) {
					# set the error code so that we can display it
					$captcha_error = $resp->error;
					$errors[] = '<strong>Captcha Error</strong>- please retry the captcha field.';
				}
		}
 				
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = '<div class="fade in alert alert-alert-error">Sorry, but there are a few errors in your entry. Please correct and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
 			
 			// good enough, let's make a post! Or a custom post type
			$assignment_information = array(
				'post_title' => $assignmentTitle,
				'post_content' => $assignmentDescription,
				'tags_input'  => $assignmentTags,
				'post_type' => 'assignments',
				'post_status' => $my_new_status,			
			);

			// insert as a post
			$post_id = wp_insert_post( $assignment_information );
		
			// check for success
			if ( $post_id ) {
				
				// set metadata
				update_post_meta( $post_id, 'fwp_name', esc_attr( $submitterName  ) );
				update_post_meta( $post_id, 'submitter_email', esc_attr( $submitterEmail  ) );
				
				// set url if provided in form
				if ( $assignmentExampleOpts < 3) {
					update_post_meta( $post_id, 'fwp_url', esc_url_raw( $_POST['assignmentURL'] ) );
				}
				
				// set the term for the type of assignment
				wp_set_object_terms( $post_id, $assignmentType, 'assignmenttypes');
				
				// update the new tags
				update_assignment_tags( $post_id );

				
				// update post meta for the initial rating, the average and score = the entered value
				update_post_meta( $post_id,  'ratings_average', $assignmentRating );
				update_post_meta( $post_id,  'ratings_score', $assignmentRating );
	
				// the rating count set to 1
				update_post_meta( $post_id,  'ratings_users', 1 );


				// user selected license
				if ( $my_cc_mode == 'user' ) update_post_meta( $post_id,  'cc', $assignmentCC);
				
				// upload thumnbail
				if ( $_FILES ) {
					foreach ( $_FILES as $file => $array ) {
						$newupload = bank106_insert_attachment( $file, $post_id );
					}
				}
				
				// if we got an attachment id, then update meta data to indicate thumbnal
				if ($newupload) update_post_meta ($post_id, '_thumbnail_id', $newupload );	
				
				if  ( ds106bank_option( 'new_thing_status' ) == 'publish' ) {
				
					// build feedback if new things are automatically published
					
					// grab link to new assignment
					$assignmentLink = get_permalink( $post_id );
				
					// feedback success
					$feedback_msg = '<div class="fade in alert alert-alert-info">Your new ' . $assignmentType . ' ' . THINGNAME . ' has been created. Check out <a href="' . get_permalink( $post_id ) . '">' . $assignmentTitle . '</a> or you can <a href="' . get_permalink( $current_ID ) .'">create another ' . lcfirst(THINGNAME) . '</a>.</div>';  
					
				} else {
					// feedback if new things are set to draft
					$feedback_msg = '<div class="fade in alert alert-alert-info">Your new ' . $assignmentType . ' ' . THINGNAME . ', "' . $assignmentTitle . '" has been created. Once it has been approved it will appear among the other <a href="' . site_url() . '/type/' . sanitize_title($assignmentType) .  '">' . $assignmentType . ' ' . THINGNAME . 's</a> . Do you want to <a href="' . get_permalink( $current_ID ) .'">create another ' . lcfirst(THINGNAME) . '</a>?</div>';  
				
				}
 
			} else {
			
				// generic error of post creation failed
				$feedback_msg = '<div class="fade in alert alert-alert-error">ERROR: the new ' . lcfirst(THINGNAME) . ' could not be created. We are not sure why, but let someone know.</div>';
			} // end if ($post_id)
					
		} // end count errors
}	
?>

<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							
							<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>
					
						</section> <!-- end article section -->
						
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","wpbootstrap") . ':</span> ', ', ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php endwhile; ?>		
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
				<?php echo $feedback_msg?>
				</div> <!-- end #main -->
        
			
<?php if (!$post_id) : //hide form if we had success ?>
			
	<form action="" id="bank106form" class="bank106form" method="post" action="" enctype="multipart/form-data">
	
	<div class="clearfix row">
		<div class="col-md-5 col-md-offset-1 clearfix">
		
			<fieldset>
				<label for="assignmentType"><?php _e( 'Type of ' . THINGNAME , 'wpbootstrap' ) ?></label>
				<select name="assignmentType" id="assignmentType" tabindex="1">
				<option value="-1">Select <?php echo lcfirst(THINGNAME)?> type</option>
				
				<?php 
					// build options based on assignment types
					// yes this might have been done with wp_dropdown_categories
					
					$atypes = get_assignment_types();
					
					foreach ($atypes as $thetype) {
						$aselected = ($assignmentType == $thetype->name) ? ' selected' : '';
						
						echo '<option value="' . $thetype->name . '"' . $aselected . '>' .  $thetype->name . '</option>';
					}					
					?>			
				</select>	
 			</fieldset>
 
			<fieldset>
				<label for="assignmentTitle"><?php _e( THINGNAME . ' Title', 'wpbootstrap' ) ?></label>
				<input type="text" name="assignmentTitle" id="assignmentTitle" class="required" value="<?php  echo $assignmentTitle; ?>" tabindex="2" />
			</fieldset>
			
			<fieldset>
					<label for="assignmentDescription"><?php _e(THINGNAME . ' Description', 'wpbootstrap') ?></label>
					<textarea name="assignmentDescription" id="assignmentDescription" rows="8" cols="30" class="required" tabindex="3"><?php echo stripslashes( $assignmentDescription );?></textarea>
			</fieldset>
			
			<fieldset>
				<label for="assignmentTags"><?php _e( THINGNAME . ' Tags (optional)', 'wpbootstrap' ) ?></label>
				<p><em>Separate tags with commas</em></p>
				<input type="text" name="assignmentTags" id="assignmentTags" value="<?php  echo $assignmentTags; ?>" tabindex="4" />
			</fieldset>
			
			<fieldset>
				<label for="submitterName"><?php _e( 'Your Name:', 'wpbootstrap' ) ?></label>
				<input type="text" name="submitterName" id="submitterName" class="required" value="<?php echo $submitterName; ?>" tabindex="5" />
			</fieldset>
			
			<fieldset>
				<label for="submitterEmail"><?php _e( 'Your Email Address', 'wpbootstrap' ) ?></label>
				<p><em>Your email address is never displayed, and is only used if we need to contact you about your submission.</em></p>
				<input type="text" name="submitterEmail" id="submitterEmail" class="required" value="<?php echo $submitterEmail; ?>" tabindex="6" />
			</fieldset>
			
			</div> 
			
			<div class="col-md-5">
 				<?php if (function_exists('the_ratings') ): // use ratings input ?>
 				
 				<fieldset>
 				<label for="assignmentRating"><?php _e( 'Difficulty Rating' , 'wpbootstrap' ) ?></label>
 				<p>Give your assignment a difficulty rating from 1=easy to 5=difficult</p>
 				
 				<?php
 				
 					// ratings from 1 to 5
 					for ( $i = 1; $i <  6; $i++ ) {
 					
 						// extra labels for 1 and 5
 						switch ($i) {
 							 case 1: 
 								$ratingextra = ' (easy)';
 								break;
 							case 5: 
 								$ratingextra = ' (hard)';
 								break;
 							default;
 								$ratingextra = '';
 						}
 						
 						$is_checked = ( $assignmentRating == $i) ? ' checked' : '';
 							
 						echo '<input type="radio" name="assignmentRating" value="' . $i . '"' . $is_checked . ' tabindex="' . (5 + $i) . '"/> ' . $i . $ratingextra . '<br />';
 					}

 				?>
				</fieldset>
 			<?php endif?>
 				
 				
 				<fieldset>
 				<label for="assignmentExampleOpts"><?php _e( 'Do you have an example that demonstrates this ' . lcfirst(THINGNAME) . ' ?', 'wpbootstrap' )?></label>
 				<br />
 				<input type="radio" name="assignmentExampleOpts" id="assignmentSoMeURL" value="1" <?php if ($assignmentExampleOpts == 1) echo ' checked'?> tabindex="9" />Yes, and it is from YouTube, Flickr, Vimeo, or SoundCloud<br />
 				
 				<input type="radio" name="assignmentExampleOpts" id="assignmentOtherURL" value="2" <?php if ($assignmentExampleOpts == 2) echo ' checked'?> tabindex="10" />Yes, but it is on another site<br />
 				
 				<input type="radio" name="assignmentExampleOpts" id="assignmentNoURL" value="3" <?php if ($assignmentExampleOpts == 3) echo ' checked'?> tabindex="11" />No, I really just want to write it without providing an example

 				</fieldset>
 				
 				<fieldset id="assignmentURLfield">
 				<label for="assignmentURL"><?php _e( 'URL for Example', 'wpbootstrap' )?></label>
 				<input type="text" name="assignmentURL" id="assignmentURL" class="required" value="<?php echo $assignmentURL; ?>" tabindex="13" />
 				
 				</fieldset>
 				
 				<fieldset id="uploadThumbfield">
 				<label for="uploadThumb"><?php _e( 'Upload Thumbnail Image', 'wpbootstrap' )?></label>
 				Your image will be cropped if its dimensions are not proportional to the display size:<br />
 				<img src="http://placehold.it/<?php echo THUMBW?>x<?php echo THUMBH?>" alt="" /><br />
 				<input type="file" name="assignmentImage" id="assignmentImage" tabindex="14" />
 				
 				</fieldset>
 				
 				<?php if ($my_cc_mode == 'site') :?>
 				<p><strong>All <?php echo lcfirst(THINGNAME)?>s added to this site will be licensed</strong></p>
 				
 				<p style="text-align:center">
				<?php echo cc_license_html(ds106bank_option( 'cc_site' ));?>
 				</p>
 				
 				<?php elseif  ($my_cc_mode == 'user') :?>
					<fieldset id="assignmentCCfield">
					<label for="assignmentCC"><?php _e( 'License Options', 'wpbootstrap' )?></label>
					<select name="assignmentCC" id="assignmentCC" class="required">
					<option value="--">Select...</option>
					<?php echo cc_license_select_options($assignmentCC) ?>
					</select>				
					</fieldset>
 				<?php endif?>
 				
 				
 				<?php if ($use_captcha):?>
 				
 				 <script type="text/javascript">
				 var RecaptchaOptions = {
					theme : '<?php echo ds106bank_option("captcha_style");?>'
				 };
				 </script>
 				
 				
 				<fieldset id="recaptcha">
 				<label for="recaptcha"><?php _e( 'Spam Protection', 'wpbootstrap' )?></label>
 				Unfortunately, this test is necessary to keep this site safe from spammers.<br />
 				<?php echo recaptcha_get_html( ds106bank_option('captcha_pub'), $captcha_error );?>
 				</fieldset>
 				<?php endif?>
 				
 				<fieldset>
				<?php wp_nonce_field( 'bank106_form_add_assignment', 'bank106_form_add_assignment_submitted' ); ?>
	
				<input type="submit" class="btn btn-primary" value="Add This <?php echo THINGNAME?>" tabindex="40" id="submitassignment" name="submitassignment" tabindex="15">
			</fieldset>

		</div>

 		
</form>
<?php endif?>
			
			
    
</div> <!-- end #formcontent -->
<?php get_footer(); ?>