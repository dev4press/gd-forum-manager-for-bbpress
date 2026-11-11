# Integrating with the Forum and Topic Editors

<!-- type: user-guide -->
<!-- category: features -->
<!-- tag: integration,developers,editor -->
<!-- banner: user-guide-forum-manager-editor-integration -->
<!-- seo-title: %%post_title%% - %%sitetitle%% -->
<!-- seo-description: Register and process custom forumManager editor elements for topics and forums. -->
<!-- seo-keywords: bbpress,editor,integration,actions,filters -->

forumManager's editor is extensible: another plugin can register a field for a single forum or topic, for bulk editing, or both. This guide targets PHP developers. The examples use topic metadata; replace the example names and storage with your integration's own. Test on a staging site with bbPress and forumManager active.

## How an editor action works

Register an action during [gdfar_register_actions](https://www.dev4press.com/kb/reference/action-gdfar-register-actions.html). `gdfar_register_action()` must be called inside that hook. Specify a unique name, a `scope` of `topic` or `forum`, and an `action` of `edit` or `bulk`. Register each combination separately. Registration with the same name, scope, and action replaces the prior entry.

The action's `label` appears beside the control; `description` and `source` appear in the dashboard's Registered Actions list. Optional `class` adds a CSS class and `notice` supplies help text when action notices are enabled. The `prefix` defaults to `gdfar`; use your own prefix to avoid collisions.

For a registered action, the default filter names are:

```text
{prefix}-action-visible-{scope}-{action}-{name}
{prefix}-action-display-{scope}-{action}-{name}
{prefix}-action-process-{scope}-{action}-{name}
```

The registration arguments `filter_visible`, `filter_display`, and `filter_process` can override those names. Visibility starts as `true`; return false to hide the control in a given rendering context. The display filter starts with an empty string and must return field HTML. An empty label or display result produces no field.

The display callback receives `$args['base']` for the field name, `element` for the input ID, `type`, and `context`. Single edit also supplies `id`. Context contains `is` and `forum` from the request. The visibility callback receives `action` and `context`, plus `id` for single edits. Use the provided name and ID to associate the input with the label forumManager renders.

## Example: add a topic review flag

This complete example registers a select for both single and bulk topic editing. Put it in a site-specific plugin loaded before forumManager fires its registration hook. It stores a simple example value in topic post meta; it is not a built-in moderation status.

```php
<?php
add_action( 'gdfar_register_actions', function () {
    foreach ( array( 'edit', 'bulk' ) as $mode ) {
        gdfar_register_action( 'example-review', array(
            'scope'       => 'topic',
            'action'      => $mode,
            'prefix'      => 'example',
            'label'       => 'Review flag',
            'description' => 'Change the example review flag.',
            'source'      => 'Site integration',
        ) );
    }
} );

foreach ( array( 'edit', 'bulk' ) as $mode ) {
    $key = 'topic-' . $mode . '-example-review';

    add_filter( 'example-action-display-' . $key, function ( $html, $args ) {
        // An empty choice leaves the existing value unchanged.
        return gdfar_render()->select(
            array( '' => "Don't change", 'needed' => 'Needs review', 'done' => 'Reviewed' ),
            array(
                'name' => $args['base'] . '[flag]',
                'id'   => $args['element'],
            )
        );
    }, 10, 2 );

    add_filter( 'example-action-process-' . $key, function ( $result, $args ) {
        $fields = $args['value'];
        if ( $fields === false ) {
            return $result; // The field was not submitted.
        }
        if ( ! is_array( $fields ) || ! isset( $fields['flag'] ) || ! is_string( $fields['flag'] ) ) {
            return new WP_Error( 'example_invalid_flag', 'Invalid review flag.' );
        }

        $value = $fields['flag'];
        if ( $value === '' ) {
            return $result;
        }
        if ( ! in_array( $value, array( 'needed', 'done' ), true ) ) {
            return new WP_Error( 'example_invalid_flag', 'Invalid review flag.' );
        }

        $ids = is_array( $args['id'] ) ? $args['id'] : array( $args['id'] );
        // Validate all targets before changing any of them.
        foreach ( $ids as $id ) {
            if ( ! bbp_is_topic( $id ) ) {
                return new WP_Error( 'example_invalid_topic', 'Invalid topic.' );
            }
        }

        foreach ( $ids as $id ) {
            $old = get_post_meta( $id, '_example_review_flag', true );
            if ( $old !== $value ) {
                $updated = update_post_meta( $id, '_example_review_flag', $value );
                if ( $updated === false ) {
                    return new WP_Error( 'example_update_failed', 'Could not update the review flag.' );
                }
                \Dev4Press\Plugin\GDFAR\Manager\Process::instance()->modded(
                    'topic', $id, 'example-review', $value, $old
                );
            }
        }

        return $result;
    }, 10, 2 );
}
```

The example leaves the value unchanged by default, including for a single edit. It does not display the current meta value. To show it for a single edit, read it using `$args['id']` and pass it as the select's `selected` argument. A bulk control should retain an explicit unchanged option because selected items can have different values.

For a forum action, register `scope => 'forum'`, use the corresponding `forum-edit` or `forum-bulk` filter names, validate forum IDs and use the appropriate storage and permissions. The same action name can be registered separately for each scope and mode.

## Limit a field to a single-forum view

For an action whose choices depend on one forum, a visibility filter can hide its bulk field elsewhere:

```php
add_filter(
    'example-action-visible-topic-bulk-example-review',
    function ( $visible, $args ) {
        return $visible && ( $args['context']['is'] ?? '' ) === 'forum';
    },
    10,
    2
);
```

This only changes the dialog. The processing callback must still handle a missing field and validate any submitted value; it must not rely on visibility as an authorization check.

## Validate and report changes

The process filter receives an initial `true` result and an array containing `id` and `value`. For a single edit, `id` is one ID; for bulk, it is an array. `value` is the submitted data under your action's base name, or `false` when absent. Return the incoming result on success or no change; return `WP_Error` to report a field error.

Visibility only controls rendering: registered process filters are still called even when a field was hidden. Handle missing data safely, validate all input and IDs, and enforce any additional feature-specific permissions on the server. Bulk field values are not generally sanitized by the AJAX handler. A field error does not roll back other fields or earlier updates in a bulk loop, so validate before writing and check for partial changes.

When your integration actually changes an item, `Process::instance()->modded()` marks it as changed. Passing an element name, new value, and old value also adds it to the changes reported to processing-end hooks. For topics, marking the item allows the optional topic edit log to be attempted when its checkbox is selected; a saved revision is still required. Do not mark unchanged items.

## Existing integrations

GD Topic Prefix Pro registers Prefix fields for single and bulk topic edits. It builds selectors from prefix groups and restricts the bulk field to a single-forum context. It uses [gdfar_ajax_bulk_process_start](https://www.dev4press.com/kb/reference/action-gdfar-ajax-bulk-process-start.html) to prepare a topic cache before bulk processing. The Prefix field is dependent on that plugin and its available groups.

forumToolbox for bbPress registers separate Private and Lock topic actions for single and bulk editing when the corresponding features are loaded. Its bulk selectors include an unchanged choice; processing validates the selected value and marks changed topics. These integrations are examples of plugin-specific fields, not forumManager defaults. For the user-facing setup and options, see [Plugins That Add Editor Options](plugins-that-add-editor-options.md).

## Observe and test

The [single edit start](https://www.dev4press.com/kb/reference/action-gdfar-ajax-edit-process-start.html) and [single edit end](https://www.dev4press.com/kb/reference/action-gdfar-ajax-edit-process-end.html) hooks surround processing after the request checks. Bulk processing has corresponding [start](https://www.dev4press.com/kb/reference/action-gdfar-ajax-bulk-process-start.html) and [end](https://www.dev4press.com/kb/reference/action-gdfar-ajax-bulk-process-end.html) hooks. End hooks can receive errors as well as recorded changes; they do not imply every field succeeded.

Test individual and bulk edits, unchanged and invalid values, missing fields, and an account without access. Verify the result and optional topic edit log, and test across the lists and contexts where the field should appear. The [ultimate guide](ultimate-guide-to-forum-manager.md) covers the moderator workflow.
