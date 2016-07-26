<p>For terminology, this site creates a collection of <strong>things</strong> which may be assignments, challenges, tasks, dog toys, etc. You can create any number of <strong>types</strong> of things.</p>

<h2>Set Up Specific Pages</h2>
<p>This theme provides three templates for Wordpress Pages that provide specific functionality for your site. You should at least create these all for a new site, even if you do not use them.</p>

<ol>
<li>Create a new Page for your  <strong>Main Menu</strong>  This generates the index of all types of things; they will be listed the order specified by the option defined in General Settings. The title and content of the page is displayed above a two column grid of types of things. To enable the functionality for the index, set the page template to <strong>Assignment Menu</strong><br /><br/>If you wish this page to be the front of the site (<a href="http://bank.ds106.us/">like the demo</a>), use the <a href="<?php echo admin_url( 'options-reading.php')?>">Reading Settings </a> to set the Front Page as a static page (if you plan to use the blog, create a blank page that you can use for a Posts page).<br /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/reading-settings.jpg" alt="" style="border:3px solid #000;" /></li>

<li>Create a new Page to <strong>Submit New Things</strong>; you will need this even if you do not allow visitors to add them so you can add them yourself (the page can be unlinked or have a password set on it). The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Assignments</strong>.<br /><br />
<em>I strongly recommend creating your Things from this form, special tags and custom fields are created automatically for you. Doing it within the dashboard is an invitation for Trouble. If you are logged in to the site, the form will auto populate the name and email fields based on your Wordpress profile and will hide the captcha if it is enabled for public users.</em>
</li>

<li>Create a new Page to <strong>Submit Examples</strong>. This form is used to allow visitors to add examples for things via a web form. The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Example/Tutorial Form</strong>.</li>

</ol>


<h2>Bank Options: General Settings: Thing Settings</h2>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/general-thing-settings.jpg" alt="" style="border:3px solid #000;" /><br />

<strong>Define the name of things</strong> in the bank- the name here should be singular. This is used in numerous places throughout the site; note that changing this name will revise the name of the tags used to identify them for user tagging.</p>

<p>If you allow users to submit new Things to the site, you can set the <strong>default status for new things</strong> to Draft so you can moderate them. If the form will only be used by admins or if you allow new Things to go directly to the site, set this option to Publish Immediately.</p>

<p>The <strong>display order</strong> controls how the types of Things are sequenced on the main index; by title, order created, or by the number of things in each type. This order can be switched direction via the <strong>display order sorting</strong>.</p>

<p>The <strong>excerpt length</strong> is used to set the word length of short descriptions of examples and things on index pages (the Wordpress default is 55 words).</p>


<h2>Bank Options: General Settings: Twitter Settings</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter-options.jpg" alt="" style="border:3px solid #000;" /><br />
<strong>Use twitter name on submission forms?</strong> provides an option to include a twitter user name on form submission, and whether to make entry optional or not. When enabled, the twitter names are added to each item as a tag. This allows for tracking of work using twitter name as a marker and enabling of leaderboard options</p>

<p><strong>Twitter Hashtags</strong> can be added to output for twitter buttons added to challenges and examples. More than one can be added if separated by commas.</p>



<h2>Bank Options: General Settings: Captcha Settings</h2>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/captcha-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Spam is a sad fact of life. Enabling this option will put a <a href="https://www.google.com/recaptcha">Google reCaptcha</a> on all submission forms. You can use one of the four styles of captcha. Public and private keys are needed to use the captcha and <a href="https://www.google.com/recaptcha/admin/create">can be obtained from the Google Recpatcha site</a><p/>

<h2>Bank Options: General Settings: Media Settings</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/media-settings.jpg" alt="" style="border:3px solid #000;" /><br />
Set the width and height of thumbnail images on all index (320 x 240px should would for most nearly every purpose), archive screens, and as displayed on a single Thing view.</p>

<p>The <strong>default thumbnail image</strong> is what is used for a Thing if it is not specified via the submission form. The image can be uploaded here to or selected from the Wordpress media library. The image should be at least larger than the default thumbnail width.</p>


<h2>Bank Options: General Settings: Creative Commons Settings</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/creative-commons-settings.jpg" alt="" style="border:3px solid #000;" /><br />Creative commons licenses can be attached to all Things on the site. Choose <strong>Apply one license to all challenges</strong> to place the same license on all things (a notice will be displayed on the submission form).</p>

<p>Setting the Creative Commons options to <strong>Enable users to choose license when submitting a challenge</strong> will put the menu on the submission form so users can choose a license (or set to All Rights Reserved). At this time, the only way to reduce the number of license options is to edit <code>functions.php</code> in the template directory. Look for the function <code>function cc_license_select_options</code> and comment out the lines containing license options to hide.</p>

<h2>Bank Options: General Settings: Thing Popularity Ratings</h2>
<p>To enable user popularity ratings of Things, you must install the <a href="http://wordpress.org/plugins/wp-postratings/" target="_blank">WP-PostRatings plugin</a>; when installed, its status will be indicated on the options screen. If the plugin is not installed, all references to popularilty ratings will be hidden</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ratings-options.jpg" alt="" style="border:3px solid #000;" /><br />On the <strong><a href="<?php echo admin_url('admin.php?page=wp-postratings/postratings-options.php')?>">Post Rating Options</a></strong> choose the graphic style for the ratings, any of the options can be used, and you can also choose a value for the maximum setting (the default of 5 works well for stars).</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ratings-text-value.jpg" alt="" style="border:3px solid #000;" /><br />On the same screen you can also define the labels for the ratings scale (this is used on the form where visitors add new "Things").</p>

<p>Set the "Allow to Rate" option to <strong>Registered Users and Guests</strong> to allow any site visitor to rate.</p>

<p>You should also edit the <strong><a href="<?php echo admin_url('admin.php?page=wp-postratings/postratings-templates.php')?>">Post Rating Templates</a></strong> to customize the text displayed to show the ratings- the first two templates are used. <strong>Ratings Vote Text:</strong> designates how the ratings are show; <strong>Ratings Voted Text:</strong> is shown as feedback and adds an indicated if a visitor has already voted.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ratings-custom-fields.jpg" alt="" style="border:3px solid #000;" /><br />Data for ratings are stored as custom fields on all Things in three fields; they can be edited to adjust any rating if you can sort out the arithmetic:</p>

<ul>
<li><strong>ratings_score</strong> is the total cumulative votes submitted (in the above example, 7)</li>
<li><strong>ratings_users</strong> is the number of people who voted (in the above example, 2)</li>
<li><strong>ratings_average</strong> is the value that will be displayed (in the above example, 3.5)</li>
</ul>

<p>If you feel the vote of 5 by one person is too high, you might change the values to be ratings_score=2, ratings_users and ratings_average=2 to reduce the rating to 2. Changing votes is your decision!</p>

<h2>Bank Options: General Settings: Thing Difficulty Ratings</h2>
<p>An initial difficulty rating is provided by the creator of a Thing, separate from popularity ratings. Check the box to enable this feature and add it to the form for creating new Things.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/challenge-ratings.jpg" alt="" style="border:3px solid #000;" /></p>


<h2>Bank Options: General Settings: Settings for Responses to / Examples for Things</h2>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/display-single-options.jpg" alt="" style="border:3px solid #000;" /><br />
For some implementations, the display of a thing might not need examples, or tutorials or either listed. This new options allows you to set what is shown. If set to <code>both</code> they are displayed in two columns (default). If either is selected to display, it is shown as a single centered column, a bit wider. And by setting this option to <code>Neither</code> the listings of examples and tutorials is supressed.</p>

<p>Note that if not shown, you can still choose to have a form where either or both are added to the site. </p>




<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/submit-examples.jpg" alt="" style="border:3px solid #000;" /><br />By checking the first box, this section enables a web form for site visitors to submit their examples and support materials as response to a Thing (the form asks for name, email title, description, and a link). This page is only reached by following a link from a Thing, which passes it two variables to indicate the Thing, and whether it is an Example or a Tutorial.</p>

<p>If the expectation of the site is that users will be linking to their work as stored elsewhere (e.g. blogs or other user maintained site) check the option for <strong>Link to Form Submitted Examples</strong> to be <strong>No, links go to example URL</strong>.</p>

<p>On the other hand, setting this option to be <strong>Yes, links go to entry on the bank site</strong> sets the site up to house all submitted responses. A form will eb presented with a rich text editor, that allows the users to preview and review their work before final submission.</p>

<p>For the link to the form to work, you must have previously created a Page that uses the <strong>Submit Example/Tutorial Form</strong> template. The drop down menu will list all pages on the site; choose the one that should house the form. If you do not want to make the form public, just avoid adding menu links to it (or put a password on the form).</p>

<p>You can set whether a new example is published immediately or set to draft for moderation. All examples and tutorials added to the site (by the form or via the syndication methods below) can be reviewed and edited via the <a href="<?php echo admin_url( 'edit.php?post_type=examples')?>">dashboard menu for Examples Done</a>. The most likely item to be edited is the URL for the example; it is stored in the Custom Field value for <strong>syndication_permalink</strong>.</p>

<p>Finally, the items added to the right side can be Resources, Tutorials, Extra links- and you can use <strong>Name for Support Things</strong> to define how they are labeled. The submission form for these offer a place for a title, URL, and a short description.</p>


<h2>Bank Options: General Settings: Settings for Syndication of Examples</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/syndication-for-examples.jpg" alt="" style="border:3px solid #000;" /><br />The last section enables the features to syndicate in examples that users publish on their own blogs. If this feature is not desired, leave the default setting for <strong> No syndication</strong>. Examples are added only via web form (if enabled above) or only via WordPress Admin and you can ignore the rest of the settings.</p>

<p>There are two approaches to syndication, both require the <a href="http://wordpress.org/plugins/feedwordpress/">Feed Wordpress plugin</a> to be installed.</p>

<p> One is to turn the Assignment Bank site into its own syndication hub (<strong> Use a local install of Feed Wordpress to aggregate examples to this site.</strong>). This means that RSS feeds will have to be added directly to the local install of Feed Wordpress. The rest of the settings can be ignored. For a local syndication, users will only need to provide one tag, e.g. <strong>Assignment12</strong> to each of their posts for this site to be able to publish the examples directly to the Thing associated with it.</p>

<p>The second approach is the setup used for ds106; the assignment bank will rely on another site that is managing the direct syndication of user content. The local install of Feed Wordpress is used to "re-syndicate" the content to the bank. In this case, you must specify a <strong>required tag</strong> users should use to indicate a post is in response to something in the bank; the second tag specifies the thing it should be associated with. Finally the name and the URL for the example are used in the instruction text for each Thing.</p>

<h2>Extra Settings for Feed Wordpress</h2>
<p>These settings are suggested to enable Feed Wordpress to syndicate external posts in as examples.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-update-scheduling.jpg" alt="" style="border:3px solid #000;" /><br />
Under <strong>Updates Scheduling</strong> in the  <a href="<?php echo admin_url( 'admin.php?page=feedwordpress/feeds-page.php')?>">Feed and Updates Feed Wordpress Settings</a> set the  <strong>set to automatically check for updates after pages load</strong> to generate the process of feed checking. This is the easiest approach that is triggered by site activity- if you understand cron scripts you can set that up as an alternative.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-post-settings.jpg" alt="" style="border:3px solid #000;" /><br />
At the bottom of the <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/posts-page.php')?>">Posts and Links Feed Wordpress Settings</a>  in the section for <strong>Custom Post Types (advanced database settings)</strong>, set the option for Custom Post Types to <strong>Examples Done</strong>. What this does is to associate all syndicated posts with the content type that defines the examples. </p>


<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-tag-settings.jpg" alt="" style="border:3px solid #000;" /><br />To the RSS feeds that Feed Wordpress syndicates, any tags or categories an incoming feed are actually associated as categories in the RSS structure. </p>

<p>In the <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/categories-page.php')?>">Feed Categories & Tags Feed Wordpress Settings</a>  check the options for <strong>Match feed categories</strong> and <strong>Match inline tags</strong> to include <strong>Thing Tags</strong> and <strong>Tutorial Tags</strong>. This will match all incoming tags to be associated with the taxonomy that organize the examples into the proper Thing types.</p>


<p>If you have no use for other tags in posts, under <strong>Unmatched Categories</strong> check the option for <strong>Don't create any matching terms</strong>. This keeps the database from being filled by un-used user tags/categories.</p>

<p>If you have any use to mark all of the syndicated posts, the options at the bottom of this screen allow you to add Wordpress tags or categories to them (e.g. add a "syndicated" tag).</p>


<h2>Adding Feeds</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/fwp-add-feeds.jpg" alt="" style="float:right; border:3px solid #000;" /><br />At this time, all RSS feeds must be added to your site via the screen for <a href="<?php echo  admin_url( 'admin.php?page=feedwordpress/syndication.php')?>">Feed Wordpress Syndication Sites</a>. The <strong>add multiple</strong> button opens a field where you can enter in a list of sites or feeds.</p>

<p>For each feed, you will have to confirm or select the correct Feed URL (some sites offer several options of Feed format or the content it finds as an RSS feed</p>.

<p>If you are using an external syndication site, you only need to add one feed- the one that corresponds to the tag entered in the <strong>Required Tag</strong> Assignment Bank option. If your required tag is <strong>bank106</strong>, than you want to bring in to this site only the posts on the syndication site that have this tag, for example:</p>

<pre>http://www.mycoolcourse.org/webhub/tag/bank106/feed</pre>

<p>It is recommended to test the feed first to make sure it is pulling in data and the correctly tagged content.</p>

<h2>Bank Options: Thing Types</h2>
<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/new-thing-types.jpg" alt="" style="border:3px solid #000;" /><br />The second tab of the theme options is where you can create and edit the categories or types of things in your collection. You can add any number of new things by listing them one per line in the <strong>Names for New Types</strong> field at the bottom.</p>

<p>After clicking <strong>Save Changes</strong>, each type will now have its own editing field. They will be listed in the same display order you set in the General Settings.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/edit-thing-types.jpg" alt="" style="border:3px solid #000;" /><br />The title can always be edited, and you can add/edit the short description. Then, use the build in interface to the Wordpress media library, upload a thumbnail image-- it should be larger then the default place holder image, but does not have to be the exact size. Wordpress will handle the image sizing for you.</p>

<p>Checking a type for deletion will remove it permanently upon saving.</p>

<p>Hi, you've made it to the bottom of the documentation, where few have tread.</p>

