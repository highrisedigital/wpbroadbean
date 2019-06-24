=== WP Broadbean ===
Contributors: wpmarkuk, keithdevon, highrisedigital
Tags: jobs, recruitment
Requires at least: 5.1
Requires PHP: 5.6
Tested up to: 5.2
Stable tag: 3.0.4
Donate link: https://store.highrise.digital/downloads/wpbroadbean-support-docs/
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WP Broadbean is a plugin which allows jobs added to Broadbean to show in your WordPress site.

== Description ==

[WP Broadbean](https://highrise.digital/products/wpbroadbean-wordpress-plugin/) is a WordPress plugin designed to work alongside the [Broadbean job posting and distribution](https://www.broadbean.com/uk/products/features/job-posting-distribution/) system allowing jobs written in Broadbean to show in your WordPress site.

The plugin adds custom post types and taxonomies to allow you to add jobs. More importantly it allows your site to accept feed data sent by Broadbean to create jobs on your site.

The plugin requires some collaboration with the Broadbean integrations team. This is because they need to add your WordPress site as a posting destination, and to build a "feed" to your WordPress sites endpoint, provided by this plugin, in the form of XML data. They are likely to charge you for this service.

## Support and documentation

This plugin is provided as is, and community support is available via the support forums here. If you are looking for expert help in getting your site setup to post jobs from Broadbean, Highrise Digital offer a couple of solutions. We can provide [bespoke consultancy](https://highrise.digital/contact/), support and development however we also have a [support add-on package](https://store.highrise.digital/downloads/wpbroadbean-support-docs/). This add-on package provides all the information Broadbean need to build your job feed, access to our extensive plugin documentation resource and limited email support from the team at Highrise Digital.

## WP Broadbean add-ons

We also have some add-ons for this plugin which you can find out more about on the [WP Broadbean plugin page](https://highrise.digital/products/wpbroadbean-wordpress-plugin/). Current available add-ons are:

### [Support and documentation](https://store.highrise.digital/downloads/wpbroadbean-support-docs/)

This add-on not only provides you with access to limited email support, but it gives you full access to all the plugins [documentation](https://store.highrise.digital/docs/wpbroadbean/). This includes articles on how to setup and install the plugin, submit your feed build to the Broadbean integrations team, but also includes a plugin to add to your site which analyses your install and provides a readme file which Broadbean can used to help gether the required information to build your sites job feed.

### [Shortcodes](https://store.highrise.digital/downloads/wp-broadbean-shortcodes/)

The shortcodes add-on provides a flexible shortcode to allow you to add a list of jobs anywhere you site supports shortcode output. This is great for showing latest jobs on any page, post or sidebar area. It also includes an ajax style load more option, give you the ability to include you full job listing page on any page of your website. The plugin also includes the ability to use different templates for your shortcodes, so the job output can be different in different places on your website.

### [Auto-expire jobs](https://store.highrise.digital/downloads/wp-broadbean-auto-expire-jobs/)

This add-on takes the number of days set for jobs to expire when they are posted and then calculates an expiry date. The plugin then deletes the job on that date. This works for any jobs added to your site after this add-in is installed.

### Search (coming soon!)

The ability to add a granular job search to your website either as a widget or in a template file using a shortcode.

All the add-ons above are for WP Broadbean version 3.0 or higher, unless otherwise stated.

For the sake of clarity, the WP Broadbean plugin is not affiliated in any way with Broadbean Technology Limited.

> IMPORTANT NOTICE BEFORE UPDATING: WP Broadbean version 3.0 is a complete rewrite of the plugin to make it more stable, secure and extensible. If you are running a version of the plugin prior to version 3.0, updating without testing and development will break your Broadbean integration. You can find out more [information about upgrading to version 3.0 here](https://highrise.digital/blog/updating-the-wp-broadbean-plugin-to-version-3/).

== Installation ==

To install the plugin:

1. Upload `wpbroadbean` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under WP Broadbean > Settings
4. Enter a username and password as well as choosing a page to use for your application form.
5. Request your integration with Broadbean, instructing them how to send the data to your site.

== Frequently Asked Questions ==

Frequently asked questions are available [here](https://store.highrise.digital/docs/wp-broadbean/).

== Screenshots ==

1. The job edit screen in WordPress

== Changelog ==

= 3.0.4 =
* Added job feed notes to the default job fields and taxonomies.
* Added information to the settings page and the new support add-on.
* Move the after settings hooks so it is actually after all the settings on the settings page.
* Allows the job author to be set in WordPress based on the `consultant_email` field.

= 3.0.3 =
* Correct check for an empty value for the posted XML before proceeding with the inbox template. Prevents warnings when the posted data is incorrect and gives the appropriate error message.
* Fix a call to and undefined function in the post title filter functions. Thanks to @bencorke for contributing this fix and finding the bug.
* Add declaration that the plugin works with WP version 5.2.

= 3.0.2 (29/04/2019) =
* Correct the number of args referenced in the function `wpbb_set_application_email_data()`. Was set to reference 3 and now corrected to 4.
* Includes the permalink of a newly created job in the success message when a new job is posted successfully.
* Various minor bug fixes inlcuding typos.
* Show a checkbox field description next to rather than beneath the field input.

= 3.0.1 (24/04/2019) =
* Adds support for application via either an application form on site, or an external application URL.
* Improved some functions in terms of coding standards.
* Added a setting for the application type - allows site admins to choose whether candidates should apply via a form or an external URL.
* Escaped settings field description using `wp_kses_post` rather than 'esc_html' so they can include links.
* Added new function `wpbb_get_job_application_type()` which returns the type of application chosen. Either `form` or `url`.
* If applications are set to url, output a apply now button linking to the URL below the job content.
* Correct an incorrect entry in the `sample-add.xml` file.
* Adds plugin update routines.
* Corrects an issue where the plugin version does not show correctly in the admin settings page.


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
* Added a check for SSL. If your site is not running over SSL (https) a warning message is shown on the settings page.
* Allow users to display a credit to Highrise Digital below each single job post, should they wish the give something back to us!
* Allow the jobs post type to show in the REST API. This means that partial block editor (aka Gutenberg) support is provided. The edit screens now use the new block editor.
* Prepared the plugin for add-ons being released, namely allow add-ons to add settings to different admin menu pages.
* The job post ID is now appended to the jobs permalink slug. This prevents a job having the same URL as a deleted job in the future. 

To view the changelog for older versions of the plugin, please visit [the Github releases page](https://github.com/highrisedigital/wpbroadbean/releases).

== Upgrade Notice ==
Update through the WordPress admin as notified but always be sure to make a site backup before upgrading and better still, test on a staging or test site first.

Also please note that versions 2 and 3 are breaking change versions, and therefore updating from version 0 to 2 or 2 to 3 will cause the plugin to break without additional work being carried out.