# Install and update forumManager for bbPress

<!-- category: plugin-setup -->
<!-- prefix: setup -->
<!-- tag: install,update -->
<!-- seo-title: %%post_title%% - %%sitetitle%% -->
<!-- seo-description: Install and update forumManager for bbPress on your WordPress site. -->
<!-- seo-keywords: forum manager,install,update -->

This guide describes version 3.1.1, which requires WordPress 6.7 or newer, PHP 8.1 or newer, and bbPress 2.6.2 or newer. See [System Requirements](system-requirements.md). Install and activate bbPress first.

You need a WordPress account with permission to install and update plugins. Make a current backup of your site before installing or updating.

## Install from the WordPress plugin directory

1. In WordPress administration, open **Plugins → Add New Plugin**.
2. Search for **forumManager for bbPress**.
3. Select **Install Now**, then **Activate** when installation finishes.

## Install from a ZIP file

1. Download the ZIP package from the [forumManager for bbPress page on WordPress.org](https://wordpress.org/plugins/gd-forum-manager-for-bbpress/).
2. Open **Plugins → Add New Plugin** and select **Upload Plugin**.
3. Choose the ZIP file without extracting it, select **Install Now**, and then select **Activate Plugin**.

To install files manually, extract the package and upload the `gd-forum-manager-for-bbpress` folder to `wp-content/plugins/`. Activate the plugin on the WordPress **Plugins** screen.

## Update forumManager for bbPress

When an update is available, WordPress displays it on **Plugins** or **Dashboard → Updates**. Back up the site, select the update, and wait for WordPress to finish.

To update from a ZIP file, download the newer package and open **Plugins → Add New Plugin → Upload Plugin**. Choose the ZIP and select **Install Now**. When WordPress recognizes the installed plugin, review the versions and confirm the replacement. Check that the plugin is active afterwards. Follow the plugin's update screen if it appears.

## After installation or an update

Open **Settings → forumManager** and review the settings on the [Getting Started panel](panel-dashboard.md). Sign in with an allowed role, open a frontend forum or topic list, and test the [editing controls](quick-and-bulk-editing.md). After an update, check that your expected actions and settings are still available. If controls are missing or a dialog fails, see [Troubleshooting](troubleshooting.md).
