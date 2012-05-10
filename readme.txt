=== Flexible hReview ===
Contributors: steve.barnett
Tags: review, hreview
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: trunk

Easily add hReview data to a Post. Show it anywhere using the function or the shortcode.

== Description ==

Flexible hReview lets you add an <a href="http://microformats.org/wiki/hreview">hReview</a> to a Post, then show that review anywhere on your site using the function or the shortcode.
Also available on GitHub: <a href="https://github.com/SteveBarnett/Flexible-hReview">https://github.com/SteveBarnett/Flexible-hReview</a>.

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


== Frequently Asked Questions ==

= How do I display an hReview in the body of the Post? =

To display the current Post's hReview use
`[flexible_hreview]`

To display a different Post's hReview use 
`[flexible_hreview id="123"]`
where 123 is ID of the Post.


= How do I display an hReview in a Theme file? =

In single.php:

`<?php
if(function_exists('flexible_hreview_html')) {
echo flexible_hreview_html();
};
?>`

In other files:

`<?php
if(function_exists('flexible_hreview_html')) {
echo flexible_hreview_html(123);
};
?>`
where 123 is ID of the Post.

= How do hide elements of the hReview? =

Use CSS to visually hide elements of the hReview. For example, to hide the version number:

`.hreview .version {
position: absolute;
left: -999em;
}`


== Changelog ==

= 0.6 =

* Added FAQ section

= 0.5 =

* Initial release
