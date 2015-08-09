<?php 
$use_twitter_name = ds106bank_option( 'use_twitter_name' );
$sub_type = strtolower( ds106bank_option('helpthingname') );
?>

<h2>Title (Fascinating, eh?)</h2>
<p><em>You had me at title...</em><br />
Hopefully this is obvious, but we'd like to encourage you to think about being creative with your choice of title for your <?php echo $sub_type?>. It will be what catches someone's eye who might be looking at a long list of <?php echo $sub_type?>s. It should be specific to the <?php echo $sub_type?> you are writing and not just echo the name of the particular <?php echo THINGNAME?> you are responding too.</p>
<p>


<h2>Got The Link?</h2>
<p><em>It's not a thing unless it's got a link!</em><br />
The whole idea here is to link to something you have published elsewhere on the web; maybe on your blog, maybe a social media site, maybe as a document. The link must be something people can see without any special logins (and this excludes Facebook unless you are 10000% sure the link is public to more than your "friends").</p>


<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-link1.jpg" alt="" style="border:3px solid #000;" /></p>

<p>Make sure the links works! Click the orange <code>TEST</code> button to see if the link works (it opens in a new browser window).</p>


<h2>Brief Description</h2>
<p><em>Just like a blurb for a book or a movie</em><br />
Write a summary for the link being as descriptive as possible; this is the text that will be displayed below your title. You can only use plain text (HTML will be hosed off and removed) and the counter below the field will give you an idea how many words you have left to use.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-description1.jpg" alt="" style="border:3px solid #000;" /></p>

<h2>Author Information</h2>
<p><em>Give credit as credit wants to be given...</em><br />
Write in the name field your name, or pseudonym for how you wish to be credited for this <?php echo $sub_type?>. Or include the name of others as collaborators, separated by commas.</p>

<p>We also ask for one email address; this information is <strong>never</strong> publicly displayed, and is used only if we have a question to ask you about your <?php echo $sub_type?>.</p>

<?php if ($use_twitter_name) echo '<p>Add a twitter name to let this site be able to track all of the work you submitted; if you prefer not to use a twitter account, at least create a unique name fo yourself that begins with a "@". Only enter one twitter name as a primary reference here; if you wish to credit others, enter these twitter names as tags (see below)';?>

<h2>Tags</h2>
<p><em>Tag your <?php echo $sub_type?> with other descriptive information</em><br />
Tags are optional but offer another way to associate common work. You may be instructed to use a tag to identify work for a particilaur course, subject, or you may find it useful to create a set of tags that associate different collections of your own work. </p>

<p>Separate all your tags with either a space or a comma- this means that tags must be a single word.</p>

<?php if ($use_twitter_name) echo '<p>You can also add additional twitter names to credit as co-authors by including them as tags (be sure to use the "@" sign)';?>

<h2>Update, Preview, Submit</h2>
<p><em>Polish this thing up and send it on it...</em><br />
The three buttons in the bottom are the steps to get your <?php echo $sub_type?> on this site.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-submit-buttons.jpg" alt="" style="border:3px solid #000;" /></p>

<p> You first need to click the blue <code>UPDATE</code> button so the information can be verified. If we find any mistakes, we will provide instructions how to fix the problems. </p>

<p>Once the information is verified, you can optionally (but we strongly suggest using it) use the orange <code>PREVIEW</code> button to see how your information will be displayed on the site. This opens in a layer atop the form, and you can dismiss the preview via the "X" in the uppper right or just clicking outside the preview.</p>

<p>And if you are satisfied with your information, it is time to click the green <code>SUBMIT</code> button to send it to this site. Your confirmation will include a link that you can use to see what you have submitted (unless the site is moderated, you may have to wait until your entry is approved).</p>

<p><strong>Note that once you submit a <?php echo $sub_type?>, you can no longer edit it; this is why we recommend previewing your entry carefully. </strong> If you do see something you have submitted that needs fixing, just send a comment in at the bottom of the item; this will go to the site's web manager who should be able to follow up on your request.</p>



