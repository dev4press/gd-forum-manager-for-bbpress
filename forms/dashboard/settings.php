<div class="d4p-group d4p-dashboard-card d4p-dashboard-card-settings">
    <h3><?php esc_html_e( "Plugin Settings", "gd-forum-manager-for-bbpress" ); ?></h3>
    <div class="d4p-group-header">
        <ul class="d4p-full-width">
            <li>
				<?php esc_html_e( "The plugin is easy to configure, and there are only few settings you can and need to change, and they are all available from this panel.", "gd-forum-manager-for-bbpress" ); ?>
            </li>
        </ul>
        <div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e( "Moderation", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php echo _gdfar_display_option( 'moderators' ); ?>
	    <?php echo _gdfar_display_option( 'forum_moderators' ); ?>
        <h4 style="margin-top: 20px;"><?php esc_html_e( "Content Editing", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php echo _gdfar_display_option( 'forum' ); ?>
		<?php echo _gdfar_display_option( 'topic' ); ?>
        <h4 style="margin-top: 20px;"><?php esc_html_e( "Display Controls", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php echo _gdfar_display_option( 'small_screen_always_show' ); ?>
		<?php echo _gdfar_display_option( 'notices_under_fields' ); ?>
        <h4 style="margin-top: 20px;"><?php esc_html_e( "Advanced Settings", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php echo _gdfar_display_option( 'topic_edit_log' ); ?>
    </div>
</div>
