<?php 
$use_full_editor = ds106bank_option('link_examples');
$use_twitter_name = ds106bank_option( 'use_twitter_name' );
?>

<h2>Title (Fascinating, eh?)</h2>
<p><em>You had me at title...</em><br />
Hopefully this is obvious, but we'd like to encourage you to think about being creative with your choice of title for your response. It will be what catches someone's eye who might be looking at a long list of responses. It should be specific to the response you are writing and not just echo the name of the particular <?php echo THINGNAME?> you are responding too.</p>
<p>

<?php if ( ds106bank_option('link_examples') ) :?>

<h2>Got The Link?</h2>
<p><em>It's not a thing unless it's got a link!</em><br />
The whole idea here is to link to something you have published elsewhere on the web; maybe on your blog, maybe a social media site, maybe as a document. The link must be something people can see without any special logins (and this excludes Facebook unless you are 10000% sure the link is public to more than your "friends").</p>


<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-link1.jpg" alt="" style="border:3px solid #000;" /></p>

<p>Make sure the links works! Click the orange <code>TEST</code> button to see if the link works (it opens in a new browser window).</p>


<h2>Brief Description</h2>
<p><em>Just like a blurb for a book or a movie</em><br />
Write a summary for the link being as descriptive as possible; this is the text that will be displayed below your title. You can only use plain text (HTML will be hosed off and removed) and the counter below the field will give you an idea how many words you have left to use.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-description1.jpg" alt="" style="border:3px solid #000;" /></p>


<?php else:?>



<h2>Using The Rich text Editor</h2>
<p>Hopefully most of the buttons are self-explanatory; this is a document editor not unlike Google Docs and exactly like a typical wordpress blog editor.  If you need a reference for the button functions, <a href="https://make.wordpress.org/support/user-manual/content/editors/visual-editor/" target="_blank">see the visual editor documentation</a> (this link opens in a new window).</p>

<p>You should be able to past in rich formatted content form any web page or a Word document. Most basic structural elements will be preserved such as headings, lists, bold, italic, hypertext links. Most other specific text formatting will be stripped clean. You may have to clean up some extraneous white space.</p>

<p>The editor has an additional set of editing tools that you may have to toggle open using the button second from the end on the right:</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-wp-edit-bar.gif" alt="" style="border:3px solid #000;" /></p>



<h2>Using Media</h2>
<p>The image button does <strong>not</strong> allow you to upload images to the site, it lets you use the URL for an image that exists elsewhere on the internet. It should be a link to a JPG, PNG, or GIF file only.</p>

<p>You can also easily embed media from other  sites into your work simply by placing the web address for the media on a line by itself (do not hyperlink the URL). This site uses an auto embed feature to manage media. This supports media from sites such as YouTube, vimeo, flickr, Instagram, Soundcloud, Twitter, Animoto and many more (<a href="https://wordpress.org/search/embed" target="_blank">see the full list</a>, link opens in new window)</p>

<p>You will see the embedded media in the editor once you click the blue <code>UPDATE</code> button to check your entry.

<p>For example, if we want to include a YouTube video writer Ian McKewan's advice for writers, we paste it's URL on a line by itself:</p>

<pre>https://www.youtube.com/watch?v=Kyfe6DljGPY</pre>

<p>which will produce in the Writer interface, the embedded video:</p>

<?php echo wp_oembed_get('https://www.youtube.com/watch?v=Kyfe6DljGPY');?>

<p>If we want to use <a href="https://www.flickr.com/photos/8411190@N04/6036682072/" target="_blank"> a flickr image of a smiley icon on a piece of paper</a>, we put it's URL on a blank line:</p>

<pre>https://www.flickr.com/photos/8411190@N04/6036682072/</pre>

<p>and in your entry it will appear as:</p>

<?php echo wp_oembed_get('https://www.flickr.com/photos/8411190@N04/6036682072/');?>

<p>Tweets work well to for auto embed, and will display with any media they contain. You need to find the URL for a single tweet (usually linked from its timestamp), e,g.

<pre>https://twitter.com/ronald_2008/status/630150299123064832</pre>

<?php echo wp_oembed_get('https://twitter.com/ronald_2008/status/630150299123064832');?>


<p>If you work with audio, the best place to store it online is <a href="http://soundcloud.com" target="_blank">SoundCloud</a> (you can create accounts for free). Links to single SoundCloud track can be embedded by putting in the editor:</p>

<pre>https://soundcloud.com/cogdogroo/arizona-rain-sounds</pre>

<?php echo wp_oembed_get('https://soundcloud.com/cogdogroo/arizona-rain-sounds');?>


<?php endif?>


<h2>Author Information</h2>
<p><em>Give credit as credit wants to be given...</em><br />
Write in the name field your name, or pseudonym for how you wish to be credited for this response. Or include the name of others as collaborators, separated by commas.</p>

<p>We also ask for one email address; this information is <strong>never</strong> publicly displayed, and is used only if we have a question to ask you about your response.</p>

<?php if ($use_twitter_name) echo '<p>Add a twitter name to let this site be able to track all of the work you submitted; if you prefer not to use a twitter account, at least create a unique name fo yourself that begins with a "@". Only enter one twitter name as a primary reference here; if you wish to credit others, enter these twitter names as tags (see below)';?>

<h2>Tags</h2>
<p><em>Tag your response with other descriptive information</em><br />
Tags are optional but offer another way to associate common work. You may be instructed to use a tag to identify work for a particilaur course, subject, or you may find it useful to create a set of tags that associate different collections of your own work. </p>

<p>Separate all your tags with either a space or a comma- this means that tags must be a single word.</p>

<?php if ($use_twitter_name) echo '<p>You can also add additional twitter names to credit as co-authors by including them as tags (be sure to use the "@" sign)';?>

<h2>Update, Preview, Submit</h2>
<p><em>Polish this thing up and send it on it...</em><br />
The three buttons in the bottom are the steps to get your response on this site.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-ex-submit-buttons.jpg" alt="" style="border:3px solid #000;" /></p>

<p> You first need to click the blue <code>UPDATE</code> button so the information can be verified. If we find any mistakes, we will provide instructions how to fix the problems. </p>

<p>Once the information is verified, you can optionally (but we strongly suggest using it) use the orange <code>PREVIEW</code> button to see how your information will be displayed on the site. This opens in a layer atop the form, and you can dismiss the preview via the "X" in the uppper right or just clicking outside the preview.</p>

<p>And if you are satisfied with your information, it is time to click the green <code>SUBMIT</code> button to send it to this site. Your confirmation will include a link that you can use to see what you have submitted (unless the site is moderated, you may have to wait until your entry is approved).</p>

<p><strong>Note that once you submit a response, you can no longer edit it; this is why we recommend previewing your entry carefully. </strong> If you do see something you have submitted that needs fixing, just send a comment in at the bottom of the item; this will go to the site's web manager who should be able to follow up on your request.</p>




