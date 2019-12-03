<?php 
global $wp_query; // give us query or ....
get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-10 clearfix" role="main">
				
					<div class="page-header">
 						<h1 class="archive_title h2">
 						
					    	<span><?php 
					    	
					    	$title_str =  bank106_option( 'thingname' ) . ' Responses ';
							
							// are we showing tutorial links on site?
							if ( bank106_option('show_ex') == 'tut' or bank106_option('show_ex') == 'both' ) {
								$title_str .=  'and ' . bank106_option('helpthingname') . 's ';
							}
							// fetch the tag in question
							$the_tag = single_term_title( '', false);
						
							if ( $the_tag[0] == '@' ) {
								// this is a user/contributor tag
								$title_str .= "Contributed by $the_tag";
								$descriptor = ' by ' . substr($the_tag, 1) . " in " . get_bloginfo() . '. See also <a href=' . site_url() . '/tag/' .  $the_tag . '">'   . bank106_option( 'pluralthings' ) . ' created by ' . substr($the_tag, 1) . '</a>';
							} else {
								// just a regular tag
								$title_str .= 'Tagged "' . $the_tag . '"';
								$descriptor = "with this tag";
							}
							
							echo $title_str;
							
							// count of items found
							$found_things = $wp_query->found_posts;				
							// Because grammar
							if ( $found_things == 1 ) {
								$tagged_name = 'item';
								$verb = "is";
						
							} else {
					
								$tagged_name = 'items';
								$verb = "are";
						
							}
							?>
							
							</h1>
							<p class="text-center">There <?php echo $verb?> <strong><?php echo $found_things;?></strong>  <?php echo $tagged_name?> <?php echo $descriptor?></p>
							
					    </div>

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
						<?php 
						// is it a response (example) or tutorial?
						$extype =  ( get_post_meta( $post->ID, 'example_type', true ) == 'ex' ) ? 'Response' : bank106_option( 'helpthingname' );

						
						// set the classes for Bootstrap label names
						$bootstrap_label = ( $extype == 'Response' ) ? 'default' : 'info';
					
						// get the ID for the assignment this belongs to
						$aid = get_assignment_id_from_terms( get_the_ID() );
						
						// make a link string
						$assignment_str = ($aid) ? '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>' : '';
					
						// get link for item
						$the_real_permalink = bank106_get_response_link( $post->ID );
						$more_link = '<a href="' .  $the_real_permalink . '" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-link"></span> ' . $the_real_permalink . '</a>';
						
						// author name either from WP user or from meta data (uses old FWP meta)
						$assignmentAuthor = bank106_get_display_name( $post->ID, 'syndication_source' );	
						?>
			
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>
							
							<h3 class="h3"><a href="<?php echo $the_real_permalink ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
						<?php if (bank106_option('show_ex') == 'both') :?>
							<small><span class="label label-<?php echo $bootstrap_label?>"><?php echo ucfirst($extype)?></span></small>
							<?php endif?>
							</h3>
							
							<p class="meta"><?php _e("Created", "wpbootstrap"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time> <?php _e("by", "wpbootstrap"); ?> <?php echo $assignmentAuthor?>  <?php echo bank106_user_credit_link(  $post->ID, '(', ')', 'exampletags' )?>, <?php echo lcfirst($extype)?>  for  <?php echo $assignment_str?> <?php echo bank106_option( 'thingname' )?> <?php edit_post_link( __( 'Edit', 'wpbootstrap' ), '<span class="label label-warning">', '</span>' ); ?></p>
						
						</header> <!-- end article header -->
					
						<section class="post_content">
												
							<?php the_excerpt(); ?>
							
							<p class="more-link"><?php echo $more_link?></p>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php endwhile; ?>	
					
					<div class="col-sm-12 text-center">
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
					</div>		
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("No Responses Tagged Yet", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Yikes, What you were looking for is not here.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>