# Editing Topics

<!-- category: features -->
<!-- tag: topics,moderation -->
<!-- seo-title: %%post_title%% - %%sitetitle%% -->
<!-- seo-description: Manage bbPress topic details individually or in bulk. -->
<!-- seo-keywords: topic edit,bulk edit -->

Enable **Edit Topics** and sign in with an allowed role. The [editing workflow](quick-and-bulk-editing.md) is available from topic lists, and **Quick Edit** is also available on single topics.

![Edit Topic dialog with topic fields and the edit log](topic-edit-dialog.png)

For one topic, the dialog offers title, forum, author, status, sticky status, and topic tags. Enter the author's username; enter tags as a comma-separated list. Sticky choices include **No**, **Sticky**, and **Super Sticky**.

![Bulk topic editing dialog showing unchanged fields and the edit log](topic-bulk-edit-dialog.png)

The screenshots also show Prefix, Private, and Lock fields supplied by companion plugins; these are not built-in forumManager fields. See [Plugins That Add Editor Options](plugins-that-add-editor-options.md) for their requirements. Your available actions can differ.

Bulk editing offers forum, author, status, sticky status, and removal of topic tags. It does not rename topics or assign a new list of tags. Leave the bulk author field empty to skip changing it. Tag controls are available when bbPress topic tags are enabled. Some options may be unavailable in Topic Views.

The optional [Topic Edit Log](topic-edit-log.md) can record a reason for changes.
