<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							
							<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>
							
							<?php
								// Fix the examples to add new post meta
								$example_query = new WP_Query( 
									array(
										'posts_per_page' =>'-1', 
										'post_type' => 'examples',
									)
								);
	
								if ( $example_query->have_posts() ) :
								
								echo '<ol>';
 

								while ( $example_query->have_posts() ) : $example_query->the_post();
									
									 $typ = ( get_examples_type_by_tax ( get_the_ID() ) == 'Response' ) ? 'ex' : 'tut';
									 
									 echo '<li>' . get_the_title() . ': setting type to <strong>' . $typ . '</strong></li>';
									 update_post_meta( get_the_ID(), 'example_type', $typ );
								endwhile;

 								echo '</ol>';
 
								wp_reset_postdata();
 
								else :
									echo  '<p>Sorry, nothing found.</p>';
								endif;						

							
							?>
					
						</section> <!-- end article section -->
						
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","wpbootstrap") . ':</span> ', ', ', '</p>'); ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php comments_template('',true); ?>
					
					<?php endwhile; ?>		
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    
				<?php get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>