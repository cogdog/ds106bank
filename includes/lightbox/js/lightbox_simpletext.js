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



			if ( $('#displayCredit').val() ) {
				var myCredit = ' (' + $('#displaySource').val() + ')';
			} else {
				var myCredit = '';
			}
		
			this.content = '<h1 class="single-title assignment-header">Resource for this ' +   $('#thingName').val()  + '</h1><ol><li><a href="' + $('#exampleURL').val() + '" onclick="return false;">' + $('#exampleTitle').val() + '</a>'   + myCredit + '<br /><span class="user_credit"> by <strong>' + $('#submitterName').val() + '</strong> ' + $('#displayCredit').val() + '</span><br />' + $('#exampleDescription').val() + '</li></ol>';
								
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