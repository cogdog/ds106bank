jQuery(document).ready(function() { 
	
	jQuery('#exampleURL').mouseleave(function() { 
		var myval = jQuery(this).val();
		jQuery('#testURL').attr("href", myval );
	});

	jQuery('#testURL').click(function (e) {
		if (! jQuery(this).attr('href') ) {
			alert('Please enter a full URL to test!');
			e.preventDefault();
		}
	});

	jQuery('#exampleDescription').keyup(function() {
		var myval = jQuery(this).val();
		
		if (myval.length == 0) {
			wordCount = 0;
		} else {
			var regex = /\s+/gi;
    		var wordCount = myval.trim().replace(regex, ' ').split(' ').length;
		}
		
		jQuery('#wCount').text(wordCount);
	});
});
