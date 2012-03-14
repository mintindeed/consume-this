=== Consume This ===
Contributors: mintindeed
Tags: delicious, bookmarks, links
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 1.0

A Delicious.com-like bookmarklet for your site.  Tell people what you're consuming -- links, music, movies, you name it.

== Description ==

I made this plugin because I wanted a Delicious.com-like list of links I was looking at, music I was listening to, etc.  It's super simple: text, an optional link, and a tag.

It works by creating a custom post type ("Consumption") and custom taxonomy ("Consumable"), and an optional bookmarklet.  You enter the name of what you're consuming, an optional link, and an optional tag (the "Consumable" taxonomy) and that's it.  You can then display this list anywhere on your site -- a list of consumables in a tag cloud, a page with a list of consumptions, etc.

Github: https://github.com/mintindeed/consume-this

WordPress.org: http://wordpress.org/extend/plugins/consume-this/

Image by [Henning Mühlinghaus](http://www.flickr.com/photos/muehlinghaus/3519033869/); some rights reserved.  See link for details.


== Installation ==

1. Unzip and upload the `consume-this` folder to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Optional:

1. Copy `/wp-content/plugins/consume-this/theme-files/archive-consumption.php` to `/wp-content/themes/<your_theme>/archive-consumption.php` and customize it as you see fit.
1. Add a link to `/consumption/` to your navigation menu.

== Frequently Asked Questions ==

= Can I change the name of the "consumption" post type? =

The plugin does not support this as an option, but if you know what you're doing then feel free to find-and-replace.

= Can I change the name of the "consumable" taxonomy? =

See above.

= I get "Page not found" when I go to `http://example.com/consumption/` =

Please visit [this page](http://www.google.com/search?ie=UTF-8&q=wordpress+custom+post+type+archive+page+not+found) for instructions.

== Screenshots ==

1. Add new "Consumption"
2. Example display using TwentyTen theme and the included archive-consumption.php template
3. "Consume This" bookmarklet

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
N/A
