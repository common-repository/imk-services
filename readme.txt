=== Plugin Name ===
Contributors: vineshtradebuilder
Donate link: http://tradebuilderinc.com
Tags: IMK, tradebuilder, imk-blog, MLS, listing, Auto mobile inventory
Requires at least: 4.9
Tested up to: 4.9.8
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

IMKLOUD is Tradebuilder’s next generation Marketing Automation and Interaction Management Platform.

== Description ==

The plugin fetches the data automatically from the server (IMKloud.com) and displays it for you. It creates automobile inventories, blog list, pet list and lead forms. There is a provision to render the functionality using shortcodes.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `imk-services` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
There are different shortcodes to display following functionlities:

= Blog =
[IMK_blog limit=10 skip=1]

= Pet list =
[IMK_petlist limit=10 skip=1 status=sold]

= Inventory list =
[IMK_inventory_filters]
[IMK_featured_inventory]
[IMK_inventory limit=10 skip=1 offer=1]

= Investment list =
[IMK_properties limit=10 skip=1 ]
[IMK_featured_investment]

= DealerRater reviews =
[IMK_reviews]

= Contact form =
[IMK_contact_form]


== Frequently Asked Questions ==

no yet

== Screenshots ==

1. Install The Plugin.
2. Select any product to set up ( You must have account on IMK ).
3. Enter you credentials such as Title, User ID, Group ID, API Key, and API Url.
4. You can check the list of number of accounts you have saved.
5. There are a plethora shortcodes, Use these shortcodes as per your requirement.
6. Output will be.

== Changelog ==

= 0.01 =
*intial commit

= 0.02 =
Fix bug regarding database