<?php
/*
Template Name: Submit Response/Tutorial Form

Lets visitors add a new response or tutorial/resource for an assignment via a web form.

*/

// load scripts
bank106_enqueue_add_ex_scripts();

// seed variables with empty strings
$post_id = $must_login = $exampleTitle = $exampleDescription = $exampleTags = $exampleSource = $submitterName = $submitterEmail = $submitterUsername = $exampleURL = $exampleURLstatus  = '';

// mmm cookies, load if present
if ( isset($_COOKIE["bank106username"] )) $submitterUsername = $_COOKIE["bank106username"];
if ( isset($_COOKIE["bank106name"] ))  $submitterName = $_COOKIE["bank106name"];
if ( isset($_COOKIE["bank106email"] ))  $submitterEmail = $_COOKIE["bank106email"];

// disable buttons until ready
$previewBtnState = $submitBtnState = ' disabled';

// assignment id
$aid = $wp_query->query_vars['aid']; 

// flag to NOT use rich text editor and full preview features (default)	
$use_full_editor = false;

// holder for boo-boos
$errors = array();

// type of submissions (in query string)
$typ = $wp_query->query_vars['typ']; 

// keep track of the kind of submission		
if 	($typ == 'tut') {
	// a tutorial type
	
	add_action('wp_enqueue_scripts', 'bank106_enqueue_simpletext_scripts');
	
	$sub_type = strtolower( bank106_option('helpthingname') );
	
	$sub_type_title = $sub_type . ' for ';
	
	$feedback_msg = '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Prompt:</span> Enter all of information below to describe one ' . $sub_type . ' that would be helpful to someone else who responds to this '  . bank106_option( 'thingname' ) . '. It must be something available on the web at a public URL. ';

} else {
	// an example type that is a response to an assignment/thing
	
	$sub_type = 'response';

	$sub_type_title = $sub_type . ' to ';

	// start with generalized instructions for all things
	if ( !empty( bank106_option( 'example_gen' ) ) ) {
		$feedback_msg = '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
  <span class="sr-only">Note:</span> <strong>' . bank106_option( 'example_gen' ) . "</strong></div>\n";
		
		
	}

	// add any assignment specific instructions if present
	$assignment_instructions = get_post_meta( $aid, 'assignment_instructions', $single = true);
	
	if ( !empty( $assignment_instructions ) ) {
		$feedback_msg .= '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
  <span class="sr-only">Details:</span> <strong>'  . $assignment_instructions  . "</strong></div>\n";
	}
		
	if ( bank106_option('link_examples') == 1 ) {
	
		add_action('wp_enqueue_scripts', 'bank106_enqueue_simpletext_scripts');
		
		// simple editor and instructions
		$feedback_msg .= '<div class="alert alert-info" role="alert">Enter all information in the form below to share your response to this ' . bank106_option( 'thingname' ) . '. Then <a href="#" class="btn btn-primary btn-xs disabled">update</a> the information to  verify that is entered correctly. Once entered, <a href="#" class="btn btn-success btn-xs disabled">submit</a> so it will be saved as an example for others to see.';

	} else {
	
		add_action('wp_enqueue_scripts', 'bank106_enqueue_richtext_scripts');

		// flag to use rich text editor and full preview features
		$use_full_editor = true;
	
		$feedback_msg .= '<div class="alert alert-info" role="alert">Enter all information in the form below and <a href="#" class="btn btn-primary btn-xs disabled">update</a> to verify that is entered correctly. Then you can modify and <a href="#" class="btn btn-warning btn-xs disabled">preview</a> as much as necessary to finalize the entry. When satisfied, <a href="#" class="btn btn-success btn-xs disabled">submit</a> the form  and it will be saved to this site.';
		
	}
}


if ( is_user_logged_in() ) {
	//bypass captcha for logged in users
	$use_captcha = false;	
	$current_user = wp_get_current_user();
	
	// append note for login name if it is being used as option
	if ( bank106_option('use_wp_login') ) $feedback_msg .= '<br /><br />All responses submitted will be associated with your current login in as <strong>' . $current_user->display_name . '</strong>.' ;
	
	$submitterName = $current_user->display_name;
	$submitterUsername = $current_user->user_login;
	$submitterEmail = $current_user->user_email;


} else {
	// set up captcha if set as option;
	$use_captcha = bank106_option('use_captcha');

	if ( bank106_option('use_wp_login') == 1) {
		// WP login optional
		
		$feedback_msg .= '<br /><br />Logging in to this site is not required, but if you wish to have all your responses associated with your name ' . sprintf( '<a href="%s" class="btn btn-primary">%s</a>', wp_login_url( bank106_option( 'example_form_page' ) . '/?aid=' . $aid . '&typ=' . $typ ), __( 'sign in now' ) );  
	
	// WP login requred
	} elseif ( bank106_option('use_wp_login') == 2 ) {
		$must_login = true;
		
		// feedback message now an alert 
		$feedback_msg = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Warning:</span> You must sign in to ' . get_bloginfo( 'name' ) . ' to add your response to this ' . bank106_option( 'thingname' ) . '-- ' . sprintf( '<a href="%s" class="btn btn-primary">%s</a>', wp_login_url( bank106_option( 'example_form_page' ) . '/?aid=' . $aid . '&typ=' . $typ ), __( 'sign in now' ) );
	}	
}

// close dat div
$feedback_msg .=  '</div>';
$post = $wp_query->post;

// status for new submissions
$my_new_status = bank106_option( 'new_example_status' );

// flag for using/requring twitter on form
$user_code_name = bank106_option( 'user_code_name' );

// mmm cookies, load 'm if they are present
if ( isset($_COOKIE["bank106username"] )) $submitterUsername = $_COOKIE["bank106username"];
if ( isset($_COOKIE["bank106name"] ))  $submitterName = $_COOKIE["bank106name"];
if ( isset($_COOKIE["bank106email"] ))  $submitterEmail = $_COOKIE["bank106email"];

// verify that a  form was submitted and it passes the nonce check
if ( isset( $_POST['bank106_form_add_example_submitted'] ) && wp_verify_nonce( $_POST['bank106_form_add_example_submitted'], 'bank106_form_add_example' ) ) {
 
 		// grab the variables from the form
 		$exampleTitle = 			stripslashes( sanitize_text_field( $_POST['exampleTitle'] ) );		
 		$submitterName = 			stripslashes( sanitize_text_field( $_POST['submitterName'] ) ); 
 		$submitterEmail = 			sanitize_email( $_POST['submitterEmail'] ); 
 		$exampleDescription = 		stripslashes($_POST['exampleDescription']);
 		$exampleURL = 				( $_POST['exampleURL'] ) == 'n/a' ? 'n/a' : esc_url( trim($_POST['exampleURL']), array('http', 'https') ); 
 		if ($typ == 'ex') {
 			$exampleTags = 				cleanTags( sanitize_text_field( $_POST['exampleTags'] ) );
 		} 
 		
 		if ( $typ == 'tut' ) {
 			$exampleSource = 			stripslashes( sanitize_text_field( $_POST['exampleSource'] ) );
 		}
		
		// upload files if used
		if ($_FILES) {
			foreach ( $_FILES as $file => $array ) {
				$newupload = bank106_insert_attachment( $file, $post->ID );
				if ( $newupload ) {
					$exampleURL = wp_get_attachment_url( $newupload );
					$exampleURLstatus = 'File uploaded and its is link listed above. Choose another or enter any other URL to replace it.'; 
				}
			}
		}

 		if ($user_code_name) {
 			// using options for user code names
 			$submitterUsername = sanitize_text_field( $_POST['submitterUsername'] ); 
 			
 			// no @ for username but is used in metadata! 
 			if ( $submitterUsername[0] == "@" ) $submitterUsername = substr($submitterUsername, 1); 
 			
 			// set a cookie for user name
 			setcookie( "bank106username", $submitterUsername, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */
 			
 		}
 		
 		if ( isset ( $_POST['useCaptcha'] )) $use_captcha = $_POST['useCaptcha'];
 		
 		// set cookies
 		setcookie( "bank106name", $submitterName, strtotime( '+14 days' ), '/' );  /* expire in 14 days */
 		setcookie( "bank106email", $submitterEmail, strtotime( '+14 days' ),  '/' );  /* expire in 14 days */

		// make unique taxonomy terms
 		$my_assignment_tag = bank106_option( 'thingname' ) . $aid;
		$my_tutorial_tag = 'Tutorial' . $aid;
			
 		// let's do some validation, story an error message for each problem found
 		if ( $exampleTitle == '' ) $errors['exampleTitle'] = '<span class="label label-danger">Title Missing</span> - please enter a descriptive title.';
 		if ( $submitterName == '' ) $errors['submitterName'] = '<span class="label label-danger">Name Missing</span>- enter your name so we can give you credit';
 		if ( $user_code_name ) {
 			if ( $submitterUsername == '' and  $user_code_name == 2) {
 				$errors['submitterUsername'] = '<span class="label label-danger">User Name Missing</span> - please enter a unique user name to identify your work (it can be a twitter name without the @), it is required.';
 			}
		 }
 		if ( $submitterEmail == '' ) {
 			$errors['submitterEmail'] = '<span class="label label-danger">Email Address Missing</span>- Enter your email in case we have to contact you.';
 		} elseif ( !is_email( $submitterEmail ) )  {
 			$errors['submitterEmail'] = '<span class="label label-danger">Invalid Email Address</span>- "' . $submitterEmail . '" is not a valid email address, please try again.';
 		}
 		
 		// arbitrary string length to be considered a reasonable descriptions
 		if ( strlen( $exampleDescription ) < 10 )  $errors['exampleDescription'] = '<span class="label label-danger">Description Missing or Too Short</span>- please provide a full description that describes this ' . $sub_type. '.';
 		
 		// check URL, skip n/a entries
 		if ($exampleURL != 'n/a' ) { 		
 			if ( $exampleURL == 'https://' or  !wp_http_validate_url( $exampleURL ) ) {
 				$errors['exampleURL'] = '<span class="label label-danger">URL Missing or not Formatted Correctly</span>-  please check and enter the full URL where this ' . $sub_type . ' can be found- it must start with "https://" or "http://" ';	 
 			}

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
  <span class="sr-only">Error:</span> Sorry, but there are a few errors in your entry. Please correct and try again.<ul>';
 			
 			// Hah, each one is an oops, get it? 
 			foreach ($errors as $oops) {
 				$feedback_msg .= '<li>' . $oops . '</li>';
 			}
 			
 			$feedback_msg .= '</ul></div>';
 			
 		} else {
 		
 			// set up stuff if we are just doing a preview
 			$previewBtnState = '';
 			$submitBtnState = '';
 			$feedback_msg = '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
  <span class="sr-only">Success:</span> The form information looks good! Now you can review or jump down to <a href="#buttonzone" class="btn btn-warning btn-xs">preview</a> your entry and continue to edit and then <a href="#buttonzone" class="btn btn-success btn-xs">submit</a> when you are ready.</div>';
 			
 			// $exampleDescription = oembed_filter($exampleDescription);
 			
 			// Now process form only if submit button used
 			if ( isset ( $_POST['submitexample'] ) ) {
 			
				// good enough, let's make a post! Or a custom post type
			
				$add_tags = explode("," , $exampleTags); // holder for tags
			
				// add a tag for user name, if provided and not a login name; its not really twitter, but use @ to inducate tag for a person
				if ( $user_code_name AND !( username_exists( $submitterUsername ) ) ) $add_tags[] = '@' . $submitterUsername;
			
			
				$example_information = array(
					'post_title' => $exampleTitle,
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
					
					// give username credit (in earlier versions this was twitter, and metadata will still use name for backward compatibility 
					if ( $submitterUsername ) update_post_meta( $post_id, 'submitter_twitter', esc_attr( $submitterUsername ) );	
					
					// update the link URL				
					update_post_meta( $post_id, 'example_url', $exampleURL ); // example url
					update_post_meta( $post_id, 'example_source', $exampleSource ); // example source
						
					// add as syndication_permalink if we are linking directly to example URL or this is a tutorial
					if ( bank106_option('link_examples') == 1 OR $typ == 'tut' ) update_post_meta( $post_id, 'syndication_permalink', esc_url_raw( $_POST['exampleURL'] ) );
				
				
	
					// set the example tags
					if ( count($add_tags) ) wp_set_object_terms( $post_id, $add_tags, 'exampletags'); 
			
				
					if ($typ == 'ex') {
						// set the tags for example, make sure none for tutorials
						wp_set_object_terms( $post_id, $my_assignment_tag, 'assignmenttags');
						wp_set_object_terms( $post_id, null, 'tutorialtags');
						$add_another = '';
						
					} elseif ($typ == 'tut')  {
						// set the taxonmy for tutorial, make sure nont for assignment responses
						wp_set_object_terms( $post_id, $my_tutorial_tag, 'tutorialtags');
						wp_set_object_terms( $post_id, null, 'assignmenttags');
						$add_another = ' Or you can <a href="' . site_url() . '/' . bank106_option( 'example_form_page' ) . '/?aid=' . $aid  . '&typ=tut">add another ' . $sub_type . '</a> for this ' . bank106_option( 'thingname' );
					}
					
					// meta to identify which type of example this is
					update_post_meta( $post_id, 'example_type', $typ );
								
					// create feedback success messages
				
					if ($use_full_editor) {
						// feedback includes a link to the full featured entry for a response
						
						// notice of new examples are put into draft or not
						$pending_msg = ( bank106_option('new_example_status') == 'draft' ) ? ', pending moderation, will be added to the others for the <a href="' . get_permalink( $aid ) . '" class="alert-link">' . get_the_title( $aid ) . '</a> ' . bank106_option( 'thingname' ) : ' is available now at ' . '<a href="' . get_permalink(  $post_id ) . '" class="alert-link">' . get_permalink( $post_id ) . '</a>';
						 
						$feedback_msg = '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Success:</span> Your  ' . $sub_type . ' "' . $exampleTitle . '"' . $pending_msg .  '.' . $add_another . '</div>'; 
					} else {
						// feedback link returns to the assignment
						
						// notice of new examples are put into draft or not
						$pending_msg = ( bank106_option('new_example_status') == 'draft' ) ? ', pending moderation, will be added ' : ' has been added ';
						
						$feedback_msg = '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
  <span class="sr-only">Success:</span> Your ' . $sub_type . ' "' . $exampleTitle . '" ' . $pending_msg .  ' to the <a href="' . get_permalink( $aid ) . '" class="alert-link">' . get_the_title( $aid ) . '</a> ' . bank106_option( 'thingname' ) . '.' . $add_another . '</div>';  
					
					}
 
				} else {
			
					// generic error of post creation failed
					$feedback_msg = '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span> ERROR: the new ' .  $sub_type . ' could not be created. We are not sure why, but let someone know.</div>';
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
					
						<div class="page-header"><h1 class="page-title" itemprop="headline">Add a <?php echo ucfirst( $sub_type_title )?> the "<?php the_title(); ?>" <?php echo bank106_option( 'thingname' )?></h1></div>
					
					</header> <!-- end article header -->
					
					</article>


				<div class="row clearfix">
				<!-- display the icon and a snippet of the thing -->
					<div class="col-sm-2">
			
						<div class="thing-icon-single">
						<!-- insert/embed assignmet icon -->
						<a href="<?php echo get_permalink( $aid);?>"><?php echo get_thing_icon ( $aid ,'thumbnail')?></a>
						</div>
					</div><!--col-->

					<div class="col-sm-10">
					
						<p><em><?php the_excerpt(); ?></em></p>
					
						<p class="lead">Use the form below to add a <?php echo $sub_type_title?>  the <strong><a href="<?php echo get_permalink( $aid );?>"><?php the_title(); ?></a></strong> <?php echo lcfirst(bank106_option( 'thingname' ))?>.</p>
					</div><!--col-->
				</div><!-- row -->
				
				<div class="row clearfix">
				
					<div class="col-sm-offset-2 col-sm-8">	
				
					<?php echo $feedback_msg?>	
				
					</div><!--col-->
				</div><!-- row -->
			
		
      	<?php if (!$post_id and !$must_login) : //hide form if we had success ?>
      	
				<form action="" id="bank106form" method="post" action="" autocomplete="on"  enctype="multipart/form-data">
				
				
				<div class="row">
				
					<div class="col-sm-6">
						<div class="form-group<?php if (array_key_exists("exampleTitle",$errors)) echo ' has-error ';?>">
							<label for="exampleTitle"><?php bank106_form_response_title();?></label>
							<input type="text" name="exampleTitle" id="exampleTitle" value="<?php  echo $exampleTitle; ?>" class="form-control"  placeholder="<?php bank106_form_response_title_prompt();?>" aria-describedby="titleHelpBlock" />
							<span id="titleHelpBlock" class="help-block"></span>
						</div><!-- formgroup -->
						
						<?php if ( $typ == 'tut' ) :?>
						
						<div class="form-group">
							<label for="exampleSource"><?php  bank106_form_tutorial_source(); ?> (optional)</label>
							<input type="text" name="exampleSource" id="exampleSource" value="<?php  echo $exampleSource; ?>" class="form-control"  placeholder="<?php bank106_form_response_title_prompt();?>" aria-describedby="sourceHelpBlock" />
							<span id="sourceHelpBlock" class="help-block"></span>
						</div><!-- formgroup -->
						
						<?php endif?>

					</div><!-- col -->


					
						<div class="col-sm-6">
							<div class="form-group<?php if (array_key_exists("exampleURL",$errors)) echo ' has-error ';?>">

								<label for="exampleURL"><?php _e( 'Web location', 'wpbootstrap' )?> <a href="<?php echo $exampleURL?>" class="fancybox btn btn-xs btn-warning" id="testURL" target="_blank"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Test Link</a></label>
								<input type="text" name="exampleURL" id="exampleURL" class="form-control" value="<?php echo $exampleURL; ?>"  value="https://" aria-describedby="urlHelpBlock" /> 
								
								
								<?php if ( bank106_option('examples_upload' )): // use file uploads ?>
								<input type="file" name="uploadFile" id="uploadFile" value="" aria-describedby="uploadFileHelpBlock">
						
								<span id="urlHelpBlock" class="help-block">Enter the web address or upload a file (image, audio, or document &lt; <?php echo bank106_option('upload_max' )?> Mb)  that demonstrates the <?php echo $sub_type?> you are sharing. You can enter 'n/a' if no example will be provided by a link. <span id="uploadresponse"><?php echo $exampleURLstatus?></span></span>
								<?php else:?>
					
								<span id="urlHelpBlock" class="help-block">Enter the web address for a publicly available site that demonstrates the <?php echo $sub_type?> you are sharing.  You can enter 'n/a' if no evidence will be provided by a link. </span>
								
								<?php endif?>

							</div><!-- formgroup-->
								
						</div><!-- col -->

				
				</div><!-- row -->
				
				<div class="row">
					<div class="col-sm-12">
						
					<?php if ( $use_full_editor )  :?>
				
						<div class="form-group<?php if (array_key_exists("exampleDescription",$errors)) echo ' has-error ';?>">
						<label for="exampleDescription"><?php bank106_form_response_writing_area();?></label>
						<span id="exampleHelpBlock" class="help-block">For this <?php echo $sub_type;?> <?php bank106_form_response_writing_area_prompt();?>. To embed media from YouTube, vimeo, instagram, SoundCloud, Twitter, or flickr, simply put the URL for its source page on a blank line. When published, the url will be replaced by the embedded media for that link. </span>
						<?php
							// set up for inserting the WP post editor
							// media buttons enabled for logged in users
							$settings = array( 
								'textarea_name' => 'exampleDescription', 
								'media_buttons' =>  is_user_logged_in(),
								'textarea_rows' => 12
							);
							wp_editor(  $exampleDescription, 'exampleDescriptionHTML', $settings );
						?>
					
						</div>	<!-- formgroup -->			
						
				<?php else:?>
			
						<div class="form-group<?php if (array_key_exists("exampleDescription",$errors)) echo ' has-error ';?>">
							<label for="exampleDescription"><?php bank106_form_response_writing_area();?></label><br />
							<span id="exampleHelpBlock" class="help-block">For this <?php echo $sub_type;?> <?php bank106_form_response_writing_area_prompt();?> <strong><span id="wCount">0</span></strong> words used of <strong><?php echo bank106_option('exlen')?></strong> word limit</span>		
							<textarea name="exampleDescription" class="form-control" id="exampleDescription" rows="4"><?php echo stripslashes( $exampleDescription );?></textarea>
							
						</div>	<!-- formgroup -->
					<?php endif?>
					</div><!-- col -->
				</div><!--row-->
				
				<div class="row">
					<div class="col-sm-6">
					
					
						<?php  if ($typ == 'ex')  : // use tags only for responses?>
						<div class="form-group<?php if (array_key_exists("exampleTags",$errors)) echo ' has-error ';?>">
					
							<label for="exampleTags"><?php bank106_form_response_example_tags(); ?> (optional) </label>
							<input type="text" name="exampleTags" class="form-control" id="exampleTags" value="<?php echo $exampleTags; ?>"  aria-describedby="tagHelpBlock" />
							<span id="tagHelpBlock" class="help-block"><?php bank106_form_response_example_tags_prompt(); ?></span>
						</div><!-- formgroup -->
						<?php endif?>
				
						<div class="form-group<?php if (array_key_exists("submitterName",$errors)) echo ' has-error ';?>">
							<label for="submitterName"><?php _e( 'Your name  (required)', 'wpbootstrap' ) ?></label>
							<input type="text" name="submitterName" class="form-control" id="submitterName" value="<?php echo $submitterName; ?>" aria-describedby="nameHelpBlock" />
							<span id="nameHelpBlock" class="help-block">Enter your name or however you wish to be credited for sharing this <?php echo $sub_type?></span>
						</div><!-- formgroup -->
				
						<div class="form-group<?php if (array_key_exists("submitterEmail",$errors)) echo ' has-error ';?>">
							<label for="submitterEmail"><?php _e( 'Your email address  (required)', 'wpbootstrap' ) ?></label>
							<input type="email" name="submitterEmail" id="submitterEmail" class="form-control" value="<?php echo $submitterEmail; ?>" placeholder="you@somewhere.org" aria-describedby="emailHelpBlock" />
							<span id="emailHelpBlock" class="help-block">Enter your email address; it is never displayed publicly, and is only used if we need to contact you to fix your entry.</span>
						</div>	<!-- formgroup -->			
						
					<?php if ( bank106_option('use_wp_login') == 2 ): // WP login required ?>

						<div class="form-group">
							<label for="submitterUsername"><?php _e( 'Your user name ', 'wpbootstrap' ) ?></label>
							<input type="text" name="submitterUsername" class="form-control" id="submitterUsername" value="<?php echo $submitterUsername; ?>"  aria-describedby="submitterUsernameHelpBlock" readonly/>
							<span id="submitterUsernameHelpBlock" class="help-block">Your username is automatically entered, and can be used to track of all your shared <?php echo $sub_type?>s</span>
						</div>	<!-- formgroup -->						
								
					
					<?php elseif ( $user_code_name or bank106_option('use_wp_login') == 1 ): ?>

						<div class="form-group<?php if (array_key_exists("submitterUsername",$errors)) echo ' has-error ';?>">
							<label for="submitterUsername"><?php _e( 'Your unique user name ', 'wpbootstrap' ) ?> <?php if  ($user_code_name == 1) { echo '(optional)'; } else { echo '(required)';}?></label>
							<input type="text" name="submitterUsername" class="form-control" id="submitterUsername" value="<?php echo $submitterUsername; ?>"   aria-describedby="submitterUsernameHelpBlock" />
							<span id="submitterUsernameHelpBlock" class="help-block">Enter a unique user name keep track of all your shared <?php echo $sub_type?>s <?php if ( bank106_option('use_wp_login') and is_user_logged_in() ) echo ' (Your login name is automatically entered)'?></span>
						</div>	<!-- formgroup -->
			
					<?php endif?>	
				</div><!--col-sm-6-->
				
				<div class="col-sm-6">


				
					<!-- gotta nonce -->
					<?php wp_nonce_field( 'bank106_form_add_example', 'bank106_form_add_example_submitted' ); ?>
					
						<!-- hidden data used for previews shhhhhh -->
						<input type="hidden" id="aid" value="<?php echo $aid;?>" />
						<input type="hidden" id="typ" value="<?php echo $typ;?>" />
						<input type="hidden" id="thingName" value="<?php echo bank106_option( 'thingname' )?>" />
						<input type="hidden" id="subType" value="<?php echo ucfirst( $sub_type );?>" />
						
						<input type="hidden" id="displaySource" value="<?php echo $exampleSource?>" />
						<input type="hidden" id="displayCredit" value="<?php echo htmlentities( bank106_user_credit_link( $post_id, '(', ')', 'exampletags' ) )?>" />
						<input type="hidden" name="useCaptcha"  value="<?php echo $use_captcha?>" />
						
						<?php if ( $use_full_editor ) :?>
							<!-- hidden data stored for preview use -->
							<input type="hidden" id="assignmentURL" value="<?php echo get_permalink( $aid );?>" />
							<input type="hidden" id="assignmentTitle" value="<?php echo get_the_title( $aid );?>" />
							<input type="hidden" id="embedMedia" value="<?php echo htmlentities( get_media_embedded ( $exampleURL ))?>" />		
						<?php endif?>
		
						<div class="form-group" id="buttonzone">
							<label for="submitexample"><?php _e( 'Review and Submit this ' . ucfirst($sub_type), 'wpbootstrap' )?></label>
					
							<div class="row">
								<div class="col-xs-4 col-md-3">
									<button type="submit" class="btn btn-primary" id="updateassignment" name="updateassignment">
										<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Update
									</button>
								</div><!--col-->
						
								<div class="col-xs-8 col-md-9">
									<span class="help-block">Update your entered information and let us verify that it is is entered correctly.</span>
								</div><!--col-->
						
								<div class="col-xs-4 col-md-3">
									<a href="#preview" class="fancybox btn btn-warning <?php echo $previewBtnState?>" title="Preview of your <?php echo $sub_type?>; it has not yet been saved. Urls for embeddable media will render when saved."><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview</a>
								</div><!--col-->
						
								<div class="col-xs-8 col-md-9">
									<span class="help-block">Optionally generate a preview. If the body content does not change after edits, try clicking "Update" agan.</span>
								</div>	<!--col-->
						
								<div class="col-xs-4 col-md-3">					
					
									<button type="submit" class="btn btn-success <?php echo $submitBtnState?>" id="submitexample" name="submitexample">
										<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Submit
									</button>
								</div> <!--col-->
								<div class="col-xs-8 col-md-9">
									<span class="help-block">Once every thing looks perfect, submit this <?php echo $sub_type?> to the site.</span>
								</div><!-- col -->
							</div>	<!-- row -->									

						</div> <!-- form group -->

				<?php if ( $use_captcha ):?>
			
					<div class="form-group<?php if (array_key_exists("recaptcha",$errors)) echo ' has-error ';?>">
						<label for="recaptcha"><?php _e( 'Spam protection', 'wpbootstrap' )?></label>
						<span id="recaptchaHelpBlock" class="help-block">Unfortunately, this test is necessary to keep this site safe from spammers. Please enter the code!</span>
						<div class="g-recaptcha" data-sitekey="<?php echo bank106_option('captcha_pub')?>"></div>
						
					</div><!--formgroup-->
				<?php endif?>
				
					</div> <!-- col -->
				
				</div> <!-- row -->
			
			</form>
	

			<div id="preview" style="display:none;"></div>
	
		<?php endif; // form check?>
	
	<?php endwhile; // post loop ?>	
	
	
	<?php endif// type test?>			
	
	</div><!-- end #main -->
</div><!-- end #content -->

<?php get_footer(); ?>