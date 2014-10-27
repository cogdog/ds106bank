<?php
/*
Template Name: Submit Response/Tutorial Form

Lets visitors add a new response or tutorial for an thing via a web form.
Needs quite a bit of error checking to catch all the crazy things people might
pop into a form. Now with reCaptcha.
*/

// set af default values
$feedback_msg = '';

// type of submissions (in query string)
$typ = $wp_query->query_vars['typ']; 
				
// keep track of the kind fof submission
$sub_type = ($typ == 'tut') ? 'tutorial' : 'response';

// thing id
$aid = $wp_query->query_vars['aid']; 

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
$my_new_status = ds106bank_option( 'new_response_status' );

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_response_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_response_submitted'], 'bank106_form_add_response' ) ) {
 
 		// grab the variables from the form
 		$responseTitle = 			sanitize_text_field( $_POST['responseTitle'] );		
 		$submitterName = 			sanitize_text_field( $_POST['submitterName'] ); 
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$responseDescription = 		esc_textarea( trim($_POST['responseDescription']) );
 		$responseURL = 				esc_url( trim($_POST['responseURL']), array('http', 'https') ); 
 		
 		$my_thing_tag = THINGNAME . $aid;
		$my_tutorial_tag = 'Tutorial' . $aid;

 		
			
 		// let's do some validation, story an error message for each problem found
 		$errors = array();
 		
 		if ( $responseTitle == '' ) $errors[] = '<strong>Title Missing</strong> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors[] = '<strong>Name Missing</strong>- enter your name so we can give you credit';
 		if ( $submitterEmail == '' ) {
 			$errors[] = '<strong>Email Address Missing</strong>- Enter your email in case we have to contact you. If it is one associated with <a href="http://gravatar.com/" target="_blank">gravatar</a> we can list your icon as well.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors[] = '<strong>Invalid Email Address</strong>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}
 		
 		// arbitrary string length to be considered a reasonable descriptions
 		if ( strlen( $responseDescription ) < 50 )  $errors[] = '<strong>Description Missing or Too Short</strong>- please provide a full description that describes this ' . $sub_type. '.';
 		 		
 		if ($responseURL == '') {
 				$errors[] = '<strong>URL Missing or not Entered Correctly</strong>-  please enter the full URL where this ' . $sub_type . ' can be found- it must start with "http://"';	 
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
			$response_information = array(
				'post_title' => esc_attr( strip_tags( $_POST['responseTitle'] ) ),
				'post_content' => esc_attr( strip_tags( $_POST['responseDescription'] ) ),
				'post_type' => 'responses',
				'post_status' => $my_new_status,			
			);

			// insert as a post type
			$post_id = wp_insert_post( $response_information );
		
			// check for success
			if ( $post_id ) {
				
				// set metadata, will use same custom fields as FWP
				update_post_meta( $post_id, 'syndication_source', esc_attr( $submitterName  ) );
				update_post_meta( $post_id, 'submitter_email', esc_attr( $submitterEmail  ) );
				update_post_meta( $post_id, 'syndication_permalink', esc_url_raw( $_POST['responseURL'] ) );
				
				if ($sub_type == 'response') {
					// set the tags for exmaple
					wp_set_object_terms( $post_id, $my_thing_tag, 'thingtags');
				} else {
					// set the tags for tutorial
					wp_set_object_terms( $post_id, $my_tutorial_tag, 'tutorialtags');	
				}
									
				// feedback success
				
				$pending_msg = ( ds106bank_option('new_response_status') == 'draft' ) ? ', pending moderation. ' : '. ';
				
				$feedback_msg = '<div class="fade in alert alert-alert-info">Your new ' . $sub_type . ' response has been added' . $pending_msg .  '<a href=" ' . site_url() . '/?p=' . $aid . '">Return</a> and verify that it now  appears there.</div>';  
 
			} else {
			
				// generic error of post creation failed
				$feedback_msg = '<div class="fade in alert alert-alert-error">ERROR: the new ' .  $sub_type . ' could not be created. We are not sure why, but let someone know.</div>';
			} // end if ($post_id)		
		} // end count errors
}	
?>

<?php get_header(); ?>				
				
				<?php if ( ( isset($aid) and isset($typ) ) OR isset( $_POST['bank106_form_add_response_submitted'] ) ) :?>
				
					<?php // query for this post so we can display it.
					$the_query = new WP_Query( "p=$aid&post_type=things" );?>
				
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
							
						<article id="post-<?php the_ID(); ?>"  role="article">
				
						<div class="clearfix row">
					<header>
						<div class="col-md-4">
						
							<div class="thing-icon-single">
							<!-- insert/embed assignmet icon -->
							<?php get_thing_icon ( $my_id ,'thumbnail')?>
							</div>
						</div>

						<div class="col-md-6 col-md-offset-1" >

								
							<h1 class="single-title thing-header" itemprop="headline"><?php the_title(); ?></h1>
							
							<?php 
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
						
							// look for author name in Feedwordpress meta data
							$thingAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							// no author assigned
							if ( !$thingAuthor) $thingAuthor = 'Anonymous';
							?>
							
							<p class="meta">This <?php echo THINGNAME?> was 
							<?php _e("created", "wpbootstrap"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $thingAuthor?></strong>
							</p>
							
							<p><?php echo get_the_term_list( $post->ID, 'thingtypes', 'Type: ', ', ', '' ); ?> </p>
							
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags", "wpbootstrap") . ':</span> ', ' ', '</p>'); ?>
							
							<p><a href="<?php echo site_url() . '/?p=' . $aid ?>" class="btn btn-success">Return to this <?php echo THINGNAME?></a></p>
						</div>
					</header> <!-- end article header -->	
				</div>	<!-- end row -->
<div id="thingcontent" class="clearfix row">	
						<div class="col-md-12" \>
						
							<?php the_content(); ?>
					
							<footer>
							
							<?php // get_response_media($aid)?>

							</footer> <!-- end article footer -->
						</div>
				</div>
					
				</article> <!-- end article -->					
					
		<?php endwhile; ?>	
		
		<div id="content3" class="clearfix row">	
			<div  class="col-md-5">		
			<?php echo $feedback_msg?>	
			</div>
				
			<?php if (!$post_id) : //hide form if we had success ?>
	
			<form action="" id="bank106form" class="bank106form" method="post" action="">
			<div class="col-md-10">
			
				<h2 class="page-title" itemprop="headline">Add <?php _e(THINGNAME . ' ' . ucfirst($sub_type), 'wpbootstrap' ); ?></h2></
				<p>This form allows you to add your <?php echo $sub_type?> to the <strong><?php the_title(); ?></strong> <?php echo lcfirst(THINGNAME)?>.</p> 
			</div>
		
			<div class="col-md-5 clearfix">
				<fieldset>
					<label for="responseTitle"><?php _e( ucfirst($sub_type) . ' Title:', 'wpbootstrap' ) ?></label>
					<input type="text" name="responseTitle" id="responseTitle" class="required" value="<?php  echo $responseTitle; ?>" tabindex="1" />
				</fieldset>
			
				<fieldset id="responseURL">
					<label for="responseURL"><?php _e( 'URL for the ' . $sub_type, 'wpbootstrap' )?>:</label>
					<input type="text" name="responseURL" id="responseURL" class="required" value="<?php echo $responseURL; ?>" tabindex="13" />
				
				</fieldset> 				

				<fieldset>
					<label for="responseDescription"><?php _e( ucfirst($sub_type)  . ' Description:', 'wpbootstrap') ?></label>
					<textarea name="responseDescription" id="thingresponseDescriptionDescription" rows="8" cols="30" class="required" tabindex="4"><?php echo stripslashes( $responseDescription );?></textarea>
				</fieldset>	
			</div> 
			
			<div class="col-md-5 col-md-offset-1">
  			<fieldset>
				<label for="submitterName"><?php _e( 'Your Name:', 'wpbootstrap' ) ?></label>
				<input type="text" name="submitterName" id="submitterName" class="required" value="<?php echo $submitterName; ?>" tabindex="3" />
			</fieldset>
			
			<fieldset>
				<label for="submitterEmail"><?php _e( 'Your Email Address:', 'wpbootstrap' ) ?></label>
				<p>Note: Your email address is never displayed, and is only used if we need to contact you about your submission.</p>
				<input type="text" name="submitterEmail" id="submitterEmail" class="required" value="<?php echo $submitterEmail; ?>" tabindex="3" />
			</fieldset>
	
 				
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
				<?php wp_nonce_field( 'bank106_form_add_response', 'bank106_form_add_response_submitted' ); ?>

				<input type="submit" class="btn btn-primary" value="Add This <?php echo $sub_type?>" tabindex="40" id="submitthing" name="submitthing" tabindex="15">
			</fieldset>

			</div>
		</form>
	</div>
	<?php endif?>
	
	<?php else: ?>
			Harrumph. This form is only activated if you follow a link from a published <?php THINGNAME?>. I need some more information passed my way.
	</div>
	<?php endif?>			
		
<?php get_footer(); ?>