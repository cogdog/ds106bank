<?php 

global $wp_query; // give us query

// kind of sort passed by paramaters
$sortedby =  ( isset( $wp_query->query_vars['srt'] ) ) ? $wp_query->query_vars['srt'] : 'newest';

// label for the tutorials, now customizable as theme option
$help_thing_name = lcfirst( bank106_option('helpthingname') );

// we are looking for a random assignment?
if ($sortedby  == 'random') {

	// set arguments for WP_Query() using taxonomy 
	$args = array(
		'post_type' => 'assignments',
		'posts_per_page' => 1,
		'orderby' => 'rand'
	);

	// get a random post from  database
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
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php
						
					if (function_exists('the_ratings') )  {
						$sortoptions = array('newest' => 'Newest' , 'title' => 'Title', 'ratings' => 'Difficulty', 'responses' => 'Most Responses', 'views'=>'Most Viewed', 'random' => 'Choose one Randomly');
					} else {
						$sortoptions = array('newest' => 'Newest' , 'title' => 'Title', 'responses' => 'Most Responses', 'views'=>'Most Viewed', 'random' => 'Choose one Randomly');
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
			
							case 'responses':
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

					
					?>
					<div class="page-header">
						<h1 class="archive_title">All <?php echo bank106_option( 'pluralthings' )?></h1>
						
						<form action="" id="taxassignmentview" method="get" action="">
						<p>There are <strong><?php echo $wp_query->found_posts;?></strong> <?php echo lcfirst(bank106_option( 'pluralthings' ));?>s.  View sorted by <select name="goto" id="assignmentList" onchange="window.location.href= this.form.goto.options[this.form.goto.selectedIndex].value">
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

				<div id="assignmentmenu" class="clearfix row"> <!-- thing menu -->'
				
					<?php if (have_posts()) :?>
					
					  <?php
						$odd = false;
						
						while (have_posts()) : the_post();
							
							// get the terms for the assignment type taxonomy
							$assignmenttype_terms = wp_get_object_terms($post->ID, 'assignmenttypes');

							// we expext only 1 assignment type
							$my_assignment_type = $assignmenttype_terms[0];

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
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
						
						
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
							
							<?php edit_post_link( __( 'Edit', 'wpbootstrap' ), '<br /><span class="edit-link">', '</span>' ); ?></p>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
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
					</div>			
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("No Posts Yet", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, What you were looking for is not here.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
	</div> <!-- end #content -->

<?php get_footer(); ?>


</div>