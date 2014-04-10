<p>For terminology, this site creates a collection of "things" which may be assignments, challenges, tasks, etc. You can create any number of types of things.</p>

<h4>Set Up Pages</h4>
<p>This theme provides three templates for pages that provide specific functionality for your site. You should at least create these all for a new site.</p>

<ol>
<li>Create a new page for a  <strong>Main Menu</strong> It generates the index of all types of things; they will be listed in alphabetical order. The title and content of the page is displayed above a grid of types of things. To enable the functionality, set the page template to <strong>Assignment Menu</strong><br /><br/>If you wish this page to be the front of the site (<a href="http://bank.ds106.us/">like the demo</a>), use the <a href="<?php echo admin_url( 'options-reading.php')?>">Reading Settings </a> to set the Front Page as a static page (if you plan to use the blog, create a blank page that you can use for a Posts page).<br /><img src="?php echo get_stylesheet_directory_uri(); ?>/images/reading-settings.jpg" alt="" style="border:3px solid #000;" /></li>

<li>Create a new page to <strong>Submit New Things</strong>; you will need this even if you do not allow visitors to add them so you can add them yourself (the page can be unlinked or have a password set on it). The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Assignments</strong>.</li>

<li>Create a new page to <strong>Submit Examples</strong>. This form is used to allow visitors to add examples for things via a web for. The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Example/Tutorial Form</strong>.</li>

</ol>


<h4>Assignment Bank Options: General Settings: Captcha Settings</h4>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/general-thing-settings.jpg" alt="" style="border:3px solid #000;" /><br />

<strong>Define the types of things</strong> in the bank- the name here should be singular. This is used in numerous places throughout the site; note that changing this name will revise the name of the tags used to identify them for user tagging. You should set this very early in the setup process.</p>

<p>If you  allow users to submit new things to the site, you can set the <strong>default status for new things</strong> to Draft so you can moderate them. If the form will only be used by admins or i you allow new things to go directly to the site, set this option to Publish Immediately.</p>

<p>The <strong>excerpt length</strong> is used to set the word length of short descriptions of examples and things on index pages (the Wordpress default is 55 words).</p>

<h4>Assignment Bank Options: General Settings: Captcha Settings</h4>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/captcha-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Spam is a sad fact of life. Enabling this option will put a <a href="ttps://www.google.com/recaptcha">Google reCaptcha</a> on all submission forms. You can use one of the four styles of captcha. Public and private keys are needed to use the captcha and <a href="https://www.google.com/recaptcha/admin/create">can be obtained from the Google Recpatcha site</a><p/>

<h4>Assignment Bank Options: General Settings: Media Settings</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/media-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Set the width and height of thumbnail images on all index and archive pages. Any autoe-mbedded media (Youtube, vimeo, Soundcloud, flickr) will be sized to the width setting (default settings are 320px wide and 240px high)</p>

<p>The <strong>single item media size</strong> is how wide an image or embedded media will display on a single thing entry (default is 500px)</p>

<p>The <strong>default thumbnail image</strong> is what is used for a thing if not specified via the submission form. The image can be uploaded here to or selected from the Wordpress media library. The image should be at least larger than the default thumbnail width.</p>


<h4>Assignment Bank Options: General Settings: Creative Commons Settings</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/creative-commons-settings.jpg" alt="" style="border:3px solid #000;" /><br />

Creative commons licenses can be attached to all things on the site. Choose <strong>Apply one license to all challenges</strong> to place the same license on all things (a notice will be displayed on the submission form). The license uses can eb selected from the menu,</p>

<p>Setting the Creative Commons options to <string>Enable users to choose license when submitting a challenge</strong> will put the menu on the submission form so users can choose a license (or set to All Rights Reserved). At this time, the only way to reduce the number of license options is to edit <code>functions.php</code> in the template directory. Look for the function <code>function cc_license_select_options</code> and comment out the lines containing license options to hide.</p>

<h4>Assignment Bank Options: General Settings: Thing Ratings</h4>
<p>To enable user ratings of Things, you must install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a>; when installed, its status will be indicated on the options screen. A few of the plugins settings will be needed to be modified.</p>

On the <strong><a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-options.php') .'">Post Rating Options</a></strong> choose the graphic style for the ratings- the suggestion is one of the stars settings with a max ratings of 5. Set the "Allow to Rate" option to <strong>Registered Users and Guests</strong> to allow any site visitor to rate.</p>

<p>Set the <a href="' . admin_url( 'admin.php?page=wp-postratings/postratings-templates.php') .'">Post Ratings Templates</a>  as indicated to reduce the clutter the default labels create.</p>

<ul>
<li><strong>Ratings Vote Text, Ratings None</strong> Enter <code>%RATINGS_IMAGES_VOTE%</code></li>
<li><strong>Ratings Voted Text, Ratings No Permission Text, Highest Rated</strong> Enter <code>%RATINGS_IMAGES%</code></li>
</ul>

<p>"Most Rated" can be ignored</p>



