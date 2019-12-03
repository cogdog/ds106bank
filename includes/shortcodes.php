<?php

/****************************** SHORT CODES **********************************/	

// ----- short code for number of assignments in the bank
add_shortcode('thingcount', 'getThingCount');

function getThingCount() {
	return wp_count_posts('assignments')->publish  . ' ' . bank106_option( 'pluralthings' );
}

// ----- short code for number of examples in the bank
add_shortcode('examplecount', 'getExampleCount');

function getExampleCount() {
	return wp_count_posts('examples')->publish . ' examples';
}

/* ----- shortcode to generate lists of top contributors -------- */
add_shortcode("bankleaders", "bank106_leaders");  

function bank106_leaders ( $atts ) {  

	// return a list of the top responders to dailies
		
	// get the value of any passed attributes to our function
	// we want a number of results we should return (0=all)
	// and an indicator if we are looking for responders (hashtag taxonony) or contributors (tag tax)
 	extract( shortcode_atts( array( "number" => 0,  "type" => 'responders' , "exclude" => ""), $atts ) );  

	// Arguments to search hashtag terms
	// search for @ in order of highest frequency
	$args = array(
		'number' => $number,
		'orderby' => 'count',
		'order' => 'DESC',
		'exclude' =>  $exclude,
		'name__like' => '@'
	);
	
	
	if ( $type == 'contributors') {
		// search for terms in the custom taxonomy for regular tags
		$terms = get_tags( $args );
		$taxpath = 'tag';
	} else {
		// search for terms in the custom taxonomy for response tags
		$terms = get_terms('exampletags',  $args );
		$taxpath = 'exampletags';
	}
	
	$out = '<ol>';
	// here come the leaders!
	foreach ( $terms as $term) {
		$out .= '<li><a href="' . site_url() . "/$taxpath/" . $term->slug  . '">' . $term->name . ' (' . $term->count . ')</a></li>';
	}
	$out .= '</ol>';
	
	// here ya go!
	return ($out);

}

// ----- short code to create a list of Feedwordpress subscribed feeds
add_shortcode("feedroll", "bank106_feedroll");  

function bank106_feedroll( $atts ) {  
	global $wpdb;
	
	// get the value of  attributes to shortcode, name of tag
	// set default to roken for testing
 	extract( shortcode_atts( array( "tag" => '*!*' ), $atts, 'feedroll' ) );  
 	
 	// create mySQL query condition to find tags if provided
 	$tag_cond = ( $tag ==  '*!*' ) ? '' :  " AND wpl.link_notes LIKE '%%$tag%%'";
 	
 	// custom mySQL query to get subscribed blogs from the links table
	$feedblogs = $wpdb->get_results( 
		"
		SELECT DISTINCT      
					wpl.link_name, wpl.link_url
		FROM        $wpdb->links wpl,  $wpdb->postmeta wpm
		WHERE       wpm.meta_key='syndication_feed_id' AND 
					wpm.meta_value = wpl.link_id $tag_cond
		ORDER BY    wpl.link_name ASC
		"
	);

	// bail if we got nothing
	if (count($feedblogs) == 0 ) {
		
		$content = ( $tag ==  '*!*' ) ? 'No feeds available or no posts syndicated yet from feeds' :  "No feeds found for $tag (or no posts syndicated yet from feeds)";
		
	// we got feeds!
	} else {
	
		//start the output
		$content = "<ol class=\"feedroll\">\n";
		
 
		// output each item as a list item, title of blog linked to URL 
		foreach ( $feedblogs as $item ) {
 			$content  .=  '<li><a href="' . htmlspecialchars($item->link_url)   . '">' . htmlspecialchars_decode($item->link_name)  . '</a></li>' . "\n";              
		}
		
		// clean up after your lists
		$content .= '</ol>';
	}		
	
	// here comes the short code output  
    return $content;  
}
?>