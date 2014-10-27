<?php 

global $wp_query; // give us query

// kind of sort passed by paramaters
$sortedby =  ( isset( $wp_query->query_vars['srt'] ) ) ? $wp_query->query_vars['srt'] : 'newest';

// we are looking for a random thing?
if ($sortedby  == 'random') {

	// set arguments for WP_Query() using taxonomy 
	$args = array(
		'post_type' => 'things',
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
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=thing_responses&order=DESC' );
								break;
			
							case 'views':
								query_posts( $query_string . '&orderby=meta_value_num&meta_key=thing_visits&order=DESC' );
								break;
		
							default:
								query_posts( $query_string);
								$sortedby = 'newest';
						}
					} 

					
					?>
					<div class="page-header">
						<h1 class="archive_title">All <?php echo THINGNAME?>s</h1>
						
						<form action="" id="taxthingview" method="get" action="">
						<p>There are <strong><?php echo $wp_query->found_posts;?></strong> <?php echo lcfirst(THINGNAME);?>s.  View sorted by <select name="goto" id="thingList" onchange="window.location.href= this.form.goto.options[this.form.goto.selectedIndex].value">
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
 			</div> <!-- end #content -->

					<?php if (have_posts()) :?>
					
					  <?php
						$startrow = false; // flag to start a new row
						
						while (have_posts()) : the_post();
							
							// get the terms for the thing type taxonomy
							$thingtype_terms = wp_get_object_terms($post->ID, 'thingtypes');

							// we expext only 1 thing type
							$my_thing_type = $thingtype_terms[0];

							$startrow = !$startrow;
							
							// start a new row?
							if ($startrow)  {
								echo '<div class="clearfix row"><div class="col-md-5 thing_listing">'; 
							} else {
								echo '<div class="col-md-5 col-md-offset-1 thing_listing">';
							}
						?>
											
					<article id="post-<?php the_ID(); ?>" role="article" class="thing-archive">
						
						<!--  thing header -->
						<header>
							
							<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							
							<?php 
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
						
							// look for author name in Feedwordpress meta data
							$thingAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							// no author assigned
							if ( !$thingAuthor) $thingAuthor = 'Anonymous';
							?>
							
							
							<p class="meta">
								Created <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $thingAuthor?></strong> &bull; <strong><?php echo get_thing_meta( $post->ID, 'thing_visits')?></strong> views &bull;  <strong><?php echo get_thing_meta( $post->ID, 'thing_responses')?></strong> responses</strong> &bull;  <strong><?php echo get_thing_meta( $post->ID, 'thing_tutorials')?></strong> tutorials
							</p>
							
						</header> 
						<!-- end thing header -->


						<!-- thing icon or embedded media -->
						<div class="thing-icon">
						<a href="<?php the_permalink(); ?>"><?php get_thing_icon ($post->ID, 'thumbnail', true) ?></a>
						</div>
						<!-- end icon or media -->
					
						<!-- thing content -->

					
						<section class="post_content">
						
							<?php the_excerpt(); ?><p class="more-link"><a href="<?php the_permalink(); ?>" class="btn btn-primary">View <?php echo THINGNAME?>s</a></a>
							
							<?php edit_post_link( __( 'Edit', 'wpbootstrap' ), '<br /><span class="edit-link">', '</span>' ); ?></p>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->


			</div> <!-- end thing listing -->
					
					<?php if (!$startrow) echo '</div>'; // end of row?>
										
					<?php endwhile; 
					
					if ($startrow) echo '</div>'; // ended with in-complete row?
					
					?>	
					
					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="pager">
								<li class="previous"><?php next_posts_link(_e('&laquo; Older Entries', "wpbootstrap")) ?></li>
								<li class="next"><?php previous_posts_link(_e('Newer Entries &raquo;', "wpbootstrap")) ?></li>
							</ul>
						</nav>
					<?php } ?>
								
					
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
			


<?php get_footer(); ?>