jQuery(document).ready(function() { 
    jQuery('#assignmentSoMeURL').click(function () {
            jQuery('#assignmentURLfield').show(400); 
            jQuery('#uploadThumbfield').hide(400); 
    });
    
    jQuery('#assignmentOtherURL').click(function () {
            jQuery('#assignmentURLfield').show(400); 
            jQuery('#uploadThumbfield').show(400); 
    });
    
    jQuery('#assignmentNoURL').click(function () {
            jQuery('#assignmentURLfield').hide(400); 
            jQuery('#uploadThumbfield').show(400); 
    });
    
    // set defaults for assignment submission form
    jQuery('#assignmentURLfield').show();   
    jQuery('#uploadThumbfield').hide();

	// action for select of query options for assignment type
    jQuery('#taxlist').change(function() {
        this.form.submit();
    });
    
    jQuery('#assignmentList').change(function() {
        window.location = jQuery(this).val();
    });
});

