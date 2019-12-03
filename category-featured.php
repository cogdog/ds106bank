<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-10 clearfix" role="main">
				
					<div class="page-header">
 						<h1 class="archive_title h2">
					    	<span><?php _e('Featured ' . bank106_option( 'thingname' ) . ' Responses', "wpbootstrap"); ?> </span>
					    </h1>					</div>

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					

						<?php 
					
						// get the ID for the assignment this belongs to
						$aid = get_assignment_id_from_terms( get_the_ID() );
						// make a link string
						$assignment_str = ($aid) ? '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>' : '';
					
						// get link to item
						
						$the_real_permalink = bank106_get_response_link( $post->ID );
						$more_link = '<a href="' .  $the_real_permalink . '" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-link"></span> ' . $the_real_permalink . '</a>';
						?>
			
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>
							
							<h3 class="h2"><a href="<?php echo $the_real_permalink ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> </h3>
							
							<p class="meta"><?php _e("Created", "wpbootstrap"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time> <?php _e("by", "wpbootstrap"); ?> <?php echo get_post_meta($post->ID, 'syndication_source', $single = true);?>, response  for  <?php echo $assignment_str?> <?php echo bank106_option( 'thingname' )?></p>
						
						</header> <!-- end article header -->
					
						<section class="post_content">
												
							<?php the_excerpt(); ?>
							<p class="more-link"><?php echo $more_link?></p>
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php endwhile; ?>	
					
					<?php if (function_exists('wp_bootstrap_page_navi')) { // if expirimental feature is active ?>
						
						<?php wp_bootstrap_page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="pager">
								<li class="previous"><?php next_posts_link(_e('&laquo; Older Responses', "wpbootstrap")) ?></li>
								<li class="next"><?php previous_posts_link(_e('Newer Responses &raquo;', "wpbootstrap")) ?></li>
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
			
				</div> <!-- end #main -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>