=== forumManager for bbPress ===
Contributors: GDragoN
Donate link: https://buymeacoffee.com/millan
Tags: dev4press, bbpress, bulk edit, quick edit, moderation
Stable tag: 3.1
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 8.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Expand how the moderators can manage forum and topics content from the frontend, from any page showing the list of topics or forums.

== Description ==
Editing topics and forums in the bbPress powered forums can be a slow process because you can do it only from the edit pages, and for forums only from the administration side. If you need to perform quick changes, close topics, rename them, that can take a while if you need to go through several screens to reach the edit page.

= Quick Introduction Video =
https://www.youtube.com/watch?v=-zS7cZaZ11A

Now, with forumManager for bbPress, you can do it quick and accessible from any topics or forums list on the frontend in two ways: single forum or topic editing and bulk editing of one or more selected forums or topics. Every forum or topic list (single forum, forums or topics index, topic views, user profile) now has controls to edit a single item or select items for bulk editing. And editing is done using a popup modal dialog. You can even do something that bbPress doesn't allow you to do: change the author of the topic.

= Feature Overview =
* Using AJAX to retrieve actions and values
* Using AJAX to perform the edit operations
* Modal popup to display edit and bulk edit actions
* Admin side dashboard showing available actions
* Full RTL support for controls and popup

= Plugin Settings =
* Enable moderators to use the plugin
* Enable use of a plugin for forums
* Enable use of a plugin for topics
* Various display-related settings
* Option to enable saving of edit log

= Forums Editing =
* Quick Edit: title, status, and visibility
* Bulk Edit: status and visibility

= Topic Editing =
* Quick Edit: title, author, topic tags, sticky, status, and forum
* Bulk Edit: author, clear tags, sticky, status, and forum
* Quick Edit is also available from the single topic pages

= Developers Friendly =
* The ability to register new actions for edit and bulk edit
* The ability to override existing actions for additional control

= Log changes into Database =
forumManager for bbPress supports logging of editing events into a database with the use of 'coreActivity' plugin, and it is highly recommended to install and use coreActivity.

Log all edit and bulk edit events into a database with the free plugin: [coreActivity Plugin](https://wordpress.org/plugins/coreactivity/), supporting over 120 events and more than 10 popular WordPress plugins. forumManager for bbPress related events will be logged and available for later analysis.

= bbPress Plugin Versions =
forumManager for bbPress supports bbPress 2.6.2 or newer. **Older bbPress versions are not supported!**

= Home and GitHub =
* Learn more about the plugin: [forumManager for bbPress Website](https://www.dev4press.com/plugins/gd-forum-manager-for-bbpress/)
* Contribute to plugin development: [forumManager for bbPress on GitHub](https://github.com/dev4press/gd-forum-manager-for-bbpress)

= Documentation and Support =
To get help with the plugin, you can use WordPress.org support forums, or you can use Dev4Press.com support forums.

* Plugin Documentation: [forumManager for bbPress Website](https://support.dev4press.com/kb/product/gd-forum-manager-for-bbpress/)
* Support Forum: [Dev4Press Support](https://support.dev4press.com/forums/forum/plugins/gd-forum-manager-for-bbpress/)

= More Free Dev4Press plugins for bbPress =
* [membersDirectory for bbPress](https://wordpress.org/plugins/gd-members-directory-for-bbpress/) - add a page with a list of forum users
* [powerSearch for bbPress](https://wordpress.org/plugins/gd-power-search-for-bbpress/) - add advanced search to the bbPress topics
* [topicPolls for bbPress](https://wordpress.org/plugins/gd-topic-polls/) - add polls to the bbPress topics
* [GD bbPress Attachments](https://wordpress.org/plugins/gd-bbpress-attachments/) - attachments for topics and replies
* [GD bbPress Tools](https://wordpress.org/plugins/gd-bbpress-tools/) - various expansion tools for forums

= Dev4Press Pro plugins for bbPress =
Get Premium plugins for bbPress to enhance bbPress powered forums. More information is available here: [bbPress Plugins Club](https://www.dev4press.com/bbpress-club/?utm_source=wporg&utm_medium=link&utm_campaign=gd-forum-manager-for-bbpress).

== Installation ==
= General Requirements =
* PHP: 8.0 or newer

= WordPress Requirements =
* WordPress: 6.4 or newer

= bbPress Requirements =
* bbPress Plugin: 2.6.2 or newer

= Basic Installation =
* Plugin folder in the WordPress plugins folder should be `gd-forum-manager-for-bbpress`
* Upload folder `gd-forum-manager-for-bbpress` to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Where can I configure the plugin? =
The plugin adds a menu item in the WordPress Settings menu. There you have only a few toggle-based options to configure.

= Can I translate the plugin to my language? =
Yes. The POT file is provided as a base for translation. Translation files should go into the languages directory.

== Upgrade Notice ==
= 3.1 =
Various updates and improvements.

= 3.0 =
Various updates and improvements.

= 2.8 =
Various updates and improvements.

= 2.7 =
Various updates and improvements.

== Changelog ==
= Version: 3.1 / august 31 2026 =
* New: tested with WordPress 7.1
* New: tested and compatible with `PHP` 8.5
* New: build process for the JS and CSS files
* Edit: improved escaping when rendering
* Edit: Dev4Press Library 5.6.2
* Edit: Micromodal 0.7.0
* Fix: a small potential XSS vulnerability

= Version: 3.0 / june 14 2025 =
* New: tested with WordPress 6.8
* New: tested and compatible with `PHP` 8.4
* New: tested with bbPress up to 2.6.13
* New: loading of `Dev4Press Library` via Composer
* New: refactoring namespaces and plugin structure
* Edit: Dev4Press Library 5.4

= Version 2.8 / june 14 2024 =
* Edit: library `Micromodal` loaded from the shared Library
* Edit: several small improvements to the plugin JS code
* Edit: minor updates to the plugin readme file
* Edit: few more changes related to WordPress and PHP code standards
* Edit: protect all PHP files from direct file access
* Edit: Dev4Press Library 4.9.1

= Version 2.7 / april 28 2024 =
* New: directive `Requires Plugin` added into main plugin file
* Edit: plugin admin dashboard styling improvements
* Edit: Dev4Press Library 4.8

= Version 2.6 / 2023.12.21 =
* New: updated plugin system requirements
* Edit: updated use of some PHP function with WordPress replacements
* Edit: plugin admin dashboard styling improvements
* Edit: Dev4Press Library 4.5

= Version 2.5 / 2023.11.15 =
* New: tested with WordPress 6.4
* New: log each change during the edit or bulk processing
* Edit: if author name is empty, it will not be changed
* Edit: plugin admin dashboard styling improvements
* Edit: Dev4Press Library 4.3
* Fix: author username issue if the post is anonymous

= Version 2.4 / 2023.07.15 =
* New: tested with WordPress 6.2 and 6.3
* Edit: Dev4Press Library 4.2

= Version 2.3.1 / 2023.02.14 =
* Edit: few updates to the core classes
* Edit: Dev4Press Library 3.9.3

= Version 2.3 / 2023.02.03 =
* New: tested with WordPress 6.1
* New: tested with PHP 8.1/8.2
* New: updated plugin system requirements
* Edit: expanded settings with extra information
* Edit: improved admin side interface with new look
* Edit: Dev4Press Library 3.9.2

= Version 2.2 / 2022.05.17 =
* New: tested with WordPress 6.0
* New: changed order and actions for the plugin loading
* New: method to check for the forum integration
* Edit: Dev4Press Library 3.8
* Fix: bulk box HTML visible for all logged-in users

= Version 2.1.1 / 2022.03.13 =
* Edit: Dev4Press Library 3.7.3
* Fix: wrong case for some file names

= Version 2.1 / 2022.03.06 =
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

= Version 2.0 / 2021.02.16 =
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

= Version 1.4 / 2020.12.29 =
* Edit: updated plugin requirements
* Edit: various minor updates
* Edit: Dev4Press Library 3.3.1

= Version 1.3 / 2020.11.12 =
* Edit: refactored and updated to WordPress coding style
* Edit: requires bbPress 2.6.2 or newer
* Edit: removed support for bbPress 2.5
* Edit: Dev4Press Library 3.3
* Fix: few minor issues caused by the shared library changes

= Version 1.2 / 2020.08.14 =
* Edit: improved plugin dashboard displaying list of actions
* Edit: Dev4Press Library 3.2

= Version 1.1 / 2020.06.24 =
* New: responsive styling for the bulk toolbar
* New: option to always show controls on small screens
* Edit: improvements to the forums selection rendering
* Edit: improved popup height calculation and scrolling
* Edit: various styling improvements
* Fix: settings object base prefix

= Version 1.0 / 2020.06.22 =
* First plugin version

== Screenshots ==
1. Single Topic Edit Popup
2. Topics Bulk Edit Popup
3. Forums Bulk Edit Popup
4. Topics and Forums Select for Edit
5. Plugin Dashboard and Settings
