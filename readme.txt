=== Plugin Name ===
Contributors: wheatco
Donate link: https://cred.wheat.co/
Tags: reviews,verified reviews,wheat,wheatco,wheat co,cred,cred reviews
Requires at least: 2.8
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cred Reviews displays validated reviews collected by the Cred review system.

== Description ==

Cred allows you to embed and display reviews inline on your posts, pages or sidebar
by adding a Shortcode.  Usage is easy, just use the following shortcode:

[cred store="my-store" sku="my-product-1"]

= Features =

* Uses WordPress Shortcodes to embed reviews on any page, post or sidebar widget
* Has a variety of parameters to filter and format the reviews
* Fixes characters in URLs that may get mangled when editing in Visual mode
* Caches reviews and allows configuration of the cache expiration
* Outputs clean, HTML for easy styling with a CSS configuration setting
* Adds rich snippets to your site based on aggregated metrics from the reviews.
* Allows review pagination

== Installation ==

Automatic Installation:

1. Go to Admin - Plugins - Add New and search for "cred"
2. Click the Install Button
3. Click 'Activate'

Manual Installation:

1. Download cred.zip (or use the WordPress "Add New Plugin" feature)
2. Unzip and upload 'cred' folder to your '/wp-content/plugins/' directory
3. To support caching, ensure the directory 'wp-content/cache' exists and is writable.
4. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Cred control panel

== Frequently Asked Questions ==

= 1. How can I get reviews to appear in a sidebar widget? =

Shortcodes are not enabled in Widgets by default.  However there is an option to enable Shortcode processing in Widgets on the Cred settings page.  Be aware that this will enable Shortcode for all plugins, not just Cred.  WordPress currently does not support selectively enabling Shortcode for a single plugin, so it is an all-or-nothing option.

= 2. Where do I go for support? =

Documentation is available on the plugin homepage at https://cred.wheat.co and questions may be sent to cred@wheat.co

== Upgrade Notice ==

= 1.0.0 = 
Brand new plugin!

== Changelog ==

= 1.0.0 =
*  Initial Release