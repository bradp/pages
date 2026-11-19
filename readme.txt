=== Pages ===
Contributors: bradparbs
Tags: pages, widget, dashboard, content
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin to easily view your pages in the dashboard.

== Description ==

Quickly and easily view all your recently modified pages in the dashboard.

== Available filters ==

`pages_widget_icon` - The dashicon to use for the dashboard widget.
`pages_widget_title` - The title to use for the dashboard widget.
`pages_widget_query_args` - An array of arguments to pass to the WP_Query.
`pages_show_in_widget` - Will pass ID of each post, returning `false` will not add it to the dashboard widget.

== Changelog ==

= 1.1.1 =

* The widget now shows the modified date instead of the publish date.
* Returning `false` from `pages_show_in_widget` now hides the page, as documented.
* Dates use the site timezone, and titles and links are escaped.
* Requires WordPress 6.0 and PHP 7.4, tested up to WordPress 7.1.

= 1.1.0 =

* Added more filters

= 1.0.0 =

* Initial release
