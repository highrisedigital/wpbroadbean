=== Plugin Name ===
Contributors: wpmarkuk
Donate link: http://markwilkinson.me/saythanks
Tags: jobs, recruitment
Requires at least: 3.9
Tested up to: 3.9.1
Stable tag: 0.6
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