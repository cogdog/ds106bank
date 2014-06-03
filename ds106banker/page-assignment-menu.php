<?php
/*
Template Name: Assignment Menu

This formats the main menu for the types of things, linking each to the page that lists all
assignments within.  A Wordpress Page should be created and set to use this template. the
title of the page and any content are displayed above the menu.
*/
?>

<?php get_header(); ?>
<div class="container">	
		
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-md-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
						<header>
							
							<div class="page-header"><h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content clearfix" itemprop="articleBody">
							<?php the_content(); ?>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
				
					
					<?php endwhile; ?>		
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, we could not find anything to display", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->    
			</div> <!-- end #content -->
				<?php
				
				// Generate the menu of "things"
								
				// get all the terms for the custom post type for things, in sort order specified in settings
				$assignmenttypes = get_assignment_types( ds106bank_option( 'thing_order'), ds106bank_option( 'thing_orderby') );

 				if ( count($assignmenttypes) == 0 ) {
 				
 					// warning warning if no things have yet created
 					echo '<div class="clearfix row"><div class="col-md-2 col-md-offset-4 clearfix"><p><strong>Woah Neo</strong>; No ' . THINGNAME . 's have been set up. You can do that if you explore the Assignment Bank Options under the <em>Types</em> tab.</p></div></div>';
 					
 				} else {	
 					
 					$startrow = false; // status for beginning of row
 					
 					foreach ($assignmenttypes as $atype) {
 								
 							// toggle row flag signal				
 							$startrow = !$startrow;
							
							// start a new row? fix any wraps
							if ($startrow)  {
								echo '<div class="clearfix row">'; 
								echo '<div class="col-md-5">';
							} else {
								echo '<div class="col-md-5 col-md-offset-1">';
							}
								
						?>
						
 						<?php
 						// get the term for this type of taxonomy
 						$items = get_term_by('id', $atype->term_id, 'assignmenttypes');
 						
 						// Add "s" if the count is 0 or more than 1
 						$plural = ( $atype->count == 1 ) ? '' : 's';
 						
 						// string for start of link around icon
 						$type_url_str = '<a href="' . get_site_url() . '?type=' . $atype->slug . '" title="View All ' . $atype->name . ' ' . THINGNAME .  's">';
 						// string for start of link around 
 						$type_url_btn = '<a href="' . get_site_url() . '?type=' . $atype->slug . '" title="View All ' .  $atype->name . ' ' . THINGNAME .  's" class="btn btn-primary">';
 						
 						?>
 						
 						
							<article role="article" class="thing-archive">
								<!--  thing name header -->
								<header>							
									<h3 class="h2"><?php echo $type_url_str . $atype->name ?></a></h3>
								</header> 
								<!-- end thing header -->

								<!-- thing icon -->
								<div class="thing-icon">
								
								<?php echo $type_url_str . '<img src="' . ds106bank_option( 'thing_type_' . $atype->term_id . '_thumb') . '" alt="' . $atype->name . ' assignments" /></a>'; ?>
								</div>
								<!-- end icon or media -->
					
								<!-- thing content -->
								<section class="post_content">
						
									<p><?php echo $atype->description ?></p>
									<p class="more-link"><?php echo $type_url_btn?>View <?php echo $atype->count?> <?php echo THINGNAME . $plural?></a></p>
	
								</section> <!-- end article section -->
						
								<!-- end thing content -->					
							</article> <!-- end article -->
						</div>
 						
 						<?php if (!$startrow) echo '</div>'; // end of row?
 						
 					} // foreach
 					
 					
 					if ($startrow) echo '</div>'; // ended with in-complete tow?
 					
 				} // count($assignmenttypes)
 				?>
<?php get_footer(); ?>

</div>