<?php
	// unique assignment/tutorial tags
	$my_assignment_tag = ds106bank_option( 'thingname' ) . $post->ID;
	$my_tutorial_tag = 'Tutorial' . $post->ID;
	
	// display option for exmaples & tutorials
	$my_show_ex = ( empty( ds106bank_option( 'show_ex' ) ) ) ? 'both' : ds106bank_option( 'show_ex' ) ; 
	
	// allow form additions of examples, tutorials
	$my_use_example_form = ds106bank_option( 'example_via_form' ); 
	
	
	// use wp login options
	$my_use_wp_login = 	ds106bank_option('use_wp_login');
	
	// name for the support materials
	$helpthing = ds106bank_option('helpthingname'); 
	
	//options for example/tutorial syndication
	$my_fwp_mode = ds106bank_option( 'use_fwp'); // Syndication mode = none, intenal, external
	$my_hub_site = ds106bank_option('syndication_site_name'); // external syndication site
	$my_hub_url =  ds106bank_option('syndication_site_url'); // external syndication url
	$my_syndication_tag = ds106bank_option( 'extra_tag' ); // external syndication required tag	
	
	$my_cc_mode = ds106bank_option( 'use_cc' ); // creative commons usage mode

	// store assignment link for later use
	$my_permalink = get_permalink();
	$my_id = $post->ID;



	
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
							
							<h1 class="single-title assignment-header" itemprop="headline"><?php the_title();?></h1>

							<?php 
							
							// look for author name in Feedwordpress meta data
							$assignmentAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							// no author assigned
							if ( !$assignmentAuthor) $assignmentAuthor = 'Anonymous';
							?>
							
							<p class="meta">This <?php echo ds106bank_option( 'thingname' )?> was 
							<?php _e("created", "wpbootstrap"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> by <strong><?php echo $assignmentAuthor?></strong> <?php echo bank106_twitter_credit_link( $post->ID, '(', ')' )?><br />
							</p>

							<?php 							
							// insert ratings if enabled
							if ( function_exists( 'the_ratings' ) ) { the_ratings(); }
							?>

							<p>
							
							
							<?php // show creator difficulty rating if enabled

							if (ds106bank_option('difficulty_rating') and  get_post_meta($post->ID, 'assignment_difficulty', $single = true) ) {
								echo 'Difficulty: <strong>' .  get_post_meta($post->ID, 'assignment_difficulty', $single = true)  . '</strong> (rated by author; 1=easy &lt--&gt; 5=difficult)</br>';
							}
							?>
													
							Views: <strong><?php echo get_post_meta($post->ID, 'assignment_visits', $single = true); ?></strong><br />
							<!-- Thing types -->
							<?php echo get_the_term_list( $post->ID, 'assignmenttypes', ds106bank_option( 'type_name' ) . ': ', ', ', '' ); ?> <br />

							<!-- Thing categories (if allowed) -->
							<?php  
							// only display thning categories if option is 1 (user defined) or 2 (admin defined)
							if ( ds106bank_option('use_thing_cats') ) {
							
								$thingcats = get_the_term_list( $post->ID, 'assignmentcats',  ds106bank_option( 'thing_cat_name' ) . ': ', ', ', '' ); 
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
							
							$thingextras = get_post_meta($post->ID, 'assignment_extras', $single = true);
							
							if ( $thingextras ) echo '<div class="col-sm-offset-1 col-sm-9"><div class="alert alert-info" role="alert">' .  make_links_clickable($thingextras) . "</div>\n</div>\n";
							
							
							?>
							
							<div class="col-sm-9">	
							<?php bank106_twitter_button ( $post->ID, ds106bank_option( 'thingname' ) );?>
							</div>
							
							
							
						</div>
						
						<?php if ( get_post_meta($post->ID, 'fwp_url', $single = true) ): // only if we have example ?> 
						<div class="col-sm-4" id="examplemedia">
							<?php echo get_example_media($my_id)?>
						</div>
						<?php endif?>
						
				</div>
					
				</article> <!-- end article -->
				
				<?php if ( $my_show_ex != 'none'): // show at least examples and/or tutorials ?>
				
							
					<?php
					
					if ( $my_use_example_form and !is_user_logged_in() and $my_use_wp_login ) {
					
						$signin = '<div class="alert alert-warning" role="alert">';
							
						if ( $my_use_wp_login == 1 ) {
						
							$extra_btn_class = '';
							$signin .= 'Signing into this site is optional, but doing so will allow you to maintain a profile of your responses. ';
					
						} else {
							$extra_btn_class = ' btn-disabled';
							$signin .= 'You must sign in to this site to add a response to this ' . ds106bank_option( 'thingname' ) . '. ';
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
						
							<h3>Complete This <?php echo ds106bank_option( 'thingname' )?></h3>
							<p>After you do this <?php echo lcfirst(ds106bank_option( 'thingname' ))?>, please share it so it can appear with other responses below. 
												
							<?php if ( $my_fwp_mode == 'internal' ):?>
							If you are writing to a blog connected to this site just use a tag or category <strong><?php echo $my_assignment_tag;?></strong> when writing a post on your own blog. Then your response will be added to the list below. <br /><br />Or if 
						
							<?php elseif ( $my_fwp_mode == 'external' ):?>
							If you are writing to a blog that feeds  <a href="<?php echo $my_hub_url?>"><?php echo $my_hub_site?></a>  just use the following tags/categories when writing a post on your own blog. (You must use BOTH!):  <strong><?php echo $my_syndication_tag . ', ' .  $my_assignment_tag;?></strong> Then your response will be added to the list below. <br /><br />Or if 
						
							<?php else:?>
							If 
							<?php endif?>
						
							<?php if ( $my_use_example_form):?>
							
							your response exists at a public viewable URL, you can add the information directly to this site<?php if ( ds106bank_option( 'new_example_status' ) == 'draft') echo ' (it will appear pending moderator approval)'?>.</p><?php echo $signin?><p class="text-center"><a href="<?php echo site_url(); ?>/?page_id=<?php echo bank106_get_page_id_by_slug( ds106bank_option( 'example_form_page' ) )?>&aid=<?php echo $my_id?>&typ=ex" class="btn btn-primary<?php echo $extra_btn_class?>"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Add A Response</a>
							<?php endif?>
	
		
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
					
							<h3><?php echo $helpthing?>s for this <?php echo ds106bank_option( 'thingname' )?></h3>
								<p>Have you created something or know of an external resource that might help others complete this <?php echo lcfirst(ds106bank_option( 'thingname' ))?>? 
											
								<?php if ( $my_fwp_mode == 'internal' ):?>
								If you are writing to a blog connected to this site just use a tag or category <strong><?php echo $my_tutorial_tag;?></strong> when writing a post on your own blog. Then your <?php echo strtolower($helpthing)?> will be added to the list below. <br /><br />Or if 
					
								<?php elseif ( $my_fwp_mode == 'external' ):?>
								If you are writing to a blog that feeds  <a href="<?php echo $my_hub_url?>"><?php echo $my_hub_site?></a>  just use the following tags/categories when writing a post on your own blog. (You must use BOTH!):  <strong><?php echo $my_syndication_tag . ', ' .  $my_tutorial_tag;?></strong> Then your <?php echo strtolower($helpthing)?> will be added to the list below. <br /><br />Or if 
					
								<?php else:?>
								If 
								<?php endif?>
					
								<?php if ( $my_use_example_form):?>
								the <?php echo strtolower($helpthing)?> is available at a public URL please share it<?php if ( ds106bank_option( 'new_example_status' ) == 'draft') echo ' (it will appear below pending moderator approval)'?>.</p><?php echo $signin?><p class="text-center"><a href="<?php echo site_url(); ?>/?page_id=<?php echo bank106_get_page_id_by_slug( ds106bank_option( 'example_form_page' ) )?>&aid=<?php echo $my_id?>&typ=tut" class="btn btn-primary<?php echo $extra_btn_class?>"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> Add a <?php echo $helpthing?></a> 
								
								<?php endif?>
								
								
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
							$use_ajax_load_more = ( function_exists('alm_install' ) ) ? true : false;

							?>
		
							<h3><?php echo $example_count?> Response<?php echo $plural?> Completed for this <?php echo ds106bank_option( 'thingname' )?></h3>
							
							
							
							<?php if ($use_ajax_load_more) : //use the Ajax Load More Plugin

								// how many example responses to show at a time
								$examples_per_view = ds106bank_option('examplesperview');

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
										// get link
										if ( get_post_meta($post->ID, 'syndication_permalink' ) ) {
										  $the_real_permalink = get_post_meta( $post->ID, 'syndication_permalink', true );
										} else {
										  $the_real_permalink = get_permalink( $post->ID );
										} 
						
										?>
										<li><a href="<?php echo $the_real_permalink ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a> (<?php echo get_post_meta( $post->ID, 'syndication_source', true ) . bank106_twitter_credit_link( $post->ID, ', ', '', 'exampletags' ) ?>)<br />
										<?php the_excerpt(); ?></li>
										
										
								<?php endwhile; ?>
								
								</ul>
							
									<?php if ( $example_count > $examples_per_view ) :?>

										<?php echo do_shortcode ('[ajax_load_more post_type="examples" taxonomy="assignmenttags" taxonomy_terms="' . $my_assignment_tag . '"  offset="' . $examples_per_view . '" posts_per_page="' . $examples_per_view . '" pause="true" scroll="false" transition="fade" button_label="More Responses" button_loading_label="Loading Responses"]');?>


											
										
									<?php endif ?>
								
								<?php else: // just list all resposnes (no ajax)?>
									<?php 
										while ( $examples_done_query->have_posts() ) : $examples_done_query->the_post();
									
											// get link
											if ( get_post_meta($post->ID, 'syndication_permalink' ) ) {
											  $the_real_permalink = get_post_meta( $post->ID, 'syndication_permalink', true );
											} else {
											  $the_real_permalink = get_permalink( $post->ID );
											} 
							
											?>
											<li><a href="<?php echo $the_real_permalink ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a> (<?php echo get_post_meta( $post->ID, 'syndication_source', true ) . bank106_twitter_credit_link( $post->ID, ', ', '', 'exampletags' ) ?>)<br />
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
						// now get all tutorials done for this assignment
	
							$tutorials_done_query = new WP_Query( 
								array(
									'posts_per_page' =>'-1', 
									'post_type' => 'examples',
									'tutorialtags'=> $my_tutorial_tag, 	
								)
							);
							$tutorial_count = $tutorials_done_query->post_count;
							$plural = ( $tutorial_count == 1) ? '' : 's';
						?>
					
							<h3><?php echo $tutorial_count . ' ' . $helpthing . $plural?> for this <?php echo ds106bank_option( 'thingname' )?></h3>	
							<ul>
							
							<?php 
							while ( $tutorials_done_query->have_posts() ) : $tutorials_done_query->the_post();
									
								// get link
								if (get_post_meta($post->ID, 'syndication_permalink')) {
								  $the_real_permalink = get_post_meta($post->ID, 'syndication_permalink', true);
								} else {
								   $the_real_permalink = get_permalink( $post->ID );
								} ?>
								<li><a href="<?php echo $the_real_permalink ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpbootstrap' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a> (<?php echo get_post_meta( $post->ID, 'syndication_source', true ) . bank106_twitter_credit_link( $post->ID, ', ', '', 'exampletags' ) ?>)<br />
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
						if ( $my_cc_mode != 'none' ) {
							// get the license code, either define for site or post meta for user assigned						
							$cc_code = ( $my_cc_mode == 'site') ? ds106bank_option( 'cc_site' ) : get_post_meta($post->ID, 'cc', true);
							echo cc_license_html($cc_code, $assignmentAuthor, get_the_time( "Y", $my_id ));
						}
					?>						
					</p>
				</div>
					

					
					
				<div id="content" class="row">
					<div class="col-sm-8 ">
				<!-- comments -->	
					<?php comments_template('',true); ?>
				
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
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
				</div>
				<?php endif; ?>			
			
	</div> <!-- end #main -->
</div> <!-- end #content -->
<?php get_footer(); ?>