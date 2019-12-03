<?php
# -----------------------------------------------------------------
# Plugin Detectors
# -----------------------------------------------------------------

function bank106_alm_installed() {
	// return status for Ajax Load More Plugin
	if ( function_exists('alm_install' ) ) {
		return ('The Ajax Load More plugin <strong>is installed</strong> and will be used to sequentially load responses (with the value entered) if there are many of them. Check documentation tab for details on setting up the custom template in the plugin.'); 
		
	} else {
		return ('Ajax Load More plugin <strong>is not installed</strong>. This means all example responses will be loaded on a single ' . bank106_option( 'thingname' ) . ' and the number entered is ignored. If you start getting many responses, you may want to install this plugin. '); 
	}
}

function bank106_wp_ratings_installed() {
	// return status for WP-POSTRATINGS
	if ( function_exists('the_ratings' ) ) {
		return ('WP-PostRatings <strong>is installed</strong> and will be applied to each ' . lcfirst(bank106_option( 'thingname' )) . ' so visitors can crowdsource it\'s rating or popularity . Check documentation tab for details. To specify the scale and prompt see <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-options.php') .'">ratings options</a> or <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-templates.php') .'">ratings display templates</a>.'); 
	} else {
		return ('WP-PostRatings <strong>is not installed</strong>. To enable public ratings of ' . lcfirst(bank106_option( 'pluralthings' )) . ' install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a> via the Add New Plugin interface. Check the documentation tab for details on options for display of the ratings.'); 
	}
}

function bank106_fwp_installed() {
	// Status check for FeedWordPress
	if ( function_exists('is_syndicated' ) ) {
		return ('Feed Wordpress <strong>is installed</strong>.'); 
	} else {
		return ('Feed Wordpress <strong>is NOT installed</strong>. To enable syndication of examples to this site, install the <a href="http://wordpress.org/plugins/feedwordpress/" target="_blank">Feed Wordpress plugin</a> via the Add New Plugin interface. Check the documentation tab for proper Feed Wordpress settings.'); 
	}
}

/************************ GENERAL USEFUL STUFF *******************************/	

function get_id_from_tag( $input ) {
	// gets a post id from a tag, in form of Assignment114, Tutorial12
	//  e.g. use pattern matching to find numeric part of string
	// from http://stackoverflow.com/a/13538212/2418186
	
	$input = preg_replace('/[^0-9]/', '', $input);

	return $input == '' ? '1' : $input;
}

function get_the_article ( $str ) {
	// Make grammar happy, returns "an" if #str starts with vowel
	// h/t for strcspn tip http://stackoverflow.com/a/16579680/2418186

	$my_article = ( strcspn( strtolower( $str ), "aeiou") ) ? "a " : "an ";
	return ($my_article);

}

function page_with_template_exists ($template) {
	// returns true if at least one Page exists that uses given template

	// look for pages that use the give template
	$seekpages = get_posts (array (
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => $template
			));
	 
	// did we find any?
	$pages_found = ( count ($seekpages) ) ? true : false ;
	
	// report to base
	return ($pages_found);
}

function bank106_twitter_button (  $postid, $mytype  ) {
// display a tweet this button for a challenge or locally published responses

	// doth there be a twitter acct to credit?
	$mention = get_post_meta( $postid, 'submitter_twitter', 1 );	
	
	// make it proper (or not visible)
	$mention_str = 	( $mention ) ? ' by ' .  $mention : '';
	
	$urltotweet = get_site_url() . '/?p=' . $postid;

	$tweet_text = get_the_title(  $postid )  . '- ' . get_the_article ( $mytype ) . lcfirst( $mytype ) . $mention_str  ;					
	
	echo '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $urltotweet . '" data-text="' . $tweet_text . '" data-hashtags="' . bank106_option( 'hashtag' ) . '" data-dnt="true">Tweet</a>' . "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";

}

function bank106_user_credit_link ( $post_id, $prefix='', $suffix='', $path='tag' ) {
	// if a given item has a username (previously it was just twitter) credit, return an HTML credit link string
	// path = 'tag' for assignments and  'exampletags' for examples

	// look for a user code name (in earlier versions it was twitter, post meta can still use old name) 
	// if not found use one based on options for default name
	$user_code_name = (get_post_meta( $post_id, 'submitter_twitter', true )) ? get_post_meta( $post_id, 'submitter_twitter', true ) : sanitize_title(bank106_option( 'default_user'));
	
	if ( $user_code_name[0] == "@" ) $user_code_name = substr($user_code_name, 1); 
		
	if ( bank106_option( 'use_wp_login') and username_exists( $user_code_name ) ) {
		// if we are using wordpress logins and there is a user, use the author link
		
		// if the username is an email address, we will use the left side part
		$user_display_name =   ( strpos( $user_code_name, '@') === false) ?  $user_code_name : get_name_from_email( $user_code_name);
		
		return $prefix . '<a href="' . site_url() . '/author/' . $user_code_name . '">@' . $user_display_name . '</a>' . $suffix;

	
	} elseif ( bank106_option( 'user_code_name' ) and $user_code_name ) {
		// we will try if we are even requiring using names and if one exixts
		return $prefix . '<a href="' . site_url() . '/' . $path . '/' . $user_code_name . '">@' . $user_code_name . '</a>' . $suffix;

	} else {
		// otherwise, send nothing
		return ('');
	}
}


function bank106_get_display_name( $post_id, $metakey ) {
	// get a display name, sort through possibilities of using a WP user name
	// or from post meta reference ($metakey)

	// the "code" name (a user provided one or a WordPress username is stored in old metadata field
	$user_code_name = (get_post_meta( $post_id, 'submitter_twitter', true )) ? get_post_meta( $post_id, 'submitter_twitter', true ) : sanitize_title(bank106_option( 'default_user'));
	
	// strip off a @
	if ( $user_code_name[0] == "@" ) $user_code_name = substr($user_code_name, 1); 

	// author name either from WP user or from meta data (uses old FWP meta)
	$display_name = ( bank106_option( 'use_wp_login' ) and username_exists( $user_code_name ) ) ? get_the_author() : get_assignment_meta( $post_id, $metakey, bank106_option( 'default_user' ) );
	
	return ($display_name);

}

function bank106_get_add_thing_page() {

	// return slug for page set in theme options for adding a thing page
	if ( bank106_option( 'add_thing_form_page' ) )  {
		return ( get_post_field( 'post_name', get_post( bank106_option( 'add_thing_form_page' ) ) ) ); 
	} else {
		// maybe we guess
		return ('add');
	}
}

function bank106_get_add_example_page() {

	// return slug for page set in theme options for adding an example page
	if ( bank106_option( 'example_form_page' ) )  {
		return ( get_post_field( 'post_name', get_post( bank106_option( 'example_form_page' ) ) ) ); 
	} else {
		// maybe we guess
		return ('add-example');
	}
}

function bank106_get_response_link( $pid ) {
	// get the appropriate response link

	if ( get_post_meta( $pid, 'syndication_permalink' , true)) {
		// look for a syndicated link or external link for tutorials
	 	return ( get_post_meta( $pid, 'syndication_permalink', true) );
	} else {
		return (get_permalink( $pid ) );
	} 
}

function make_links_clickable( $text ) {
//----	h/t http://stackoverflow.com/a/5341330/2418186
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}


function cleanTags( $str ) {
	// replace multiple white spaces in tags to single blanks
	$cleansed = preg_replace('!\s+!', ' ', $str);
	// now convert blanks to commas
	$cleansed = str_replace ( ' ', ',' , $cleansed );
	
	$cleansed = preg_replace('!,+!', ',', $cleansed);

	// return the cleaned string
	return ($cleansed);
}

function oembed_filter( $str ) {
	// filters text for URLs WP can autoembed, and returns with proper embed code
	// lifted somewhat from oembed-in-comments plugin
	global $wp_embed;

	// Automatic discovery would be a security risk, safety first
	add_filter( 'embed_oembed_discover', '__return_false', 999 );
	$str = $wp_embed->autoembed( $str );

	// ...but don't break your posts if you use it
	remove_filter( 'embed_oembed_discover', '__return_false', 999 );

	return $str;
}


function get_name_from_email( $email ) {
	// gets the first part of an email address (up to the "@")
	// h/t https://stackoverflow.com/a/1798387/2418186

	$parts = explode("@", $email);
	return ($parts[0]);
}
?>