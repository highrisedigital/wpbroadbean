=== Plugin Name ===
Contributors: wpmarkuk
Donate link: http://markwilkinson.me/saythanks
Tags: jobs, recruitment
Requires at least: 3.9
Tested up to: 4.1
Stable tag: 2.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Broadbean is a plugin allowing jobs added to Broadbean to show in your WordPress site.

== Description ==

*If you are running a version of WP Broadbean lesson than version 2.0 please DO NOT UPDATE.*

[WP Broadbean](http://wpbroadbean.com/ "Broadbean posted jobs on your WordPress website") is a plugin designed to work alongside the Broadbean Adcourier job posting system allowing jobs added to Broadbean to show in your WordPress site. The plugin adds custom post types and taxonomies to allow you to add jobs. More importantly it provides an end-point to accept feed data sent by Broadbean and add this as job posts to your site.

You can find out more about the WP Broadbean plugin on the [WP Broadbean website](http://wpbroadbean.com/ "Broadbean posted jobs on your WordPress website").

We also offer a service to complete a Broadbean integration with WordPress for you. Find out more about our [WPBB Assist service here](http://wpbroadbean.com/assist/).

[youtube http://www.youtube.com/watch?v=CAkV09vl6UI]

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

**How do I submit my feed for development by Broadbean?**

Once you have completed the above the Broadbean team will probably direct you to the following page:

[http://api.adcourier.com/docs/index.cgi?page=jobboards_register](http://api.adcourier.com/docs/index.cgi?page=jobboards_register)

Here you are supplied with a form to complete. Follow the instructions here in order to complete this:

[https://github.com/wpmark/wpbroadbean/wiki/Job-Board-Registration](https://github.com/wpmark/wpbroadbean/wiki/Job-Board-Registration)

**Do Broadbean charge for this?**

Probably yes! Contact your Broadbean account manager in order to get a price for developing a feed to your WordPress website.

**What is the URL to which Broadbean should post the feed too?**

The URL to post to, in order to add a job is http://domain.com/?wpbb=broadbean (of course replacing domain.com with your actual domain). The end-point expects an XML feed using the standard Broadbean XML feed.

**Can I customised the how the feed is added to WordPress?**

Yes you can. The plugin is built with extensibility in mind and therefore you can make many changes and edits without altering the plugin code itself. This will protect you when the plugin is updated. The plugin handles the incoming feed in the `inbox.php` file in the plugin root folder. If you copy this file to your active theme and place it in a folder named `wpbb` this will be used instead of the plugins version. Therefore you can make amends and change how the jobs are added when posted from Broadbean.

**What if I want different meta data and taxonomies that the standard ones, can the plugin handle that?**

Broadbean can built you a completely bespoke feed to your site, with practically any date you want about each job you post. The WP Broadbean Plugin can handle this through its extensibility features. You can add fields and taxonomies as well as remove the default ones should you wish too. The plugin was built with extensibility in mind with a number of actions and filters available to developers.

Take a look at the [Wiki on Github](https://github.com/wpmark/wpbroadbean/wiki/) in order to find some examples of the things you can do.

== Screenshots ==

1. Job listings in the WordPress admin
2. Single job edit post screen

== Changelog ==

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