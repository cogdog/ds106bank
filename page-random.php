<?php
/*
Random Thing Picker
Use on page to send viewer to random thing
*/

// set arguments for WP_Query on published thing types to get 1 at random
$args = array(
    'post_type' => 'assignments',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'orderby' => 'rand'
);

// It's time! Go someplace random
$my_random_post = new WP_Query ( $args );

while ( $my_random_post->have_posts () ) {
  $my_random_post->the_post ();
  
  // redirect to the random post
  wp_redirect ( get_permalink () );
  exit;
}
?>