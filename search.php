<?php get_header(); ?>

			<div id="content" class="clearfix row">

				<div id="main" class="col col-lg-8 clearfix" role="main">

				<?php
					// if search was on post_type modify the output string
					$thing_query = ( get_query_var('post_type') == 'assignments' ) ? bank106_option( 'pluralthings' ) : '';
				?>

					<div class="page-header"><h1><span><?php _e("Search $thing_query for","wpbootstrap"); ?></span> "<?php echo esc_attr(get_search_query()); ?>"</h1></div>

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

						<header>

							<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>


							<?php
								if ( get_post_type( get_the_ID() ) == 'examples' ) {
									// get the ID for the assignment this belongs to
									$aid = get_assignment_id_from_terms( get_the_ID() );
									// make a link string
									$type_str = ($aid) ? 'for ' .  bank106_option( 'thingname' ) .  ' <a href="' . get_permalink($aid) . '">' . get_the_title($aid) . '</a>' : '';
								} elseif ( get_post_type( get_the_ID() ) == 'assignments' ) {
									$type_str = ' as a ' . bank106_option( 'thingname' );
								}
							 ?>

							<p class="meta"><?php _e("Created", "wpbootstrap"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_date(); ?></time> <?php _e("by", "wpbootstrap"); ?> <?php the_author_posts_link(); ?>  <?php echo $type_str?></p>

						</header> <!-- end article header -->

						<section class="post_content">
							<?php the_excerpt(); ?>

							<p class="more-link"><a href="<?php the_permalink(); ?>" class="btn btn-primary">View <?php echo bank106_option( 'thingname' )?></a>
							<?php edit_post_link( __( 'Edit', 'wpbootstrap' ), '<br /><span class="edit-link">', '</span>' ); ?></p>


						</section> <!-- end article section -->

						<footer>


						</footer> <!-- end article footer -->

					</article> <!-- end article -->

					<?php endwhile; ?>

					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>

						<?php page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="clearfix">
								<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "wpbootstrap")) ?></li>
								<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "wpbootstrap")) ?></li>
							</ul>
						</nav>
					<?php } ?>

					<?php else : ?>

					<!-- this area shows up if there are no results -->

					<article id="post-not-found">
					    <header>
					    	<h2><?php _e("Uh Oh, Came Up Empty", "wpbootstrap"); ?></h2>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but we could not find anything for you.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>

					<?php endif; ?>

				</div> <!-- end #main -->

    			<?php get_sidebar(); // sidebar 1 ?>

			</div> <!-- end #content -->

<?php get_footer(); ?>
