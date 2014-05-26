<p>For terminology, this site creates a collection of "things" which may be assignments, challenges, tasks, etc. You can create any number of types of things.</p>

<h4>Set Up Pages</h4>
<p>This theme provides three templates for pages that provide specific functionality for your site. You should at least create these all for a new site.</p>

<ol>
<li>Create a new page for a  <strong>Main Menu</strong> It generates the index of all types of things; they will be listed the order specified by the option in General Settings. The title and content of the page is displayed above a grid of types of things. To enable the functionality, set the page template to <strong>Assignment Menu</strong><br /><br/>If you wish this page to be the front of the site (<a href="http://bank.ds106.us/">like the demo</a>), use the <a href="<?php echo admin_url( 'options-reading.php')?>">Reading Settings </a> to set the Front Page as a static page (if you plan to use the blog, create a blank page that you can use for a Posts page).<br /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/reading-settings.jpg" alt="" style="border:3px solid #000;" /></li>

<li>Create a new page to <strong>Submit New Things</strong>; you will need this even if you do not allow visitors to add them so you can add them yourself (the page can be unlinked or have a password set on it). The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Assignments</strong>.</li>

<li>Create a new page to <strong>Submit Examples</strong>. This form is used to allow visitors to add examples for things via a web form. The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Example/Tutorial Form</strong>.</li>

</ol>


<h4>Assignment Bank Options: General Settings: Things Settings</h4>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/general-thing-settings.jpg" alt="" style="border:3px solid #000;" /><br />

<strong>Define the name of things</strong> in the bank- the name here should be singular. This is used in numerous places throughout the site; note that changing this name will revise the name of the tags used to identify them for user tagging. You should set this very early in the setup process.</p>

<p>If you  allow users to submit new things to the site, you can set the <strong>default status for new things</strong> to Draft so you can moderate them. If the form will only be used by admins or i you allow new things to go directly to the site, set this option to Publish Immediately.</p>

<p>The <strong>display order</strong> controls how the types of things are sequenced on the main index; by title, order created, or by the number of things in each type. This order can be switched direction via the <strong>display order sorting</strong>.</p>

<p>The <strong>excerpt length</strong> is used to set the word length of short descriptions of examples and things on index pages (the Wordpress default is 55 words).</p>

<h4>Assignment Bank Options: General Settings: Captcha Settings</h4>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/captcha-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Spam is a sad fact of life. Enabling this option will put a <a href="https://www.google.com/recaptcha">Google reCaptcha</a> on all submission forms. You can use one of the four styles of captcha. Public and private keys are needed to use the captcha and <a href="https://www.google.com/recaptcha/admin/create">can be obtained from the Google Recpatcha site</a><p/>

<h4>Assignment Bank Options: General Settings: Media Settings</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/media-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Set the width and height of thumbnail images on all index and archive pages. Any autoe-mbedded media (Youtube, vimeo, Soundcloud, flickr) will be sized to the width setting (default settings are 320px wide and 240px high)</p>

<p>The <strong>single item media size</strong> is how wide an image or embedded media will display on a single thing entry (default is 500px)</p>

<p>The <strong>default thumbnail image</strong> is what is used for a thing if not specified via the submission form. The image can be uploaded here to or selected from the Wordpress media library. The image should be at least larger than the default thumbnail width.</p>


<h4>Assignment Bank Options: General Settings: Creative Commons Settings</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/creative-commons-settings.jpg" alt="" style="border:3px solid #000;" /><br />Creative commons licenses can be attached to all things on the site. Choose <strong>Apply one license to all challenges</strong> to place the same license on all things (a notice will be displayed on the submission form). The license uses can eb selected from the menu,</p>

<p>Setting the Creative Commons options to <strong>Enable users to choose license when submitting a challenge</strong> will put the menu on the submission form so users can choose a license (or set to All Rights Reserved). At this time, the only way to reduce the number of license options is to edit <code>functions.php</code> in the template directory. Look for the function <code>function cc_license_select_options</code> and comment out the lines containing license options to hide.</p>

<h4>Assignment Bank Options: General Settings: Thing Ratings</h4>
<p>To enable user ratings of Things, you must install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a>; when installed, its status will be indicated on the options screen. A few of the plugins settings will be needed to be modified. If the plugin is not installed, all references to user ratings will be hidden</p>

<p>On the <strong><a href="<?php echo admin_url('admin.php?page=wp-postratings/postratings-options.php')?>">Post Rating Options</a></strong> choose the graphic style for the ratings- the suggestion is one of the stars settings with a max ratings of 5. Set the "Allow to Rate" option to <strong>Registered Users and Guests</strong> to allow any site visitor to rate.</p>

<h4>Assignment Bank Options: General Settings: Settings for  Examples</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/submit-examples.jpg" alt="" style="border:3px solid #000;" /><br />By checking the first box, this  section allows you to enable a web form for site visitors to submit their examples as response to a thing (the form asks for name, email title, description, and a link).

<p>For the link to the form to work, you must have previously created a Page that uses the <strong>Submit Example/Tutorial Form</strong> template. The drop down menu will list all pages on the site; choose the one that should house the form. If you want to not make the form public, just avoid adding menu links to it (or put a password on te form)</p>

<p>Finally, you can set whether a new example is published immediately or set to draft for moderation. All examples added to the site (by the form or via the syndication methods below) can be reviewed and edited via the <a href="<?php echo admin_url( 'edit.php?post_type=examples')?>">dashboard menu for Examples Done</a>. The most likely item to be edited is the URL for the example; it is stored in the Custom Field value for <strong>syndication_permalink</strong>.</p>


<h4>Assignment Bank Options: General Settings: Settings for Syndication of Examples</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/syndication-for-examples.jpg" alt="" style="border:3px solid #000;" /><br />The last section enables the features to syndicated in examples that users post on their own blogs. If this feature is not desired, leave the default setting for <strong> No syndication. Examples are added only via web form (if enabled above) or only via WordPress Admin</strong> and ignore the rest of the settings.</p>

<p>There are two approaches to syndication, both require the <a href="http://wordpress.org/plugins/feedwordpress/">Feed Wordpress plugin</a> to be installed.</p>

<p> One is to turn the Assignment Bank site into its own syndication hub (<strong> Use a local install of Feed Wordpress to aggregate examples to this site.</strong>). This means that RSS feeds will have to be added directly to the local install of Feed Wordpress. The rest of the settings can be ignored. For a local syndication, users will only need to provide one tag, e.g. <strong>Assignment12</strong> to each of their posts for this site to be able to publish the examples directly to the Thing associated with it.</p>

<p>The second approach is the setup used for ds106; the assignment bank will rely on another set that is managing the direct syndication of user content. The local install of Feed Wordpress is used to "re-syndicate" the content to the bank. In this case, you must specify a <strong>required tag</strong> users should use to indicate a post is in response to something in the bank; the second tag specifies the thing it should be associated with. Finally the name and the URL for the example are used in the instruction text for each Thing.</p>

<h4>Extra Settings for Feed Wordpress</h4>
<p>These settings are suggested to enable Feed Wordpress to syndicate external posts in as examples.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-update-scheduling.jpg" alt="" style="border:3px solid #000;" /><br />
Under <strong>Updates Scheduling</strong> in the  <a href="<?php echo admin_url( 'admin.php?page=feedwordpress/feeds-page.php')?>">Feed and Updates Feed Wordpress Settings</a> set the  <strong>set to automatically check for updates after pages load</strong> to generate the process of feed checking. This is the easiest approach that is triggered by site activity- if you understand cron scripts you can set that up as an alternative.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-post-settings.jpg" alt="" style="border:3px solid #000;" /><br />
At the bottom of the <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/posts-page.php')?>">Posts and Links Feed Wordpress Settings</a>  in the section for <strong>Custom Post Types (advanced database settings)</strong>, set the option for Custom Post Types to <strong>Examples Done</strong>. What this does is to associate all syndicated posts with the content type that defines the examples. </p>


<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-tag-settings.jpg" alt="" style="border:3px solid #000;" /><br />To the RSS feeds that Feed Wordpress syndicates, any tags or categories an incoming feed are actually associated as categories in the RSS structure. </p>

<p>In the <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/categories-page.php')?>">Feed Categories & Tags Feed Wordpress Settings</a>  check the options for <strong>Match feed categories</strong> and <strong>Match inline tags</strong> to include <strong>Thing Tags</strong> and <strong>Tutorial Tags</strong>. This will match all incoming tags to be associated with the taxonomy that organize the examples into the proper Thing types.</p>


<p>If you have no use for other tags in posts, under <strong>Unmatched Categories</strong> check the option for <strong>Don't create any matching terms</strong>. This keeps the database from being filled by un-used user tags/categories.</p>

<p>If you have any use to mark all of the syndicated posts, the options at the bottom of this screen allow you to add Wordpress tags or categories to them (e.g. add a "syndicated" tag).</p>


<h4>Adding Feeds</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-add-feeds.jpg" alt="" style="float:right; border:3px solid #000;" /><br />At this time, all RSS feeds must be added to your site via the screen for <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/syndication.php')?>">Feed Wordpress Syndication Sites</a>. The <strong>add multiple</strong> button opens a field where you can enter in a list of sites or feeds.</p>

<p>For each feed, you will have to confirm or select the correct Feed URL (some sites offer several options of Feed format or the content it finds as an RSS feed</p>.

<p>If you are using an external syndication site, you only need to add one feed- the one that corresponds to the tag entered in the <strong>Required Tag</strong> Assignment Bank option. If your required tag is <strong>bank106</strong>, than you want to bring in to this site only the posts on the syndication site that have this tag, for example:</p>

<pre>http://www.mycoolcourse.org/webhub/tag/bank106/feed</pre>

<p>It is recommended to test the feed first to make sure it is pulling in data and the correctly tagged content.</p>

<h4>Assignment Bank Options: Thing Types</h4>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/new-thing-types.jpg" alt="" style="border:3px solid #000;" /><br />The second tab of the theme options is where you can create and edit the categories or types of things in your collection. You can add any number of new things by listing them one per line in the <strong>Names for new types</strong> field.</p>

<p>After clicking <strong>Save Changes</strong>, each type will now have its own editing field. They will be listed in the same display order you set in the General Settings.</p>


<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/edit-thing-types.jpg" alt="" style="border:3px solid #000;" /><br />The title can always be edited, and you can add/edit the short description. Then, use the build in interface to the Wordpress media library, upload a thumbnail image-- it should be larger then the default place holder image, but does not have to be the exact size. Wordpress will handle the image sizing for you.</p>

<p>Checking a type for deletion will remove it permanently upon saving.</p>













