<?php

// called by init hook to create stuff (avoid multiple inits?)
function bank106_custom_post_types() { 
	bank106_assignmentbank_tax();
	bank106_post_type_assignments();
	
}

// ----------- Content Types  -----------------------------------------------------------

function bank106_post_type_assignments() {
	// create post type for assignments- things to do
	
	register_post_type(
		'assignments', 
		array(
				'labels' => array(
							'name' => __( 'Things to Do'),
							'singular_name' => __('Thing to Do'),
							'add_new' => 'Add New',
							'add_new_item' => 'Add New Thing to Do',
							'edit_item' => 'Edit Thing to Do',
							'new_item' => 'New Thing to Do',
							'all_items' => 'All Things to Do',
							'view_item' => 'View Thing to Do',
							'search_items' => 'Search Things to Do',
							'not_found' =>  'No things to do found',
							'not_found_in_trash' => 'No things to do found in Trash', 
						),
						'description' => __('Tasks, assignments, items, aka things in the bank'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'menu_icon' => 'dashicons-welcome-widgets-menus',
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'author',
									'custom-fields',
									'revisions',
									'thumbnail',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite' => array( 'slug' => 'thing'),
						'taxonomies' => array(
							'assignmenttypes',
							'assignmenttags',
							'tutorialtags',
							'post_tag',
						),
						'capabilities' => array(
    						'create_posts' => 'do_not_allow', // false < WP 4.5, credit @Ewout
  						 ),
  						  'map_meta_cap' => true, 

							
		)
	);
	
	// create post type for examples- what people to in response to assignments
	
	register_post_type(
		'examples', 
		array(
				'labels' => array(
						'name' => __( 'Responses'),
						'singular_name' => __('Response'),
						'add_new' => 'Add New',
						'add_new_item' => 'Add New Response ',
						'edit_item' => 'Edit Response',
						'new_item' => 'New Response',
						'all_items' => 'All Responses',
						'view_item' => 'View Response',
						'search_items' => 'Search Responses',
						'not_found' =>  'No responses found',
						'not_found_in_trash' => 'No responses found in Trash', 

						),
						'description' => __('Participant responses and/or tutorials to things'),
						'public' => true,
						'show_ui' => true,
						'menu_position' => 5,
						'menu_icon' => 'dashicons-migrate',
						'show_in_nav_menus' => true,
						'supports'  => array(
									'title',
									'editor',
									'author',
									'custom-fields',
									'revisions',
									'comments',
									'trackbacks',
						),
						'has_archive' => true,
						'rewrite' => array( 'slug' => 'response'),
						'taxonomies' => array(
							'assignmenttags',
							'tutorialtags',
							'exampletags',
						),							
		)
	);
}

// edit the post editing admin messages to reflect use of Things
// h/t http://www.joanmiquelviade.com/how-to-change-the-wordpress-post-updated-messages-of-the-edit-screen/

add_filter( 'post_updated_messages', 'bank106_thing_updated_messages', 10, 1 );

function bank106_thing_updated_messages ( $msg ) {
    $msg[ 'assignments' ] = array (
     0 => '', // Unused. Messages start at index 1.
	 1 => "Thing to do updated.",
	 2 => 'Custom fieldupdated.',  // Probably better do not touch
	 3 => 'Custom field deleted.',  // Probably better do not touch

	 4 => "Thing to do updated.",
	 5 => "Thing to do restored to revision",
	 6 => "Thing to do published.",

	 7 => "Thing to do saved.",
	 8 => "Thing to do submitted.",
	 9 => "Thing to do scheduled.",
	10 => "Thing to do draft updated.",
    );
    return $msg;
}


// edit the post editing admin messages to reflect use of Examples

add_filter( 'post_updated_messages', 'bank106_examples_updated_messages', 10, 1 );

function bank106_examples_updated_messages ( $msg ) {
    $msg[ 'examples' ] = array (
     0 => '', // Unused. Messages start at index 1.
	 1 => "Response updated.",
	 2 => 'Custom field updated.',  // Probably better do not touch
	 3 => 'Custom field deleted.',  // Probably better do not touch

	 4 => "Response updated.",
	 5 => "Response restored to revision",
	 6 => "Response published.",

	 7 => "Response saved.",
	 8 => "Response submitted.",
	 9 => "Response scheduled.",
	10 => "Response draft updated.",
    );
    return $msg;
}


// ----------- Column Customizations  ----------------------------------------------------

// modify the listings to include custom columns
add_filter( 'manage_edit-examples_columns', 'bank106_set_custom_edit_examples_columns' );
add_action( 'manage_examples_posts_custom_column' , 'bank106_custom_examples_column', 10, 2 );
 
function bank106_set_custom_edit_examples_columns( $columns ) {
	// modify the admin listing for examples
    unset($columns['categories']); //remove categories
    
    // add column for the things
    $columns['thing'] = __( bank106_option( 'thingname' ), 'bonestheme' );
    return $columns;
}

function bank106_custom_examples_column( $column, $post_id ) {
	switch ( $column ) {
        case 'thing' :
        	// get the ID for the assignment
        	$aid = get_assignment_id_from_terms( $post_id );
        	
        	if ($aid) {
        		echo '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>';
        	} else {
        		echo '--';
        	}
        	 break;
    }
        
}


function bank106_filter_things_by_types( $post_type, $which ) {
	// h/t https://generatewp.com/filtering-posts-by-taxonomies-in-the-dashboard/

	// Apply this only on a specific post type
	if ( 'assignments' !== $post_type )
		return;

	// A list of taxonomy slugs to filter by
	$taxonomies = array( 'assignmenttypes' );

	foreach ( $taxonomies as $taxonomy_slug ) {

		// Retrieve taxonomy data
		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
		$taxonomy_name = $taxonomy_obj->labels->name;
		$taxonomy_queryvar = $taxonomy_obj->query_var;

		// Retrieve taxonomy terms
		$terms = get_terms( $taxonomy_slug );

		// Display filter HTML
		echo "<select name='{$taxonomy_queryvar}' id='{$taxonomy_queryvar}' class='postform'>";
		echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
		foreach ( $terms as $term ) {
			printf(
				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
				$term->slug,
				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
				$term->name,
				$term->count
			);
		}
		echo '</select>';
	}

}
add_action( 'restrict_manage_posts', 'bank106_filter_things_by_types' , 10, 2);



// ----------- Custom Taxonmies  ---------------------------------------------------------


// Assignment Types are like categories for THINGS
function bank106_assignmentbank_tax() {

	// singular name
	$singularThing = 'Thing';
	
	// create taxonomy for assignment types
	register_taxonomy(
		'assignmenttypes', // Taxonomy name
		array( 'assignments' ), // Post Types applied to
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' Types'),
						'singular_name' => __( $singularThing .' Type'),
						'search_items'               => __( 'Search ' . $singularThing . ' Types' ),
						'all_items'                  => __( 'All ' . $singularThing . ' Types' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' Type' ),
						'update_item'                => __( 'Update ' . $singularThing . ' Type' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' Type' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' Type' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' types with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' types' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' types' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' types found.' ),
						),
			'rewrite' => array('slug' => 'type'),
			'query_var' => 'type',
			'show_ui' => true,
			'show_tagcloud' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
		)
	);

	// create taxonomy for assignment categories
	register_taxonomy(
		'assignmentcats', // Taxonomy name
		array( 'assignments' ), // Post Types applied to
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' Categories'),
						'singular_name' => __( $singularThing .' Category'),
						'search_items'               => __( 'Search ' . $singularThing . ' Categories' ),
						'all_items'                  => __( 'All ' . $singularThing . ' Categories' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' Category' ),
						'update_item'                => __( 'Update ' . $singularThing . ' Category' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' Category' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' Category' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' categories with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' categories' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' categories' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' categories found.' ),
						),
			'rewrite' => array('slug' => 'cats'),
			'query_var' => 'cats',
			'show_ui' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
		)
	);
			
	// taxonomy for assignment tags that uniquely identify them matched to examples
	register_taxonomy(
		'assignmenttags', // Taxonomy name
		array( 'assignments', 'examples' ), // Post Types
		array( 
			'labels' => array(
						'name' => __( $singularThing . ' Tags'),
						'singular_name' => __( $singularThing . 'Tag'),
						'search_items'               => __( 'Search ' . $singularThing . ' Tags' ),
						'all_items'                  => __( 'All ' . $singularThing . ' Tags' ),
						'edit_item'                  => __( 'Edit ' . $singularThing . ' Tag' ),
						'update_item'                => __( 'Update ' . $singularThing . ' Tag' ),
						'add_new_item'               => __( 'Add New ' . $singularThing . ' Tag' ),
						'new_item_name'              => __( 'New ' . $singularThing . ' Tag' ),
						'separate_items_with_commas' => __( 'Separate ' . lcfirst($singularThing) . ' tags with commas' ),
						'add_or_remove_items'        => __( 'Add or remove ' . lcfirst($singularThing) . ' tags' ),
						'choose_from_most_used'      => __( 'Choose from the most used ' . lcfirst($singularThing) . ' tags' ),
						'not_found'                  => __( 'No ' . lcfirst($singularThing) . ' tags found.' ),
						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
		)
	);
	
	// taxonomy for tutorial tags
	register_taxonomy(
		'tutorialtags', // Taxonomy name
		array( 'assignments', 'examples') , // Post Types
		array( 
			'labels' => array(
						'name' => __( 'Tutorial Tags'),
						'singular_name' => __('Tutorial Tag'),
						'search_items'               => __( 'Search Tutorial Tags' ),
						'all_items'                  => __( 'All Tutorial Tags' ),
						'edit_item'                  => __( 'Edit Tutorial Tag' ),
						'update_item'                => __( 'Update Tutorial Tag' ),
						'add_new_item'               => __( 'Add New Tutorial Tag' ),
						'new_item_name'              => __( 'New Tutorial Tag' ),
						'separate_items_with_commas' => __( 'Separate tutorial tags with commas' ),
						'add_or_remove_items'        => __( 'Add or remove tutorial tags' ),
						'choose_from_most_used'      => __( 'Choose from the most used tutorial tags' ),
						'not_found'                  => __( 'No tutorial tags found.' ),

						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => false,
			'hierarchical' => false,
		)
	);
	

	// taxonomy for example tags
	register_taxonomy(
		'exampletags', // Taxonomy name
		array( 'examples') , // Post Types
		array( 
			'labels' => array(
						'name' => __( 'Example Tags'),
						'singular_name' => __('Example Tags'),
						'search_items'               => __( 'Search Example Tags' ),
						'all_items'                  => __( 'All Example Tags' ),
						'edit_item'                  => __( 'Edit Example Tags' ),
						'update_item'                => __( 'Update Example Tags' ),
						'add_new_item'               => __( 'Add New Example Tags' ),
						'new_item_name'              => __( 'New Example Tags' ),
						'separate_items_with_commas' => __( 'Separate example tags with commas' ),
						'add_or_remove_items'        => __( 'Add or remove example tags' ),
						'choose_from_most_used'      => __( 'Choose from the most used example tags' ),
						'not_found'                  => __( 'No example tags found.' ),

						),
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
		)
	);
}

// Updates the taxonomies if the name of the Things changes...
function bank106_update_tax ( $oldthingname, $newthingname ) {
	
	// first process the assignment tags. Get 'em first
	$allterms = get_terms( 'assignmenttags', 'hide_empty=0');
			
	// check each term		
	foreach ($allterms as $term) {
	
		// try a string replacement to convert the old term to new
		$newtag = str_replace( $oldthingname, $newthingname, $term->name, $count);
				
		if ($count > 0 ) {
			// update the terms if we find 
			wp_update_term( $term->term_id, 'assignmenttags', array( 'name' => $newtag, 'slug' => sanitize_title( $newtag ) ) );			
		}
	}
	
	// next process the tutorial tags
	$allterms = get_terms( 'tutorialtags', 'hide_empty=0');
	
	// check each term	
	foreach ($allterms as $term) {
	
		// try a string replacement to convert the old term to new
		$newtag = str_replace( $oldthingname, $newthingname, $term->name, $count);
		
		if ($count > 0 ) {
			// update the terms if we foind 
			wp_update_term( $term->id, 'tutorialtags', array( 'name' => $newtag ) );
		}		
	}
}


function get_examples_type_by_tax ( $post_id ) {
	// identifies if an examples post type is a response (internallly called "example") or a tutorial
	// by looking for taxonomies present. Needed because of the mixing of both in one content type. #hindsight2020
	// returns the name of the kind of Example it is.
	
	// once older entries updated, not used as a utility script adds an identifier as post meta
	
	// look first for assignment tags
	$myterms = wp_get_post_terms( $post_id, 'assignmenttags', array('fields' => 'ids') );
	if ( count($myterms) ) return ('Response');
	
	// now look for tutorials
	$myterms = wp_get_post_terms( $post_id, 'exampletags', array('fields' => 'ids') );
	if ( count($myterms) ) return (bank106_option('helpthingname'));
	
	// got nothing
	return ('');

}



// ----------- Helpers  -----------------------------------------------------------------

/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
   h/t http://wordpress.stackexchange.com/a/99516/14945
 */
function bank106_sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0)
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        bank106_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
    }
}

// ----- set up author type queries for custom post types
add_action( 'pre_get_posts', 'bank106_author_examples' );

function bank106_author_examples( $query ) {

    if ( $query->is_author() && $query->is_main_query() ) {
    	// author query default query is for examples (aka responses) that are responses
        $query->set( 'post_type',  'examples' );
        $query->set( 'meta_key',  'example_type' );
        $query->set( 'meta_value',  'ex' );
        $query->set( 'posts_per_page',  -1 );
        return;
   }
    
    if ( $query->is_tag() && $query->is_main_query() ) {
    	// set post type for tag queries (assignmnt tags)
        $query->set( 'post_type', 'assignments' );
         return;
    }
    
     if ( $query->is_category('featured') && $query->is_main_query() ) {
     	// set post type for featured category queries
        $query->set( 'post_type', 'examples' );
         return;
    }
   
}

// ----- set unique tags on saving an assignment 
add_action( 'post_updated', 'set_assignment_tag');

function set_assignment_tag( $post_id ) {
	// on saving an assignment make sure it is assigned  unique tags 
	// based on type of assignment and post ID
	// code from http://codex.wordpress.org/Plugin_API/Action_Reference/post_updated

	// skip if not an assignment post type or it is a revision
	
	if ( isset( $_POST['post_type']  ) AND ( $_POST['post_type'] != 'assignments' or wp_is_post_revision( $post_id ) ) ) return;
	
    /* Request passes all checks; update the things's taxonomy */
	update_assignment_tags( $post_id );
	
}
    
function update_assignment_tags( $post_id ) {
    // helper function to update the bank assignmed tags 
    
    // get terms for type of assignment
	$assignmenttype_terms = wp_get_object_terms($post_id, 'assignmenttypes');
		
	// make a tag for the type of assignment, assign to both taxonomies
	// for assignment examples and tutorials
	if ( (! isset( $assignmenttype_terms->errors ) )
		 && count( $assignmenttype_terms ) ) {
		$assignment_type = $assignmenttype_terms[0]->name . bank106_option( 'thingname' );
		wp_set_object_terms( $post_id, $assignment_type , 'assignmenttags');
		wp_set_object_terms( $post_id, $assignment_type , 'tutorialtags');
	}	
    
    // create unique tag names based on post ids
    $assignment_tag = bank106_option( 'thingname' ) . $post_id; 
    $tutorial_tag =  'Tutorial' . $post_id;
     
    if ( term_exists(  $assignment_tag, 'assignments') == 0) {
    	// check if term does not exist, then add to assignment tags
    	wp_insert_term( $assignment_tag, 'assignmenttags' );
    }
    
    if ( term_exists(  $tutorial_tag , 'assignments') == 0) {
    	// check if term does not exist, then add to tutorial tags
    	wp_insert_term( $tutorial_tag , 'tutorialtags' );
    }

    // now assign tags, append to other terms
    wp_set_object_terms( $post_id, $assignment_tag, 'assignmenttags', true);
    wp_set_object_terms( $post_id, $tutorial_tag, 'tutorialtags', true );
}

// Add a new type aka category

function bank106_add_new_types( $new_types ) {
	// convert text area input into array, based on new line breaks (remove CR)
	// and add each items as a new taxonomy type
	
	$new_types = explode( "\n", str_replace( "\r", "", $new_types ) );
	
	foreach ( $new_types as $item) {
		if ( $item != '' AND term_exists(  $item, 'assignmenttypes') == 0) {
		
    		// check if term does not exist (or is blank), then add to assignment type taxonomy
    		wp_insert_term( $item, 'assignmenttypes' );
    	}
    }
}
?>