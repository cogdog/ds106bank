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
	
	
	jQuery('#assignmentImage').change(function () {
	
		// get file size 
		let file_size_MB = (this.files[0].size / 1000000.0).toFixed(2);

		// check file size first
		if ( file_size_MB >  parseFloat(bankObject.uploadMax)) { 
			alert('Error: The size of your image file, ' + file_size_MB + ' Mb, is greater than the maximum allowed for this site (' + bankObject.uploadMax + ' Mb). Try a different file or see if you can shrink the size of this one.');
			jQuery('#assignmentImage').val("");
		} else { 
			// update a response span
			jQuery("#uploadresponse").html('Click <strong>Update</strong> below to upload this file (' + file_size_MB + ' Mb).'); 
		} 

	
		if (this.value) {	
			// generate a preview
			// h/t https://codepen.io/waqasy/pen/rkuJf
			if (this.files && this.files[0]) {
				var freader = new FileReader();

				freader.onload = function (e) {
					jQuery('#thingthumb').attr('src', e.target.result);
				};

				freader.readAsDataURL(this.files[0]);

			} else {
				  reset_defthumb();
			}
			
		} else {
			reset_defthumb();
		}
	});
	
	
	function reset_defthumb() {
		//reset thumbnail preview
		jQuery('#thingthumb').attr('src', bankObject.default_thumb);
	}
	
});
