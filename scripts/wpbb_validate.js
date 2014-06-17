/* 
 * Uses jquery.validate to validate the hrh_cds page form
 */
//$.validator.unobtrusive.parse('#hrh_cds_form');
//$.metadata.setType("attr", "validate");
jQuery(document).ready(function($) {
// validate the comment form when it is submitted
    $("#wpbb_application_form").validate({ 
        rules: {
            wpbb_name: {
            	required:true
            },
            wpbb_email: {
				required:true
            },
            wpbb_tel: {
				required:true
            }
        }
    });
});