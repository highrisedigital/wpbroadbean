/* 
 * Uses jquery.validate to validate the page form
 */
jQuery(document).ready(
	function($) {
		// validate the application form when it is submitted
	    $("#wpbb-application-form").validate({
	    	errorClass: "wpbb-field-error",
	    	errorPlacement: function( error, element ) {
				error.appendTo( element.parent().parent() );
			}
	    });
	}
);