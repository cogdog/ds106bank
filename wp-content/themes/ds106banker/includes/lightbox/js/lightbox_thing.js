function wp_tags( taglist ) {
	var mystr = '';
	
	if (taglist === undefined) return '';
	
	var tagarray = taglist.split(',');
		
	for (i = 0; i < tagarray.length; i++) { 
		mystr += '<a href="#" rel="tag" class="label" onclick="return false;">' + tagarray[i] + '</a>, ';
    }
    return (mystr.substr(0, mystr.length-2)); 
}

function capitalizeEachWord(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

function decodeEntities(input) {
  var y = document.createElement('textarea');
  y.innerHTML = input;
  return y.value;
}



(function($) {
 
    // Initialize the Lightbox for any links with the 'fancybox' class
	$(".fancybox").fancybox({

        maxWidth        : 1024,
        maxHeight       : 780,	
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
			
			if ( $('#embedRating').val() == '-1') {
				var myRatings = '';
			} else {
				var myRatings = decodeEntities( $('#embedRating').val() );
			}
						
			var adiff = $('input[name=assignmentDifficulty]:checked', '#bank106form').val();
			
			if ( adiff === undefined) {
				adiff = '';
			} else {
				adiff = 'Difficulty: <strong>' + adiff + '</strong> (<strong>1</strong> = very easy, <strong>5</strong> = very difficult)';
			}

			if ( $('#assignmentCategories').val() == '') {
				catd = ' ';
			} else {
				catd = ', ';
			}

			
			if ( $('#assignmentTags').val() == '') {
				tagd = ' ';
			} else {
				tagd = ', ';
			}
			
			var thingtypes = [];
			
           $("input[name='assignmentType[]']:checked").each(function() {            
                ts = $(this).val().replace(/-/g, " ");
                thingtypes.push(capitalizeEachWord(ts));
            });
            
            var thingcats = [];
            
           $("input[name='assignmentCategories[]']:checked").each(function() {            
                ts = $(this).val().replace(/-/g, " ");
                thingcats.push(capitalizeEachWord(ts));
            });
          
            
			
			this.content = '<div class="col-sm-3"><div class="thing-icon-single"><img src="' + $('#thingthumb').attr('src')  + '"></div></div><div class="col-sm-8" ><h1 class="single-title assignment-header">' + $('#assignmentTitle').val() + '</h1>' + myRatings + '<br />' + adiff + '<br />Created <strong><time>' + moment().format('MMM D, YYYY') + '</time></strong> by <strong>' + $('#submitterName').val() + '</strong><br />Number of views: <strong>0</strong></p><p>Type: ' + thingtypes.join(", ") + '<br />Categories: ' + thingcats.join(", ") + '<br/><span class="tags">Tags: ' + wp_tags( $('#assignmentTags').val() + tagd + $('#submitterTwitter').val()) + '</span></p></div>	<div class="col-sm-8 clearfix">' + $('#assignmentDescriptionHTML').val() +  '</div><div class="col-md-4" id="examplemedia"><strong>Example for "' + $('#assignmentTitle').val() + '"</strong><br /><a href="' + $('#assignmentURL').val() + '">' + $('#assignmentURL').val() + '</a><br />' + myEmbed + '</div>';
			
			$('#submitassignment').removeClass( "disabled" );
		},
		helpers : {
			title: {
				type: 'outside',
				position: 'top'
			}
    	},
	}); 
	
})(jQuery);