<?php get_header(); ?>

<?php
	// get terms for type of assignment
	$assignmenttype_terms = wp_get_object_terms($post->ID, 'assignmenttypes');

	// we expext only 1 assignment type
	$my_assignment_type = $assignmenttype_terms[0];
	
	// unique assignment/tutorial tags
	$my_assignment_type_tag = $my_assignment_type->name . THINGNAME;
	$my_assignment_tag = THINGNAME . $post->ID;
	$my_tutorial_tag = 'Tutorial' . $post->ID;
	
	//options for example/tutorial syndication
	
	$my_fwp_mode = ds106bank_option( 'use_fwp'); // Syndication mode = none, intenal, external
	$my_use_example_form = ds106bank_option( 'example_via_form' ); // allow form additions of examples, tutorials
	
	$my_hub_site = ds106bank_option('syndication_site_name'); // external syndication site
	$my_hub_url =  ds106bank_option('syndication_site_url'); // external syndication url
	$my_syndication_tag = ds106bank_option( 'extra_tag' ); // external syndication required tag

	// store assignment link for later use
	$my_permalink = get_permalink();
	$my_id = $post->ID;
?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="assignmment-title" class="col-md-8 clearfix" role="main">				
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
						<div class="assignment-header">
							<h1 class="single-title" itemprop="headline"><?php the_title(); ?> <?php if(function_exists('the_ratings')) { the_ratings(); } ?></h1>
						</div>
						
						<?php 
							// look for author name in Feedwordpress meta data
							$assignmentAuthor = get_post_meta($post->ID, 'fwp_name', $single = true); 
							
							if ( !$assignmentAuthor) $assignmentAuthor = 'Anonymous';
							?>
							
						<p class="meta">
						<?php _e("Created", "bonestheme"); ?> <strong><time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time></strong> • a <a href="/type/<?php echo $my_assignment_type->slug?>"><?php echo $my_assignment_type->name?> <?php echo THINGNAME?></a> created by <strong><?php echo $assignmentAuthor?></strong>
						</p>
												
						</header> <!-- end article header -->
					</div> <!-- //atitle -->
					
					<div class="col-md-4 assignment-header alignright">						
							<!-- Selector Section -->
	  						<div class="assignment-selector">
	  						
							<?php 
							// get all of the assignments of this type and make a menu selector
							$all_assignment_query = new WP_query(
									array(
										'post_type' => 'assignments', 
										'tax_query' => array(
												array(
													'taxonomy' => 'assignmenttypes',
													'field' => 'slug',
													'terms' => $my_assignment_type->slug
												)
											),
										'posts_per_page' => '-1', 
										'orderby' => 'title', 
										'order' => 'ASC' 
										)
								); ?>
								
								
							<?php if ($all_assignment_query->post_count > 1) : // do we have more than one assignment? ?>
							<form name="assignment_selector" id="assignmentSelect">
							
								<select name="goto" id="assignmentList" onchange="window.location.href= this.form.goto.options[this.form.goto.selectedIndex].value">
									<option>Choose Another <?php echo $my_assignment_type->name?> <?php echo THINGNAME?></option>
									<option value="<?php echo get_term_link( $my_assignment_type )?>?srt=random">-- Pick One Randomly --</option>
									<?php while ($all_assignment_query->have_posts() ) : $all_assignment_query->the_post(); 
										if (  get_permalink() == $my_permalink) continue; // skip this assignment ?>
										
										<option value="<?php the_permalink(); ?>"><?php the_title()?></option>
									
									<?php endwhile; ?>
								</select>
							 </form>
							 <?php else: ?>
							 This is the only <?php echo $my_assignment_type->name?> <?php echo THINGNAME?>
							 
							 <?php endif?>
							 
							 <?php wp_reset_query(); ?>
							 
							</div> <!-- end Selector Section -->
							
						</div>

					</div> <!-- //content title row -->
					
					
					<div id="content2" class="clearfix row">
						<div  class="col-md-5">

						<?php get_assignment_icon ($post->ID, MEDIAW, 'medium')?>

						</div>
					
						<div class="col-md-5 col-md-offset-1" >
							<?php the_content(); ?>
					
					
							<footer>
								<?php 
								// only show edit button if user has permission to edit posts
								if( $user_level > 0 ) { 
								?>
								<a href="<?php echo get_edit_post_link(); ?>" class="btn btn-success edit-post"><i class="icon-pencil icon-white"></i> <?php _e("Edit post","bonestheme"); ?></a>
								<?php } ?>
						
							</footer> <!-- end article footer -->
						</div>	<!-- end content -->
					</div>
					
					</article> <!-- end article -->

					<div id="content3" class="clearfix row hilite">
						<div class="clearfix col-md-5">
						<h3>Do this <?php echo THINGNAME?></h3>
						<p>Once you complete this <?php echo lcfirst(THINGNAME)?>, share it! 
												
						<?php if ( $my_fwp_mode == 'internal' ):?>
						If you are writing to a blog connected to this site just use the tag <strong><?php echo $my_assignment_tag;?></strong> when writing a post on your own blog. Then your example will be added to the list below. <br /><br />Or if 
						
						<?php elseif ( $my_fwp_mode == 'external' ):?>
						If you are writing to a blog that feeds  <a href="<?php echo $my_hub_url?>"><?php echo $my_hub_site?></a>  just use the following tags when writing a post on your own blog. (You must use BOTH tags!):  <strong><?php echo $my_syndication_tag . ', ' .  $my_assignment_tag;?></strong> Then your example will be added to the list below. <br /><br />Or if 
						
						<?php else:?>
						If 
						<?php endif?>
						
						<?php if ( $my_use_example_form):?>
						your example exists elsewhere at a public URL <a href="<?php echo site_url(); ?>/<?php echo ds106bank_option( 'example_form_page' )?>/?aid=<?php echo $my_id?>&typ=ex">add your example directly</a><?php if ( ds106bank_option( 'new_example_status' ) == 'draft') echo ' (pending moderator approval)'?>.
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
						
								<?php if ( $my_use_example_form):?>
								your tutorial exists elsewhere at a public URL <a href="<?php echo site_url(); ?>/<?php echo ds106bank_option( 'example_form_page' )?>/?aid=<?php echo $my_id?>&typ=tut">add your tutorial directly</a><?php if ( ds106bank_option( 'new_example_status' ) == 'draft') echo ' (pending moderator approval)'?>.
								<?php endif?>
			
								</p>
						</div>
					</div>
					
					<div id="content4" class="clearfix row">	
						<div class="col-md-5">
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
						
							?>
		
							<h3><?php echo $example_count?> Example<?php echo $plural?> Completed for this <?php echo THINGNAME?></h3>
							<ol>
							<?php 
	
			
							while ( $examples_done_query->have_posts() ) : $examples_done_query->the_post();
									
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
					
					</div>
					
					<div id="content" class="row">
						<div class="col-md-8">
						<!-- comments -->	
						<?php comments_template('',true); ?>
					
						</div>
					
					<?php endwhile; ?>	
					
					<?php
					// let's update meta data for this assignment (count of exmaples done, bump visit count)
					update_assignment_meta($post->ID, $example_count, $tutorial_count);		
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