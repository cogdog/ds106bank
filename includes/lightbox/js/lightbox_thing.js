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


function replaceURLWithHTMLLinks(text) {
	// h/t http://stackoverflow.com/a/19548526/2418186
    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(exp,"<a href='$1'>$1</a>"); 
}

function nl2br (str, is_xhtml) {
	// h/t http://stackoverflow.com/a/7467863/2418186
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}


(function($) {
 
    // Initialize the Lightbox for any links with the 'fancybox' class
	$(".fancybox").fancybox({

		maxWidth	: 0.95 * window.innerWidth,
		autoHeight	: true,
		autoSize	: false,
		fitToView	: false,
		closeClick	: false,
		openEffect  : 'fade',
		closeEffect : 'fade',
		scrolling   : 'yes',
		afterLoad   : function() {
		
			if ( $('#embedMedia').val() == '-1') {
				var myEmbed = '<img src="https://placehold.it/240x180" alt=""><br /><em>(place holder for media preview until you have saved once)</em>'; 
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
           		ts = $('label[for=' + $(this).val() + ']').text();          
                // ts = $(this).val().replace(/-/g, " ");
                thingcats.push(capitalizeEachWord(ts));
            });
          
          // start building output
           var  output = '<div class="row"><div class="col-sm-3"><div class="thing-icon-single"><img src="' + $('#thingthumb').attr('src')  + '"></div></div><div class="col-sm-8" ><h1 class="single-title assignment-header">' + $('#assignmentTitle').val() + '</h1>' + myRatings + '<br />' + adiff + '<br />Created <strong><time>' + moment().format('MMM D, YYYY') + '</time></strong> by <strong>' + $('#submitterName').val() + '</strong><br />Number of views: <strong>0</strong></p><p>' + $('#thing_type_hole').data( "typelabel" ) + ': ' + thingtypes.join(", ");
           
           // check if we are using categories
           
           if ( $('#thing_cat_hole').length )   output +=  '<br />' + $('#thing_cat_hole').data( "catlabel" )  + ': ' + thingcats.join(", ") 
           
            output += '<br/><span class="tags">Tags: ' + wp_tags( $('#assignmentTags').val());
           
           // add content from rich text editor
           
           // using visual editor?
			if ( $("#assignmentDescriptionHTML-wrap").hasClass("tmce-active") ){
				wtext = $('#assignmentDescriptionHTML_ifr').contents().find("html").html();	
			// using HTML editor
			} else {
				wtext =  $('#assignmentDescriptionHTML').val();
			}
           
           
            output += '</span></p></div></div>	<div class="row clearfix"> <div class="col-sm-8 wtext">' +  wtext;
            
            if ( $('#assignmentExtras').val() )  output += '<div class="col-sm-offset-1 col-sm-9"><div class="alert alert-info" role="alert">'  +  replaceURLWithHTMLLinks($('#assignmentExtras').val()) + '</div></div>';
           
			output += '</div><div class="col-sm-4" id="examplemedia"><strong>Example for "' + $('#assignmentTitle').val() + '"</strong><br /><a href="' + $('#assignmentURL').val() + '">' + $('#assignmentURL').val() + '</a><br />' + myEmbed + '</div></div>';

			this.content = output;
			
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