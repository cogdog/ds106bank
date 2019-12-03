<?php get_header(); ?>
			
<div id="content" class="clearfix row">
	<div id="main" class="col-sm-12 clearfix" role="main">
	
				
		<div class="page-header">
			<h1 class="archive_title h2">
			<span><?php _e( get_bloginfo() . " &bull; ", "wpbootstrap"); ?></span> 
			<?php 
				$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
				$author_display_name = get_the_author_meta('display_name', $curauth->ID);
				echo $author_display_name;
			?>

			</h1>
		</div>	<!-- page-header -->		

		<?php if ( bank106_option( 'show_ex' ) == 'both' OR bank106_option( 'show_ex' ) == 'ex' ): // show response types?>

		<div class="row exlist">
			<div class="col-sm-10">	
				<?php 
					
					// count of items found
					$found_things = $wp_query->found_posts;				

					$title_str =  $found_things . ' ' . bank106_option( 'thingname' ) . ' Response';
					//because grammar
					$title_str .= ( $found_things == 1 ) ? '' : 's';
									
					// array to hold results
					$author_results = [];
					?>
							
					<h2><?php echo $title_str?></h2>		
								
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
						<?php 
						/* to group the responses by Type, we need to load all results
						   in an array, then sort and display 
						*/
								
						// get the ID for the assignment this belongs to
						$aid = get_assignment_id_from_terms( get_the_ID() );
						
						// get the assignment types for this thing
						$terms = get_the_terms( $aid, 'assignmenttypes' );
						
						// make a link string for the thing/assignment this is response to
						$assignment_str = ($aid) ? '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>' : '';
					
						// get link for item
						$the_real_permalink = bank106_get_response_link( $post->ID );
						$more_link = '<a href="' .  $the_real_permalink . '" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-link"></span> ' . $the_real_permalink . '</a>';
						
						$byline = bank106_user_credit_link( $aid, '', '', 'exampletags' );
						
						// build the output
						$example_str = '';
						
						$example_str .= '<article class="clearfix" role="article"><header><h4><a href="' . $the_real_permalink . '">' . get_the_title() . '</a>' ; 
						
						$example_str .= '</h4><p class="meta"><time datetime="' . get_the_time('Y-m-j') . '" pubdate>' . get_the_date() . '</time> &bull; Response for ' . $assignment_str . ' ' . bank106_option( 'thingname' ) . '</p></header><section class="post_content">' . get_the_excerpt() . ' <p class="more-link">' . $more_link .  '</p></section></article>';
						
						// now iterate over the assignment terms
						
						if ($terms) {
							foreach ($terms as $term) {
								if (array_key_exists( $term->slug, $author_results ) ) {
									// see if we have used this term, if so append to results
									$author_results[$term->slug]["results"][] = $example_str;
								} else {
									$author_results[$term->slug] = array( 
										'name' => $term->name,
										'results' => array($example_str)
									);
								}
							}
						}
						?>
					
					<?php endwhile; ?>	
					
					
					<?php 
						
						foreach ($author_results as $key => $value) {
						
							echo '<h3>' . $value['name'] . ' '  . ucfirst( bank106_option('type_name') ) . '</h3><ul>' . implode("\n", $value['results']) . '</ul>';
						
						}
					?>
													
					<?php else : ?>
					    <p><?php _e('No responses to' . bank106_option( 'pluralthings' ) . ' have been made by ' . $author_display_name . '.', "wpbootstrap"); ?></p>
					<?php endif; ?>
					
				</div><!--col -->
			</div><!-- row -->

		<?php if ( bank106_option( 'show_ex' ) == 'both' OR bank106_option( 'show_ex' ) == 'tut' ): // show tutorial types?>
		
		<div class="row tutlist">
			<div class="col-sm-10">	
				<?php 

					// Fix the examples to add new post meta
					$example_query = new WP_Query( 
						array(
							'posts_per_page' =>'-1', 
							'post_type' => 'examples',
							'meta_key' => 'example_type', 
        					'meta_value' =>  'tut'
						)
					);
					
					if ( $example_query->have_posts() ) {
						// count of items found
						$found_things = $example_query->found_posts;				

						$title_str = $found_things . ' ' . bank106_option( 'thingname' ) . ' ' . bank106_option('helpthingname');
						//because grammar
						$title_str .= ( $found_things == 1 ) ? '' : 's';
									
						// array to hold results
						$author_results = [];
						
						echo '<h2>' . $title_str . '</h2>';		

						while ($example_query->have_posts()) : $example_query->the_post();

							/* to group the responses by Type, we need to load all results
							   in an array, then sort and display 
							*/
								
							// get the ID for the assignment this belongs to
							$aid = get_assignment_id_from_terms( get_the_ID() );
						
							// get the assignment types for this thing
							$terms = get_the_terms( $aid, 'assignmenttypes' );
						
							// make a link string for the thing/assignment this is response to
							$assignment_str = ($aid) ? '<a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>' : '';
					
							// get link for item
							$the_real_permalink = bank106_get_response_link( $post->ID );
							$more_link = '<a href="' .  $the_real_permalink . '" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-link"></span> ' . $the_real_permalink . '</a>';
						
							$byline = bank106_user_credit_link( $aid, '', '', 'exampletags' );
						
							// build the output
							$example_str = '';
						
							$example_str .= '<article class="clearfix" role="article"><header><h4><a href="' . $the_real_permalink . '">' . get_the_title() . '</a>' ; 
						
							$example_str .= '</h4><p class="meta"><time datetime="' . get_the_time('Y-m-j') . '" pubdate>' . get_the_date() . '</time> &bull; ' . bank106_option( 'helpthingname' ) . ' for ' . $assignment_str . ' ' . bank106_option( 'thingname' ) . '</p></header><section class="post_content">' . get_the_excerpt() . ' <p class="more-link">' . $more_link .  '</p></section></article>';
						
							// now iterate over the assignment terms
						
							if ($terms) {
								foreach ($terms as $term) {
									if (array_key_exists( $term->slug, $author_results ) ) {
										// see if we have used this term, if so append to results
										$author_results[$term->slug]["results"][] = $example_str;
									} else {
										$author_results[$term->slug] = array( 
											'name' => $term->name,
											'results' => array($example_str)
										);
									}
								}
							}						
						
						endwhile;	

					foreach ($author_results as $key => $value) {	
						echo '<h3>' . $value['name'] . ' '  . ucfirst( bank106_option('type_name') ) . '</h3><ul>' . implode("\n", $value['results']) . '</ul>';
					}
						
				} else {
					echo '<p>No ' .  bank106_option( 'thingname' ) . 's to' . bank106_option( 'pluralthings' ) . ' have been made by ' . $author_display_name . '.</p>';			
				
				}
				?>	
				
			</div><!--col -->
		</div><!-- row -->
			
		<?php endif?>				
			
			
			<?php
				// find all things created by this user
				$things_query = new WP_Query( 
					array(
						'posts_per_page' =>'-1', 
						'post_type' => 'assignments',
						'author' => $curauth->ID
					)
				);
				
				
			?>	
				
			<?php if ($things_query->have_posts()) :?>
			
			<div class="row">
				<div class="col-sm-10">
			
					<?php 
					// count of items found
					$found_things = $things_query->found_posts;				
					// Because grammar
					$plural = ( $found_things == 1 ) ? bank106_option( 'thingname' ) : bank106_option( 'pluralthings' );
					
					$use_public_ratings = function_exists('the_ratings');
					?>
					
					<h2><?php echo $found_things . ' ' . $plural?> Created</h2>

				</div><!--col -->
			</div><!-- row -->
			
			<?php endif?>
					
			<div id="assignmentmenu" class="clearfix row"> <!-- thing menu -->
				<?php
					  
					$odd = false; // flag to start a new row
					
					while ($things_query->have_posts()) : $things_query->the_post();
						
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
							if ( $use_public_ratings ) { the_ratings(); }
							
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
					
						</section> <!-- end article section -->
						
						<!-- end thing content -->
					
					</article> <!-- end article -->
					
					</div> <!-- end assignment listing -->
					<?php if ( !$odd ) echo '</div> <!-- end row -->';?>
					
					<?php endwhile; ?>	
					
					</div> <!-- end assignment menu -->
				<?php endif; ?>		
				
			
	</div> <!-- end #main -->


</div> <!-- end #content -->

<?php get_footer(); ?>