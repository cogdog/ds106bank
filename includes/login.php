<?php

// ------------------------------- user registration ------------------------------------

// add first and last names to the user registration form
// h/t https://codex.wordpress.org/Customizing_the_Registration_Form
add_action( 'register_form', 'bank106_register_form' );

function bank106_register_form() {

	// get form values
    $first_name = ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? sanitize_text_field( $_POST['last_name'] ) : '';
 
 	// next, add to form
        ?>
        <p>
            <label for="first_name"><?php _e( 'First Name', 'wpbootstrap' ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(  $first_name  ); ?>" size="25" /></label>
        </p>
         <p>
            <label for="last_name"><?php _e( 'Last Name', 'wpbootstrap' ) ?><br />
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr(  $last_name  ); ?>" size="25" /></label>
        </p>
    
        <?php
}

// Do  validation. 
add_filter( 'registration_errors', 'bank106_registration_errors', 10, 3 );

function bank106_registration_errors( $errors, $sanitized_user_login, $user_email ) {
        
        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        	$errors->add( 'first_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'wpbootstrap' ),__( 'You must include a first name.', 'wpbootstrap' ) ) );
        }

        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
        	$errors->add( 'last_name_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'wpbootstrap' ),__( 'You must include your last name.', 'wpbootstrap' ) ) );
        }


        return $errors;
}

// Finally, save our extra registration user meta.
add_action( 'user_register', 'bank106_user_register' );

function bank106_user_register( $user_id ) {
	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
	}
	
	if ( ! empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
	}
	
	// update the display name
	$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' =>  sanitize_text_field( $_POST['first_name'] ) . ' ' . sanitize_text_field( $_POST['last_name'] )  ) );

}


// ------------------------------ login links/buttons  -----------------------------------
// create a link for the wordpress login link
function bank106_get_author_menu_link() {

	$current_user = wp_get_current_user();
	$user_id = get_current_user_id();
	
	return '<a href="' . site_url() . '/author/' . $current_user->user_login  . '" class="btn btn-default">' . $current_user->display_name . '</a>';

}


// change the text of the login / logout link
// h/t https://core.trac.wordpress.org/ticket/34356#comment:4
function bank106_login_menu_customize( $link ) {

        if ( ! is_user_logged_in() ) {
        	
        	// return to current page when logged in
			return sprintf( '<a href="%s" class="btn btn-info">%s</a>', wp_login_url( get_permalink() ), __( 'Sign In' ) );
        } else {
        
        	// send to home page on logout
			return sprintf( '<a href="%s" class="btn btn-info">%s</a>', wp_logout_url( home_url() ), __( 'Sign Out' ) );
        }

        return $link;
}

// add a login / logout option to the menu, will work only if a menu is created in 
// Appaearances -> menus (who does not want menus?)
// h/t http://vanweerd.com/enhancing-your-wordpress-3-menus/#add_login

function bank106_login_logout_link( $items, $args ) {
        ob_start();
        wp_loginout();
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
        
        if (  is_user_logged_in() ) {
        	 $items .= '<li>'. bank106_get_author_menu_link() .'</li>';
        	 
        } elseif (bank106_option( 'register_btn_link' ) == '#' ) {
        	// register link uses label from theme options, but is disabled
        	$items .= '<li><a href="#" class="btn btn-info" onClick="alert(\'Registration is not currently available.\')">' . bank106_option( 'register_btn_name' ) . ' </a></li>';
        
        } else {
        	// register link uses label and destination from theme options
        	$items .= '<li><a href="'. bank106_option( 'register_btn_link' ) . '" class="btn btn-info">' . bank106_option( 'register_btn_name' ) . ' </a></li>';
        }
        
    	return $items;
}

/* ------ Limit media library access -------------------------------------------------  */
// Limit media library access so authors can see only their media
// h/t https://www.wpbeginner.com/plugins/how-to-restrict-media-library-access-to-users-own-uploads-in-wordpress/
  
add_filter( 'ajax_query_attachments_args', 'bank106_show_current_user_attachments' );
 
function bank106_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts
') ) {
        $query['author'] = $user_id;
    }
    return $query;
}
?>