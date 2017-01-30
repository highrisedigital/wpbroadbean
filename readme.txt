=== Plugin Name ===
Contributors: wpmarkuk, highrisedigital
Donate link: http://markwilkinson.me/saythanks
Tags: jobs, recruitment
Requires at least: 3.9
Tested up to: 4.7.2
Stable tag: 2.1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Broadbean is a plugin allowing jobs added to Broadbean to show in your WordPress site.

== Description ==

[WP Broadbean](http://wpbroadbean.com/ "Broadbean posted jobs on your WordPress website") is a plugin designed to work alongside the Broadbean Adcourier job posting system allowing jobs added to Broadbean to show in your WordPress site. The plugin adds custom post types and taxonomies to allow you to add jobs. More importantly it provides an end-point to accept feed data sent by Broadbean and add this as job posts to your site.

You can find out more about the WP Broadbean plugin on the [WP Broadbean website](http://wpbroadbean.com/ "Broadbean posted jobs on your WordPress website").

We also offer a service to complete a Broadbean integration with WordPress for you. Find out more about our [WPBB Assist service here](http://wpbroadbean.com/assist/).

>If you are running a version of WP Broadbean less than version 2.0 please DO NOT UPDATE.

== Installation ==

To install the plugin:

1. Upload `wpbroadbean` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under WP Broadbean > Settings
4. Enter a username and password as well as choosing a page to use for your application form.
5. View the how to get start question in the FAQs

== Frequently Asked Questions ==

**How do I get started?**

Your first task is to contact Broadbean and let them know that you want to create a new feed from your Broadbean Adcourier account to your WordPress website. It is also important to tell them that your integration will be using the WP Broadbean Plugin by Mark Wilkinson. You will need to indicate that you require the standard feed to be setup for this plugin without any changes being made.

**Do Broadbean charge for this?**

Probably yes! Contact your Broadbean account manager in order to get a price for developing a feed to your WordPress website.

**What is the URL to which Broadbean should post the feed too?**

The URL to post to, in order to add a job is http://domain.com/?wpbb=broadbean (of course replacing domain.com with your actual domain). The end-point expects an XML feed using the standard Broadbean XML feed.

**Can I customise the how the feed is added to WordPress?**

Yes you can. The plugin is built with extensibility in mind and therefore you can make many changes and edits without altering the plugin code itself. This will protect you when the plugin is updated. The plugin handles the incoming feed in the `inbox.php` file in the plugin root folder. If you copy this file to your active theme and place it in a folder named `wpbb` this will be used instead of the plugins version. Therefore you can make amends and change how the jobs are added when posted from Broadbean.

**What if I want different meta data and taxonomies than the standard ones, can the plugin handle that?**

Broadbean can build you a completely bespoke feed to your site, with practically any date you want about each job you post. The WP Broadbean Plugin can handle this through its extensibility features. You can add fields and taxonomies as well as remove the default ones should you wish too. The plugin was built with extensibility in mind with a number of actions and filters available to developers. Take a look at the source code for `do_action` and `apply_filters`.

== Screenshots ==

1. Job listings in the WordPress admin
2. Single job edit post screen

== Changelog ==

= 2.1.9 =
* Remove attachment link from the application email - dead link since 2.1.6
* Remove the application attachments meta field
* Make all application form labels translatable
* Output the title of the job being applied for above the application form

= 2.1.8 =
* Add support for PHP7 so that taxonomies are added correctly when WordPress is running on PHP7. Thanks for @bencorke and @pixelanddot for assisting in this fix.

= 2.1.7 =
* Allow application uploads to work on multisite - thanks to @sijones-uk for contribution here
* Add support for the job short description saved as the post excerpt

= 2.1.6 =
* Delete attachments from application form once emailed to broadbean. This is an important update for clients who do not want to store CVs on there website.

= 2.1.5 =
* Correct an wrong filter name to filter the application email attachments
* Moved the job added action hook outside of the job fields loop in the inbox. This was wrongly being fired after each field was added.
* Sanitize a call to the post ID using $_GET within the Applications posts when listing the CV attachment
* When outputting the application form, check whether an application form has been set in the settings screen before filtering the content. Thanks for [@jonnyauk](https://profiles.wordpress.org/jonnyauk) for spotting this issue!

= 2.1.4 =
* Make the messages output by the application form filterable by developers using the filter 'wpbb_application_form_messages'.
* Correct a typo in the application form success message.

= 2.1.3 =
* Correct a bug introduced in 2.1.2 which prevented application emails from sending.
* Add filters to allowing developers to filter the application email headers, subject etc.
* Sanitize the $_GET requests in the application form
* Use function for setting the email content type.
* Add a filter to allow the insert job post argumnets pasted to wp_insert_post to be filterable.

= 2.1.2 =
* Sanitize all posted data from the application form.
* Add in a hook (wpbb_after_application_form_processing) which fires after the application form has been processed. This could be used for example to redirect users to a different page on the site, like a thank you page.
* Prevent an application from being saved and processed if the CV file type does not match those allowed.
* Make all the fields in the application form mandatory or required. The JS validate will prevent form submission unless all fields are completed.

= 2.1.1 =
* Include the application message in the email sent back to the application tracking email.

= 2.1 =
* In strange circumstances if a job was added where there was a taxonomy term which was an empty string it caused an WP_Error and the job was not posted correctly. This has now be fixed but checking for an empty string before creating a new term. Thanks to Julian Taylor for assisting with this bug.

= 2.0.9 =
* Prevent an error when trying to add a term with an empty string as the term name. It essentially allows you to have blank terms when sent. Thanks to Susie Black for input on this issue.
* Force XML value in added fields to be a string
* Correct typo on the job post edit screen in meta boxes

= 2.0.8 =
* Application form php warnings with WP_DEBUG on are not removed on the application form page
* Adds a message input to the application form so that applicants can add notes to their applications
* Enables additional file types to be uploaded other than PDFs through the addition of filterable allowed file types on the application form

= 2.0.7 =
* Addition of a action hook with fires after a job has been added by the plugin. This allows for developer to trigger actions once a job is added such as clearing search cache in searching plugins.
* Only send the application for the contact email and tracking email if they have an email applied.

= 2.0.6 =
* Applications where CV uploads had spaces in their names where failing to attach to the Broadbean email and the link in the admin led to a 404 error. This has now been corrected and all should be fixed!

= 2.0.5 =
* Correct declaration of constant to remove debug warnings.
* Add additional filters to allow developers to changes the names of the admin menus added by the plugin.
* Additional filters added which allows developers to change the page titles in the admin area.
* Functions for retrieving the password and username
* Some simple functions docs updates

= 2.0.4 =
* Add columns on the settings screen with call to action boxes for WP Broadbean Assist
* Redirect to the settings screen on plugin activation
* Change the way application forms are saved to prevent conflicts with other plugins
* Add actions which fire after job terms and meta are added to a job when sent from Broadbean

= 2.0.3 =
* Correct an issue where an incorrect taxonomy term could be added to a job. This was because the term could have belonged to another taxonomy. This fix force it to look for only wpbb taxonomies when adding terms to jobs posted through Broadbean.

= 2.0.2 =
* Corrected an issue where jobs could not be deleted via broadbean

= 2.0.1 =
* Corrected an issue where username and password failed to authenticate

= 2.0 =
* Removed the beta status!
* Added a filter to allow developers to use a different custom post type for jobs
* WP Broadbean sub menus are added using a filter so developers can add their own menus more easily
* Created a template function to get the value of a job field from post meta
* Fields are now added using a filter. This allows developers to easily add other fields and have the data processed in the feed as well as the WP backend.
* The processing of the feed is now more dynamic to tie in with other registered taxonomies and fields.
* Amended the way in which the application form is rendered (no shortcode) and processed. Also slimmed the number of fields on this form.
* Documented most functions etc. in the plugin code itself as well as cleaning up the file structure a little.
* Allow both hierarchical and non hierarchical taxonomies to be registered for use with jobs
* Output fields and taxonomies on the front end - using the_content filter. These get added below the post content. Control of which taxonomies and fields are shown on the front-end is controlled when they are registered.
* Updated the readme.txt file.

= 1.0.2 =
* Set skills taxonomy to hierarchical

= 1.0.1 =
* Correct name for the job locations taxonomy - showed an incorrect label in the taxonomy meta box

= 1.0 =
* Thanks to @getdave for contributions to v1.0
* Ensure taxonomies (and all assoc data and admin menus) are created dynamically rather than hard coded
* Ability to add custom taxonomies via hooks/filters
* Add new Broadbean default taxonomy "Industry"
* Ensure new taxonomy terms are created if they don't exist. Ensures client can dynamically created new terms without having to manually create them in WP first
* Ensure job days_to_advertise field is used to calculate an expiry date which is stored as post meta. Developers can then use this to ensure "expired" jobs are not included in search results

= 0.8 =
* Add the select2 js library for select input in the metaboxes

= 0.7 =
* Corrected a type in the email header function that resulted in a semi-colon appearing before the email content.

= 0.6 =
* Added the ability to use WYSIWYG when adding your own settings to the setting page
* Removed the post type support filters as post type support can be added with add_post_type_support()

= 0.5 =
* Corrected issue where using an inbox.php file from the theme folder would not work.

= 0.4 =
* Added filters for meta box fields in applications and job post types. This allows devs to be able to add to or remove existing fields from a metabox.

= 0.3 =
* Removed the admin stylesheet - use dashicons for the admin menu icon
* Removed filterable post type labels, not needed as core provides this functionality
* Add additional filters for post title and post editor content
* General bug fixes and code comment updates

= 0.2 =
* Minor bug fixes

= 0.1 =
* Initial Beta Release

== Upgrade Notice ==
Update through the WordPress admin as notified always be sure to make a site backup before upgrading.