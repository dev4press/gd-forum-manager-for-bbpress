# Troubleshooting

<!-- category: troubleshooting -->
<!-- tag: troubleshooting,moderation -->
<!-- seo-title: %%post_title%% - %%sitetitle%% -->
<!-- seo-description: Troubleshoot missing controls and editing errors in forumManager. -->
<!-- seo-keywords: troubleshooting,bbpress -->

## Controls are missing

Confirm bbPress and forumManager are active and that you are signed in with an allowed role. On the [Getting Started panel](panel-dashboard.md), enable the relevant content type and moderator access. Forum-list editing requires a keymaster or an enabled global moderator; the individual forum moderator setting applies to eligible topic controls. On a small screen, try enabling **Always show on small screens**.

The plugin adds controls through bbPress template hooks. A custom theme or template that omits those hooks may not display them. Test with a standard bbPress-compatible theme.

## A dialog does not load or save

Reload the page and try again while signed in. The dialogs use JavaScript and WordPress AJAX; check for browser console errors, blocked requests, or conflicts with other plugins and the theme. If the dialog reports a field error, inspect the current items before retrying: other fields may already have been changed.

## A field is missing

Topic tags require bbPress topic tags to be enabled. Some topic edit options may be unavailable in Topic Views, and other code can alter registered actions. The dashboard lists the currently registered actions.
