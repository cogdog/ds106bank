<?php
/*
Template Name: Submit Thing Form

Creates a new thing/assignment from user input via web form. Lotsa options!

*/

// load scripts
bank106_enqueue_add_thing_scripts();

// ----- set defaults ---------------------

// start these variables empty
$assignmentURL = $assignmentTitle = $assignmentDescription = $assignmentType = $assignmentTags = $assignment_thumb_id = $assignmentExtras = $assignmentInstructions = $sub_type = $submitterName = $submitterEmail = $assignmentType = $submitterUsername = $use_public_ratings = $assignmentCategories = $assignmentCC = $assignment_thumb_status = ''; 		

// mmm cookies, load if present
if ( isset($_COOKIE["bank106username"] )) $submitterUsername = $_COOKIE["bank106username"];
if ( isset($_COOKIE["bank106name"] ))  $submitterName = $_COOKIE["bank106name"];
if ( isset($_COOKIE["bank106email"] ))  $submitterEmail = $_COOKIE["bank106email"];

// set af default rating values
$assignmentRating = 3;    	// default public rating
$assignmentDifficulty = 3;  // default author rating

$errors = array(); 			// holder for bad form entry warnings

$feedback_msg = '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Prompt:</span> Create a new ' . lcfirst(bank106_option( 'thingname' )) . '  here! Enter all required information below and <a href="#prettybuttons" style="text-decoration: underline">use the buttons below</a> to <a href="#prettybuttons" class="btn btn-primary btn-xs">update</a> your information to verify it. Then you can modify and <a href="#prettybuttons" class="btn btn-warning btn-xs">preview</a> as much as necessary to make it look beautiful (get it right, you will not be able to edit it later). Once you are happy with it, <a href="#prettybuttons" class="btn btn-success btn-xs">submit</a> the form for the last time and it will be saved to this site.';

$previewBtnState = ' disabled';
$submitBtnState = ' disabled';


if ( is_user_logged_in() ) {

	//bypass captcha for logged in users
	$use_captcha = false;
	
	// load user object, hello there!
	$current_user = wp_get_current_user();
	
	$feedback_msg .= '<br /><br />All ' . bank106_option( 'pluralthings' ) . ' submitted here will be associated with your current login in as <strong>' . $current_user->display_name . '</strong>.' ;
	
	// set contact details with info from account
	$submitterName = $current_user->display_name;
	$submitterUsername = $current_user->user_login;
	$submitterEmail = $current_user->user_email;

} else {

	// use  captchas? store as variable as we may over-ride it
	$use_captcha = bank106_option('use_captcha');
	
	if ( bank106_option('use_wp_login') == 1) {
		// WP login optional
		
		$feedback_msg .= '<br /><br />Logging in to this site is not required, but if you wish to have all  ' . bank106_option( 'pluralthings' ) . ' created associated with your name ' . sprintf( '<a href="%s" class="btn btn-primary btn-xs">%s</a>', wp_login_url( get_permalink( ) ), __( 'sign in now' ) );  
	
	// WP login requred
	} elseif ( bank106_option('use_wp_login') == 2 ) {
		$must_login = true;
		
		// feedback message now an alert
		$feedback_msg = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Warning:</span> You must sign in to ' . bloginfo( 'name' ) . ' to add a '  . bank106_option( 'thingname' ) .  ' to this site. ' . sprintf( '<a href="%s" class="btn btn-primary btn-xs">%s</a>', wp_login_url( get_permalink( ) ), __( 'sign in now' ) );
	}
		
}

$feedback_msg .=  '</div>'; // close dat div

if (bank106_option('use_thing_cats') == 1) {
	// ony if we are using taxonomy terms (categories for assignments)
	$assignmentTaxTerms = get_terms( array(
		'taxonomy' => 'assignmentcats',
		'hide_empty' => false,
	) );

	// now let's sort them if there is a heirarchy, a litle bit of judo...
	$assignmentCats = array();
	bank106_sort_terms_hierarchicaly( $assignmentTaxTerms, $assignmentCats );
}

// a little mojo to get current page ID so we can build a link back here
$post = $wp_query->post;
$current_ID = $post->ID;

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_assignment_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_assignment_submitted'], 'bank106_form_add_assignment' ) ) {
 
 		// grab the variables from the form
 		$assignmentTitle = 			stripslashes(sanitize_text_field( $_POST['assignmentTitle'] ));		
 		$submitterName = 			stripslashes(sanitize_text_field( $_POST['submitterName'] )); 
 		$assignmentTags = 			cleanTags( sanitize_text_field( $_POST['assignmentTags'] ) );	
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$assignmentDescription = 	$_POST['assignmentDescription'];
 		$assignmentType = 			( isset ( $_POST['assignmentType'] ))  ? $_POST['assignmentType'] : '';
 		if ( isset( $_POST['assignmentCategories'] ) )  $assignmentCategories = 	$_POST['assignmentCategories'];
 		if ( isset( $_POST['assignmentRating'] ) ) $assignmentRating = $_POST['assignmentRating'];	
 		if ( isset( $_POST['assignmentDifficulty'] ) ) $assignmentDifficulty = 	$_POST['assignmentDifficulty'];		
 		$assignmentURL = 			esc_url( trim($_POST['assignmentURL']), array('http', 'https') ); 
 		if ( isset( $_POST['assignmentCC'] ) ) $assignmentCC = $_POST['assignmentCC'];
 		$assignmentExtras = 		stripslashes(sanitize_text_field( $_POST['assignmentExtras'] ));
 		$assignment_thumb_id = 		$_POST['assignment_thumb_id'];
 		
 		$assignmentInstructions = 	stripslashes(sanitize_text_field( $_POST['assignmentInstructions'] )); 
 		if ( isset ( $_POST['useCaptcha'] )) $use_captcha = $_POST['useCaptcha'];

 		if (bank106_option( 'user_code_name' )) {
 			// using options for user code names
 			$submitterUsername = sanitize_text_field( $_POST['submitterUsername'] ); 
 			
 			// no @ for user name! Just in case they think this is twitter, it's not
 			if ( $submitterUsername[0] == "@" ) $submitterUsername = substr($submitterUsername, 1); 
 			
 			// set a cookie for user name
 			setcookie( "bank106username", $submitterUsername, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */	
 		}
 		
 		// set cookies
 		setcookie( "bank106name", $submitterName, strtotime( '+14 days' ), '/' );  /* expire in 14 days */
 		setcookie( "bank106email", $submitterEmail, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */
 		
		// upload thumnbail if we got one
		if ( $_FILES ) {
			foreach ( $_FILES as $file => $array ) {
				$newupload = bank106_insert_attachment( $file, $post->ID );
				if ($newupload) {
					$assignment_thumb_id = $newupload;	
					$assignment_thumb_status = 'Image file uploaded. Choose another to replace it.'; 
				}
			}
		}
 			
 		// let's do some validation, store an error message for each problem found
	
 		if ( $assignmentTitle == '' ) $errors['assignmentTitle'] = '<span class="label label-danger">' . bank106_option( 'thingname' ) . ' Title Missing</span> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
 		if ( $submitterEmail == '' ) {
 			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}

 		if ( bank106_option( 'user_code_name' )) {
 			if ( $submitterUsername == '' and  bank106_option( 'user_code_name' ) == 2) {
 				$errors['submitterUsername'] = '<span class="label label-danger">User Name Missing</span> - please enter a unique user name to identify your work (e.g. can be twitter name without an @), it is required.'; 
 			}	
 		}
	
 		// arbitrary and puny string length to be considered a reasonable descriptions
 		if ( strlen( $assignmentDescription ) < 10 )  $errors['assignmentDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that will help someone complete this ' . lcfirst(bank106_option( 'thingname' )) . '. You might need a sentence or two.';
 		
 		if ( empty( $assignmentType ) ) $errors['assignmentType'] = '<span class="label label-danger">' .  bank106_option( 'thingname' ) . ' ' . bank106_option( 'type_name' ) . ' Not Selected</span>- select at least one type of ' . lcfirst(bank106_option( 'thingname' ));

 		 		
 		// check selection of license option
 		if ($assignmentCC == '--')  {
 			$errors['assignmentCC'] = '<span class="label label-danger">Creative Commons License not Selected</span>- Choose the license you wish to attach to thie ' . lcfirst(bank106_option( 'thingname' ));	 
 		}
 		
		if ($assignmentURL == '') {
 				$errors['assignmentURL'] = '<span class="label label-danger">URL Missing or not Entered Correctly</span>-  please enter the full URL where the example for this ' .  lcfirst(bank106_option( 'thingname' )) . ' can be found- it must start with "http://" or "https://" ';	 
 		} // end url CHECK 	
 					
 		// check captcha
 		// after https://codeforgeek.com/2014/12/google-recaptcha-tutorial/
		if ( $use_captcha and isset( $_POST["g-recaptcha-response"] ) ) {
		
        	$ip = $_SERVER['REMOTE_ADDR'];
        	
        	// API call, check captcha
			$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=". bank106_option( 'captcha_pri' ) . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip);
        	$responseKeys = json_decode($response,true);
		
			if ( intval( $responseKeys["success"] ) !== 1 ) {
					$errors['recaptcha'] =  '<span class="label label-danger">Captcha error</span>. Please verify again.</li>';
			} else {
				$use_captcha = false;
			}
		}	
		
 				
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span> Sorry, but there are a few errors in your entry. Please correct the following items and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
 		
 			// set up stuff if we are just doing a preview
 			$previewBtnState = '';
 			$feedback_msg = '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
  <span class="sr-only">Success:</span>  The information for this new ' . bank106_option( 'thingname' ) . ' seems to be ok! Now you can preview your entry <a href="#prettybuttons" style="text-decoration: underline">using the buttons below</a>. Continue to edit and modify until you think it is ready. You must <a href="#prettybuttons" class="btn btn-warning btn-xs">preview</a> at least once to activate the submit button. Note that if you change the URL, you will have to click the <a href="#" class="btn btn-primary btn-xs">update</a> button again to refresh its content.</div>';
 			
 			// make the links into embeds
 			// $assignmentDescription = oembed_filter( $assignmentDescription );
 			
 			// Now process form only if submit button used
 			if ( isset ( $_POST['submitassignment'] ) ) {
 			
 				$add_tags = $assignmentTags; // holder for tags
			
				// add a tag for user name, if provided and it's not a wp user - with special @ symbol so we know tag is for user
				if ( $submitterUsername != '' AND !( username_exists( $submitterUsername ) ) ) $add_tags .= ',' . '@' . $submitterUsername;
 			
				// good enough, let's add this thing
				$assignment_information = array(
					'post_title' => $assignmentTitle,
					'post_content' => $assignmentDescription,
					'tags_input'  => $add_tags,
					'post_type' => 'assignments',
					'post_status' => bank106_option( 'new_thing_status' ),			
				);

				// insert as a post
				$post_id = wp_insert_post( $assignment_information );
		
				// check for success
				if ( $post_id ) {
				
					// set metadata and yes we are using ancient feedwordpress meta data fields
					update_post_meta( $post_id, 'fwp_name', esc_attr( $submitterName  ) );
					update_post_meta( $post_id, 'submitter_email', esc_attr( $submitterEmail  ) );
				
					// set url if provided in form, yes more ancient feedwordpress meta data fields
					update_post_meta( $post_id, 'fwp_url', esc_url_raw( $_POST['assignmentURL'] ) );

					// give username credit (in earlier versions this was twitter, and metadata will still use name for backward compatibility 
					if ( $submitterUsername ) update_post_meta( $post_id, 'submitter_twitter', esc_attr( $submitterUsername ) );
				
					// set the term for the type of thing
					wp_set_object_terms( $post_id, $assignmentType, 'assignmenttypes');
					
					// set the taxonomy terms for categories for the type of thing (if we are using cats)
					if ( bank106_option('use_thing_cats') ) wp_set_object_terms( $post_id, $assignmentCategories, 'assignmentcats');
				
					// update the new tags
					update_assignment_tags( $post_id );

					// update post meta for the initial rating, the average and score = the entered value
					update_post_meta( $post_id,  'ratings_average', $assignmentRating );
					update_post_meta( $post_id,  'ratings_score', $assignmentRating );
	
					// the rating count set to 1
					update_post_meta( $post_id,  'ratings_users', 1 );
					
					// seed the counter
					update_post_meta( $post_id,  'assignment_visits', 1 );
				
					if ( bank106_option('difficulty_rating') ) update_post_meta( $post_id,  'assignment_difficulty', $assignmentDifficulty );

					// user selected license if allowed
					if ( bank106_option( 'use_cc' ) == 'user' ) update_post_meta( $post_id,  'cc', $assignmentCC);
					
					// extra info for assignment
					update_post_meta( $post_id,  'assignment_extras', $assignmentExtras );
					
					// specific instructions for assignment
					update_post_meta( $post_id,  'assignment_instructions', $assignmentInstructions );
					
					// if we got an attachment id, then update meta data to indicate thumbnal
					if ($assignment_thumb_id) update_post_meta ($post_id, '_thumbnail_id', $assignment_thumb_id );	
				
					if  ( bank106_option( 'new_thing_status' ) == 'publish' ) {
				
						// build feedback if new things are automatically published
					
						// grab link to new assignment
						$assignmentLink = get_permalink( $post_id );
				
						// feedback success
						$feedback_msg = '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Success:</span>  The new ' . bank106_option( 'thingname' ) . ' has been created. Check out <a href="' . get_permalink( $post_id ) . '" class="alert-link">' . $assignmentTitle . '</a> or you can <a href="' . get_permalink( $current_ID ) .'"  class="alert-link">create another ' . lcfirst(bank106_option( 'thingname' )) . '</a>.</div>';  
					
					} else {
						// feedback if new things are set to draft
						$feedback_msg = '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Success:</span>  Your new ' . bank106_option( 'thingname' ) . ', "' . $assignmentTitle . '" has been created. Once it has been approved it will appear on this site. Do you want to <a href="' . get_permalink( $current_ID ) .'"  class="alert-link">create another ' . lcfirst(bank106_option( 'thingname' )) . '</a>?</div>';  
				
					}
 
				} else {
			
					// generic error of post creation failed
					$feedback_msg = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span> ERROR: the new ' . lcfirst(bank106_option( 'thingname' )) . ' could not be created. We are not sure why, but let someone know.</div>';
				} // end if ($post_id)
			} // end if isset submit button	
		} // end count errors
}	
?>

<?php get_header(); ?>
			
<div id="content" class="clearfix row">
	<div id="main" class="col-sm-12 clearfix" role="main">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					
					<header>
						
					<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
					
					</header> <!-- end article header -->
				
					<section class="post_content clearfix" itemprop="articleBody">
						<?php the_content(); ?>
					</section> <!-- end article section -->
					
					<footer>
					</footer> <!-- end article footer -->
				
				</article> <!-- end article -->
				
				<?php endwhile; ?>		
				
				<?php else : ?>
				
				<article id="post-not-found">
					<header>
						<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					</header>
					<section class="post_content">
						<p><?php _e("Oh oh, but the page for the form has not been site or has gone missing", "wpbootstrap"); ?></p>
					</section>
					<footer>
					</footer>
				</article>
				
				<?php endif; ?>
						
			<div class="row clearfix"> <!-- message row -->
			
				<div class="col-sm-offset-2 col-sm-8">	
					<?php echo $feedback_msg?>	
				</div><!--col-->
				
			</div>
			
			<?php if (!isset($post_id) and !isset($must_login)) : //hide form if we had success ?>
			
				<form action="" id="bank106form" class="bank106form" method="post" action="" autocomplete="on" enctype="multipart/form-data">
	
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group<?php if (array_key_exists("assignmentTitle",$errors)) echo ' has-error ';?>">
							<label for="assignmentTitle"><?php bank106_form_thing_title(); ?></label>
							<input type="text" name="assignmentTitle" id="assignmentTitle" value="<?php  echo $assignmentTitle; ?>" class="form-control"  placeholder="Enter a title" aria-describedby="titleHelpBlock" />
							<span id="titleHelpBlock" class="help-block"><?php bank106_form_thing_title_prompt();?></span>
						</div><!--formgroup-->
					</div><!--col-->

					<div class="col-sm-6">
						<div class="form-group<?php if (array_key_exists("assignmentURL",$errors)) echo ' has-error ';?>">
								<label for="assignmentURL"><?php _e( 'Web address for an example of this ' . lcfirst(bank106_option( 'thingname' )), 'wpbootstrap' )?> <a href="<?php echo $assignmentURL?>" class="btn btn-xs btn-warning" id="testURL" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Test Link</a></label>
								
								<input type="url" name="assignmentURL" id="assignmentURL" class="form-control" value="<?php echo $assignmentURL; ?>"  placeholder="http://" aria-describedby="urlHelpBlock" /> 
								
								<span id="urlHelpBlock" class="help-block">Enter the URL for an example of the <?php echo bank106_option( 'thingname' )?> you are adding to <?php bloginfo()?>. Please test the link to make sure it works.</span>
						</div><!--formgroup-->	
											
					</div><!--col-->
					
					
				</div><!--row-->
				
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group<?php if (array_key_exists("assignmentDescription",$errors)) echo ' has-error ';?>">
							<label for="assignmentDescription"><?php bank106_form_thing_writing_area();?></label>
							<span id="assignmentHelpBlock" class="help-block"><?php bank106_form_thing_writing_area_prompt();?>  To embed media from sites like YouTube, Vimeo, SoundCloud, Giphy, Slideshare, Twitter, Flickr (see <a href="https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from" target="_blank">all supported sites</a>) put the URL for it  as plain text on a blank line. When your <?php echo bank106_option( 'thingname' )?> is saved/previewed, these URLs will be replaced by a media embed.</span>
							<?php
								// set up for inserting the WP post editor
								// media buttons enabled for logged in users
								$settings = array( 
								'textarea_name' => 'assignmentDescription',  
								'media_buttons' =>  is_user_logged_in(),
								'textarea_rows' => 12)
								;

								wp_editor(  stripslashes( $assignmentDescription ), 'assignmentDescriptionHTML', $settings );
							?>
						</div><!--formgroup-->
					</div><!--col-->
				</div><!--row-->


				<div class="row">
					<div class="col-sm-6">

						<div class="form-group">
							<label for="assignmentInstructions"><?php bank106_form_thing_instructions(); ?> (optional)</label>
							<span id="extrasHelpBlock" class="help-block"><?php bank106_form_thing_instructions_prompt();?></span>
							
							<textarea name="assignmentInstructions" id="assignmentInstructions" rows="4" class="form-control"  aria-describedby="extrasHelpBlock"><?php echo $assignmentInstructions; ?></textarea>	
						
						</div><!--formgroup-->

					
					</div><!--col-->				
					<div class="col-sm-6">
						
						<div class="form-group">
							<label for="assignmentExtras"><?php bank106_form_thing_end_notes(); ?> (optional)</label>
							<span id="extrasHelpBlock" class="help-block"><?php bank106_form_thing_end_notes_prompt();?></span>
							
							<textarea name="assignmentExtras" id="assignmentExtras" rows="4" class="form-control"   aria-describedby="extrasHelpBlock"><?php  echo $assignmentExtras; ?></textarea>	
						
						</div><!--formgroup-->					
					</div><!--col-->				
				</div><!--row-->				
				
				<div class="row">
					<div class="col-sm-6 clearfix">
						<div class="form-group<?php if (array_key_exists("assignmentType",$errors)) echo ' has-error ';?>" id="thing_type_hole" data-typelabel="<?php echo bank106_option( 'type_name' ) ?>">
							<label for="assignmentType"><?php bank106_form_thing_types(); ?></label>
							<span id="assignmentTypeHelpBlock" class="help-block"><?php echo bank106_form_thing_types_prompt()?></span>
					
							<?php 
								// build options based on assignment types
								// yes this might have been done with wp_dropdown_categories
					
								$atypes = get_assignment_types();
					
								foreach ($atypes as $thetype) {
									$checked = ( is_array($assignmentType) and in_array( $thetype->slug, $assignmentType ) ) ? 'checked="checked"' : ''; 
									echo '<div class="checkbox"><label><input type="checkbox" name="assignmentType[]" value="' . $thetype->slug . '" ' . $checked .'> ' . $thetype->name . '</label></div>';
								}					
								?>	
						</div><!--formgroup-->
 
						<?php if ( bank106_option('use_thing_cats') == 1 ): // offer only if categories in use and set for users to select ?>
 					
 					
						<!-- hack of a way to send the category label to jQuery for the preview -->
						<div class="form-group" id="thing_cat_hole" data-catlabel="<?php echo bank106_option( 'thing_cat_name' ) ?>">
					
							<label for="assignmentCategories"><?php bank106_form_thing_categories(); ?></label>
							<span id="assignmentCategoriesHelpBlock" class="help-block"><?php bank106_form_thing_categories_prompt(); ?></span>
										
						<?php  
							// let's walk the categories and output checkboxes for each
							foreach ($assignmentCats as $theCat) {
										$checked = ( is_array($assignmentCategories) and in_array( $theCat->slug, $assignmentCategories ) ) ? 'checked="checked"' : ''; 
											echo '<div class="checkbox"><label for="' . $theCat->slug . '"><input type="checkbox" name="assignmentCategories[]" id="' . $theCat->slug . '" value="' . $theCat->slug . '" ' . $checked .'> ' . $theCat->name . '</label></div>';
									
								// are there children? If so walk them and do the same.			
								if ( is_array( $theCat->children ) ) {
									foreach ($theCat->children as $subCat) {
										$checked = ( is_array($assignmentCategories) and in_array( $subCat->slug, $assignmentCategories ) ) ? 'checked="checked"' : ''; 
										echo '<div class="checkbox" style="margin-left:1em;"><label for="' . $subCat->slug . '"><input type="checkbox" name="assignmentCategories[]" id="' . $subCat->slug .'" value="' . $subCat->slug . '" ' . $checked .'> ' . $subCat->name . '</label></div>';
									}
								}
							}
							?>
							
						</div><!--formgroup-->
					
						<?php endif; // for cats in use. Meow ?>
 
						<div class="form-group<?php if (array_key_exists("assignmentTags",$errors)) echo ' has-error ';?>">
							<label for="assignmentTags"><?php bank106_form_thing_tags();?></label>

							<input type="text" name="assignmentTags" class="form-control" id="assignmentTags" value="<?php echo $assignmentTags; ?>" aria-describedby="tagHelpBlock" />
							<span id="tagHelpBlock" class="help-block"><?php bank106_form_thing_tags_prompt();?></span>
						</div><!--formgroup-->

						<?php if ( bank106_option('difficulty_rating') ) :?>
							<div class="form-group">
								<label for="assignmentDifficulty"><?php bank106_form_thing_difficulty();?></label>
								<span id="assignmentDifficultyHelpBlock" class="help-block"><?php bank106_form_thing_difficulty_prompt();?></span>
						
								<?php 
									// labels for ratings, might make this an option one day!
									$extralabels = ['', ' very easy' , '', '', '',' very difficult'];
							
									for ( $i=1; $i<6; $i++ ) {
										$checked = ( $i == $assignmentDifficulty ) ? ' checked' : '';
								
										echo '<div class="radio"><label><input type="radio" name="assignmentDifficulty"  value="' . $i . '"' . $checked . '> ' .  $i . $extralabels[$i] . '</label></div>';
									}
								?>
							</div><!--formgroup-->
						<?php endif?>
				
						<?php if ( function_exists( 'the_ratings' ) ): // use ratings input
							$use_public_ratings = true; // flag this for later
					
							// get the prompt for the ratings
							$postratings_ratingstext = get_option( 'postratings_ratingstext' );

		?>
							<div class="form-group">
								<label for="assignmentRating"><?php bank106_form_thing_user_rating(); ?></label>
								<span id="twitterHelpBlock" class="help-block"><?php bank106_form_thing_user_rating_prompt(); ?></span>
			
								<?php
								// get wp-ratings settings
								$postratings_max = intval( get_option( 'postratings_max' ) );
								$ratings_image = get_option( 'postratings_image' );
						
								// place holder string for preview
								$ratingsHTML = get_ratings_images( '',  $postratings_max, $assignmentRating, $ratings_image, '', '') . ' (<strong>1</strong> rating, average <strong>' . $assignmentRating . '.00</strong> out of <strong>' . $postratings_max . '</strong>)';

									// rlist the options and labels for each available rating
									for ( $i = 1; $i <= $postratings_max; $i++ ) {
		
										$is_checked = ( $assignmentRating == $i) ? ' checked' : '';
										$rating_label = ( empty( $postratings_ratingstext[$i-1] ) ) ? $i : $postratings_ratingstext[$i-1];
										echo '<div class="radio"><label><input type="radio" name="assignmentRating" value="' . $i . '"' . $is_checked . '"/> ' . get_ratings_images('',  $postratings_max, $i, $ratings_image, '', '')  . ' '   . $rating_label .  '</label></div>';
									}
								?>
								</div><!--formgroup-->
							<?php endif?>
							
					</div> <!-- end col-sm-6 -->			
			
					<div class="col-sm-6">

						<div class="form-group">
							<label for="uploadThumb"><?php bank106_form_thing_thumbnail();?></label>
							<span id="uploadHelpBlock" class="help-block"><?php bank106_form_thing_thumbnail_prompt();?> (&lt; <?php echo bank106_option('upload_max' )?> Mb). Images should be larger than <?php echo bank106_option('thumb_w' )?>x<?php echo bank106_option('thumb_h' )?> px (they will be center cropped to these proportions). <span id="uploadresponse"><?php echo $assignment_thumb_status?></span></span>
						
							<?php 
						
							if ( $assignment_thumb_id ) {
								//display the last uploaded file
								//echo wp_get_attachment_image( $assignment_thumb_id, 'thumbnail', 0, array('id' => 'thingthumb') );
								$defthumb = wp_get_attachment_image_src( $assignment_thumb_id, 'thumbnail' );
							} else {
								// display the default one
								$defthumb = [];
								$defthumb[] = bank106_option('def_thumb' );
							}
							?>
							
							<img src="<?php echo $defthumb[0]?>" alt="thumbnail image for this thing" id="thingthumb"  />
							<div>
								<input type="file" class="filestyle" data-buttonText="Choose Image" data-iconName="glyphicon-upload" name="assignmentImage" id="assignmentImage"  aria-describedby="uploadHelpBlock" />
							</div>
			
						</div><!--formgroup-->

				
					<?php if ( bank106_option( 'use_cc' ) != 'none' ):?>
						<!-- creative commons options -->
					
						<div class="form-group<?php if (array_key_exists("assignmentCC",$errors)) echo ' has-error ';?>">
					
							<?php if (bank106_option( 'use_cc' ) == 'site') :?>
					
							<label for="assignmentCC"><?php _e( 'Creative Commons License Applied', 'wpbootstrap' )?></label>
								<span class="help-block">All <?php echo lcfirst(bank106_option( 'thingname'))?> added to this site will be licensed</span>
								<p class="form-control-static"><?php echo cc_license_html(bank106_option( 'cc_site' ));?></p>
				
							<?php elseif  (bank106_option( 'use_cc' ) == 'user') :?>
								<label for="assignmentCC"><?php _e( 'Choose a license to apply to this ' . bank106_option( 'thingname' ), 'wpbootstrap' )?></label>
								<select name="assignmentCC" id="assignmentCC" class="form-control">
								<option value="--">Select...</option>
								<?php echo cc_license_select_options( $assignmentCC )?>
								</select>				
							<?php endif; // -- cc_mode type = site or user?>
						
						</div><!--formgroup-->
						<?php endif; // -- cc_mode != none?>
						
					</div> <!-- form column -->
				</div> <!-- form row -->	
			
				<div class="clearfix row"><!-- form row -->	
					<div class="col-sm-6">

						<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
							<label for="submitterName"><?php _e( 'Your name (required)', 'wpbootstrap' ) ?></label>
							<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" aria-describedby="nameHelpBlock" />
							<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this <?php echo $sub_type?></span>
						</div><!--formgroup-->
				
						<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
							<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'wpbootstrap' ) ?> </label>
							<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>"  placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
							<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
						</div><!--formgroup-->	
								
						<?php if ( bank106_option('use_wp_login') == 2 ): // WP login required ?>

							<div class="form-group">
								<label for="submitterUsername"><?php _e( 'Your user name ', 'wpbootstrap' ) ?></label>
								<input type="text" name="submitterUsername" class="form-control" id="submitterUsername" value="<?php echo $submitterUsername; ?>"  aria-describedby="submitterUsernameHelpBlock" readonly/>
								<span id="submitterUsernameHelpBlock" class="help-block">Your username is automatically entered</span>
							</div><!--formgroup-->						
									
						
						<?php elseif ( bank106_option( 'user_code_name' ) or bank106_option('use_wp_login') == 1 ): ?>

							<div class="form-group<?php if (array_key_exists("submitterUsername",$errors)) echo ' has-error ';?>">
								<label for="submitterUsername"><?php _e( 'Your unique user name ', 'wpbootstrap' ) ?> <?php if (bank106_option( 'user_code_name' ) == 1) { echo '(optional)'; } else { echo '(required)';}?></label>
								<input type="text" name="submitterUsername" class="form-control" id="submitterUsername" value="<?php echo $submitterUsername; ?>"   aria-describedby="submitterUsernameHelpBlock" />
								<span id="submitterUsernameHelpBlock" class="help-block">Enter a unique username to identify your work <?php if (bank106_option('use_wp_login')) echo ' (Your login name is automatically entered)'?></span>
							</div><!--formgroup-->
	
						<?php endif?>		
					</div> 	<!-- end col-sm-6 -->	
	
				<div class="col-sm-6">	

				<!-- gotta nonce -->
				<?php wp_nonce_field( 'bank106_form_add_assignment', 'bank106_form_add_assignment_submitted' ); ?>
			
				<!-- hidden data stored for preview use -->
				<input type="hidden" id="thingName" value="<?php echo bank106_option( 'thingname' )?>" />
				<input type="hidden" name="assignment_thumb_id"  value="<?php echo $assignment_thumb_id?>" />
				<input type="hidden" id="embedMedia" value="<?php echo htmlentities( get_media_embedded ( $assignmentURL ))?>" />
				<input type="hidden" name="useCaptcha"  value="<?php echo $use_captcha?>" />
				
				<?php if ($use_public_ratings):?>
					<input type="hidden" id="embedRating" value="<?php echo htmlentities( $ratingsHTML )?>" />
				<?php else:?>
					<input type="hidden" id="embedRating" value="-1" />
				<?php endif?>
						

				<div class="form-group" id="prettybuttons">
					<label for="submitassignment"><?php _e( 'Review and Submit this ' . bank106_option( 'thingname' ), 'wpbootstrap' )?></label>
					
					<div class="row"> <!-- submit buttons row -->
						<div class="col-xs-4 col-md-3">
							<button type="submit" class="btn btn-primary" id="updateassignment" name="updateassignment">
  								<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update
							</button>
						</div><!--col-->
						
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
						</div><!--col-->
						
						<div class="col-xs-4 col-md-3">
						<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your <?php echo lcfirst(bank106_option( 'thingname' ))?>; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
						</div><!--col-->
						
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Generate a preview of your submission. If the body content does not change, try clicking "Update" agan.</span>
						</div><!--col-->
						
						<div class="col-xs-4 col-md-3">				
					
							<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitassignment" name="submitassignment">
  								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
							</button>
						</div><!--col-->
						
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Once every thing looks good, submit this <?php echo lcfirst(bank106_option( 'thingname' ))?> to <?php echo get_bloginfo( 'name' )?>.</span>
						</div><!--col-->
					</div>	<!-- end submit buttons row -->									
				</div><!--formgroup-->
				
					
				<?php if ( $use_captcha ):?>
			
					<div class="form-group<?php if (array_key_exists("recaptcha",$errors)) echo ' has-error ';?>">
						<label for="recaptcha"><?php _e( 'Spam protection', 'wpbootstrap' )?></label>
						<span id="recaptchaHelpBlock" class="help-block">Unfortunately, this test is necessary to keep this site safe from spammers. Please enter the code!</span>
						<div class="g-recaptcha" data-sitekey="<?php echo bank106_option('captcha_pub')?>"></div>
						
					</div><!--formgroup-->
				<?php endif?>

				
			</div>	<!-- end col-sm-6-->					
		</div> <!-- end row -->	
	</form>
	
	<?php endif?>

	
	
<?php get_footer(); ?>