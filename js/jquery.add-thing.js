jQuery(document).ready(function() { 
	
	jQuery('#assignmentURL').mouseleave(function() { 
		var myval = jQuery(this).val();
		jQuery('#testURL').attr("href", myval );
	});

	jQuery('#testURL').click(function (e) {
		if (! jQuery(this).attr('href') ) {
			alert('Please enter a full URL to test!');
			e.preventDefault();
		}
	});
	
});
