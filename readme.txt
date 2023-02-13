=== GD Forum Manager for bbPress ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/gd-forum-manager-for-bbpress/
Version: 2.3.1
Tags: dev4press, bbpress, edit, bulk edit, quick edit, moderation
Requires at least: 5.5
Tested up to: 6.1
Requires PHP: 7.3
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Expand how the moderators can manage forum content from the frontend, from any page showing list of topics or forums.

== Description ==
Editing topics and forums in the bbPress powered forums can be a slow process because you can do it only from the edit pages, and for forums only from the administration side. If you need to perform quick changes, close topics, rename them, that can take a while if you need to go through several screens to reach the edit page.

Now, with GD Forum Manager for bbPress, you can do it quick and accessible from any topics or forums list on the frontend in two ways: single forum or topic editing and bulk editing of one or more selected forums or topics. Every forum or topic list (single forum, forums or topics index, topic views, user profile) now has controls to edit a single item or select items for bulk editing. And editing is done using popup modal dialog. You can even do something that bbPress doesn't allow you to do: change the author of the topic.

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
* Various display related settings
* Option to enable saving of edit log

= Forums Editing =
* Quick Edit: title, status, and visibility
* Bulk Edit: status and visibility

= Topic Editing =
* Quick Edit: title, author, topic tags, sticky, status, and forum
* Bulk Edit: author, clear tags, sticky, status and forum
* Quick Edit also available from the single topic pages

= Developers Friendly =
* Ability to register new actions for edit and bulk edit
* Ability to override existing actions for additional control

= bbPress Plugin Versions =
GD Forum Manager supports bbPress 2.6.2 or newer. Older bbPress versions are not supported!

= Home and GitHub =
* Learn more about the plugin: [GD Forum Manager for bbPress Website](https://plugins.dev4press.com/gd-forum-manager-for-bbpress/)
* Contribute to plugin development: [GD Forum Manager for bbPress on GitHub](https://github.com/dev4press/gd-forum-manager-for-bbpress)

= Documentation and Support =
To get help with the plugin, you can use WordPress.org support forums, or you can use Dev4Press.com support forums.

* Plugin Documentation: [GD Forum Manager for bbPress Website](https://support.dev4press.com/kb/product/gd-forum-manager-for-bbpress/)
* Support Forum: [Dev4Press Support](https://support.dev4press.com/forums/forum/plugins/gd-forum-manager-for-bbpress/)

= More Free Dev4Press plugins for bbPress =
* [GD Members Directory](https://wordpress.org/plugins/gd-members-directory-for-bbpress/) - add page with list of forum users
* [GD Power Search](https://wordpress.org/plugins/gd-power-search-for-bbpress/) - add advanced search to the bbPress topics
* [GD Topic Polls](https://wordpress.org/plugins/gd-topic-polls/) - add polls to the bbPress topics
* [GD bbPress Attachments](https://wordpress.org/plugins/gd-bbpress-attachments/) - attachments for topics and replies
* [GD bbPress Tools](https://wordpress.org/plugins/gd-bbpress-tools/) - various expansion tools for forums

= Dev4Press Pro plugins for bbPress =
Get Premium plugins for bbPress to enhance bbPress powered forums. More information is available here: [bbPress Plugins Club](https://bbpress.dev4press.com/?utm_source=wporg&utm_medium=link&utm_campaign=gd-forum-manager-for-bbpress).

== Installation ==
= General Requirements =
* PHP: 7.3 or newer

= WordPress Requirements =
* WordPress: 5.5 or newer

= bbPress Requirements =
* bbPress Plugin: 2.6.2 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder should be `gd-forum-manager-for-bbpress`
* Upload folder `gd-forum-manager-for-bbpress` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Where can I configure the plugin? =
The plugin adds a menu item in the WordPress Settings menu. There you have only a few toggle based options to configure.

= Can I translate the plugin to my language? =
Yes. The POT file is provided as a base for translation. Translation files should go into languages directory.

== Upgrade Notice ==
= 2.3 =
Various updates and improvements.

= 2.2 =
Various updates and improvements.

= 2.1 =
Various updates and improvements.

= 2.0 =
* Change topic tags and topic author. Saving of topics edit log. New actions, filters and variables. Many improvements and fixes.

== Changelog ==
= 2.3.1 - 2023.02.14 =
* Edit: few updates to the core classes
* Edit: Dev4Press Library 3.9.3

= 2.3 - 2023.02.03 =
* New: tested with WordPress 6.1
* New: tested with PHP 8.1/8.2
* New: updated plugin system requirements
* Edit: expanded settings with extra information
* Edit: improved admin side interface with new look
* Edit: Dev4Press Library 3.9.2

= 2.2 - 2022.05.17 =
* New: tested with WordPress 6.0
* New: changed order and actions for the plugin loading
* New: method to check for the forum integration
* Edit: Dev4Press Library 3.8
* Fix: bulk box HTML visible for all logged-in users

= 2.1.1 - 2022.03.13 =
* Edit: Dev4Press Library 3.7.3
* Fix: wrong case for some file names

= 2.1 - 2022.03.06 =
* New: allow editing of topics to forum moderators
* New: improved admin side interface through updated shared library
* Edit: many improvements to sanitation and escaping on echo
* Edit: more string translations using escape functions
* Edit: updated plugin requirements
* Edit: including only minified version of MicroModal library
* Edit: improved AJAX request error handling
* Edit: MicroModal library 0.4.10
* Edit: Dev4Press Library 3.7.3
* Fix: some issues with the minified JavaScript file

= 2.0 - 2021.02.16 =
* New: actions: change single topic tags
* New: actions: bulk remove topic tags
* New: actions: change single topic author username
* New: actions: bulk change topic author username
* New: global var defined when the editor is active or not
* New: function to determine if the editor is active or not
* New: option to control display of the important action notices
* New: option to control saving of the edit log or topics
* New: fire actions before and after ajax processing calls
* New: filters to modify Edit and Quick Edit button HTML content
* Edit: many improvements to the plugin initialization
* Edit: various improvements to the plugin core code
* Edit: several styling improvements and changes to popup dialog
* Edit: more compact styling layout for the dialog elements
* Edit: MicroModal library 0.4.6
* Edit: Dev4Press Library 3.4
* Fix: minor issues with the admin side plugin settings handling
* Fix: various typos and other wording and naming issues

= 1.4 - 2020.12.29 =
* Edit: updated plugin requirements
* Edit: various minor updates
* Edit: Dev4Press Library 3.3.1

= 1.3 - 2020.11.12 =
* Edit: refactored and updated to WordPress coding style
* Edit: requires bbPress 2.6.2 or newer
* Edit: removed support for bbPress 2.5
* Edit: Dev4Press Library 3.3
* Fix: few minor issues caused by the shared library changes

= 1.2 - 2020.08.14 =
* Edit: improved plugin dashboard displaying list of actions
* Edit: Dev4Press Library 3.2

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
2. Topics Bulk Edit Popup
3. Forums Bulk Edit Popup
4. Topics and Forums Select for Edit
5. Plugin Dashboard and Settings
