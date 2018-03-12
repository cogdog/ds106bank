# ds106 Assignment Bank Wordpress Theme

The DS106 Assignment Bank Theme is a Wordpress Theme allows you to create and customize a site that has all of the functionality of the [DS106 Open Assignment Bank](http://assignments.ds106.us/). With it you can build a site to house any collection of assignments, tasks etc, create a categorization, and then like [ds106](http://ds106.us) allow your community to add to the collection.  

Got a question? Suggestion? Feature Requests? Problem? A surplus of money? Please send them my way via [GitHub Issues](https://github.com/cogdog/ds106bank/issues) or directly to cogdogblog@gmail.com

## What Can You Build With This?
This Wordpress Theme is modeled after the original [DS106 Open Assignment Bank](http://assignments.ds106.us/) developed for the open digital storytelling course [DS106](http://ds106.us).

The banks we know of include:

* [All The Toys](http://www.allthetoys.org/)
* [Box of Magic Web Tricks](https://cog.dog/roo/box/) Alan Levine, ISS Institute Tour Workshops, Nov 2017
* [Connected Learning Make Bank](http://clmoocmb.educatorinnovator.org/2014/)
* [Foodworks Assignment Bank](http://foodworks.middcreate.net/assignments/) Middlebury College
* [HumanMOOC Activity Bank](http://humanmooc.com/activities/)
* [Instructional Technology Assignments](http://tcoffman.org/INDT/) INDT501 at University of Mary Washington
* [Mobile Social Media Learning Technologies Project Bank](http://mosomelt.org/the-project-bank/view-all/) Aukland University of Technology
* [Ontario Extend Activity Bank](https://extend-bank.ecampusontario.ca/)
* [OER's for Transitional Learning](http://ctl.openlcc.net/oer/) Lansing Community College
* [Still Web Contemplative Practices Bank](http://practices.stillweb.org/)
* [UBC Open For Learning Challenges](http://openlearning.sites.olt.ubc.ca/) University of British Columbia 
* [UDG Agora Challenge Bank](http://udg.theagoraonline.net/bank) University of Guadalajara Agora Project

Hey, if you know more...  let me know-- or better yet, DIY; please fork this in GitHub, add your example, and submit a pull request. That's how this git thing gets things!

For more details behind how this theme came to be see [related posts on CogDogBlog](http://cogdogblog.com/tag/106bank/).

## What's New?

* Mar 12, 2018: CSS support for [User Interface Options plugin](https://github.com/fluid-project/uio-wordpress-plugin)
* Oct 16, 2017: New site option to include a general set of instructions to appear when responding to an assignment. In addition, each assignment can include extra instructions that are specific to it. Both of these will appear at the top of the form when used to submit a reponse.
* June 22, 2017: New Assignment Bank options to make use of Wordpress user accounts to track contributions, either optionally or make it require. In the latter case, submssion forms are hidden until a visitor has logged in.
* May 30, 2017: Added capability for thin header image atop nav bar that can be added via theme customizer (60px high)
* Feb 2, 2017: There is a new field on adding a new "thing" for extra information, such as attribution credit for it's thumbnail image or other ancillary content. When published, it is displayed below the description in a blue info box. Javascript generated previews for Things and Examples updated to correctly mark paragraphs in content. Default thumbnail size bumped to 640x480 and CSS updated on archive listings to better preserve proportions.
* Sep 29, 2016: Added a category-like taxonomy for things, another way to organize them. The primary "bins" are the "types" that appear on the main menu. But now you can also use categories to organize things across these bins. Because I was using the Wordpress Category taxonomy for responses, I had to create a new one, with an archive template. Through the options panel, you can choose to not use the categories; to allow site users to select categories when they create Things; or to leave that as an option for site moderators to do. You can also assign it a different label than "Category" when displayed on the site.
* Jul 16, 2016: Finally revamped the Options interface to overcome a bug in the broken tabbed display of sections. All settings are now on one long scrolling tab, a second one is used for documentation. Also added a new feature by request of Jim Luke for options to suppress the section of tutorials and/or examples on the single Thing display 
* May 3, 2016: New theme option to allow for archive views of assignments to use the embed-able media of it's example as an icon (e.g. YouTube video) rather than the thumbnail.
* May 30, 2015: Major updates for UDG Agora project- vastly improved submission forms with previews, options to track users by twitter handle, improved single layout, leaderboard.

## Terminology / Jargon
Generically I call the things inside the bank "Things" (internally you may find code references to "assignments", legacy of the first iteration of code).

So in the ds106 site there are types of things (Assignments) such as [Design Assignments](http://assignments.ds106.us/types/designassignments/), [Video Assignments](http://assignments.ds106.us/types/videoassignments/), etc. Each assignment has a crowdsourced difficulty rating; any site visitor add their own vote. 

A specific assignment, for example, the [Six Word Memoir](http://assignments.ds106.us/assignments/six-word-memoir/) has a linked example, an associated icon, and a description. The site generates a unique pair of tags for each assignment. When a participant who has their blog registered at ds106, writes up their assignment with the tags, through RSS syndication the post gets attached as an example.

The idea for a general version of this functionality is a site where types of "things" are created by the site owner (more or less categories).  They are displayed on a main index, which can be, but does not have to be, the entrance to your site:

![](images/assignment-bank-front.jpg "Assignment Bank Index")

Within each "type" is a collection of "things"

![](images/assignment-bank-type-view.jpg "Assignment Bank Type View")

So we might have a site of "Challenges", and within there you might groups of Cooking Challenges, Fitness Challenges (think of them as categories). Within each are specific challenges to do, like "Make Bread From Scratch" "Cook Ham Bone Soup", or "Do 100 Pushups"). 

And each "thing" has its own page, with associated examples and tutorials:

![](images/assignment-bank-single.jpg "Assignment Bank Single Thing View")

The site options allow people to submit their examples created in response to a thing via a web form, or if part of an ongoing activity or community, to aggregate them in from external blogs (as [ds106](http://ds106.us) does).

You can allows site visitors to add their own challenges via a web form, this is [what made the DS106 assignment bank valuable](http://assignments.ds106.us/submit-an-assignment/).

**Note: You should always, always create your "things" via this form, even if you do not allow the public to do so.** The form adds a number of post custom field values necessary to make everything work. You can always modify these later, in the editor (making sure you make Custom Fields visible via screen options).

The one field that you may need to edit is the URL for the example of the assignment, stored as custom field named `fwp_url`. If you prefer not to display any example, simply leave the value blank.

![](images/add-thing.jpg "Form to add a new thing")

Besides descriptive information, a creator of an assignment can associate a new item with one or more types, and can also add free form tags to better describe it.  If the ratings capability is enabled, they can assign a first seed value for the rating. 

And if the option is used to let users submit to the site via a web form, they can add a set of instructions that are specific to just this assignment (the generalized instructions added to all assignments are entered as described below by a site option);

![](images/assignment-specific-instructions.jpg "Create instructions specific to assignment")

The combination of these two features can let you customize instructions:

![](images/add-example-instructions.jpg "Extra assignment instructions")

## Theming

This ds106 Assignment Bank Theme is build as a child theme of the [Wordpress Bootstrap Theme[(https://github.com/320press/wordpress-bootstrap) chosen for its responsive layout and flexible grid display (and sadly no longer being supported). 

It is pretty basic on design, but hopefully flexible to your use cases. The design allows you to create a site where the front of the site is the menu of types of things, but that could also be an internal page, and a normal blog flow can be front and center. The theme does not create any of the navigation menus for you, but you will find suggestions as to the types of things you can make available via the built in Wordpress menu editor.

## Requirements
A self hosted Wordpress hosted site (in other words "you cannot use this on Wordpress.com").  This theme can work on a multisite or as a single install. Depending on how you wish to run the site, you might install plugins below. You will also find suggestions for Widgets that are useful for sidebars and footers.


## Installing and Configuring the Theme
*(see headings below for more detail, there's a boatload of detail...)*

Note: Do not upload the zip for this GitHub repo to install the themes. It won't work!

1. Upload the **ds106banker** and the **wp-bootstrap** directories (from wp-content/ on this distro) to your site's wp-content/themes directory. Or if you wish to install from within the Wordpress Dashboard, from **Appearance** select **Themes** and click the **Upload** links.  A zip of both themes is included with this repo, or you can download directly [installable-ds106banker.zip](https://github.com/cogdog/ds106bank/blob/master/installable-ds106banker.zip) and  [installable-wp-bootstrap.zip](https://github.com/cogdog/ds106bank/blob/master/installable-wp-bootstrap.zip) 

2. Activate the **ds106banker** theme (you do not have to activate the wp-bootstrap theme)
3. Install WP-PostRatings and/or Feed Wordpress plugins according to the way you plan to use the theme (see below).
4. Create /modify as needed pages for the Main Index, the page with a form to add examples, and a form for creating new "things" (see below).  The theme *should* create these for you, but if not you can create them and set the appropriate template as described below.
5. Set the theme options (detailed in length below). Find the **Assignment Bank Options** listed both under the **Appearance** settings in the Wordpress Dashboard, or via the admin nav bar.
6. Customize the site menus. The theme provides a few shortcodes you can use on any page or sidebar.
7. See other suggested plugins
8. Create some stuff


## Updating the Theme

If you have ftp/sftp access to your site (or this can be done in a cpanel file manager), simply upload the new theme files to the `wp-content/themes` directory that includes the older version theme. 

For those that lack direct file upload access or maybe that idea sends shivers down the spine, upload and activate the [Easy Theme and Plugin Upgrades](https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/) plugin -- this will allow you to upload a newer version of a theme as a ZIP archive, the same way you add a theme by uploading.


------

### Setting up WP-PostRatings for Popularity Ratings
Install the [WP-PostRatings plugin](http://wordpress.org/plugins/wp-postratings/) to activate the user thing popularity rating feature. Not installing the plugin (or de-activating it) removes the feature from the site. the purpose here is to allow visitors to rate Things, and provide sorting of things based on said ratings.

A few settings for the plugin are necessary (found in the new Ratings option in the Admin Sidebar).

 ![](wp-content/themes/ds106banker/images/ratings-options.jpg)

On the **Post Rating Options** choose the graphic style for the ratings- the suggestion is one of the stars settings with a max ratings of 5 but any setting is viable. The suggested set up mode is to use the ratings as a measure of popularity, so the default `1 Star`, `2 Stars`, etc can be used. On the other hand, the original DS106 Assignment Bank was a crowd sourced measured of difficulty, so the labels could be customized as follows

![](wp-content/themes/ds106banker/images/ratings-text-value.jpg)

Create any labels for your scale (these are used on the form to submit new Things). Set the "Allow to Rate" option to **Registered Users and Guests** to allow any site visitor to cast a vote.

Set the **Post Ratings Templates**  to customize the text displayed to show the ratings- the first two templates are used. **Ratings Vote Text:** designates how the ratings are shows; **Ratings Voted Text:** is shown as feedback and adds an indicated if a visitor has already voted; **Ratings None:** is shown for an item that has not been rated yet.

These templates are generally not used by Bank sites but certainly could be used: **Ratings No Permission Text:**, **Highest Rated:**, and **Most Rated:**


For a setup as a popularity voting, the **Ratings Vote Text** template might be:

```
Popularity: %RATINGS_IMAGES_VOTE% (**%RATINGS_USERS%** votes, average:
**%RATINGS_AVERAGE%** out of **%RATINGS_MAX%**)
<br />%RATINGS_TEXT%
```

For **Ratings Voted Text**:

```
Popularity: %RATINGS_IMAGES% (<em>**%RATINGS_USERS%** votes, average:
**%RATINGS_AVERAGE%** out of **%RATINGS_MAX%**; 
you have rated this</em>)
```

Finally, for **Ratings None**:

```
Popularity:  %RATINGS_IMAGES_VOTE% (No Ratings Yet)<br />%RATINGS_TEXT%
```

FYI the data for ratings are stored in three custom fields on all Things; they can be edited to adjust any rating if you can sort out the arithmetic:

* **ratings_score** is the total cumulative votes submitted (in the above example, 7)
* **ratings_users** is the number of people who voted (in the above example, 2)
* **ratings_average** is the value that will be displayed (in the above example, 3.5)

If you feel the vote of 5 by one person is too high, you might change the values to be ratings_score=2, ratings_users and ratings_average=2 to reduce the rating to 2. Changing votes is your decision.

### Author Challenge Ratings

![](wp-content/themes/ds106banker/images/challenge-ratings.jpg)

Found under Appearance/Assignment Bank Options, enable this option to allow creators of new things to define their own rating of difficulty. This is completely separate from user popularity rating. 


### Setting Up Feed Wordpress
Install the [Feed Wordpress plugin](http://wordpress.org/plugins/feedwordpress/) if you wish to syndicate in responses to "things" as examples. This means that you can add blog feeds to the bank (it does the feed aggregation) or you can syndicate in from another site that is aggregating feeds (the ds 106 model). 

If this is not a desired feature, the plugin is not needed. You can still allow visitors to submit their examples via a web form.

A few settings must be made in Feed Wordpress to work correctly with the Assignment Bank Theme.

![](wp-content/themes/ds106banker/images/fwp-update-scheduling.jpg)
Under **Updates Scheduling** in the  **Feed and Updates** option under **Syndication Settings** set the dropdown menu to **set to automatically check for updates after pages load** to generate the process of feed checking. This is the easiest approach that is triggered by site activity- if you understand cron scripts you can set that up as an alternative.

![](wp-content/themes/ds106banker/images/fwp-post-settings.jpg)
At the bottom of the **Posts and Links** page of the **Syndication Settings** in the section for **Custom Post Types (advanced database settings)**, set the option for Custom Post Types to **Responses**. What this does is to associate all syndicated posts with the content type that defines the examples. 


![](wp-content/themes/ds106banker/images/fwp-tag-settings.jpg)

To the RSS feeds that Feed Wordpress syndicates, any tags or categories in an incoming feed are actually associated as categories in the RSS structure. 

In the **Feed Categories & Tags** section of the **Syndication Settings** check the options for **Match feed categories** and **Match inline tags** to include **Thing Tags** and **Tutorial Tags**. This will match all incoming tags to be associated with the taxonomy that organize the examples into the proper Thing types.

If you have no use for other tags in posts, under **Unmatched Categories** check the option for **Don't create any matching terms**. This keeps the database from being filled by un-used user tags/categories.

If you have any use to mark all of the syndicated posts, the options at the bottom of this screen allow you to add Wordpress tags or categories to them (e.g. add a "syndicated" tag).


### Setting Up Pages
A few Wordpress pages should be created on theme activation, to create the main index of all things, another to house the form for adding a new thing,one  for users to add an example of a response or a tutorial to a thing, and yet one more to set up a help system.

If the pages are *not* created for you on theme activation...

1. Create a new page for a  **Main Menu**. This generates the index of all types of things; they will be listed in the order specified by your theme options. The title and content of the page (which you edit) is displayed above a grid of types of things. To enable the functionality, set the page template to **Assignment Menu** If you wish this page to be the front of the site, use the **Wordpress Reading Settings** to set the Front Page as a static page (if you plan to use the blog, create a blank page that you can use for a Posts page).

![](wp-content/themes/ds106banker/images/reading-settings.jpg)

2. Create a new page to **Submit New Things** You will need this even if you do not allow visitors to add them so you can add them yourself (the page can be unlinked or have a password set on it). The title and content of the page is displayed above the input form. To enable the functionality, set the page template to **Submit Thing Form**.

![](images/add-thing.jpg "Form to add a new thing")

**To repeat: You should always, always create your "things" via this form, even if you do not allow the public to do so.** The form adds a number of post custom field values necessary to make everything work. You can always modify these later, in the editor (making sure you make Custom Fields visible via screen options).

The one field that you may need to edit is the URL for the example of the assignment, stored as custom field named `fwp_url`. If you prefer not to display any example, simply leave the value blank.

3. Create a new page to **Submit Examples**. This form is used to allow visitors to add examples or tutorial for things via a web form. The title and content of the page is displayed above the input form. To enable the functionality, set the page template to **Submit Example/Tutorial Form**

![](images/add-example.jpg "Form to add a new example")

Note that this page is never viewed directly on it's own. It will be be linked from a single item page with additional paramaters to construct the full submission form.

4. Create a new page to manage the  **Help** pages for submission forms (this one must have a permalink/slug of **help**). The title and content of the page is displayed above the help content (specific for the type of content being created). 


----------
### Assignment Bank Theme Options
This theme has a comprehensive set of options, available via the **Assignment Bank Options## form the main admin toolbar.

#### Assignment Bank Options: General Settings: Things Settings

![](wp-content/themes/ds106banker/images/general-thing-settings.jpg)

**Define the name of things** in the bank- the name here should be singular. This is used in numerous places throughout the site; note that changing this name will revise the name of the tags used to identify them for user tagging. You should set this very early in the setup process.

If you  allow users to submit new things to the site, you can set the **default status for new things** to Draft so you can moderate them. If the form will only be used by admins or if you allow new things to go directly to the site, set this option to Publish Immediately.

The **display order** controls how the types of things are sequenced on the main index; by title, order created, or by the number of things in each type. This order can be switched direction via the **display order sorting**.

The **excerpt length** is used to set the word length of short descriptions of examples and things on index pages (the Wordpress default is 55 words).

#### Bank Options: General Settings: Thing Categories

![](wp-content/themes/ds106banker/images/categories-for-things.jpg)

For other ways to organize your Things across types you can enable the use of categories. For example, in a Bank of Assignments where they are organized by Types of Media (e.g. the original ds106 Assignment Bank), you could create a categorization based on different classes / courses using the bank.

If you do want to use categories, you set them up first in the Dashboard under **Things to Do** -&gt; **Thing Categories**. The Bank will recognize only one set of child categories is applied.

**Use Categories for _____** by default is set to no-- they will not be used at all on the site or visible, even if used in the past. The second option will present the categories on the public page when your site visitors create a new thing-- they check the appropriate boxes for categories.

But there might be cases where you want to categorize only on the back end; e.g. do not let users self categorize but do it when you moderate / review things added. This is the second "yes" option.

And if you use Categories but want to call them something else, like `Sections` enter that in the setting for **Label for Category**. This will be used on the entry forms and any where the categories are displayed.


### Bank Options: General Settings: Login Settings

![](wp-content/themes/ds106banker/images/wp-login-options.jpg)

**Use wordpress accounts for adding responses and/or items to the bank.** provides a bank owner the ability to use Wordpress user accounts to track activity. Note that a site owner will need to manage user account creation. No Wordpress capabilities are needed, a contributor level account will work. 

If you enable the second or third options, a **Sign in** button will appear at the end of the navigation bar:

![](wp-content/themes/ds106banker/images/not-logged-in.jpg)

After logging in, a user will be redirected back to the page they were using. The menu bar will now display they name adjacent to what is now a log out button, and the display name will link to their archive of contributions.

![](wp-content/themes/ds106banker/images/logged-in.jpg)

If the setting is for a user account  required, on the buttons that link to the form from a Thing, the button is disabled and a sign in button is provided:

![](wp-content/themes/ds106banker/images/must-login-ex.jpg)

If the user account is optional, the alert offers a chance to sign in as an option:

![](wp-content/themes/ds106banker/images/optional-login.jpg)

And if logged in, the submission forms indicate that their work will be associated with their account:

![](wp-content/themes/ds106banker/images/logged-in-add-ex.jpg)

The same prompts are provided on the page used to add a new Thing to a bank site.

#### Assignment Bank Options: General Settings: Twitter Settings

![](wp-content/themes/ds106banker/images/twitter-options.jpg)

**Use twitter name on submission forms?** provides an option to include a twitter user name on form submission, and whether to make entry optional or not. When enabled, the twitter names are added to each item as a tag. This allows for tracking of work using twitter name as a marker and enabling of leaderboard options

**Twitter Hashtags** can be added to output for twitter buttons added to challenges and examples. More than one can be added if separated by commas.


#### Assignment Bank Options: General Settings: Captcha Settings

![](wp-content/themes/ds106banker/images/captcha-settings.jpg)

Spam is a sad fact of life. Enabling this option will put a [Google reCaptcha](https://www.google.com/recaptcha) on all submission forms. You can use one of the four styles of captcha. Public and private keys are needed to use the captcha and [can be obtained from the Google Recpatcha site](https://www.google.com/recaptcha/admin/create)

#### Assignment Bank Options: General Settings: Media Settings
![](wp-content/themes/ds106banker/images/media-settings.jpg)

Set the width and height of thumbnail images on all index and archive pages. 

The **default thumbnail image** is what is used for a thing if not specified via the submission form. The image can be uploaded here to or selected from the Wordpress media library. The image should be at least larger than the default thumbnail width.


![](wp-content/themes/ds106banker/images/embed-media-icon.jpg)

Activating **Embed Media Icon** will use an assignments example media as an icon on archive listings (instead of the thumbnail) if the example URL can be embedded (e.g. YouTube video, SoundCloud audio, a tweet). The default is "no" or off.

#### Assignment Bank Options: General Settings: Creative Commons Settings
![](wp-content/themes/ds106banker/images/creative-commons-settings.jpg)

Creative commons licenses can be attached to all things on the site. Choose **Apply one license to all challenges** to place the same license on all things (a notice will be displayed on the submission form). The license uses can eb selected from the menu,

Setting the Creative Commons options to **Enable users to choose license when submitting a challenge** will put the menu on the submission form so users can choose a license (or set to All Rights Reserved). At this time, the only way to reduce the number of license options is to edit `functions.php` in the template directory. Look for the function `function cc_license_select_options` and comment out the lines containing license options to hide.


#### Assignment Bank Options: General Settings: Settings for Responses to / Tutorials for Things

![](wp-content/themes/ds106banker/images/display-single-options.jpg)

For some implementations, the display of a thing might not need examples, or tutorials or either listed. This new options allows you to set what is shown. If set to `both` they are displayed in two columns (default). If either is selected to display, it is shown as a single centered column, a bit wider. And by setting this option to `Neither` the listings of examples and tutorials is supressed.

Note that if not shown, you can still choose to have a form where either or both are added to the site.


![](wp-content/themes/ds106banker/images/submit-examples.jpg)

By checking the first box, this section enables a web form for site visitors to submit their examples and support materials as response to a Thing (the form asks for name, email title, description, and a link). This page is only reached by following a link from a Thing, which passes it two variables to indicate the Thing, and whether it is an Example or a Tutorial.

If the expectation of the site is that users will be linking to their work as stored elsewhere (e.g. blogs or other user maintained site) check the option for **Link to Form Submitted Examples** to be **No, links go to example URL**.

On the other hand, setting this option to be **Yes, links go to entry on the bank site** sets the site up to house all submitted responses. A form will be presented with a rich text editor, that allows the users to preview and review their work before final submission. The rich text editor supports wordpress autoembeds.


![](wp-content/themes/ds106banker/images/response-form.jpg)

As a new feature, you can add a set of instructions that will appear at the top of the example response submission form.

![](wp-content/themes/ds106banker/images/general-form-instructions.jpg)

For the link to the submission form to work, you must have previously created a Page that uses the **Submit Example/Tutorial Form** template. The drop down menu will list all pages on the site; choose the one that should house the form. If you want to not make the form public, just avoid adding menu links to it (or put a password on te form)

You can set whether a new example is published immediately or set to draft for moderation. All examples added to the site (by the form or via the syndication methods below) can be reviewed and edited via the dashboard menu for Examples Done. The most likely item to be edited is the URL for the example; it is stored in the Custom Field value for **syndication_permalink**.

Finally, the items added to the right side can be Resources, Tutorials, Extra links- and you can use **Name for Support Things** to define how they are labeled. The submission form for these offer a place for a title, URL, and a short description.


#### Assignment Bank Options: General Settings: Settings for Syndication of Examples
![](wp-content/themes/ds106banker/images/syndication-for-examples.jpg)

The last section enables the features to syndicated in examples that users post on their own blogs. If this feature is not desired, leave the default setting for ** No syndication. Examples are added only via web form (if enabled above) or only via WordPress Admin** and ignore the rest of the settings.

There are two approaches to syndication, both require the [eed Wordpress plugin](http://wordpress.org/plugins/feedwordpress/) to be installed.

One is to turn the Assignment Bank site into its own syndication hub Use a local install of Feed Wordpress to aggregate examples to this site). This means that RSS feeds will have to be added directly to the local install of Feed Wordpress. The rest of the settings can be ignored. For a local syndication, users will only need to provide one tag, e.g. **Assignment12** to each of their posts for this site to be able to publish the examples directly to the Thing associated with it.

The second approach is the setup used for ds106; the assignment bank will rely on another set that is managing the direct syndication of user content. The local install of Feed Wordpress is used to "re-syndicate" the content to the bank. In this case, you must specify a **required tag** users should use to indicate a post is in response to something in the bank; the second tag specifies the thing it should be associated with. Finally the name and the URL for the example are used in the instruction text for each Thing.

#### Adding Feeds
![](wp-content/themes/ds106banker/images/fwp-add-feeds.jpg)

At this time, all RSS feeds must be added to your site via the screen for **Feed Wordpress Syndication Sites**. The **add multiple** button opens a field where you can enter in a list of sites or feeds.

For each feed, you will have to confirm or select the correct Feed URL (some sites offer several options of Feed format or the content it finds as an RSS feed.

If you are using an external syndication site, you only need to add one feed- the one that corresponds to the tag entered in the **Required Tag** Assignment Bank option. If your required tag is **bank106**, than you want to bring in to this site only the posts on the syndication site that have this tag, for example:

`http://www.mycoolcourse.org/webhub/tag/bank106/feed`

It is recommended to test the feed first to make sure it is pulling in data and the correctly tagged content.

#### Assignment Bank Options: Thing Types
![](wp-content/themes/ds106banker/images/new-thing-types.jpg)

At the bottom of the theme options is where you can create and edit the categories or types of things in your collection. You can add any number of new things by listing them one per line in the **Names for new types** field.

After clicking **Save Changes**, each type will now have its own editing field. They will be listed in the same display order you set in the General Settings.


![](wp-content/themes/ds106banker/images/edit-thing-types.jpg)

The title can always be edited, and you can add/edit the short description. Then, use the build in interface to the Wordpress media library, upload a thumbnail image-- it should be larger then the default place holder image, but does not have to be the exact size. Wordpress will handle the image sizing for you.

Checking a type for deletion will remove it permanently upon saving.

----------

## Other Wordpress Stuff to Do

### Creating Menus
The structure of the sites navigation is left to the owner; use the built in Wordpress menu editor to activate a top menu the footer menu seems to not be formatted in the parent theme, and has been removed from the assignment bank footer.php template). Be sure to open the *Screen Options* so you can and more kinds of items to menus (like Thing Types, Thing Categories).

This means you can create any structure you like, including archives for the types of things. Below is the structure of the demo site:

![](images/assignment-bank-menus.jpg)

A few special URLs are available, for say a site set up on `/bank.yourdomain.org` (the slug `assignments` is unfortunately basked in)

* `http://bank.yourdomain.org/assignments` an archive of all things
* `http://bank.yourdomain.org/assignments/?srt=random` a random thing
* `http://bank.yourdomain.org/examples` an archive of all examples added to the site

### Thing Categories
Under *Things to Do* you can add a category taxonomy that work like regular blog posts (*Thing Categories*); this allows another way to organize the things in your bank. If you do not create any categories, they will never be seen. But once you add a few, the will show up as selectable items on the Thing creation form.

### Shortcodes
These shortcodes can be used in an page, post, widget

`[thingcount]` generates a count of all "things" in the bank such as **34 challenges**

`[examplecount]` generates a count of all "examples" in the bank such as **112 examples**

`[feedroll]`  If Feed Wordpress is installed, this shortcode generates a list of all subscribed blogs, useful as a sidebar widget. If feeds have different tags to segment them, a specific list can be produced by `[feedroll tag="section5"]`

#### Leaderboard Shortcodes
These codes can be used in posts or widgets to list the most active participants (if the option is enabled to track submissions by twitter name)

List all respondents in order of most active to least

	[bankleaders]

List the top 10 respondents

	[bankleaders number="10"]

List the top 10 respondents and exclude the ones identified in the hashtag taxonomy as ids 8 and 10

	[bankleaders number="10" exclude="8,10"]

List all the twitter names that have contributed new Things via the submission form 

	[bankleaders type="contributors"]
	
### Header Image 
You can now upload a header image via the Wordpress Customizer. The size is relatively short (60px) since it is fixed with the scroll of the navbar. But it's room to fit a small logo that links to the home page. It will work best if the image is 970px wide and 60px high. A background transparent PNG will really look nice!

![](images/bank-header.jpg)

If there is no header image set in the Customizer... then you get none!
	
###  Customizing CSS
Most of the theme's design is managed by the parent WP-Bootstrap theme. You do know to never edit that, right?

Because of the way styles are loaded, the typical child theme for the Assignment Bank [style.css](ds106banker/style.css) is blank except for the information needed to establish the relationship to the parent theme. 

Any custom style over-rides should be added to [style.css](ds106banker/ds106bank.css). If you add additional styles, make a back up copy as updates to this theme will override your edits.

### Other Suggested plugins

The following plugins are useful for a bank.

* [Flexible Post Widget](http://wordpress.org/plugins/flexible-posts-widget/‎) provides a widget that can list the custom post types used in the theme; as used in the footer of the demo site, it can provide a  widget listing of randomly ordered "things" and "examples"

![](images/flexible-post-widget.jpg)

* [List Custom Taxonomy Widget](http://wordpress.org/plugins/list-custom-taxonomy-widget/‎) provides a widget like the built in Category widget, but for custom Taxonomies (the structure used to create the types of things). This widget can list all as links, plus put the count of items Within

![](images/list-custom-taxonomy-widget.jpg)

* By default, the search on the site searches all Things, if you wish to have a broader search (say for a blog attached, or for examples too, add the [Search Everything](https://wordpress.org/plugins/search-everything/) plugin. You will also have to modify `header.php` and remove or comment out this line

```
<input type="hidden" name="post_type" value="assignments" />
```

* This theme's stylesheet has been set up to work with the Fluid Project [User Interface Options Wordpress plugin](https://github.com/fluid-project/uio-wordpress-plugin) which adds a series of accessibility options to a site. This is only made available when the plugin is activated.


-----

### Licensely Yours

    This theme is Copyright (C) 2014 Alan Levine http://cog.dog/ http://cogdogblog.com/

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.







