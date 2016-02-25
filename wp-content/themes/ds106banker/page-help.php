<?php

// type of help content to fetch (in query string)
$typ = $wp_query->query_vars['typ']; 


switch( $typ ) {
	
	case 'thing':
		// it's an assignment/thing
		$help_title = 'How to Submit ' . get_the_article(THINGNAME) . THINGNAME;
		break;
	
	case 'tut':
		// it's for submitting a tutorial/resource
		$help_title = 'How to Submit ' . get_the_article( ds106bank_option('helpthingname') ) . ds106bank_option('helpthingname') . ' for '   . get_the_article(THINGNAME) . THINGNAME;
		break;
		
	default:
		// default assume it's a response
		$help_title = 'How to Submit a Response to ' . get_the_article(THINGNAME) . THINGNAME;
		$typ = 'response';
}
	

	
?>

<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-8 clearfix" role="main">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						<header>
							
							<div class="page-header"><h1><?php echo $help_title ?></h1></div>
						
						</header> <!-- end article header -->
					
						<section class="post_content">
							<?php the_content(); ?>
							
							<?php include( get_stylesheet_directory() . '/includes/help-' . $typ . '.php'); ?>
					
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

<?php get_footer(); ?>