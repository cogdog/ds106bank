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
	
	
	
	jQuery('#uploadFile').change(function () {
	
		let file_size_MB = (this.files[0].size / 1000000.0).toFixed(2);
	
		// check file size first
		if ( file_size_MB >  parseFloat(bankObject.uploadMax)) { 
            alert('Error: The size of your selected file, ' + file_size_MB + ' Mb, is greater than the maximum allowed for this site (' + bankObject.uploadMax + ' Mb). Try a different file or see if you can shrink the size of this one.');
            jQuery('#uploadFile').val("");
		} else { 
			// update a response span
			jQuery("#uploadresponse").html('Click <strong>Update</strong> below to upload this file (' + file_size_MB + ' Mb).  When done, a web address for it will be inserted into the location field above.'); 
		} 
	
		
		
	});
	
	
});
