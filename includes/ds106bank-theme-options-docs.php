  <style>
      code{white-space: pre-wrap;}
      span.smallcaps{font-variant: small-caps;}
      span.underline{text-decoration: underline;}
      div.column{display: inline-block; vertical-align: top; width: 50%;}
      img {max-width:90%; }
  </style>



<p><em>For complete setup documentation that includes suggestions for setup, plugins, <a href="https://github.com/cogdog/ds106bank"  target="_blank">see the theme repository on GitHub</a>. That is also <a href="https://github.com/cogdog/ds106bank/issues"  target="_blank">a good place to ask question or toss accolades</a>.</em></p>


<h3 id="setting-up-pages">Setting Up Pages</h3>
<p>A few Wordpress pages should be created on theme activation, to create the main index of all things, another to house the form for adding a new thing,one for users to add an example of a response or a tutorial to a thing, and yet one more to set up a help system.</p>
<p>If the pages are <em>not</em> created for you on theme activation…</p>
<ol type="1">
<li>Create a new page for a <strong>Main Menu</strong>. This generates the index of all types of things; they will be listed in the order specified by your theme options. The title and content of the page (which you edit) is displayed above a grid of types of things. To enable the functionality, set the page template to <strong>Assignment Menu</strong> If you wish this page to be the front of the site, use the <strong>Wordpress Reading Settings</strong> to set the Front Page as a static page (if you plan to use the blog, create a blank page that you can use for a Posts page).</li>
</ol>
<p><img src="<?php echo get_template_directory_uri()?>/images/reading-settings.jpg" /></p>
<ol start="2" type="1">
<li>Create a new page to <strong>Submit New Things</strong> You will need this even if you do not allow visitors to add them so you can add them yourself (the page can be unlinked or have a password set on it). The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Thing Form</strong>.</li>
</ol>
<p><img src="<?php echo get_template_directory_uri()?>/images/add-thing.jpg" title="Form to add a new thing" /></p>
<p><strong>To repeat: You should always, always create your “things” via this form, even if you do not allow the public to do so.</strong> The form adds a number of post custom field values necessary to make everything work. You can always modify these later, in the editor (making sure you make Custom Fields visible via screen options).</p>
<ol start="3" type="1">
<li>Create a new page to <strong>Submit Examples</strong>. This form is used to allow visitors to add examples or tutorial for things via a web form. The title and content of the page is displayed above the input form. To enable the functionality, set the page template to <strong>Submit Example/Tutorial Form</strong></li>
</ol>
<p><img src="<?php echo get_template_directory_uri()?>/images/add-example.jpg" title="Form to add a new example" /></p>
<p>Note that this page is never viewed directly on it’s own. It will be be linked from a single item page with additional paramaters to construct the full submission form.</p>
<ol start="4" type="1">
<li>Create a new page to manage the <strong>Help</strong> pages for submission forms (this one must have a permalink/slug of <strong>help</strong>). The title and content of the page is displayed above the help content (specific for the type of content being created).</li>
</ol>
<hr />
<h3 id="assignment-bank-theme-options">Assignment Bank Theme Options</h3>
<p>This theme has a comprehensive set of options, available via the <strong>Assignment Bank Options</strong> from the main admin toolbar.</p>
<h4 id="theme-setup">Theme setup</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/theme-setup.jpg" /></p>
<p>On theme activation several pages will be created for you, but to make sure they are all present, click <strong>Create Theme Specific Pages</strong> – it will generate ones needed that are not present. Two pages specifically are used for forms on your site, one for adding new “things” and the other for adding a response to the things. If no Page is found with the correct template, you will see a prompt to create one. If the Page is found (and there might even be more than one, you can select the one you want used for each special page.</p>
<h4 id="things-in-this-bank">Things in this Bank</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/things-in-bank.jpg" /></p>
<p><strong>Define the name of things</strong> in the bank- enter both singular and plural correct names.</p>
<p>If you allow users to submit new things to the site, you can set the <strong>default status for new things</strong> to Draft so you can moderate them. If the form will only be used by admins or if you allow new things to go directly to the site, set this option to Publish Immediately.</p>
<p>For other ways to organize your Things across types you can enable the use of categories. For example, in a Bank of Assignments where they are organized by Types of Media (e.g. the original ds106 Assignment Bank), you could create a categorization based on different classes / courses using the bank.</p>
<p>If you do want to use categories, you set them up first in the Dashboard under <strong>Things to Do</strong> -&gt; <strong>Thing Categories</strong>. The Bank will recognize only one set of child categories is applied.</p>
<p><strong>Use Categories for _____</strong> by default is set to no– they will not be used at all on the site or visible, even if used in the past. The second option will present the categories on the public page when your site visitors create a new thing– they check the appropriate boxes for categories.</p>
<p>But there might be cases where you want to categorize only on the back end; e.g. do not let users self categorize but do it when you moderate / review things added. This is the second “yes” option.</p>
<p>And if you use Categories but want to call them something else, like <code>Sections</code> enter that in the setting for <strong>Label for Category</strong>. This will be used on the entry forms and any where the categories are displayed.</p>
<h4 id="settings-for-thing-responses-and-tutorials">Settings for Thing Responses and Tutorials</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/setting-responses-1.jpg" /></p>
<p>For some implementations, the display of a thing might not need examples, or tutorials or either listed. This new options allows you to set what is shown. If set to <code>both</code> they are displayed in two columns (default). If either is selected to display, it is shown as a single centered column, a bit wider. And by setting this option to <code>Neither</code> the listings of examples and tutorials is supressed.</p>
<p>Note that if not shown, you can still choose to have a form where either or both are added to the site.</p>
<p>Enable <strong>Allow Uploads for Responses</strong> to allow visitors to upload a file as a way of responding beyond entering a web address. You can limit to the file size of uploads.</p>
<p>The <strong>Number of Responses to Display at a Time</strong> is used if the <strong>Ajax Load More</strong> plugin us enabled, it will sequentially load this number of responses at a time.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/setting-responses-2.jpg" /></p>
<p>By checking the first box, this section enables a web form for site visitors to submit their examples and support materials as response to a Thing (the form asks for name, email title, description, and a link). This page is only reached by following a link from a Thing, which passes it two variables to indicate the Thing, and whether it is an Example or a Tutorial.</p>
<p>If the expectation of the site is that users will be linking to their work as stored elsewhere (e.g. blogs or other user maintained site) check the option for <strong>Link to Form Submitted Examples</strong> to be <strong>No, links go to example URL</strong>.</p>
<p>On the other hand, setting this option to be <strong>Yes, links go to entry on the bank site</strong> sets the site up to house all submitted responses. A form will be presented with a rich text editor, that allows the users to preview and review their work before final submission. The rich text editor supports wordpress autoembeds.</p>
<p>As a new feature, you can add a set of instructions that will appear at the top of the example response submission form.</p>
<p>You can set whether a new example is published immediately or set to draft for moderation. All examples added to the site (by the form or via the syndication methods below) can be reviewed and edited via the dashboard menu for Examples Done. The most likely item to be edited is the URL for the example; it is stored in the Custom Field value for <strong>syndication_permalink</strong>.</p>
<p>Finally, the items added to the right side can be Resources, Tutorials, Extra links- and you can use <strong>Name for Support Things</strong> to define how they are labeled. The submission form for these offer a place for a title, URL, and a short description.</p>
<h4 id="media-settings">Media Settings</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/media-settings-1.jpg" /></p>
<p>Set the width and height of thumbnail images on all index and archive pages.</p>
<p>The <strong>default thumbnail image</strong> is what is used for a thing if not specified via the submission form. The image can be uploaded here to or selected from the Wordpress media library. The image should be at least larger than the default thumbnail width.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/media-settings-2.jpg" /></p>
<p>Enter a <strong>default attribution</strong> for this image.</p>
<p>Activating <strong>Embed Media Icon</strong> will use an assignments example media as an icon on archive listings (instead of the thumbnail) if the example URL can be embedded (e.g. YouTube video, SoundCloud audio, a tweet). The default is “no” or off.</p>
<h4 id="user-options">User Options</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/user-options.jpg" /></p>
<p><strong>Use wordpress accounts for adding responses and/or items to the bank.</strong> provides a bank owner the ability to use Wordpress user accounts to track activity.</p>
<p>If you enable the second or third options, a <strong>Sign in</strong> button will appear at the end of the navigation bar:</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/not-logged-in.jpg" /></p>
<p>After logging in, a user will be redirected back to the page they were using. The menu bar will now display they name adjacent to what is now a log out button, and the display name will link to their archive of contributions.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/logged-in.jpg" /></p>
<p>If the setting is for a user account required, on the buttons that link to the form from a Thing, the button is disabled and a sign in button is provided:</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/must-login-ex.jpg" /></p>
<p>If the user account is optional, the alert offers a chance to sign in as an option:</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/optional-login.jpg" /></p>
<p>A lesson learned the hard way; never allow self-registration on WordPress sites where accounts get authoring privileges- this is a spam account magnet. The theme has an option for you to label the button as you see fit, but also to provide a link where it should go- it could be a page on the site with information on how to get an account or link to a managed signup form. Putting <code>#</code> in the link field will disable the button and it provides a Javascript alert that registration is not available.</p>
<p>The setting for <strong>User names on submission forms</strong> creates a field for a user to enter a desired username to identify their shared content (if not logging in); previously this was a twitter account but it cold be any unique name a user chooses; it acts more like a tag,</p>
<p>Whether a WordPress username or a user defined one, we use the convention of <code>@username</code> when displayed (again this is not linked to twitter).</p>
<p>The default user name is a fall back if for some reason none was entered.</p>
<h4 id="apply-creative-commons-to-each-thing">Apply Creative Commons to Each Thing</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/creative-commons.jpg" /></p>
<p>Creative commons licenses can be attached to all things on the site. Choose <strong>Apply one license to all challenges</strong> to place the same license on all things (a notice will be displayed on the submission form). The license uses can eb selected from the menu,</p>
<p>Setting the Creative Commons options to <strong>Enable users to choose license when submitting a challenge</strong> will put the menu on the submission form so users can choose a license (or set to All Rights Reserved). At this time, the only way to reduce the number of license options is to edit <code>functions.php</code> in the template directory. Look for the function <code>function cc_license_select_options</code> and comment out the lines containing license options to hide.</p>
<h4 id="thing-ratings">Thing Ratings</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/thing-ratings.jpg" /></p>
<p>An indicator will show if the WP-Ratings plugin is installed for user ratings (see below).</p>
<p>Enable the <strong>Allow Author Difficulty Rating</strong> option to allow creators of new things to define their own rating of difficulty. This is completely separate from user popularity rating.</p>
<h4 id="email-notifications">Email Notifications</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/email.jpg" /></p>
<p>Enter email addresses (separated by commas) for notifcations of new Things submitted and/or Responses to things.</p>
<h4 id="twitter-options">Twitter Options</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/twitter.jpg" /></p>
<p>These settings allow a Tweet This button to appear on your bank, and offers a way to add your own hashtags to the tweets.</p>
<h5 id="syndication-for-responses">Syndication for Responses</h5>
<p><img src="<?php echo get_template_directory_uri()?>/images/syndication.jpg" /></p>
<p>These settings offer a way to syndicate in examples that users post on their own blogs. If this feature is not desired, leave the default setting for <strong>No syndication</strong>.</p>
<p>There are two approaches to syndication, both require the <a href="http://wordpress.org/plugins/feedwordpress/">Feed Wordpress plugin</a> installed.</p>
<p>One is to turn the Assignment Bank site into its own syndication hub– using a local install of Feed Wordpress to aggregate examples to this site. This means that RSS feeds will have to be added directly to the local install of FeedWordpress in a bank site. The rest of the settings here can be ignored. For a local syndication, users will only need to provide one tag, e.g. <strong>Assignment12</strong> to each of their posts for this site to be able to publish the examples directly to the Thing associated with it.</p>
<p>The second approach is like <a href="http://ds106.us/flow">the setup used for ds106</a>; the assignment bank will rely on another sitr that is managing the syndication of user content. The local install of Feed Wordpress is used to “re-syndicate” the content to the bank. In this case, you must specify a <strong>required tag</strong> users should use to indicate a post is in response to something in the bank; the second tag specifies the thing it should be associated with. Finally the name and the URL for the example are used in the instruction text for each Thing.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/fwp-add-feeds.jpg" /></p>
<p>At this time, all RSS feeds must be added to your site via the screen for <strong>Feed Wordpress Syndication Sites</strong>. The <strong>add multiple</strong> button opens a field where you can enter in a list of sites or feeds.</p>
<p>For each feed, you will have to confirm or select the correct Feed URL (some sites offer several options of Feed format or the content it finds as an RSS feed.</p>
<p>If you are using an external syndication site, you only need to add one feed- the one that corresponds to the tag entered in the <strong>Required Tag</strong> Assignment Bank option. If your required tag is <strong>bank106</strong>, than you want to bring in to this site only the posts on the syndication site that have this tag, for example:</p>
<p><code>http://www.mycoolcourse.org/webhub/tag/bank106/feed</code></p>
<p>Test the feed first to make sure it is pulling in data and the correctly tagged content.</p>
<h4 id="captcha-settings">Captcha Settings</h4>
<p>Spam is a sad fact of the internet. Enabling this option will put a <a href="https://www.google.com/recaptcha">Google reCaptcha</a> on all submission forms. Site and secret keys are needed to use the captcha and <a href="https://www.google.com/recaptcha/admin/create">can be obtained from the Google Recpatcha site</a></p>
<h4 id="thing-type-organization">Thing Type Organization</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/types-things.jpg" /></p>
<p>These settings let you enter a name to represent the name for how you organize your things (they could be “Types”, “Modules”, etc), as well as the order and sorting for they way they appear on the front of the site.</p>
<h4 id="thing-type-editing">Thing Type Editing</h4>
<p>Here is where you can create and edit the categories or types of things in your collection. You can add any number of new things by listing them one per line in the <strong>Names for new types</strong> field.</p>
<p>After clicking <strong>Save Changes</strong>, each type will now have its own editing field. They will be listed in the same display order you set in the General Settings.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/edit-thing-types.jpg" /></p>
<p>The title can always be edited, and you can add/edit the short description. Then, use the build in interface to the Wordpress media library, upload a thumbnail image– it should be larger then the default place holder image, but does not have to be the exact size. Wordpress will handle the image sizing for you.</p>
<p>Checking a type for deletion will remove it permanently upon saving.</p>
<hr />
<h2 id="other-wordpress-stuff-to-do">Other Wordpress Stuff to Do</h2>
<h3 id="creating-menus">Creating Menus</h3>
<p>The structure of the sites navigation is left to the owner; use the built in Wordpress menu editor to activate a top menu the footer menu seems to not be formatted in the parent theme, and has been removed from the assignment bank footer.php template). Be sure to open the <em>Screen Options</em> so you can and more kinds of items to menus (like Thing Types, Thing Categories).</p>
<p>This means you can create any structure you like, including archives for the types of things. Below is the structure of the demo site:</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/assignment-bank-menus.jpg" /></p>
<p>A few special URLs are available, for say a site set up on <code>/bank.yourdomain.org</code> (the slug <code>assignments</code> is unfortunately basked in)</p>
<ul>
<li><code>http://bank.yourdomain.org/assignments</code> an archive of all things</li>
<li><code>http://bank.yourdomain.org/assignments/?srt=random</code> a random thing</li>
<li><code>http://bank.yourdomain.org/examples</code> an archive of all examples added to the site</li>
</ul>
<h3 id="thing-categories">Thing Categories</h3>
<p>Under <em>Things to Do</em> you can add a category taxonomy that work like regular blog posts (<em>Thing Categories</em>); this allows another way to organize the things in your bank. If you do not create any categories, they will never be seen. But once you add a few, the will show up as selectable items on the Thing creation form.</p>
<h3 id="shortcodes">Shortcodes</h3>
<p>These shortcodes can be used in any page, post, widget:</p>
<p><code>[thingcount]</code> generates a count of all “things” in the bank such as <strong>34 challenges</strong></p>
<p><code>[examplecount]</code> generates a count of all “examples” in the bank such as <strong>112 examples</strong></p>
<p><code>[feedroll]</code> If Feed Wordpress is installed, this shortcode generates a list of all subscribed blogs, useful as a sidebar widget. If feeds have different tags to segment them, a specific list can be produced by <code>[feedroll tag="section5"]</code></p>
<h4 id="leaderboard-shortcodes">Leaderboard Shortcodes</h4>
<p>These codes can be used in posts or widgets to list the most active participants (if the option is enabled to track submissions by twitter name)</p>
<p>List all respondents in order of most active to least</p>
<pre><code>[bankleaders]</code></pre>
<p>List the top 10 respondents</p>
<pre><code>[bankleaders number=&quot;10&quot;]</code></pre>
<p>List the top 10 respondents and exclude the ones identified in the hashtag taxonomy as ids 8 and 10</p>
<pre><code>[bankleaders number=&quot;10&quot; exclude=&quot;8,10&quot;]</code></pre>
<p>List all the twitter names that have contributed new Things via the submission form</p>
<pre><code>[bankleaders type=&quot;contributors&quot;]</code></pre>
<h3 id="setting-up-wp-postratings-for-popularity-ratings">Setting up WP-PostRatings for Popularity Ratings</h3>
<p>Install the <a href="http://wordpress.org/plugins/wp-postratings/">WP-PostRatings plugin</a> to activate the user thing popularity rating feature. Not installing the plugin (or de-activating it) removes the feature from the site. the purpose here is to allow visitors to rate Things, and provide sorting of things based on said ratings.</p>
<p>A few settings for the plugin are necessary (found in the new Ratings option in the Admin Sidebar).</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/ratings-options.jpg" /></p>
<p>On the <strong>Post Rating Options</strong> choose the graphic style for the ratings- the suggestion is one of the stars settings with a max ratings of 5 but any setting is viable. The suggested set up mode is to use the ratings as a measure of popularity, so the default <code>1 Star</code>, <code>2 Stars</code>, etc can be used. On the other hand, the original DS106 Assignment Bank was a crowd sourced measured of difficulty, so the labels could be customized as follows</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/ratings-text-value.jpg" /></p>
<p>Create any labels for your scale (these are used on the form to submit new Things). Set the “Allow to Rate” option to <strong>Registered Users and Guests</strong> to allow any site visitor to cast a vote.</p>
<p>Set the <strong>Post Ratings Templates</strong> to customize the text displayed to show the ratings- the first two templates are used. <strong>Ratings Vote Text:</strong> designates how the ratings are shows; <strong>Ratings Voted Text:</strong> is shown as feedback and adds an indicated if a visitor has already voted; <strong>Ratings None:</strong> is shown for an item that has not been rated yet.</p>
<p>These templates are generally not used by Bank sites but certainly could be used: <strong>Ratings No Permission Text:</strong>, <strong>Highest Rated:</strong>, and <strong>Most Rated:</strong></p>
<p>For a setup as a popularity voting, the <strong>Ratings Vote Text</strong> template might be:</p>
<pre><code>Popularity: %RATINGS_IMAGES_VOTE% (**%RATINGS_USERS%** votes, average:
**%RATINGS_AVERAGE%** out of **%RATINGS_MAX%**)
&lt;br /&gt;%RATINGS_TEXT%</code></pre>
<p>For <strong>Ratings Voted Text</strong>:</p>
<pre><code>Popularity: %RATINGS_IMAGES% (&lt;em&gt;**%RATINGS_USERS%** votes, average:
**%RATINGS_AVERAGE%** out of **%RATINGS_MAX%**; 
you have rated this&lt;/em&gt;)</code></pre>
<p>Finally, for <strong>Ratings None</strong>:</p>
<pre><code>Popularity:  %RATINGS_IMAGES_VOTE% (No Ratings Yet)&lt;br /&gt;%RATINGS_TEXT%</code></pre>
<p>FYI the data for ratings are stored in three custom fields on all Things; they can be edited to adjust any rating if you can sort out the arithmetic:</p>
<ul>
<li><strong>ratings_score</strong> is the total cumulative votes submitted (in the above example, 7)</li>
<li><strong>ratings_users</strong> is the number of people who voted (in the above example, 2)</li>
<li><strong>ratings_average</strong> is the value that will be displayed (in the above example, 3.5)</li>
</ul>
<p>If you feel the vote of 5 by one person is too high, you might change the values to be ratings_score=2, ratings_users and ratings_average=2 to reduce the rating to 2. Changing votes is your decision.</p>
<h3 id="author-challenge-ratings">Author Challenge Ratings</h3>
<p><img src="<?php echo get_template_directory_uri()?>/images/challenge-ratings.jpg" /></p>
<p>Found under Appearance/Assignment Bank Options, enable this option to allow creators of new things to define their own rating of difficulty. This is completely separate from user popularity rating.</p>
<h3 id="setting-up-feed-wordpress">Setting Up Feed Wordpress</h3>
<p>Install the <a href="http://wordpress.org/plugins/feedwordpress/">Feed Wordpress plugin</a> if you wish to syndicate in responses to “things” as examples. This means that you can add blog feeds to the bank (it does the feed aggregation) or you can syndicate in from another site that is aggregating feeds (the ds 106 model).</p>
<p>If this is not a desired feature, the plugin is not needed. You can still allow visitors to submit their examples via a web form.</p>
<p>A few settings must be made in Feed Wordpress to work correctly with the Assignment Bank Theme.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/fwp-update-scheduling.jpg" /> Under <strong>Updates Scheduling</strong> in the <strong>Feed and Updates</strong> option under <strong>Syndication Settings</strong> set the dropdown menu to <strong>set to automatically check for updates after pages load</strong> to generate the process of feed checking. This is the easiest approach that is triggered by site activity- if you understand cron scripts you can set that up as an alternative.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/fwp-post-settings.jpg" /> At the bottom of the <strong>Posts and Links</strong> page of the <strong>Syndication Settings</strong> in the section for <strong>Custom Post Types (advanced database settings)</strong>, set the option for Custom Post Types to <strong>Responses</strong>. What this does is to associate all syndicated posts with the content type that defines the examples.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/fwp-tag-settings.jpg" /></p>
<p>To the RSS feeds that Feed Wordpress syndicates, any tags or categories in an incoming feed are actually associated as categories in the RSS structure.</p>
<p>In the <strong>Feed Categories &amp; Tags</strong> section of the <strong>Syndication Settings</strong> check the options for <strong>Match feed categories</strong> and <strong>Match inline tags</strong> to include <strong>Thing Tags</strong> and <strong>Tutorial Tags</strong>. This will match all incoming tags to be associated with the taxonomy that organize the examples into the proper Thing types.</p>
<p>If you have no use for other tags in posts, under <strong>Unmatched Categories</strong> check the option for <strong>Don’t create any matching terms</strong>. This keeps the database from being filled by un-used user tags/categories.</p>
<p>If you have any use to mark all of the syndicated posts, the options at the bottom of this screen allow you to add Wordpress tags or categories to them (e.g. add a “syndicated” tag).</p>
<h3 id="customizing-your-bank">Customizing Your Bank</h3>
<p>The WordPress Customizer used used to manage the appearance of your site as well as to edit the labels and prompts on your forms. This theme creates it’s own special section, plus others you can use.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/customizer.jpg" /></p>
<p>Open the “Thing Bank” panel to find two panels for customizing the forms on your site.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/customize-thing-bank-sections.jpg" /></p>
<h4 id="new-thing-form-prompts">New Thing Form Prompts</h4>
<p>This panel lets you modify form labels and prompts for your own site, some examples to see how it works. The forms will change in real time as you change the text on the left. It helps if you first navigate to the form, and then open the Customizer.</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/customizer-add-thing-form-1.jpg" /></p>
<p><img src="<?php echo get_template_directory_uri()?>/images/customizer-add-thing-form-2.jpg" /></p>
<h4 id="responseresource-form-prompts">Response/Resource Form Prompts</h4>
<p>In similar way, you can edit the form visitors to your site use to enter their response to things/assignments as well as contributing resources/tutorials. It helps if you first navigate to the form, and then open the Customizer. Note that the same prompts are used for both adding a response and adding a resource (if they are activated on your site).</p>
<p><img src="<?php echo get_template_directory_uri()?>/images/customizer-add-example.jpg" /></p>
<h4 id="add-a-header-image">Add a Header Image</h4>
<p><img src="<?php echo get_template_directory_uri()?>/images/bank-header.jpg" /></p>
<p>You can upload a header image via the Wordpress Customizer. The size is relatively short (60px) since it is fixed with the scroll of the navbar. But it’s room to fit a small logo that links to the home page. It will work best if the image is 970px wide and 60px high. A background transparent PNG will really look nice!</p>
<h4 id="add-custom-css">Add Custom CSS</h4>
<p>This is best done on the Customizrr too.</p>
<h3 id="other-suggested-plugins">Other Suggested plugins</h3>
<p>The following plugins are useful for a bank.</p>
<ul>
<li><a href="http://wordpress.org/plugins/flexible-posts-widget/‎">Flexible Post Widget</a> provides a widget that can list the custom post types used in the theme; as used in the footer of the demo site, it can provide a widget listing of randomly ordered “things” and “examples”</li>
</ul>
<p><img src="<?php echo get_template_directory_uri()?>/images/flexible-post-widget.jpg" /></p>
<ul>
<li><a href="http://wordpress.org/plugins/list-custom-taxonomy-widget/‎">List Custom Taxonomy Widget</a> provides a widget like the built in Category widget, but for custom Taxonomies (the structure used to create the types of things). This widget can list all as links, plus put the count of items Within</li>
</ul>
<p><img src="<?php echo get_template_directory_uri()?>/images/list-custom-taxonomy-widget.jpg" /></p>
<ul>
<li>By default, the search on the site searches all Things, if you wish to have a broader search (say for a blog attached, or for examples too, add the <a href="https://wordpress.org/plugins/search-everything/">Search Everything</a> plugin. You will also have to modify <code>header.php</code> and remove or comment out this line</li>
</ul>
<pre><code>&lt;input type=&quot;hidden&quot; name=&quot;post_type&quot; value=&quot;assignments&quot; /&gt;</code></pre>
<ul>
<li>This theme’s stylesheet has been set up to work with the Fluid Project <a href="https://github.com/fluid-project/uio-wordpress-plugin">User Interface Options Wordpress plugin</a> which adds a series of accessibility options to a site. This is only made available when the plugin is activated.</li>
</ul>



