<?php

/************************** FOR ASSIGNMENTS  *********************************/	

function is_url_embeddable( $url ) {
// test if URL matches the ones that Wordpress can do oembed on
// test by by string matching
	
	$allowed_embeds = array(
					'youtube.com/watch?',
					'youtu.be',
					'flickr.com/photos',
					'flic.kr',
					'vimeo.com', 
					'soundcloud.com',
					'vine.co',				
					'instagram.com',
					'twitter.com',
					'imgur.com',
					'animoto.com',
					'giphy.com',
	);
	
	// walk the array til we get a match
	foreach( $allowed_embeds as $fragment ) {
  		if  (strpos( $url, $fragment ) !== false ) {
			return ( true );
		}
	}	
	
	// no matches, no embeds for you
	return ( false );
}

function url_is_video ($url) {
// tests if URl is for a potentially media site so we can wrap with
// boostrap resppnsive tags
	$allowed_videos = array(
					'youtube.com/watch?',
					'youtu.be',
					'vimeo.com',
					'soundcloud.com',
					'vine.co',
					'instagram.com',
					'animoto.com',
					'giphy.com'
	);


	// walk the array til we get a match
	foreach( $allowed_videos as $fragment ) {
  		if  (strpos( $url, $fragment ) !== false ) {
			return ( true );
		}
	}	
	
	// no matches, no videos for you
	return ( false );
}


function get_thing_icon ($pid, $imgsize, $imgclass = "thing-pic") {
// Display the thumbnail for a thing; assume by default it's for a single assignment (class= 'thing-pic')
// For an archive view ($imgclass = 'thing-archive') we will use embedded media as icon (if option set)
	
	if ( $imgclass == "thing-archive" AND bank106_option('media_icon')) {
		// try to use example media as icon if its embeddable and option set to look for embeddable icon
			
		// do we have an example URL as meta data
		if ( get_post_meta( $pid, 'fwp_url' , true ) ) {
			$check_for_embed =  get_media_embedded (  get_post_meta( $pid, 'fwp_url' , true) );
			
			// if we got some embed code, return it
			if ( $check_for_embed != '') return ( $check_for_embed );
		}
	}
	
	// Let's just to the regular thumbnail route...
	// Do we have a thumbnail defined?

	// get clean string for alt attribute
	$title_str =  trim(strip_tags( get_the_title( $pid ) ) );

	if ( '' != get_the_post_thumbnail($pid) ) {
		the_post_thumbnail( $imgsize, array ('class'=> $imgclass,
	'alt'	=> $title_str) );
	} else {
		// Otherwise use the default image
		return '<img src="' . bank106_option('def_thumb' ) . '" $imgclass alt="' . esc_attr( $title_str) . '" />';
	} 
} 


function get_example_media ( $pid, $metafieldname='fwp_url' ) {
// output link to example, display media or embeded media if example is embeddable

	$str = ''; // hold output
	
	if ( get_post_meta( $pid, $metafieldname , true ) ) {
		// url for example of assignment
				
		$assignmentURL = get_post_meta( $pid, $metafieldname, true );
		
		// add to link for mp3 links		
		$download_option = ( url_is_type( $assignmentURL, array( 'mp3' ) ) ) ? ' download' : '';

		// make header
		$str .= '<p class="example-url"><strong>Example for "' . get_the_title($pid) . '":</strong><br />';
		
		if ($assignmentURL == "n/a") {
		
			// no linked example
			$str .= 'n/a</p>';
			
		} else {
			// build link
			$str .= '<a href="' . $assignmentURL . '" target="_blank"' . $download_option . '>' . $assignmentURL  . ' </a></p>';
		
			// add  embedded media if there is some
			$str .= get_media_embedded ( $assignmentURL );	
		}
	}
	
	return( $str );		
} 


function get_media_embedded ( $url ) {
// get the media embeds for a given URL
	
	if ($url == '' or ( strpos( $url, 'commons.wikimedia.org' ) !== false ) ) return ('');

	$str = ''; // hold output
	$display_code = ''; // hold display code
			
	if ( url_is_type( $url, array( 'mp3' ) ) ) {
		// see if the URL points to an embeddable audio type, display code set for mp3
		$display_code = '<audio controls="controls" class="audio-player"><source src="' . $url . '" /> </audio>';
	}
		
	// try and get embed code for the link (e.g. youtube, flickr, soundcloud)
	// $embedcode = ( is_url_embeddable($url) ) ? wp_oembed_get( $url ) : false;
	
	$embedcode = wp_oembed_get( $url );
	
	// if example is an image, we will use that as a display code
	if ( url_is_type( $url ) ) $display_code = '<img src="' . $url . '" alt="" />';
	
	if (!$embedcode) {
		// if we are not showing an embeddable media type, display either an image or an mp3 player	
		$str .=  $display_code;

	} else {
		// do some embedding, wrap if needed
		if ( url_is_video ( $url ) ) {
			// wrap the video so it is responsive
			$str .=  '<div class="videoWrapper">' . $embedcode . '</div>';	
		} else {
			// just output the embed code
			$str .=  $embedcode;
		}
	}
	return( $str );		
} 


function update_assignment_meta($id, $example_count, $tutorial_count) {
// update custom post meta to track the views and the number of examples done for each assignment
// called on each view of an assignment

	// get current value, if it does nto exist, then 0
	$visit_count = ( get_post_meta( $id, 'assignment_visits', true ) ) ? get_post_meta( $id, 'assignment_visits', true ) : 0; 
	$visit_count++;
	
	//update visit counts
	update_post_meta( $id,  'assignment_visits', $visit_count );
	
	// now update the number of examples
	update_post_meta( $id,  'assignment_examples', $example_count );
	
	// now update the number of tutorials
	update_post_meta( $id,  'assignment_tutorials', $tutorial_count );
}

function update_example_meta( $id ) {
// update custom post meta to track the views called on each view of an example

	// get current value, if it does not exist, then initialize it to  0
	$visit_count = ( get_post_meta($id, 'examples_visits', true) ) ? get_post_meta($id, 'examples_visits', true) : 0; 
	$visit_count++;
	
	//update visit counts
	update_post_meta($id,  'examples_visits', $visit_count);
}


function get_assignment_meta_string( $id ) {
// get thing meta data counts for views, examples, tutorials for use in archive views

	// you gotta start somewhere
	$str = ' &bull; <strong>' . get_assignment_meta( $id, 'assignment_visits') . '</strong> views ';
	
	
	if ( bank106_option('show_ex' )  == 'both') {
		// display examples and tutorials counts
		
		$count_ex = get_assignment_meta( $id, 'assignment_examples');
		$count_tut = get_assignment_meta( $id, 'assignment_tutorials');
		
		
		if ($count_ex) {
			$str .=  ' &bull; <strong>' . $count_ex . '</strong> response';
			$str .= ($count_ex == 1) ? '' : 's';
		}
		
		if ( $count_tut ) {
			$str .= ' &bull;  <strong>' .  $count_tut . '</strong> ' .  lcfirst( bank106_option('helpthingname') );
			$str .= ($count_tut == 1) ? '' : 's';
		}
		
	} elseif ( bank106_option('show_ex' )  == 'ex' ) {
		// display example counts only
		$count_ex = get_assignment_meta( $id, 'assignment_examples');
		$str .=  ' &bull; <strong>' . $count_ex . '</strong> response';
		$str .= ($count_ex == 1) ? '' : 's';
		
	} elseif ( bank106_option('show_ex' )  == 'tut') {
		// display tutorial counts only
		$count_tut = get_assignment_meta( $id, 'assignment_tutorials');
		$str .=  '  &bull;  <strong>' .  get_assignment_meta( $id, 'assignment_tutorials') . '</strong> ' .  lcfirst( bank106_option('helpthingname') );
		$str .= ($count_tut == 1) ? '' : 's';
		
	}
	
	return ($str);
}

function get_assignment_id_from_terms( $postid ) {
	// for a given example post ID, get the terms from either the assignment tag or tutorial tag
	$all_the_terms = wp_get_object_terms( $postid, array( 'assignmenttags' ,  'tutorialtags' ) );

	if ( !empty( $all_the_terms ) ) {	  
		if ( !is_wp_error( $all_the_terms ) ) {

				foreach( $all_the_terms as $term ) {
					$tid =  get_id_from_tag( $term->name );
					if ( $tid ) return ( $tid );
				}		
				return (0);
		}
	}
	return (0);
}

function get_assignment_meta( $pid, $metadname, $default='' ) {
// return post metadata
	return( ( get_post_meta($pid, $metadname, true ) ) ? get_post_meta( $pid,  $metadname, true)  : $default );
}


function get_assignment_types( $orderby='name', $order ='ASC' ) {
// get all assignment types from terms in the taxonomy, basically all the types of THINGS
	$atypes = get_terms( 'assignmenttypes', 
		array(
			'orderby'	=>  $orderby,
			'order'		=>  $order,
			'hide_empty'=> 0,
			)
	);

	return( $atypes );
}

function url_is_type ($url, $allowables = array( 'jpg', 'jpeg', 'png', 'gif' ) ) {
// generalized function than original url_is_img to check file name extension on url
// against an expected array of file names (default is for images), could be called to check for
// mp3 by calling  url_is_type($url, array('mp3') )

	// get file extension for url
	$fileExtention 	= pathinfo ( $url, PATHINFO_EXTENSION ); 	
	
	// check the url file extension to ones we will allow
	return ( in_array( strtolower( $fileExtention ),  $allowables  ) );
}
?>