<div class="d4p-group d4p-dashboard-card d4p-dashboard-card-actions">
    <h3><?php esc_html_e( "Registered Actions", "gd-forum-manager-for-bbpress" ); ?></h3>
    <div class="d4p-group-header">
        <ul class="d4p-full-width">
            <li>
                <i aria-hidden="true" class="d4p-icon d4p-icon-bbpress-forum"></i>
				<?php esc_html_e( "For Forums", "gd-forum-manager-for-bbpress" ); ?>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-purple"><?php echo sprintf( esc_html__( "%s for Bulk Edit", "gd-forum-manager-for-bbpress" ), '<strong>' . esc_html( gdfar()->actions()->count_actions( 'forum', 'bulk' ) ) . '</strong>' ); ?></span>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-blue"><?php echo sprintf( esc_html__( "%s for Edit", "gd-forum-manager-for-bbpress" ), '<strong>' . esc_html( gdfar()->actions()->count_actions( 'forum', 'edit' ) ) . '</strong>' ); ?></span>
            </li>
            <li>
                <i aria-hidden="true" class="d4p-icon d4p-icon-bbpress-topic"></i>
				<?php esc_html_e( "For Topics", "gd-forum-manager-for-bbpress" ); ?>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-purple"><?php echo sprintf( esc_html__( "%s for Bulk Edit", "gd-forum-manager-for-bbpress" ), '<strong>' . esc_html( gdfar()->actions()->count_actions( 'topic', 'bulk' ) ) . '</strong>' ); ?></span>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-blue"><?php echo sprintf( esc_html__( "%s for Edit", "gd-forum-manager-for-bbpress" ), '<strong>' . esc_html( gdfar()->actions()->count_actions( 'topic', 'edit' ) ) . '</strong>' ); ?></span>
            </li>
        </ul>
        <div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php esc_html_e( "Forums, Edit", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php _gdfar_display_actions( 'forum', 'edit' ); ?>
        <h4 style="margin-top: 15px;"><?php esc_html_e( "Forums, Bulk Edit", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php _gdfar_display_actions( 'forum', 'bulk' ); ?>
        <h4 style="margin-top: 15px;"><?php esc_html_e( "Topics, Edit", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php _gdfar_display_actions( 'topic', 'edit' ); ?>
        <h4 style="margin-top: 15px;"><?php esc_html_e( "Topics, Bulk Edit", "gd-forum-manager-for-bbpress" ); ?></h4>
		<?php _gdfar_display_actions( 'topic', 'bulk' ); ?>
    </div>
</div>
