<?php
/*
Template Name: Assignment Menu
*/
?>

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
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->    
			</div> <!-- end #content -->
				<?php
								
				// get the terms for the assugnment types, eventually with options
				// fed by theme
				$assignmenttypes = get_assignment_types();

 				if ( count($assignmenttypes) == 0 ) {
 				
 					// warning warning if no types yet created
 					echo '<div class="clearfix row"><div class="col-sm-2 col-md-offset-4 clearfix"><p><strong>Woah Neo</strong>; No ' . THINGNAME . 's have been set up. You can do that if you explore the Assignment Bank Options under tge <em>Types</em> tab.</p></div></div>';
 					
 				} else {	
 					
 					$startrow = false; // flag to start a new row
 					
 					foreach ($assignmenttypes as $atype) {
 												
 							$startrow = !$startrow;
							
							// start a new row?
							if ($startrow)  {
								echo '<div class="clearfix row">'; 
							}
						?>
						
 						<?php
 						// get the taxonomy for this type
 						$items = get_term_by('id', $atype->term_id, 'assignmenttypes');
 						
 						// buld string for icon
 						$type_url_str = '<a href="' . get_site_url() . '/type/' . $atype->slug . '" title="All ' . $atype->name . 's">';
 						// build link string for text
 						$type_url_btn = '<a href="' . get_site_url() . '/type/' . $atype->slug . '" title="All ' . $atype->name . 's" class="btn btn-primary">';
 						?>
 						<div class="col-sm-4 col-md-offset-1 frontmenu">
 						
							<article role="article" class="thing-archive">
								<!--  thing header -->
								<header>							
									<h3 class="h2"><?php echo $type_url_str . $atype->name ?></a></h3>
								</header> 
								<!-- end thing header -->

								<!-- thing icon or embedded media -->
								<div class="thing-icon">
								<?php echo $type_url_str . '<img src="' . ds106bank_option( $atype->slug . '_type_thumb' ) . '" alt="' . $atype->name . ' assignments" /></a>'; ?>
								</div>
								<!-- end icon or media -->
					
								<!-- thing content -->
								<section class="post_content">
						
									<p><?php echo $atype->description ?></p>
									<p class="more-link"><?php echo $type_url_btn?>View <?php echo THINGNAME?>s</a></p>
	
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