=== Frontpage Manager ===
Contributors: kirilisa
Donate link: http://kirilisa.com/
Tags: front page, frontpage, limit, category limit, paragraph limit, word limit, character limit, limit posts
Requires at least: 2.8
Tested up to: 2.8.6
Stable tag: trunk

Lets you customize how frontpage posts appear in a number of ways: limiting by category, number of posts, number of words/characters/paragraphs.

== Description ==
Frontpage Manager allows you to customize how the posts appear on your frontpage in several different ways. 

* you can have the posts drawn from all categories or a single one of your choosing 
* you can choose how many posts should display on the front page (this is distinct from WordPress' built-in Reading Settings which applies to all blog pages)
* you can have each frontpage post limited in length by number of characters, words, or paragraphs
* you can choose which HTML tags should be stripped from frontpage posts

The plugin will attempt to make sure that any tags that are interrupted by the post-limiting feature are matched so as not to cause formatting issues.

== Installation ==
* Install directly from WordPress (go to Plugins -> Add New)
OR
* Install manually
1. Unzip the plugin files to the `/wp-content/plugins/fp-manager/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set your preferred settings through the Frontpage Manager section under WordPress' Settings menu

== Compatibility ==
I have only tested this plugin on WordPress 2.8 since that is the platform I wrote it on.

== Screenshots ==
1. See screenshot.jpg.

== Frequently Asked Questions ==
= Does this plugin also apply if you have a static page set as your frontpage rather than latest posts? =

No. At the moment, if you have a static page set for your frontpage, the plugin will effectively be ignored. This will probably change in the future.

= Can you limit the frontpage posts to being drawn from one of several categories? =

No. At this time, you may select a single category or all categories for frontpage posts to be drawn from. This will probably change in the future, however.

= How can I style how the read more link looks? =

The link is contained within a div that has been given the class 'fpm`_`readon'. Simply add .fpm`_`readon{} to your theme's style.css and put whatever CSS styling you want in it.

== Download ==
[Version 0.9 beta](http://kirilisa.com/downloads/projects/wordpress/fp-manager_0.9.zip "Download version 0.9 beta") - 37.2KB zip file

== Changelog ==
= 0.9 beta -- December 2009 = 
* Plugin launched


