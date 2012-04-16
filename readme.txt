=== Flexible hReview ===
Contributors: steve.barnett
Tags: review, hreview
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: trunk

Easily add hReview data to a Post. Show it anywhere using the function or the shortcode.

== Description ==

Flexible hReview lets you add an <a href="http://microformats.org/wiki/hreview">hReview</a> to a Post, then show that review anywhere on your site using the function or the shortcode.

The options page lets you set:

* field names for multi-dimensional reviews
* the maximum rating
* whether or not to calculate and display an average of the ratings
* characters for before and after the rating text


== Installation ==

1. Install _Flexible hReview_ via the WordPress.org plugin directory, or by uploading the files to your server.
1. Activate the plugin through the Plugins menu in WordPress.
1. Add the function or shortcode in the place you want the hreview to display.
1. Add CSS to visually hide elements of the hreview you do not wish to display.

On pages other than Single Post pages, the ID of the Post must be passed as an argument. Examples below.

= Single Post page =

`<?php
if(function_exists('flexible_hreview_html')) {
echo flexible_hreview_html();
};
?>`

or

[flexible_hreview]


= Anywhere else =

`<?php
if(function_exists('flexible_hreview_html')) {
echo flexible_hreview_html(123);
};
?>`

or

[flexible_hreview id="123"]

= CSS Example - hide the hReview version number =

.hreview .version {
position: absolute;
left: -999em;
}



== Changelog ==

= 0.5 =

* Initial release
