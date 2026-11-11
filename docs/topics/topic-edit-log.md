# Topic Edit Log

<!-- category: features -->
<!-- tag: topics,edit-log -->
<!-- seo-title: %%post_title%% - %%sitetitle%% -->
<!-- seo-description: Enable the topic edit log control in forumManager. -->
<!-- seo-keywords: topic edit log,revisions -->

On the [Getting Started panel](panel-dashboard.md), enable **Show 'Keep The Edit Log' for topics controls**. Topic edit and bulk edit dialogs then show **Keep a log of this edit**, checked by default, and an optional reason. Clear the checkbox when you do not want to request a log for that edit.

For a changed topic, the plugin attempts to save a WordPress revision and add a bbPress revision log entry with the current user and reason. A log entry depends on a revision being saved. This control applies to topics, not forums.

The separate coreActivity integration mentioned in the plugin readme is intended for activity events; it is distinct from this topic revision log.
