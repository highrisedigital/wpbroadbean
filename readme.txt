=== WP Broadbean ===
Contributors: highrisedigital, wpmarkuk
Tags: jobs, recruitment
Requires at least: 3.9
Requires PHP: 5.6
Tested up to: 5.0.3
Stable tag: 3.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WP Broadbean is a plugin allowing jobs added to Broadbean to show in your WordPress site.

== Description ==

[WP Broadbean](https://highrise.digital/products/wpbroadbean) is a plugin designed to work alongside the [Broadbean job posting and distribution](https://www.broadbean.com/uk/products/features/job-posting-distribution/) system allowing jobs written in Broadbean to show in your WordPress site. The plugin adds custom post types and taxonomies to allow you to add jobs. More importantly it provides an end-point to accept feed data sent by Broadbean to create jobs on your site.

The plugin requires some collaboration with the Broadbean integrations team. This is because they need to add your WordPress site as a posting destination, and to build a "feed" to your WordPress sites endpoint, provided by this plugin, in the form of XML data.

If you are struggling with any aspects of a site integration, we offer a complete integration service which you can take advantage of. Find out more about our this on our [WP Broadbean information page](https://highrise.digital/products/wpbroadbean).

Coming soon we have some add-ons for the plugin available for purchase. These will include:

* Job search
* Expiry of jobs according to the number of "days to advertise"
* Schema markup output for jobs, allowing services such as Google jobs to index and display jobs on your site
* Gutenberg blocks
* Shortcodes

For the sake of clarity, the WP Broadbean plugin is not affiliated in any way with Broadbean Technology Limited.

> IMPORTANT NOTICE BEFORE UPDATING: WP Broadbean version 3.0 is a complete rewrite of the plugin to make it more stable, secure and extensible. If you are running a version of the plugin prior to version 3.0, you should NOT update as the upgrade will likely break your Broadbean integration.

== Installation ==

To install the plugin:

1. Upload `wpbroadbean` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under WP Broadbean > Settings
4. Enter a username and password as well as choosing a page to use for your application form.
5. Request your integration with Broadbean, instructing them how to send the data to your site.

== Frequently Asked Questions ==

It is a new version, therefore we don't have any FAQs just yet. Feel free to [submit an issue](https://github.com/highrisedigital/wpbroadbean/issues) over on the Gitehub repository. We can then turn the popular questions into FAQs on the Wiki.

== Screenshots ==

1. The plugin settings screen

== Changelog ==

= 3.0 =
* IMPORTANT NOTICE BEOFRE UPDATING: WP Broadbean version 3.0 is a major overhaul of the plugin from earlier versions. With this in mind, this version is NOT backward compatible to earlier versions. This means if you are running a version earlier than version 3.0 already, it is crucial that you test the update on a staging or test site before updating to the latest version.
* Deprecated the theme inbox file version located at `wpbb/inbox.php` in the active theme and replaced with `wpbroadbean.php` in the root of the active theme.
* Deprecated the double settings arrays. Settings should now be registered against the `wpbb_plugin_settings` filter.
* Adds options footer credit with a link back to plugin authors.
* No longer use the CMB Meta Box framework to provide the job fields on the job edit screen. This framework is no longer supported and therefore this has been removed and replaced with a native meta box solution.
* Newly configured way of storing applications, temporarily whilst notifications are sent and then removed for privacy and security reasons.
* Extensible application form where developers can now make changes to application fields.
* New endpoint URL which no longer uses a query string name and value, but an actual URL. The endpoint for jobs to be posted to is now `/wpbb/jobfeed/`.
* Application forms are now shown on job single page views rather than having a seperate page.
* Added a check for SSL. If you site is not running over SSL (https) a warning message is shown on the settings page.
* Allow users to display a credit to Highrise Digital below each single job post, should they wish the give something back to us!
* Allow the jobs post type to show in the REST API. This means that partial block editor (aka Gutenberg) support is provided. The edit screens now use the new block editor.

= 2.2.4 =
* Prep for the release of version 3.0 coming soon.

= 2.2.3 =
* IMPORTANT NOTE: to avoid any confusion with users understand whether applicant data is stored within WordPress, please visit the plugin settings page up update and review the settings.
* Removal of the deprecated call to the settings page screen option.
* Introduces new settings to allow users to prevent applications and their associated attachments being stored in WordPress. If set to remove they are removed once sent across to Broadbean.
* Make sure the `wpbb_after_application_form_processing` hook is the last thing to fire in the processing function. This could have prevented code after it from running if a function was running on this hook that died or exited. An example is a function which redirected users on application form completion.
* Adds a new filter for filtering the error message on the application form when no job reference is detected - `wpbb_application_form_messages`.
* Adds a new setting to allow a user to switch off the output of job data on a job post - meta and taxonomy output.

= 2.2.2 =
* Set applications post type to not have an archive. Even though the post type is set to not public (`'public' => false`) it would appear that in some setups the applications had a front-end page.
* Set the applications to draft post status by default
* Create a new filter named `wpbb_insert_application_args` to allow devs to change the args used when creating the application post.

= 2.2.1 =
* Allow jobs to be edited once they are sent through from Broadbean. If a job is sent with the same reference as an existing job in WordPress, the job will be updated rather than a new job being created. If you don't not want this functionality you can add the `add_filter( 'wpbb_allow_job_updates', '__return_false' );` to either your themes `functions.php` file or a plugin / mu-plugin.
* More strings are now translatable, particularly in the applications form and associated messages.

= 2.2 =
* Fix Duplicate Terms Showing. After WP 4.7.5 was released the `args` param in the `register_taxonomy` was causing the terms to be outputted twice. This was because of a change in the `wp_get_object_terms` function released with WP 4.7.5. Removing this param that is not needed fixes the problem.

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
Update through the WordPress admin as notified but always be sure to make a site backup before upgrading and better still, test on a staging or test site first.