/**
 *  Main javascript file. This will handle table control column
 */


// IIFE - Immediately Invoked Function Expression
(function($, window, document) {

    // The $ is now locally scoped 

// Listen for the jQuery ready event on the document
$(function() {
	
	var monetaryFmt = $(".monetary-fmt");	

	// for each occurence, convert to IDR format. By using
	// Germany as locale, we could get the same result
	monetaryFmt.each( function() {
		var number = parseInt($(this).text());
		// workaround for negative number
		if (number < 0) {
			number = number * -1;
			number = $.formatNumber(number, {format:"Rp #,###.00", locale:"de"});
			number = '('+number+')';
		} else {
			number = $.formatNumber(number, {format:"Rp #,###.00", locale:"de"});
		}
		$(this).text(number);
	});



    
});

// The rest of the code goes here!

}(window.jQuery, window, document));
// The global jQuery object is passed as a parameter



