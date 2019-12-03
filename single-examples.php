<?php
	// check if this is a syndicated external link
	$the_real_permalink = get_post_meta( $post->ID, 'syndication_permalink', true );
	
	if ( $the_real_permalink ) {
		// let's go to the link instead of doing anything else
		 wp_redirect (  $the_real_permalink );
		 exit;
	}
	
	// We got a real thing to show, get some meta data first
								  
	$exampleURL = get_post_meta( $post->ID, 'example_url', true ); 
	$exampleTwitter = get_post_meta( $post->ID, 'submitter_twitter', true );
	$exampleCredit = ( get_post_meta( $post->ID, 'example_source', true ) ) ? ' (' . get_post_meta( $post->ID, 'example_source', true ) . ')' : '';
	
	// get the assignment ID and permalink this example is a response to
	$assignment_id = get_assignment_id_from_terms( $post->ID );
	$assignment_link = get_permalink( $assignment_id );
	
	get_header();
?>

<div id="content" class="clearfix row">

	<div id="main" class="col-sm-12 clearfix" role="main">	
		<?php if (have_posts()) : while (have_posts()) : the_post(); 
			
			$exampleSource = bank106_get_display_name( $post->ID, 'syndication_source' );
			?>
			
			
			<article id="post-<?php the_ID(); ?>"  role="article">
			
				<div class="clearfix row">
					<header>
						<div class="col-sm-8">
						
							<h1 class="single-title assignment-header" itemprop="headline"><?php the_title(); echo $exampleCredit; ?></h1>
						
							<p class="meta">A response to the <a href="<?php echo $assignment_link?>"><?php echo get_the_title( $assignment_id );?></a> <?php echo bank106_option( 'thingname' )?><br />
							<?php _e("created", "wpbootstrap"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $exampleSource?></strong> <?php echo bank106_user_credit_link( $post->ID, '(', ')', 'exampletags' )?><br /><br />
							Number of views: <strong><?php echo get_post_meta( $post->ID, 'examples_visits', true);?></strong>
							</p>
						
							<p class="tags"><?php echo get_the_term_list( $post->ID, 'exampletags', 'Tags: ', ', ', '' ); ?></p>
						
						<hr />
							
						<?php the_content(); ?>
						
						<?php bank106_twitter_button ( $post->ID, 'Response' )?>
						
						</div>	
						
						<div class="col-sm-4" id="examplemedia">
						
						<?php echo get_example_media( $post->ID,  'example_url' )?>
						
						</div>
							
						
					</header> <!-- end article header -->	
				</div>	<!-- end row -->
								
				</article> <!-- end article -->

					
					
					<div id="content" class="row">
						<div class="col-sm-8 ">
					<!-- comments -->	
						<?php comments_template('',true); ?>
					
						</div>
					</div>
					<?php endwhile; ?>	
					
					<?php
					// let's update meta data for this assignment (count of exmaples done, bump visit count)
					update_example_meta( $post->ID );		
					?>
							
					
					<?php else : ?>
					<div class="col-sm-8">
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Uh oh", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Egads, but the requested item was not found.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					</div>
					<?php endif; ?>			
			
	</div> <!-- end #main -->
</div> <!-- end #content -->
<?php get_footer(); ?>