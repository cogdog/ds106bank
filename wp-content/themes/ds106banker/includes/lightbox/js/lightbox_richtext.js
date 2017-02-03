function wp_tags( taglist ) {
	var mystr = '';
	var tagarray = taglist.split(',');
		
	for (i = 0; i < tagarray.length; i++) { 
		mystr += '<a href="#" rel="tag" class="label" onclick="return false;">' + tagarray[i] + '</a>, ';
    }
    return (mystr.substr(0, mystr.length-2)); 
}

function decodeEntities(input) {
  var y = document.createElement('textarea');
  y.innerHTML = input;
  return y.value;
}

function nl2br (str, is_xhtml) {
	// h/t http://stackoverflow.com/a/7467863/2418186
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}




(function($) {
 
    // Initialize the Lightbox for any links with the 'fancybox' class
	$(".fancybox").fancybox({
	
		fitToView	: false,
		width		: '90%',
		height		: '90%',
		autoSize	: false,
		closeClick	: false,
		openEffect  : 'fade',
		closeEffect : 'fade',
		scrolling   : 'yes',
		afterLoad   : function() {
		
			if ( $('#embedMedia').val() == '-1') {
				var myEmbed = '<img src="http://placehold.it/240x180" alt=""><br /><em>(place holder for media preview until you have saved once)</em>'; 
			} else {
				var myEmbed = decodeEntities( $('#embedMedia').val() );
			}
			
			if ( $('#exampleURL').val() == "#") {
				var myExample = '';
			} else {
				var myExample = '<strong>Example for "' + $('#exampleTitle').val() + '"</strong><br /><a href="' + $('#exampleURL').val() + '">' + $('#exampleURL').val() + '</a><br />' + myEmbed;
			}
			
			this.content = '<div class="col-sm-8"><h1 class="single-title assignment-header">' + $('#exampleTitle').val() + '</h1><p class="meta">A response to the <a href="' + $('#assignmentURL').val()  + '">' + $('#assignmentTitle').val() + ' </a> ' +   $('#thingName').val() + '<br />Created <strong><time>' + moment().format('MMM D, YYYY') + '</time></strong> by <strong>' + $('#submitterName').val() + '</strong><br />Number of views: <strong>0</strong></p><p class="tags">Tags ' + wp_tags( $('#exampleTags').val() + ',' + $('#submitterTwitter').val()) + '</p><hr />' + nl2br( $('#exampleDescriptionHTML').val()) + '</div><div class="col-sm-4" id="examplemedia">' + myExample + '</div></div>';
			
			$('#submitexample').removeClass( "disabled" );
		},
		helpers : {
			title: {
				type: 'outside',
				position: 'top'
			}
    	},
	}); 
	
})(jQuery);