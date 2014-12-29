=== Plugin Name ===
Contributors: wpmarkuk
Donate link: http://markwilkinson.me/saythanks
Tags: jobs, recruitment
Requires at least: 3.9
Tested up to: 4.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

** Beta Plugin - it works but is rusty! **
WP Broadbean is a plugin allowing jobs added to Broadbean to show in your WordPress site.

== Description ==

WP Broadbean is a plugin designed to work alongside the Broadbean Adcourier job post system allowing jobs added to Broadbean to show in your WordPress site. The plugin adds custom post types and taxonomies to allow you to enter jobs into your WordPress site. More importantly it provides an end-point to accept feed data sent by Broadbean and add this as job posts to your site.

== Installation ==

To install the plugin:

1. Upload `wpbroadbean` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under WP Broadbean > Settings
4. Enter a username and password as well as choosing a page to use for your application form.
5. Add the application form short code to your apply page
6. Complete your [Broadbean feed request here](http://api.adcourier.com/docs/index.cgi?page=jobboards_register)

== Frequently Asked Questions ==

Do Broadbean charge for this?

Yes they do. When you choose to include your own site in a multi job posting Broadbean have the setup a "feed" to your site and they charge a one off fee for this.

== Screenshots ==

1. The WP Broadbean screen, added to get you started.

== Changelog ==

= 2.0 =
* Added a filter to allow developers to use a different custom post type for jobs
* WP Broadbean sub menus are added using a filter so developers can add their own menus more easily
* Created a template function to get the value of a job field from post meta
* Fields are now added using a filter. This allows developers to easily add other fields and have the data processed in the feed as well as the WP backend.
* The processing of the feed is now more dynamic to tie in with other registered taxonomies and fields.

= 1.0.2 =
* Set skills taxonomy to hierarchical

= 1.0.1 =
* Correct name for the job locations taxonomy - showed an incorrect label in the taxonomy metabox

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
Update through the WordPress admin as notified.