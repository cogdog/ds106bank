<?php
	// defaults set to empty
	$signin = $extra_btn_class = '';
	
	// zero the counts
	$example_count = $tutorial_count = 0;
	
	// store assignment link for later use
	$my_permalink = get_permalink();
	$my_id = $post->ID;

	// unique assignment/tutorial tags
	$my_assignment_tag = bank106_option( 'thingname' ) . $post->ID;
	$my_tutorial_tag = 'Tutorial' . $post->ID;

	// display option for examples &/or tutorials or none
	$my_show_ex = ( empty( bank106_option( 'show_ex' ) ) ) ? 'both' : bank106_option( 'show_ex' ) ; 
					
	// let's roll! 
	get_header();
?>

<div id="content" class="clearfix row">

	<div id="main" class="col-sm-12 clearfix" role="main">	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			
			<article id="post-<?php the_ID(); ?>"  role="article">
			
				<div class="clearfix row">
					<div class="page-header">
					<header>
						<div class="col-sm-3">
						
							<div class="thing-icon-single">
							<!-- insert/embed assignment icon -->
							<?php echo get_thing_icon ( $my_id ,'thumbnail')?>
							</div>
						</div>

						<div class="col-sm-8" >
							
							<h1 class="single-title assignment-header" itemprop="headline">
							<?php the_title();?>
							
							
							</h1>

							<?php 
							// author name either from WP user or from meta data (uses old FWP meta)
							$assignmentAuthor = bank106_get_display_name( $post->ID, 'fwp_name' );
							?>
							
							<p class="meta">This <?php echo bank106_option( 'thingname' )?> was 
							<?php _e("created", "wpbootstrap"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $assignmentAuthor?></strong> <?php echo bank106_user_credit_link( $post->ID, '(', ')' )?><br />
							</p>

							<?php 							
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
							?>

							<p>
							<?php // show creator difficulty rating if enabled

							if (bank106_option('difficulty_rating') and  get_post_meta($post->ID, 'assignment_difficulty', true) ) {
								echo 'Difficulty: <strong>' .  get_post_meta($post->ID, 'assignment_difficulty', true)  . '</strong> (rated by author; 1=easy &lt--&gt; 5=difficult)</br>';
							}
							?>
													
							Views: <strong><?php echo get_post_meta($post->ID, 'assignment_visits', true); ?></strong><br />
							<!-- Thing types -->
							<?php echo get_the_term_list( $post->ID, 'assignmenttypes', bank106_option( 'type_name' ) . ': ', ', ', '' ); ?> <br />

							<!-- Thing categories (if allowed) -->
							<?php  
							// only display thning categories if option is 1 (user defined) or 2 (admin defined)
							if ( bank106_option('use_thing_cats') ) {
							
								$thingcats = get_the_term_list( $post->ID, 'assignmentcats',  bank106_option( 'thing_cat_name' ) . ': ', ', ', '' ); 
								if ($thingcats) echo $thingcats . '<br />'; 
							}
							?> 
							
							<!-- Thing tags -->
							<?php $thingtags = the_tags('<span class="tags"><span class="tags-title">' . __("Tags", "wpbootstrap") . ': </span> ', ' ', '</span>'); if ($thingtags) echo $thingtags ?>
							</p>
							
						</div>
					</header> <!-- end article header -->	
					</div>
				</div>	<!-- end row -->
					
				<div class="clearfix row">	
						<div class="col-sm-8">
						
							<?php 
							
							the_content(); 
							
							$thingextras = get_post_meta($post->ID, 'assignment_extras', true);
							
							if ( $thingextras ) echo '<div class="col-sm-offset-1 col-sm-9"><div class="alert alert-info" role="alert">' .  make_links_clickable($thingextras) . "</div>\n</div>\n";
							?>
							
							
							<?php if (bank106_option('show_tweet_button')):?>
							<div class="col-sm-9">	
							<?php bank106_twitter_button ( $post->ID, bank106_option( 'thingname' ) );?>
							</div>
							<?php endif?>
							
						</div>
						
						<?php if ( get_post_meta($post->ID, 'fwp_url', true) ): // only if we have example ?> 
						<div class="col-sm-4" id="examplemedia">
							<?php echo get_example_media($my_id)?>
						</div>
						<?php endif?>
						
				</div> <!--row -->
					
				</article> <!-- end article -->
				
				<?php if ( $my_show_ex != 'none'): // show at least examples and/or tutorials ?>
					
					<?php
					
					if ( !is_user_logged_in() and bank106_option('use_wp_login') ) {
					
						$signin = '<div class="alert alert-warning" role="alert">';
							
						if ( bank106_option('use_wp_login') == 1 ) {
						
							$signin .= 'Signing into this site is optional, but doing so will allow your profile to include your responses. ';
					
						} else {
							$extra_btn_class = ' btn-disabled';
							$signin .= 'You must sign in to this site to add a response to this ' . bank106_option( 'thingname' ) . '. ';
						}
						
						$signin .= wp_loginout('', false);
						$signin .= '</div>';
						
					}			
					?>
								
					<div class="clearfix row hilite">
				
						<?php 
							if ( $my_show_ex == 'both') {
								// 2 columns
								 echo '<div class="clearfix col-sm-5">';
							} else {
								// just 1
								echo '<div class="clearfix col-sm-8 col-sm-offset-2">';
							}	
						?>
					
						<?php if ( $my_show_ex != 'tut' ) :?>
						
							<h3>Complete This <?php echo bank106_option( 'thingname' )?></h3>
							
							<p>After you complete this <?php echo lcfirst(bank106_option( 'thingname' ))?> please share a link to it and a description so it can be added to the responses below. 
												
							<?php if ( bank106_option('helpthingname') == 'internal' ): // syndicated responses on this site ?>
							If you are writing on a blog connected to this site include a tag <strong><?php echo $my_assignment_tag;?></strong> when writing a post on your own blog. A link to your response should be automatically added below within an hour. <br /><br />Or you 
						
							<?php elseif ( bank106_option('helpthingname') == 'external' ): // syndicated responses from another site ?>
							If you are writing to a site that is connected to  <a href="<?php echo bank106_option('syndication_site_url')?>"><?php echo bank106_option('syndication_site_name')?></a>  include the following tags when writing a post on your own blog. (You must use BOTH!):  <strong><?php echo bank106_option( 'extra_tag' ) . ', ' .  $my_assignment_tag;?></strong> Then a link to your response will be automatically added within an hour to the ones below. <br /><br />Or you 
						
							<?php else: // no syndication?>
							You 
							<?php endif?>
						
							can add it directly to this site<?php if ( bank106_option( 'new_example_status' ) == 'draft') echo ' (it will appear after moderator approval)'?>.</p><?php echo $signin?><p class="text-center"><a href="<?php echo site_url(); ?>/<?php echo bank106_option( 'example_form_page' ) ?>/?aid=<?php echo $my_id?>&typ=ex" class="btn btn-primary<?php echo $extra_btn_class?>"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Add A Response</a>
	
		
							</p>
						
						</div>
						<?php endif // my_show_ex != 'tut' ?>
						
						<?php
					
						if ( $my_show_ex == 'both' ) {
							// 2 columns, we need another div, otherwise we already have it
							 echo '<div class="col-sm-5  col-sm-offset-1">';
						}
						?>
					
						
						<?php if ( $my_show_ex != 'ex' ) : ?>	
					
							<h3><?php echo bank106_option('helpthingname')?>s for this <?php echo bank106_option( 'thingname' )?></h3>
								<p>Have you created a helpful guide or do you know one that might help others complete this <?php echo lcfirst(bank106_option( 'thingname' ))?>? 
											
								<?php if ( bank106_option('helpthingname') == 'internal' ):?>
								If you are writing on a blog connected to this site include the tag <strong><?php echo $my_tutorial_tag;?></strong> when writing a post on your own blog. A link to your <?php echo strtolower(bank106_option('helpthingname'))?> A should be automatically added below within an hour.  <br /><br />Or you  
					
								<?php elseif ( bank106_option('helpthingname') == 'external' ):?>
								If you are writing to a site that is connected to  <a href="<?php echo bank106_option('syndication_site_url')?>"><?php echo bank106_option('syndication_site_name')?></a> include the following yags when writing a post on your own blog. (You must use BOTH!):  <strong><?php echo bank106_option( 'extra_tag' ) . ', ' .  $my_tutorial_tag;?></strong> Then a link to your <?php echo strtolower(bank106_option('helpthingname'))?> will be automatically added within an hour to the ones below. <br /><br />Or you 
					
								<?php else:?>
								You 
								<?php endif?>
					
								can share a <?php echo strtolower(bank106_option('helpthingname'))?> if it is available at a public URL. <?php if ( bank106_option( 'new_example_status' ) == 'draft') echo ' (it will appear below after moderator approval)'?>.</p><?php echo $signin?><p class="text-center"><a href="<?php echo site_url(); ?>/<?php echo bank106_option( 'example_form_page' );?>/?aid=<?php echo $my_id?>&typ=tut" class="btn btn-primary<?php echo $extra_btn_class?>"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Add a <?php echo bank106_option('helpthingname')?></a> 
								
								
								
								</p>
							</div>							
						<?php endif // my_show_ex != 'ex' ?>
					</div> <!-- end row -->
				
					
					<div class="clearfix row">	
						
						<?php 
							if ( $my_show_ex == 'both') {
								// 2 columns
								 echo '<div class="clearfix col-sm-5">';
							} else {
								// just 1
								echo '<div class="clearfix col-sm-8 col-sm-offset-2">';
							}	
						?>
					
						<?php if ( $my_show_ex != 'tut' ) :?>
			
							<?php
							// find all examples done for this assignment
							$examples_done_query = new WP_Query( 
								array(
									'posts_per_page' =>'-1', 
									'post_type' => 'examples',
									'assignmenttags'=> $my_assignment_tag, 
								)
							);
						
							$example_count = $examples_done_query->post_count;
							$plural = ( $example_count == 1) ? '' : 's';
							
							// flag if the Ajax Load More plugin is loaded
							$use_ajax_load_more = ( function_exists('alm_install' ) ) ? true : false; ?>
		
							<h3><?php echo $example_count?> Response<?php echo $plural?> for this <?php echo bank106_option( 'thingname' )?></h3>
							
							<?php if ($use_ajax_load_more) : //use the Ajax Load More Plugin

								// how many example responses to show at a time
								$examples_per_view = bank106_option('examplesperview');

								// query to get first set of responses
								$responses_query = new WP_Query( 
									array(
										'posts_per_page' => $examples_per_view, 
										'post_type' => 'examples',
										'assignmenttags'=> $my_assignment_tag, 
									)
								);
								?>
								
								<ul>
								
								<?php while ( $responses_query->have_posts() ) : $responses_query->the_post();?>
									<?php 
									// author name either from WP user or from meta data (uses old FWP meta)
									$example_source = bank106_get_display_name( $post->ID, 'syndication_source' );
									$exampleCredit = ( get_post_meta( $post->ID, 'example_source', true ) ) ? ' (' . get_post_meta( $post->ID, 'example_source', true ) . ')' : '';
									?>

										<li><a href="<?php echo bank106_get_response_link( $post->ID )  ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" target="_blank"><?php the_title(); ?></a> <?php echo $exampleCredit . '<br /><span class="user_credit"> by <strong>'  . $example_source . '</strong> (' .  bank106_user_credit_link( $post->ID, '', '', 'exampletags' ); ?>)</span><br />
										<?php the_excerpt(); ?></li>
										
										
								<?php endwhile; ?>
								
								</ul>
							
									<?php if ( $example_count > $examples_per_view ) :?>

										<?php echo do_shortcode ('[ajax_load_more post_type="examples" taxonomy="assignmenttags" taxonomy_terms="' . $my_assignment_tag . '"  offset="' . $examples_per_view . '" posts_per_page="' . $examples_per_view . '" pause="true" scroll="false" transition="fade" button_label="More Responses" button_loading_label="Loading Responses"]');?>
										
									<?php endif ?>
								
								<?php else: // just list all resposnes (no ajax)?>
									<?php 
										while ( $examples_done_query->have_posts() ) : $examples_done_query->the_post();
											// author name either from WP user or from meta data (uses old FWP meta)
											$example_source = bank106_get_display_name( $post->ID, 'syndication_source' );
											$exampleCredit = ( get_post_meta( $post->ID, 'example_source', true ) ) ? ' (' . get_post_meta( $post->ID, 'example_source', true ) . ')' : '';
											?>
											
											<li><a href="<?php echo bank106_get_response_link( $post->ID );?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" target="_blank"><?php the_title(); ?></a> <?php echo $exampleCredit . '<br /><span class="user_credit"> by <strong>'  . $example_source . '</strong> (' .  bank106_user_credit_link( $post->ID, '', '', 'exampletags' ); ?>)</span><br />
											<?php the_excerpt(); ?></li>
						
										<?php endwhile; ?>

								<?php endif?>						
							
						
						</div>	
						<?php endif // my_show_ex != 'tut' ?>
						
						<?php
					
						if ( $my_show_ex == 'both') {
							// 2 columns
							 echo '<div  class="col-sm-5  col-sm-offset-1">';
						} 
						?>
					
						
						<?php if ( $my_show_ex != 'ex' ) : ?>	
								
						<?php 
						// now get all tutorials done for this assignment, list alphabetically
	
							$tutorials_done_query = new WP_Query( 
								array(
									'posts_per_page' =>'-1', 
									'post_type' => 'examples',
									'tutorialtags'=> $my_tutorial_tag, 
									'orderby' => 'title',
									'order' => 'ASC'	
								)
							);
							$tutorial_count = $tutorials_done_query->post_count;
							$plural = ( $tutorial_count == 1) ? '' : 's';
						?>
					
							<h3><?php echo $tutorial_count . ' ' . bank106_option('helpthingname') . $plural?> for this <?php echo bank106_option( 'thingname' )?></h3>	
							<ul>
							
							<?php 
							
							while ( $tutorials_done_query->have_posts() ) : $tutorials_done_query->the_post();
							
								// author name either from WP user or from meta data (uses old FWP meta)
								$example_source = bank106_get_display_name( $post->ID, 'syndication_source' );
								$exampleCredit = ( get_post_meta( $post->ID, 'example_source', true ) ) ? ' (' . get_post_meta( $post->ID, 'example_source', true ) . ')' : '';
								?>
								
								<li><a href="<?php echo bank106_get_response_link( $post->ID); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" target="_blank"><?php the_title(); ?></a>  <?php echo $exampleCredit . '<br /><span class="user_credit">shared by <strong>'  . $example_source . '</strong> (' .  bank106_user_credit_link( $post->ID, '', '', 'exampletags' ); ?>)</span><br />
								<?php the_excerpt(); ?></li>
						
							<?php endwhile; ?>
						
							</ul>
						</div>							
						<?php endif // my_show_ex != 'ex' ?>
					</div> <!-- end row -->							
						
					<?php wp_reset_query(); ?>
					
						
				<?php endif // my_show_ex != 'none' ?>	
								
					
				<div class="col-sm-12 hilite clearfix">
					<p class="meta" style="text-align:center; padding:1em;">
					<?php 
						// display creative commons?
						if ( bank106_option( 'use_cc' ) != 'none' ) {
							// get the license code, either define for site or post meta for user assigned						
							$cc_code = ( bank106_option( 'use_cc' ) == 'site') ? bank106_option( 'cc_site' ) : get_post_meta($post->ID, 'cc', true);
							echo cc_license_html($cc_code, $assignmentAuthor, get_the_time( "Y", $my_id ));
						}
					?>						
					</p>
				</div>					
					
				<div id="content" class="row">

					<div class="col-sm-8 ">
					<!-- begin comments -->	
					<?php comments_template('',true); ?>
					<!-- end comments -->	
					</div>
					
				<?php endwhile; ?>	
					
					<?php
					// let's update meta data for this assignment (count of exmaples done, bump visit count)
					update_assignment_meta($post->ID, $example_count, $tutorial_count);		
					?>
						
					
				<?php else : ?>
				
				<div class="col-sm-8">
				<article id="post-not-found">
					<header>
						<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					</header>
					<section class="post_content">
						<p><?php _e("Sorry, but an entry was not found on this site.", "wpbootstrap"); ?></p>
					</section>
					<footer>
					</footer>
				</article>
			</div>
			<?php endif; ?>			
			
	</div> <!-- end #main -->
</div> <!-- end #content -->
<?php get_footer(); ?>