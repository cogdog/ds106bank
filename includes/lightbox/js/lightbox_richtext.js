function wp_tags( taglist ) {
		
		if (taglist === undefined) return '';
		
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
	
		maxWidth	: 0.85 * window.innerWidth,
		autoHeight	: true,
		autoSize	: false,
		fitToView	: false,
		closeClick	: false,
		openEffect  : 'fade',
		closeEffect : 'fade',
		scrolling   : 'yes',
		afterLoad   : function() {
		
			if ( $('#embedMedia').val() == '-1') {
				var myEmbed = '<img src="https://place-hold.it240x180" alt=""><br /><em>(place holder for media preview until you have saved once)</em>'; 
			} else {
				var myEmbed = decodeEntities( $('#embedMedia').val() );
			}
			
			// build example output
			var myExample = '<strong>Example for "' + $('#exampleTitle').val() + ' ' + $('#displaySource').val() +  '"</strong><br />';
			
			if ( $('#exampleURL').val() == "n/a") {
				myExample += 'n/a';
			} else {
				myExample += '<a href="' + $('#exampleURL').val() + '">' + $('#exampleURL').val() + '</a><br />' + myEmbed;
			}						
			
			if ( $('#displaySource').val() == '' ) {
				var mySource = '';
			} else {
				var mySource = ' (' + $('#displaySource').val() + ')';
			}


           // using visual editor?
			if ( $("#exampleDescriptionHTML-wrap").hasClass("tmce-active") ){
				wtext = $('#exampleDescriptionHTML_ifr').contents().find("html").html()

				
			// using HTML editor
			} else {
				wtext =  $('#exampleDescriptionHTML').val();
			}

				
			this.content = '<div class="col-sm-8"><h1 class="single-title assignment-header">' + $('#exampleTitle').val()  + mySource  + '</h1><p class="meta"><em>A response to the <a href="' + $('#assignmentURL').val()  + '">' + $('#assignmentTitle').val() + ' </a> ' +   $('#thingName').val() + '</em><br />Created <strong><time>' + moment().format('MMM D, YYYY') + '</time></strong> by <strong>' + $('#submitterName').val() + '</strong> ' +  $('#displayCredit').val()  + '<br />Number of views: <strong>' + Math.floor((Math.random() * 100) + 1)   + '</strong></p><p class="tags">Tags: ' + wp_tags( $('#exampleTags').val() ) + '</p><hr />' + wtext + '</div><div class="col-sm-4" id="examplemedia">' + myExample + '</div></div>';
			
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