<?php get_header(); ?>

<?php
	
	// unique thing/tutorial tags
	$my_thing_tag = THINGNAME . $post->ID;
	$my_tutorial_tag = 'Tutorial' . $post->ID;
	
	//options for response/tutorial syndication
	
	$my_fwp_mode = ds106bank_option( 'use_fwp'); // Syndication mode = none, intenal, external
	$my_use_response_form = ds106bank_option( 'response_via_form' ); // allow form additions of responses, tutorials
	
	$my_hub_site = ds106bank_option('syndication_site_name'); // external syndication site
	$my_hub_url =  ds106bank_option('syndication_site_url'); // external syndication url
	$my_syndication_tag = ds106bank_option( 'extra_tag' ); // external syndication required tag
	
	$my_cc_mode = ds106bank_option( 'use_cc' ); // creative commons usage mode

	// store thing link for later use
	$my_permalink = get_permalink();
	$my_id = $post->ID;
?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			
			<article id="post-<?php the_ID(); ?>"  role="article">
			
				<div class="clearfix row">
					<header>
						<div class="col-md-4">
						
							<div class="thing-icon-single">
							<!-- insert/embed assignmet icon -->
							<?php get_thing_icon ( $my_id ,'thumbnail')?>
							</div>
						</div>

						<div class="col-md-6 col-md-offset-1" >

								
							<h1 class="single-title thing-header" itemprop="headline"><?php the_title(); ?></h1>
							
							<?php 
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
						
							// look for author name in Feedwordpress meta data
							$thingAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							// no author assigned
							if ( !$thingAuthor) $thingAuthor = 'Anonymous';
							?>
							
							<p class="meta">This <?php echo THINGNAME?> was 
							<?php _e("created", "wpbootstrap"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $thingAuthor?></strong>
							</p>
							
							<p><?php echo get_the_term_list( $post->ID, 'thingtypes', 'Type: ', ', ', '' ); ?> </p>
							
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags", "wpbootstrap") . ':</span> ', ' ', '</p>'); ?>
							
							<?php 
								// only show edit button if user has permission to edit posts
								if( $user_level > 0 ) { 
								?>
								<a href="<?php echo get_edit_post_link(); ?>" class="btn btn-success edit-post"><i class="icon-pencil icon-white"></i> <?php _e("Edit This " . THINGNAME,"wpbootstrap"); ?></a>
								<?php } ?>
						</div>
					</header> <!-- end article header -->	
				</div>	<!-- end row -->
					
				<div id="thingcontent" class="clearfix row">	
						<div class="col-md-12" \>
						
							<?php the_content(); ?>
					
							<footer>
							
							<?php get_response_media($my_id)?>
							
						
							</footer> <!-- end article footer -->
						</div>
				</div>
					
				</article> <!-- end article -->

				<div  class="clearfix row hilite">
						<div class="clearfix col-md-5">
						<h3>Do this <?php echo THINGNAME?></h3>
						<p>Once you complete this <?php echo lcfirst(THINGNAME)?>, share it! 
												
						<?php if ( $my_fwp_mode == 'internal' ):?>
						If you are writing to a blog connected to this site just use the tag <strong><?php echo $my_thing_tag;?></strong> when writing a post on your own blog. Then your response will be added to the list below. <br /><br />Or if 
						
						<?php elseif ( $my_fwp_mode == 'external' ):?>
						If you are writing to a blog that feeds  <a href="<?php echo $my_hub_url?>"><?php echo $my_hub_site?></a>  just use the following tags when writing a post on your own blog. (You must use BOTH tags!):  <strong><?php echo $my_syndication_tag . ', ' .  $my_thing_tag;?></strong> Then your response will be added to the list below. <br /><br />Or if 
						
						<?php else:?>
						If 
						<?php endif?>
						
						<?php if ( $my_use_response_form):?>
						your response exists elsewhere at a public URL <a href="<?php echo site_url(); ?>/<?php echo ds106bank_option( 'response_form_page' )?>/?aid=<?php echo $my_id?>&typ=ex">add your response directly</a><?php if ( ds106bank_option( 'new_response_status' ) == 'draft') echo ' (pending moderator approval)'?>.
						<?php endif?>
			
						</p>
						</div>
						
						<div  class="col-md-5  col-md-offset-1">
							<h3>Tutorials for this <?php echo THINGNAME?></h3>
								<p>Have you created something to help others complete this <?php echo lcfirst(THINGNAME)?>? Share it and help someone else. 
												
								<?php if ( $my_fwp_mode == 'internal' ):?>
								If you are writing to a blog connected to this site just use the tag <strong><?php echo $my_tutorial_tag;?></strong> when writing a  post on your own blog. Then your tutorial will be added to the list below. <br /><br />Or if 
						
								<?php elseif ( $my_fwp_mode == 'external' ):?>
								If you are writing to a blog that feeds  <a href="<?php echo $my_hub_url?>"><?php echo $my_hub_site?></a>  just use the following tags when writing a post on your own blog. (You must use BOTH tags!):  <strong><?php echo $my_syndication_tag . ', ' .  $my_tutorial_tag;?></strong> Then your tutorial will be added to the list below. <br /><br />Or if 
						
								<?php else:?>
								If 
								<?php endif?>
						
								<?php if ( $my_use_response_form):?>
								your tutorial exists elsewhere at a public URL <a href="<?php echo site_url(); ?>/<?php echo ds106bank_option( 'response_form_page' )?>/?aid=<?php echo $my_id?>&typ=tut">add your tutorial directly</a><?php if ( ds106bank_option( 'new_response_status' ) == 'draft') echo ' (pending moderator approval)'?>.
								<?php endif?>
			
								</p>
						</div>
					</div>
					
					<div id="content4" class="clearfix row">	
						<div class="col-md-5">
							<?php
							// find all responses done for this thing
							$responses_done_query = new WP_Query( 
								array(
									'posts_per_page' =>'-1', 
									'post_type' => 'responses',
									'thingtags'=> $my_thing_tag, 
								
								)
							);
						
							$response_count = $responses_done_query->post_count;
							$plural = ( $response_count == 1) ? '' : 's';
						
							?>
		
							<h3><?php echo $response_count?> Response<?php echo $plural?> Completed for this <?php echo THINGNAME?></h3>
							<ol>
							<?php 
	
			
							while ( $responses_done_query->have_posts() ) : $responses_done_query->the_post();
									
								// get link
								if (get_post_meta($post->ID, 'syndication_permalink')) {
								  $the_real_permalink = get_post_meta($post->ID, 'syndication_permalink', true);
								} else {
								  $the_real_permalink = the_permalink();
								} ?>
							<li><a href="<?php echo $the_real_permalink ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a><br />
							<?php the_excerpt(); ?></li>
						
							<?php endwhile; ?>
						
							</ol>
						</div>
						
						<div class="col-md-5  col-md-offset-1">
					

					
						<?php 
						// now get all tutorials done for this thing
	
							$tutorials_done_query = new WP_Query( 
								array(
									'posts_per_page' =>'-1', 
									'post_type' => 'responses',
									'tutorialtags'=> $my_tutorial_tag, 	
								)
							);
							$tutorial_count = $tutorials_done_query->post_count;
							$plural = ( $tutorial_count == 1) ? '' : 's';
						?>
					
					
							<h3><?php echo $tutorial_count?> Tutorial<?php echo $plural?> Created for this <?php echo THINGNAME?></h3>	
							<ol>
							<?php 

							while ( $tutorials_done_query->have_posts() ) : $tutorials_done_query->the_post();
									
								// get link
								if (get_post_meta($post->ID, 'syndication_permalink')) {
								  $the_real_permalink = get_post_meta($post->ID, 'syndication_permalink', true);
								} else {
								  $the_real_permalink = the_permalink();
								} ?>
							<li><a href="<?php echo $the_real_permalink ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a><br />
							<?php the_excerpt(); ?></li>
						
							<?php endwhile; ?>
						
							</ol>
							 <?php wp_reset_query(); ?>
						</div>
						<div class="col-md-12 hilite clearfix">
							<p class="meta" style="text-align:center; padding:1em;">
							<?php 
								// display creative commons?
						
								if ( $my_cc_mode != 'none' ) {
							
								// get the license code, either define for site or post meta for user assigned						
								$cc_code = ( $my_cc_mode == 'site') ? ds106bank_option( 'cc_site' ) : get_post_meta($post->ID, 'cc', true);
								echo cc_license_html($cc_code, $thingAuthor, get_the_time( "Y", $my_id ));
							}
							?>						
						
							</p>
						</div>
					
					</div>
					
					<div id="content" class="row">
						<div class="col-md-8 ">
					<!-- comments -->	
						<?php comments_template('',true); ?>
					
						</div>
					
					<?php endwhile; ?>	
					
					<?php
					// let's update meta data for this thing (count of exmaples done, bump visit count)
					update_thing_meta($post->ID, $response_count, $tutorial_count);		
					?>
							
					
					<?php else : ?>
					<div class="col-md-8">
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
					</div>
					<?php endif; ?>
			
			</div>

<?php get_footer(); ?>