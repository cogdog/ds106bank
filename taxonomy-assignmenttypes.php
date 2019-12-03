<?php
global $wp_query; // give us query

$term =	$wp_query->queried_object; // the term we need for this taxonomy

// kind of sort passed by paramaters
$sortedby =  ( isset( $wp_query->query_vars['srt'] ) ) ? $wp_query->query_vars['srt'] : 'newest';

$use_public_ratings = function_exists('the_ratings');

// we are looking for a random assignment?
if ($sortedby  == 'random') {

	// set arguments for WP_Query() using taxonomy 
	$args = array(
		'post_type' => 'assignments',
		'tax_query' => array(
				array(
					'taxonomy' => 'assignmenttypes',
					'field' => 'slug',
					'terms' => $term->slug
				)
			),
		'posts_per_page' => 1,
		'orderby' => 'rand'
	);

	// get a random post from the database
	$my_random_post = new WP_Query ( $args );

	// process the database request through WP_Query
	while ( $my_random_post->have_posts () ) {
	  $my_random_post->the_post ();
	  // redirect the user to the random post
	  wp_redirect ( get_permalink () );
	  exit;
	}

}

get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8" role="main">
					<?php

						$sortoptions = array('newest' => 'Newest' , 'title' => 'Title', 'examples' => 'Most Responses', 'views'=>'Most Viewed', 'random' => 'Choose One Randomly');
					
						if ( $use_public_ratings )  {
							// add option for sorting by wp-ratings
							$sortoptions['ratings'] = 'Public Ratings';
						} 
						
						if ( bank106_option('difficulty_rating') ) {
							// add option for sorting by wp-ratings
							$sortoptions['difficulty'] = 'Difficulty';
						}
			

					if ($sortedby != 'newest') {
						// if not default taxonomy view, we need to adjust the query
						global $query_string;
	
						switch ($sortedby) {
							case 'title':
								query_posts( $query_string . '&orderby=title&order=ASC' );
								break;
							case 'ratings':		
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=ratings_average&order=DESC' );
								break;
							case 'difficulty':		
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=assignment_difficulty&order=DESC' );
								break;
											
							case 'examples':
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=assignment_examples&order=DESC' );
								break;
			
							case 'views':
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=assignment_visits&order=DESC' );
								break;
		
							default:
								query_posts( $query_string);
								$sortedby = 'newest';
						}
					} 


					// count of items found
					$found_things = $wp_query->found_posts;				
					// Because grammar
					if ( $found_things == 1 ) {
						$plural = bank106_option( 'thingname' );
						$verb = "is";
					} else {
						$plural = bank106_option( 'pluralthings' );
						$verb = "are";
					}

					
					?>				
					<div class="page-header">
						<h1 class="archive_title"><?php echo $term->name;?> <?php echo $plural;?></h1>
						<p><em><?php echo $term->description;?></em></p>
						
						<form id="taxassignmentview" method="get">
						<p>There <?php echo $verb?>  <strong><?php echo $found_things;?></strong> <?php echo $term->name;?> <?php echo   $plural?>. View sorted by <select name="goto" id="assignmentList" onchange="window.location.href= this.form.goto.options[this.form.goto.selectedIndex].value">
							<?php
							// remove any query string from current URL
							$base_url = strtok( $_SERVER["REQUEST_URI"], '?' );
							
							foreach ($sortoptions as $key => $value) {
								$selected = ($key == $sortedby) ? ' selected' : '';
								echo '<option value="' . $baseurl . 
								'?srt=' .  $key . '"' . $selected . '>' . $value . '</option>';
							}
							?>
							</select>
							</p>
							</form>	
					</div>		

				</div> <!-- end #main -->


					<?php if (have_posts()) :?>
					
					   <div id="assignmentmenu" class="clearfix row"> <!-- thing menu -->
					  <?php
					  
						$odd = false; // flag to start a new row
						
						while (have_posts()) : the_post();
							
							
							if ( $odd ) {
								echo '<div class="col-sm-offset-1 col-sm-5">';
							} else {
								echo '<div class="clearfix row"><div class="col-sm-5">'; 
							}
						 
							$odd = !$odd;
						?>
					
					
						<article id="post-<?php the_ID(); ?>" role="article" class="thing-archive">
						<!--  thing header -->
						<header>
							
							<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							
							<?php 
							// insert ratings if enabled
							if ( $use_public_ratings ) { the_ratings(); }
							
							// author name either from WP user or from meta data (uses old FWP meta)
							$assignmentAuthor = bank106_get_display_name( $post->ID, 'fwp_name' );
							?>
							
							
							<p class="meta">
								Created <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $assignmentAuthor?></strong> <?php echo bank106_user_credit_link( $post->ID, '(', ')' )?> <?php echo get_assignment_meta_string( $post->ID );?>
							</p>
							
						</header> 
						<!-- end thing header -->

						<!-- thing icon or embedded media -->
						<div class="thing-icon">
						<a href="<?php the_permalink(); ?>"><?php echo get_thing_icon ($post->ID, 'thumbnail', 'thing-archive') ?></a>
						</div>
						<!-- end icon or media -->
					
						<!-- thing content -->
						<section class="post_content">
						
							<?php the_excerpt(); ?><p class="more-link"><a href="<?php the_permalink(); ?>"  class="btn btn-primary"><?php echo bank106_option( 'thingname' )?> Details</a><?php edit_post_link( __( 'Edit', 'wpbootstrap' ), '<br /><span class="edit-link">', '</span>' ); ?></p>
					
						</section> <!-- end article section -->
						
						<!-- end thing content -->
					
					</article> <!-- end article -->
					
					</div> <!-- end assignment listing -->
					<?php if ( !$odd ) echo '</div> <!-- end row -->';?>
					
					<?php endwhile; ?>	
					
					</div> <!-- end assignment menu -->
				
					
					
					
					
					<div class="col-sm-12 text-center">
					<?php if (function_exists('wp_bootstrap_page_navi')) { // if expirimental feature is active ?>
						
						<?php wp_bootstrap_page_navi(); // use the page navi function ?>

							<?php } else { // if it is disabled, display regular wp prev & next links ?>
								<nav class="wp-prev-next">
									<ul class="pager">
										<li class="previous"><?php next_posts_link(_e('&laquo; Older Entries', "wpbootstrap")) ?></li>
										<li class="next"><?php previous_posts_link(_e('Newer Entries &raquo;', "wpbootstrap")) ?></li>
									</ul>
								</nav>
							<?php } ?>
							<?php else:?>
							<div class="col-sm-8">
								<article>
									<header>
									</header>
									<section class="post_content">
										<p><?php _e("Hmmm, Nothing here. You should create some " . bank106_option( 'pluralthings' ) . " to go here!", "wpbootstrap"); ?></p>
									</section>
									<footer>
									</footer>
								</article>
								</div>

													
							<?php endif; ?>
						</div>

<?php get_footer(); ?>

</div>