=== WP Broadbean ===
Contributors: wpmarkuk, keithdevon, highrisedigital
Tags: jobs, recruitment
Requires at least: 5.1
Requires PHP: 5.6
Tested up to: 5.1
Stable tag: 3.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WP Broadbean is a plugin allowing jobs added to Broadbean to show in your WordPress site.

== Description ==

[WP Broadbean](https://highrise.digital/products/wpbroadbean-wordpress-plugin/) is a plugin designed to work alongside the [Broadbean job posting and distribution](https://www.broadbean.com/uk/products/features/job-posting-distribution/) system allowing jobs written in Broadbean to show in your WordPress site. The plugin adds custom post types and taxonomies to allow you to add jobs. More importantly it allows your site to accept feed data sent by Broadbean to create jobs on your site.

The plugin requires some collaboration with the Broadbean integrations team. This is because they need to add your WordPress site as a posting destination, and to build a "feed" to your WordPress sites endpoint, provided by this plugin, in the form of XML data.

If you are struggling with any aspects of a site integration, we offer a complete integration service which you can take advantage of. Find out more about our this on our [WP Broadbean information page](https://highrise.digital/products/wpbroadbean-wordpress-plugin/).

We also have some add-ons for this plugin which you can find out more about on the [WP Broadbean plugin page](https://highrise.digital/products/wpbroadbean-wordpress-plugin/).

Highrise Digital also offer a number of [WordPress Broadbean integration services](https://highrise.digital/broadbean-wordpress-integrations/) as well as services to [integrate LogicMelon with WordPress](https://highrise.digital/services/integrate-logicmelon-wordpress/).

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

It is a new version, therefore we don't have any FAQs just yet. Feel free to [submit an issue](https://github.com/highrisedigital/wpbroadbean/issues) over on the Github repository. We can then turn the popular questions into FAQs on the Wiki.

== Screenshots ==

1. The job edit screen in WordPress

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
* Added a check for SSL. If your site is not running over SSL (https) a warning message is shown on the settings page.
* Allow users to display a credit to Highrise Digital below each single job post, should they wish the give something back to us!
* Allow the jobs post type to show in the REST API. This means that partial block editor (aka Gutenberg) support is provided. The edit screens now use the new block editor.
* Prepared the plugin for add-ons being released, namely allow add-ons to add settings to different admin menu pages.

To view the changelog for older versions of the plugin, please visit [the Github releases page](https://github.com/highrisedigital/wpbroadbean/releases).

== Upgrade Notice ==
Update through the WordPress admin as notified but always be sure to make a site backup before upgrading and better still, test on a staging or test site first.

Also please note that versions 2 and 3 are breaking change versions, and therefore updating from version 0 to 2 or 2 to 3 will cause the plugin to break without additional work being carried out.