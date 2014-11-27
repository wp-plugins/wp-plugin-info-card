=== WP Plugin Info Card ===
Contributors: briKou
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8
Tags: API, plugin, card, blog, developper, design, dashboard, shortcode, ajax, WordPress, plugin API, CSS, rotate, flip card, awesome, UX, ui
Requires at least: 3.7
Tested up to: 4.0
Stable tag: 1.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 
WP Plugin Info Card displays plugins identity cards in a beautiful box with a smooth rotation effect using WP Plugin API. Dashboard widget included.

== Description ==

= How does it work? =

WP Plugin Info Card allows you to display plugins identity cards in a beautiful box with a smooth 3D rotation effect.

It uses Wordpress.org plugin API to fetch data. All you need to do is provide a valid plugin ID (slug name), and then insert the shortcode in any page to make it work at once!

This plugin is very light and includes scripts and CSS only if and when required. The shortcode may be added everywhere shortcodes are supported in your theme.

The plugin also uses Wordpress transients to store data returned by the API for 10 minutes (by default), so your page loading time will not be increased due to too many requests.

**Plugin is now using the TinyMCE API to improve UI and makes it easy to insert shortcodes!**

The dashboard widget is very easy to set up: you simply add as many plugins as you want in the admin page and they become visible in your dashboard. Fields are added on-the-fly and are sortable via drag-and-drop.


[CHECK OUT THE DEMO](http://b-website.com/wp-plugin-info-card-for-wordpress "Try It!")

**Please ask for help or report bugs if anything goes wrong. It is the best way to make the community benefit!**

= Shortcode parameters =

* **slug:** plugin slug name - Please refer to the plugin URL on wordpress.org to determine its slug: https://wordpress.org/plugins/THE-SLUG/
* **image:** image url to replace WP logo - Best size is 175px X 175px(default: empty)
* **logo:** 128x128.jpg, 256x256.jpg, 128x128.png, 256x256.png, svg, no (default: svg)
* **banner:** jpg, png, no (default:empty)
* **align:** center, left, right (default: empty)
* **containerid:** custom div id, may be used for anchor (default: wp-pic-PLUGIN-NAME)
* **margin:** custom container margin - eg: "15px 0" (default: empty)
* **clear:** clear float before or after the card: before, after (default: empty)
* **expiration:** cache duration in minutes - numeric format only (default: 10)
* **ajax:** (BETA) load the plugin data asynchronously with AJAX: yes, no (default: no)
* **custom:** value to output : url, name, version, author, requires, rating, num_ratings, downloaded, last_updated, download_link (default: empty)

= Examples =

The slug is the only required parameter.
`[wp-pic slug="wordpress-seo"]`

`[wp-pic slug="theme-check" logo="128x128.png" align="right" banner="jpg" expiration="60" ajax="yes"]`

`[wp-pic slug="wordpress-seo" custom="downloaded"]`


[FULL DOCUMENTATION AND EXAMPLES](http://b-website.com/wp-plugin-info-card-for-wordpress "Documentation & examples")



= Languages =

* English
* French
* Polish [Kuba Mikita](http://www.wpart.pl/ "Kuba Mikita")
* Spanish (Soon)

Become a translator and send me you translation! [Contact-me](http://b-website.com/contact "Contact")


== Installation ==

1. Upload and activate the plugin (or install it through the WP admin console)
2. Click on the "WP Plugin Info" menu
3. Follow instructions, every option is documented ;-)

== Frequently Asked Questions ==

= Is it cross-browser compatible? =

Yes, it is compatible with most recent browsers, except for Opera (but IE10+ works!)


== Screenshots ==

1. Plugin identity card
2. Admin page
3. Dashboard widget
4. Shortcode builder


== Changelog ==

= 1.6.2 =
* Polish translation by [Kuba Mikita](http://www.wpart.pl/ "Kuba Mikita")
* Widget UI translation
* Plugin meta translation

= 1.6.1.1 =
* Logo on plugin directory list
* Remove unnecessary files from trunk

= 1.6.1 =
* New logo
* Minor CSS fixes
* Admin + widget minor updates

= 1.6 =
* New param added to ajaxify the card
* Fix minor php issue
* Improve dashboard widget ajax function.
* New admin option to activate or deactivate ajax on widget
* Minor CSS fixes and improvement
* Now use minify css and scripts un front
* Added error message when slug is wrong or plugin offline
* readme.txt update + admin page documentation update

= 1.5 =
* Shortcode can now be displayed everywhere in the page (content/widget) because JS & CSS are loaded via a global var.
* Ajaxify dashboard widget.
* Fix bug on saving empty plugin list with deactivated dashboard widget
* New param added to specify transient life time
* Daily cron added to purge transients

= 1.04 =
* Fix on foreach. Related topic: https://wordpress.org/support/topic/errors-on-saving-dashboard-widget?replies=5#post-6197891

= 1.03 =
* Fix on widget if plugin list is empty

= 1.02 =
* Typo fix.
* PHP fix if no plugin slug is set during options updates
* CSS fix for transparent logos
* Fix if required version already includes 'WP'
* Now translatable + add French translation
* Update readme.txt

= 1.01 =
* Typo fix.
* Add link to admin page
* Update readme.txt

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.5 =
* If you have installed the plugin before version 1.5 you have to deactivate it then reactivate it to register the cron job which will purge the plugin's transients everyday.

= 1.0 =
* First release.