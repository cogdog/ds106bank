<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">
					<div class="page-header">
						<h1 class="archive_title"><?php echo THINGNAME?>s Tagged "<?php single_tag_title(); ?>"</h1>

					</div>

				</div> <!-- end #main -->
 			</div> <!-- end #content -->

					
					  <?php
						$startrow = false; // flag to start a new row ?>
						
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
							
							<?php
							// get the terms for the assignment type taxonomy
							$assignmenttype_terms = wp_get_object_terms($post->ID, 'assignmenttypes');

							// we expext only 1 assignment type
							$my_assignment_type = $assignmenttype_terms[0];

							$startrow = !$startrow;
							
							// start a new row?
							if ($startrow)  {
								echo '<div class="clearfix row"><div class="col-md-5 assignment_listing">'; 
							} else {
								echo '<div class="col-md-5 col-md-offset-1 assignment_listing">';
							}
						?>
											
					<article id="post-<?php the_ID(); ?>"  role="article" class="thing-archive">
						
						<!--  thing header -->
						<header>
							
							<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							
							<?php 
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
						
							// look for author name in Feedwordpress meta data
							$assignmentAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							// no author assigned
							if ( !$assignmentAuthor) $assignmentAuthor = 'Anonymous';
							?>
							
							
							<p class="meta">
								Created <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $assignmentAuthor?></strong> &bull; <strong><?php echo get_assignment_meta( $post->ID, 'assignment_visits')?></strong> views &bull;  <strong><?php echo get_assignment_meta( $post->ID, 'assignment_examples')?></strong> examples</strong> &bull;  <strong><?php echo get_assignment_meta( $post->ID, 'assignment_tutorials')?></strong> tutorials
							</p>
							
						</header> 
						<!-- end thing header -->


						<!-- thing icon -->
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


			</div> <!-- end assignment listing -->
					
					<?php if (!$startrow) echo '</div>'; // end of row?>
										
					<?php endwhile; 
					
					if ($startrow) echo '</div>'; // ended with in-complete row?
					
					?>	
					
					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="pager">
								<li class="previous"><?php next_posts_link(_e('&laquo; Previous ', "wpbootstrap")) ?></li>
								<li class="next"><?php previous_posts_link(_e('Newer  &raquo;', "wpbootstrap")) ?></li>
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