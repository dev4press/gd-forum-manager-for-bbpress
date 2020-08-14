=== GD Forum Manager for bbPress ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/gd-forum-manager-for-bbpress/
Version: 1.2
Tags: dev4press, bbpress, edit, bulk edit, quick edit, moderation
Requires at least: 4.9
Tested up to: 5.5
Requires PHP: 7.0
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Expand how the moderators can manage forum content from the frontend, from any page showing list of topics or forums.

== Description ==
Editing topics and forums in the bbPress powered forums can be a slow process because you can do it only from the edit pages, and for forums only from the administration side. If you need to perform quick changes, close topics, rename them, that can take a while if you need to go through several screens to reach the edit page.

Now, with GD Forum Manager for bbPress, you can do it quick and accessible from any topics or forums list on the frontend in two ways: single forum or topic editing and bulk editing of one or more selected forums or topics. Every forum or topic list (single forum, forums or topics index, topic views, user profile) now has controls to edit a single item or select items for bulk editing. And editing is done using popup modal dialog.

= Plugin In Action =
https://www.youtube.com/watch?v=-zS7cZaZ11A

= Features Overview =
* Using AJAX to retrieve actions and values
* Using AJAX to perform the edit operations
* Modal popup to display edit and bulk edit actions
* Admin side dashboard showing available actions
* Full RTL support for controls and popup

= Plugin Settings =
* Enable moderators to use the plugin
* Enable use of a plugin for forums
* Enable use of a plugin for topics

= Forums Editing =
* Quick Edit: title, status, and visibility
* Bulk Edit: status and visibility

= Topic Editing =
* Quick Edit: title, sticky, status, and forum
* Bulk Edit: sticky, status and forum
* Quick Edit also available from the single topic pages

= Developers Friendly =
* Ability to register new actions for edit and bulk edit
* Ability to override existing actions for additional control

= bbPress Plugin Versions =
GD Forum Manager supports bbPress 2.5.12 or newer. But, you should be using this plugin with bbPress 2.6 or newer. Older bbPress versions are not supported!

= More Free Dev4Press plugins for bbPress =
* [GD bbPress Attachments](https://wordpress.org/plugins/gd-bbpress-attachments/) - attachments for topics and replies
* [GD bbPress Tools](https://wordpress.org/plugins/gd-bbpress-tools/) - various expansion tools for forums
* [GD Topic Polls](https://wordpress.org/plugins/gd-topic-polls/) - add polls to the bbPress topics
* [GD Power Search](https://wordpress.org/plugins/gd-power-search-for-bbpress/) - add advanced search to the bbPress topics

= Dev4Press Pro plugins for bbPress =
Get Premium plugins for bbPress to enhance bbPress powered forums. More information is available here: [bbPress Plugins Club](https://bbpress.dev4press.com/?utm_source=wporg&utm_medium=link&utm_campaign=gd-bbpress-tools).

== Installation ==
= General Requirements =
* PHP: 7.0 or newer

= WordPress Requirements =
* WordPress: 4.9 or newer

= bbPress Requirements =
* bbPress Plugin: 2.5.12 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-forum-manager-for-bbpress`
* Upload folder `gd-forum-manager-for-bbpress` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Where can I configure the plugin? =
The plugin adds a menu item in the WordPress Settings menu. There you have only a few toggle based options to configure.

= Can I translate the plugin to my language? =
Yes. The POT file is provided as a base for translation. Translation files should go into languages directory.

== Upgrade Notice ==
= 1.2 =
Few updates and improvements.

== Changelog ==
= 1.2 - 2020.08.14 =
* Edit: improved plugin dashboard displaying list of actions
* Edit: d4pLib 3.2

= 1.1 - 2020.06.24 =
* New: responsive styling for the bulk toolbar
* New: option to always show controls on small screens
* Edit: improvements to the forums selection rendering
* Edit: improved popup height calculation and scrolling
* Edit: various styling improvements
* Fix: settings object base prefix

= 1.0 - 2020.06.22 =
* First plugin version

== Screenshots ==
1. Single Topic Edit Popup
2. Forums Bulk Edit Popup
3. Topics and Forums Select for Edit
4. Plugin Dashboard and Settings
