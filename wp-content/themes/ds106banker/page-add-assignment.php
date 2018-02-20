<?php
/*
Template Name: Submit Thing Form
Creates a new thing for a ds106 bank like site from user input via web form.

*/

// enqueue jquery for this form
add_action( 'wp_enqueue_scripts', 'ds106bank_enqueue_add_thing_scripts' );

// ----- get options ---------------------
// allow authors to define a difficulty rating?
$use_difficulty_rating = ds106bank_option('difficulty_rating');

// creative commons usage mode
$my_cc_mode = ds106bank_option( 'use_cc' ); 

// status for new submissions
$my_new_status = ds106bank_option( 'new_thing_status' );

// flag for using/requring twitter on form
$use_twitter_name = ds106bank_option( 'use_twitter_name' );

// use evil captchas?
$use_captcha = ds106bank_option('use_captcha');

// use thing categories (=1 to use on this form)
$use_thing_cats = ds106bank_option('use_thing_cats');

// require wp login?
$use_wp_login = ds106bank_option('use_wp_login');


// ----- set defaults ---------------------
// set af default rating values

// mmm cookies
$submitterTwitter = $_COOKIE["bank106twitter"];
$submitterName = $_COOKIE["bank106name"];
$submitterEmail = $_COOKIE["bank106email"];


$assignmentRating = 3;    	// default public rating
$assignmentDifficulty = 3;  // default author rating
$assignmentURL = ''; 		// start it empty, baby
$errors = array(); 			// holder for bad form entry warnings

$feedback_msg = '<div class="alert alert-info" role="alert">Create a new ' . lcfirst(THINGNAME) . ' right here! Enter all required information below and <a href="#prettybuttons" style="text-decoration: underline">use the buttons below</a> to <a href="#" class="btn btn-primary btn-xs disabled">update</a> your information to verify it. Then you can modify and <a href="#" class="btn btn-warning btn-xs disabled">preview</a> as much as necessary to make it look beautiful (get it right, you will not be able to edit it later). Once you are happy with it, <a href="#" class="btn btn-success btn-xs disabled">submit</a> the form for the last time and it will be saved to this site.';

$previewBtnState = ' disabled';
$submitBtnState = ' disabled';


if ( is_user_logged_in() ) {
	//bypass captcha for logged in users
	$use_captcha = false;
	
	$current_user = wp_get_current_user();
	
	$feedback_msg .= '<br /><br />All ' . THINGNAME . 's submitted here will be associated with your current login in as <strong>' . $current_user->display_name . '</strong>.' ;

	
} else {
	
	
	if ( $use_wp_login == 1) {
		// WP login optional
		
		
		$feedback_msg .= '<br /><br />Logging in to this site is not required, but if you wish to have all  ' . THINGNAME . 's created  associated with your name ' . sprintf( '<a href="%s" class="btn btn-primary btn-xs">%s</a>', wp_login_url( get_permalink( ) ), __( 'sign in now' ) );  
	
	// WP login requred
	} elseif ( $use_wp_login == 2 ) {
		$must_login = true;
		
		// feedback message now an alert
		$feedback_msg = '<div class="alert alert-danger" role="alert"> You must sign in to ' . bloginfo( 'name' ) . ' to add a '  . THINGNAME .  ' to this site. ' . sprintf( '<a href="%s" class="btn btn-primary btn-xs">%s</a>', wp_login_url( get_permalink( ) ), __( 'sign in now' ) );
	}
		
}

$feedback_msg .=  '</div>'; // close dat div


if ($use_thing_cats == 1) {
	// ony if we are using  taxonomy terms (categories for asignments)
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

// include captch lib if we need to
if ($use_captcha) require_once( get_stylesheet_directory() . '/includes/recaptchalib.php');

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_assignment_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_assignment_submitted'], 'bank106_form_add_assignment' ) ) {
 
 		// grab the variables from the form
 		$assignmentTitle = 			stripslashes(sanitize_text_field( $_POST['assignmentTitle'] ));		
 		$submitterName = 			stripslashes(sanitize_text_field( $_POST['submitterName'] )); 
 		$assignmentTags = 			cleanTags( sanitize_text_field( $_POST['assignmentTags'] ) );	
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$assignmentDescription = 	$_POST['assignmentDescription'];
 		$assignmentType = 			$_POST['assignmentType'];
 		$assignmentCategories = 	$_POST['assignmentCategories'];
 		$assignmentRating = 		$_POST['assignmentRating'];	
 		$assignmentDifficulty = 	$_POST['assignmentDifficulty'];		
 		$assignmentURL = 			esc_url( trim($_POST['assignmentURL']), array('http', 'https') ); 
 		$assignmentCC = 			$_POST['assignmentCC'];
 		$assignmentExtras = 		stripslashes(sanitize_text_field( $_POST['assignmentExtras'] ));
 		$assignment_thumb_id = 		$_POST['assignment_thumb_id'];
 		
 		$assignmentInstructions = 	stripslashes(sanitize_text_field( $_POST['assignmentInstructions'] )); 

 		if ($use_twitter_name) {
 		
 			$submitterTwitter = sanitize_text_field( $_POST['submitterTwitter'] ); 
 			// set a cookie
 			setcookie( "bank106twitter", $submitterTwitter, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */
 			
 		}
 		
 		// more cookies
 		setcookie( "bank106name", $submitterName, strtotime( '+14 days' ), '/' );  /* expire in 14 days */
 		setcookie( "bank106email", $submitterEmail, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */
 		

		// upload thumnbail if selected
		if ( $_FILES ) {
			
			foreach ( $_FILES as $file => $array ) {
				$newupload = bank106_insert_attachment( $file, $post_id );
				if ($newupload) $assignment_thumb_id = $newupload;
				
			}
		}
 			
 		// let's do some validation, store an error message for each problem found
	
 		if ( $assignmentTitle == '' ) $errors['assignmentTitle'] = '<span class="label label-danger">' . THINGNAME . ' Title Missing</span> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
 		if ( $submitterEmail == '' ) {
 			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}

 		if ( $use_twitter_name) {
 			if ( $submitterTwitter == '' and  $use_twitter_name == 2) {
 				$errors['submitterTwitter'] = '<span class="label label-danger">Twitter Name Missing</span> - please enter your twitter user name, it is required.';
 			} elseif ( strlen($submitterTwitter) > 2 AND substr( $submitterTwitter, 0, 1 ) != '@')  {
 				$errors['submitterTwitter'] = '<span class="label label-danger">@ Missing in Twitter Name</span>- a twitter username must begin with "@", it was added to your entry, but please review' . $submitterTwitter;
 				$submitterTwitter = '@' . $submitterTwitter;
 			}	
 		}
	
 		// arbitrary and puny string length to be considered a reasonable descriptions
 		if ( strlen( $assignmentDescription ) < 10 )  $errors['assignmentDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that will help someone complete this ' . lcfirst(THINGNAME) . '. You might need a sentence or two.';
 		
 		if ( empty( $assignmentType ) ) $errors['assignmentType'] = '<span class="label label-danger">' .  THINGNAME . ' Type Not Selected</span>- select at least one type of ' . lcfirst(THINGNAME);
 		 		
 		// check selection of license option
 		if ($assignmentCC == '--')  {
 			$errors['assignmentCC'] = '<span class="label label-danger">Creative Commons License not Selected</span>- Choose the license you wish to attach to thie ' . lcfirst(THINGNAME);	 
 		}
 		
		if ($assignmentURL == '') {
 				$errors['assignmentURL'] = '<span class="label label-danger">URL Missing or not Entered Correctly</span>-  please enter the full URL where the example for this ' .  lcfirst(THINGNAME) . ' can be found- it must start with "http://"';	 
 		} // end url CHECK 	
 					
 		// check captcha
		if ( $use_captcha and isset( $_POST["recaptcha_response_field"] ) ) {
				$resp = recaptcha_check_answer ( ds106bank_option('captcha_pri'),
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
				
				if ( !( $resp->is_valid ) ) {
					# set the error code so that we can display it
					$captcha_error = $resp->error;
					$errors['captcha'] = '<span class="label label-danger">Captcha Error</span>- please retry the captcha field.';
				}
		}
 				
 		if ( count($errors) > 0 ) {
 			// form errors, build feedback string to display the errors
 			$feedback_msg = '<div class="alert alert-danger" role="alert">Sorry, but there are a few errors in your entry. Please correct the following items and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
 		
 			// set up stuff if we are just doing a preview
 			$previewBtnState = '';
 			$feedback_msg = '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-thumbs-up""></span> The information for this new ' . THINGNAME . ' seems to be ok! Now you can preview your entry; continue to edit and modify until you think it is ready. You must <a href="#" class="btn btn-warning btn-xs disabled">preview</a> at least once to activate the submit button. Note that if you change the URL, you will have to click the <a href="#" class="btn btn-primary btn-xs disabled">update</a> button again to refresh its content.</div>';
 			
 			
 			$assignmentDescription = oembed_filter( $assignmentDescription );
 			
 			// Now process form only if submit button used
 			if ( isset ( $_POST['submitassignment'] ) ) {
 			
 				$add_tags = $assignmentTags; // holder for tags
			
				// add a tag for twitter name, if provided
				if ( $submitterTwitter != '' ) $add_tags .= ',' . $submitterTwitter;
 			
				// good enough, let's add this thing
				$assignment_information = array(
					'post_title' => $assignmentTitle,
					'post_content' => $assignmentDescription,
					'tags_input'  => $add_tags,
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
					
					// give twitter credit if used
					if ( $submitterTwitter ) update_post_meta( $post_id, 'submitter_twitter', esc_attr( $submitterTwitter ) );
				
					// set the term for the type of thing
					wp_set_object_terms( $post_id, $assignmentType, 'assignmenttypes');
					
					// set the taxonomy terms for categories for the type of thing (if we are using cats)
					if ( $use_thing_cats ) wp_set_object_terms( $post_id, $assignmentCategories, 'assignmentcats');
				
					// update the new tags
					update_assignment_tags( $post_id );

					// update post meta for the initial rating, the average and score = the entered value
					update_post_meta( $post_id,  'ratings_average', $assignmentRating );
					update_post_meta( $post_id,  'ratings_score', $assignmentRating );
	
					// the rating count set to 1
					update_post_meta( $post_id,  'ratings_users', 1 );
					
					// give it a count
					update_post_meta( $post_id,  'assignment_visits', 1 );
				
					if ( $use_difficulty_rating ) update_post_meta( $post_id,  'assignment_difficulty', $assignmentDifficulty );

					// user selected license
					if ( $my_cc_mode == 'user' ) update_post_meta( $post_id,  'cc', $assignmentCC);
					
					// extra info for assignment
					update_post_meta( $post_id,  'assignment_extras', $assignmentExtras );
					
					// specific instructions for assignment
					update_post_meta( $post_id,  'assignment_instructions', $assignmentInstructions );
					
					// if we got an attachment id, then update meta data to indicate thumbnal
					if ($assignment_thumb_id) update_post_meta ($post_id, '_thumbnail_id', $assignment_thumb_id );	
				
					if  ( ds106bank_option( 'new_thing_status' ) == 'publish' ) {
				
						// build feedback if new things are automatically published
					
						// grab link to new assignment
						$assignmentLink = get_permalink( $post_id );
				
						// feedback success
						$feedback_msg = '<div class="alert alert-success" role="alert">The new ' . THINGNAME . ' has been created. Check out <a href="' . get_permalink( $post_id ) . '" class="alert-link">' . $assignmentTitle . '</a> or you can <a href="' . get_permalink( $current_ID ) .'"  class="alert-link">create another ' . lcfirst(THINGNAME) . '</a>.</div>';  
					
					} else {
						// feedback if new things are set to draft
						$feedback_msg = '<div class="alert alert-success" role="alert">Your new ' . THINGNAME . ', "' . $assignmentTitle . '" has been created. Once it has been approved it will appear on this site. Do you want to <a href="' . get_permalink( $current_ID ) .'"  class="alert-link">create another ' . lcfirst(THINGNAME) . '</a>?</div>';  
				
					}
 
				} else {
			
					// generic error of post creation failed
					$feedback_msg = '<div class="alert alert-danger" role="alert">ERROR: the new ' . lcfirst(THINGNAME) . ' could not be created. We are not sure why, but let someone know.</div>';
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
				</div>
			
			<?php if (!$post_id and !$must_login) : //hide form if we had success ?>
			
				<form action="" id="bank106form" class="bank106form" method="post" action="" autocomplete="on" enctype="multipart/form-data">
	
				<div class="clearfix row">
		
				<div class="col-sm-12">
					<div class="form-group<?php if (array_key_exists("assignmentTitle",$errors)) echo ' has-error ';?>">
						<label for="assignmentTitle"><?php _e( 'Title for this ' . ucfirst(THINGNAME), 'wpbootstrap' ) ?></label>
						<input type="text" name="assignmentTitle" id="assignmentTitle" value="<?php  echo $assignmentTitle; ?>" class="form-control" tabindex="1" placeholder="Enter a title" aria-describedby="titleHelpBlock" />
						<span id="titleHelpBlock" class="help-block">Enter a title that describes this <?php echo THINGNAME?> so that it might make a curious visitor want to read more about it.</span>
					</div>
			
					<div class="form-group<?php if (array_key_exists("assignmentDescription",$errors)) echo ' has-error ';?>">
							<label for="assignmentDescription"><?php _e( 'Full Description for this '  . ucfirst(THINGNAME), 'wpbootstrap') ?></label>
							<span id="assignmentHelpBlock" class="help-block">Use the rich text editor to compose everything someone might need to complete this <?php echo THINGNAME?>. See  <a href="https://make.wordpress.org/support/user-manual/content/editors/visual-editor/" target="_blank">documentation for using the editing tools</a> (link will open in a new tab/window). To embed media from YouTube, vimeo, instagram, SoundCloud, Twitter, flickr, just put the URL for its source page as plain text on a blank line. When your <?php echo THINGNAME?> is published the link will be replaced by a media embed.</span>
							<?php
								// set up for inserting the WP post editor
								$settings = array( 'textarea_name' => 'assignmentDescription',  'tabindex'  => "3", 'media_buttons' => false, 'textarea_rows' => 8);

								wp_editor(  stripslashes( $assignmentDescription ), 'assignmentDescriptionHTML', $settings );
							?>
					</div>
				</div>

				<div class="col-sm-6 clearfix">
		
					<div class="form-group<?php if (array_key_exists("assignmentType",$errors)) echo ' has-error ';?>">
						<label for="assignmentType"><?php _e( 'Type of ' . THINGNAME , 'wpbootstrap' ) ?></label>
						<span id="assignmentTypeHelpBlock" class="help-block">Choose at least one.</span>
					
						<?php 
							// build options based on assignment types
							// yes this might have been done with wp_dropdown_categories
					
							$atypes = get_assignment_types();
					
							foreach ($atypes as $thetype) {
								$checked = ( is_array($assignmentType) and in_array( $thetype->slug, $assignmentType ) ) ? 'checked="checked"' : ''; 
								echo '<div class="checkbox"><label><input type="checkbox" name="assignmentType[]" value="' . $thetype->slug . '" ' . $checked .'> ' . $thetype->name . '</label></div>';
							}					
							?>	
					</div>
 
 					<?php if ( $use_thing_cats == 1 ): // offer only if categories in use and set for users to select ?>
 					
 					<!-- hack of a way to send the category label to jQuery for the preview -->
 					<div class="form-group" id="thing_cat_hole" data-catlabel="<?php echo ds106bank_option( 'thing_cat_name' ) ?>">
 					
 					<label for="assignmentCategories"><?php _e( THINGNAME . ' ' . ds106bank_option( 'thing_cat_name' ) , 'wpbootstrap' ) ?></label>
 					<span id="assignmentCategoriesHelpBlock" class="help-block">Choose any/all that apply.</span>
 					 					
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
							
 					</div>
 					
 					<?php endif; // for cats in use. Meow ?>
 
					<div class="form-group<?php if (array_key_exists("assignmentTags",$errors)) echo ' has-error ';?>">
						<label for="assignmentTags"><?php _e( 'Tags that describe this ' . THINGNAME . ' (optional)', 'wpbootstrap' ) ?></label>

						<input type="text" name="assignmentTags" class="form-control" id="assignmentTags" value="<?php echo $assignmentTags; ?>" tabindex="4" aria-describedby="tagHelpBlock" />
						<span id="tagHelpBlock" class="help-block">All tags must be a single word; separate each tag with a comma or a space.</span>
					</div>

					<?php if ( $use_difficulty_rating ) :?>
						<div class="form-group">
							<label for="assignmentDifficulty"><?php _e( 'Difficulty Rating' , 'wpbootstrap' ) ?></label>
							<span id="assignmentDifficultyHelpBlock" class="help-block">As the creator of this <?php echo THINGNAME?> you can assign a difficulty rating that is displayed when it is viewed.</span>
						
							<?php 
								// labels for ratings, might make this an option one day!
								$extralabels = ['', ' very easy' , '', '', '',' very difficult'];
							
								for ( $i=1; $i<6; $i++ ) {
									$checked = ( $i == $assignmentDifficulty ) ? ' checked' : '';
								
									echo '<div class="radio"><label><input type="radio" name="assignmentDifficulty"  value="' . $i . '"' . $checked . '> ' .  $i . $extralabels[$i] . '</label></div>';
								}
							?>
						</div>
					<?php endif?>
				
					<?php if ( function_exists( 'the_ratings' ) ): // use ratings input
						$use_public_ratings = true; // flag this for later
					
						// get the prompt for the ratings
						$postratings_ratingstext = get_option( 'postratings_ratingstext' );

	?>
						<div class="form-group">
							<label for="assignmentRating"><?php _e( 'Initial Public Rating' , 'wpbootstrap' ) ?></label>
							<span id="twitterHelpBlock" class="help-block">Any visitor can rate this <?php echo THINGNAME?> on the scale shown below. Give it an initial seed value.</span>
			
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
									echo '<div class="radio"><label><input type="radio" name="assignmentRating" value="' . $i . '"' . $is_checked . ' tabindex="' . (5 + $i) . '"/> ' . get_ratings_images('',  $postratings_max, $i, $ratings_image, '', '')  . ' '   . $rating_label .  '</label></div>';
								}
							?>
							</div>
						<?php endif?>
					</div> <!-- end column -->			

			
					<div class="col-sm-6">

						<div class="form-group<?php if (array_key_exists("assignmentURL",$errors)) echo ' has-error ';?>">
								<label for="assignmentURL"><?php _e( 'Web address for an example of this ' . THINGNAME, 'wpbootstrap' )?> <a href="<?php echo $assignmentURL?>" class="btn btn-xs btn-warning" id="testURL" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Test Link</a></label>
								<input type="url" name="assignmentURL" id="assignmentURL" class="form-control" value="<?php echo $assignmentURL; ?>" tabindex="13" placeholder="http://" aria-describedby="urlHelpBlock" /> 
								<span id="urlHelpBlock" class="help-block">Enter the URL that is the example for the <?php echo THINGNAME?> you are editing. If the link is an mp3 file or is on YouTube, vimeo, soundcloud, or flickr, then it will be embedded; otherwise it will be linked. Please test the link to make sure it works.</span>
						</div>		
					
						<div class="form-group">
							<label for="uploadThumb"><?php _e( 'Upload Thumbnail Image', 'wpbootstrap' )?></label>
							<span id="uploadHelpBlock" class="help-block">Upload a JPG or PNG image to represent your <?php echo THINGNAME?> in place of the default image below. Please use an image size larger than <?php echo THUMBW?>x<?php echo THUMBH?> pixels (it will be center cropped to these proportions). </span>
						
							<?php 
						
							if ( $assignment_thumb_id ) {
								//display the last uploaded file
								echo wp_get_attachment_image( $assignment_thumb_id, 'thumbnail', 0, array('id' => 'thingthumb') );
							} else {
								// display the default one
								echo '<img src="' . ds106bank_option('def_thumb' ) . '" id="thingthumb" class="" alt="" />';
							}
							?>
							<div><input type="file" class="filestyle" data-buttonText="Choose Image" data-iconName="glyphicon-upload" name="assignmentImage" id="assignmentImage" tabindex="14" aria-describedby="uploadHelpBlock" /></div>
				
						</div>
						
						<div class="form-group">
							<label for="assignmentExtras"><?php _e( 'Extra Information' , 'wpbootstrap' ) ?></label>
							<span id="extrasHelpBlock" class="help-block">Use this field to append additional end notes for this <?php echo THINGNAME?> such as an attribution for the thumbnail image or other credits. Any  HTML will be removed but URLs will be converted to hyperlinks.</span>
							
							<textarea name="assignmentExtras" id="assignmentExtras" rows="4" class="form-control" tabindex="15"  aria-describedby="extrasHelpBlock"><?php  echo $assignmentExtras; ?></textarea>	
						
						</div>
						
						<?php if ( ds106bank_option('example_via_form') ) : // use only if doing form subs?>
						<div class="form-group">
							<label for="assignmentInstructions"><?php echo THINGNAME?><?php _e(' Specific Information' , 'wpbootstrap' ) ?></label>
							<span id="extrasHelpBlock" class="help-block">Insert instructions that are individualized for this item that will appear above the form for adding a response</span>
							
							<textarea name="assignmentInstructions" id="assignmentInstructions" rows="4" class="form-control" tabindex="16"  aria-describedby="extrasHelpBlock"><?php  echo $assignmentInstructions; ?></textarea>	
						
						</div>

						<?php endif?>
				
					<?php if ( $my_cc_mode != 'none' ):?>
						<!-- creative commons options -->
					
						<div class="form-group<?php if (array_key_exists("assignmentCC",$errors)) echo ' has-error ';?>">
					
							<?php if ($my_cc_mode == 'site') :?>
					
							<label for="assignmentCC"><?php _e( 'Creative Commons License Applied', 'wpbootstrap' )?></label>
								<span class="help-block">All <?php echo lcfirst(THINGNAME)?>s added to this site will be licensed</span>
								<p class="form-control-static"><?php echo cc_license_html(ds106bank_option( 'cc_site' ));?></p>
				
							<?php elseif  ($my_cc_mode == 'user') :?>
								<label for="assignmentCC"><?php _e( 'Choose a license to apply to this ' . THINGNAME, 'wpbootstrap' )?></label>
								<select name="assignmentCC" id="assignmentCC" class="form-control">
								<option value="--">Select...</option>
								<?php echo cc_license_select_options( $assignmentCC )?>
								</select>				
							<?php endif; // -- cc_mode type = site or user?>
						</div>
						<?php endif; // -- cc_mode != none?>
					</div> <!-- form column -->
				</div> <!-- form row -->	
			
				<div class="clearfix row"><!-- form row -->	
					<div class="col-sm-6">

						<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
							<label for="submitterName"><?php _e( 'Your name (required)', 'wpbootstrap' ) ?></label>
							<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" tabindex="5" aria-describedby="nameHelpBlock" />
							<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this <?php echo $sub_type?></span>
						</div>
				
						<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
							<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'wpbootstrap' ) ?> </label>
							<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>" tabindex="6" placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
							<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
						</div>				
						
						<?php if ( $use_twitter_name ): ?>

						<div class="form-group<?php if (array_key_exists("submitterTwitter",$errors)) echo ' has-error ';?>">
							<label for="submitterTwitter"><?php _e( 'Your Twitter username ', 'wpbootstrap' ) ?> <?php if ($use_twitter_name == 1) { echo '(optional)'; } else { echo '(required)';}?></label>
							<input type="text" name="submitterTwitter" class="form-control" id="submitterTwitter" value="<?php echo $submitterTwitter; ?>" tabindex="7" placeholder="@" aria-describedby="twitterHelpBlock" />
							<span id="twitterHelpBlock" class="help-block">Enter your twitter name including the "@" symbol</span>
						</div>	
			
						<?php endif?>		
					</div> 	<!-- end column -->			
		
				<!-- gotta nonce -->
				<?php wp_nonce_field( 'bank106_form_add_assignment', 'bank106_form_add_assignment_submitted' ); ?>
			
				<!-- hidden data stored for preview use -->
				<input type="hidden" id="thingName" value="<?php echo THINGNAME?>" />
				<input type="hidden" name="assignment_thumb_id"  value="<?php echo $assignment_thumb_id?>" />
				<input type="hidden" id="embedMedia" value="<?php echo htmlentities( get_media_embedded ( $assignmentURL ))?>" />
		
				<?php if ($use_public_ratings):?>
					<input type="hidden" id="embedRating" value="<?php echo htmlentities( $ratingsHTML )?>" />
				<?php else:?>
					<input type="hidden" id="embedRating" value="-1" />
				<?php endif?>
						
					
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
					
				<div class="form-group" id="prettybuttons">
					<label for="submitassignment"><?php _e( 'Review and Submit this ' . THINGNAME, 'wpbootstrap' )?></label>
					
					<div class="row"> <!-- submit buttons row -->
						<div class="col-xs-4 col-md-3">
							<button type="submit" class="btn btn-primary" id="updateassignment" name="updateassignment">
  								<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update
							</button>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
						</div>
						
						<div class="col-xs-4 col-md-3">
						<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your <?php echo lcfirst(THINGNAME)?>; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Generate a preview of your submission. If the body content does not change, try clicking "Update" agan.</span>
						</div>	
						
						<div class="col-xs-4 col-md-3">				
					
							<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitassignment" name="submitassignment">
  								<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
							</button>
						</div>
						<div class="col-xs-8 col-md-9">
							<span class="help-block">Once every thing looks good, submit this <?php echo lcfirst(THINGNAME)?> to the site.</span>
						</div>
					</div>	<!-- end submit buttons row -->									

				</div>	<!-- end columns-->					
			</div> <!-- end row -->	
	</form>
	
	<?php endif?>
	</div> <!-- end row -->		

	</div> <!-- end #main -->
	
	
<?php get_footer(); ?>