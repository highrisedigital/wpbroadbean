/* 
 * Uses jquery.validate to validate the page form
 */
jQuery(document).ready(
	
	function($) {

		// validate the application form when it is submitted
	    $("#wpbb-application-form").validate({ 
	        rules: {
	            wpbb_name: {
	            	required:true
	            },
	            wpbb_email: {
					required:true
	            },
	            wpbb_message: {
	            	required:true
	            },
	            wpbb_file: {
					required:true
	            }
	        },
	        messages: {
		    	 wpbb_name: "Please add your name - this is a required field",
		    	 wpbb_email: "Please enter a valid email address.",
		    	 wpbb_message: "Please enter a message to go with your application."
		    }
	    });
   
	}
);