<?php

use function Dev4Press\v43\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-setup-wrapper">
        <div class="d4p-update-info">
			<?php

			gdfar_settings()->set( 'install', false, 'info' );
			gdfar_settings()->set( 'update', false, 'info', true );

			?>

            <div class="d4p-install-block">
                <h3>
					<?php esc_html_e( "All Done", "gd-forum-manager-for-bbpress" ); ?>
                </h3>
                <div>
					<?php esc_html_e( "Installation completed.", "gd-forum-manager-for-bbpress" ); ?>
                </div>
            </div>

            <div class="d4p-install-confirm">
                <a class="button-primary" href="<?php echo panel()->a()->panel_url( 'about' ) ?>&install"><?php esc_html_e( "Click here to continue", "gd-forum-manager-for-bbpress" ); ?></a>
            </div>
        </div>
    </div>
</div>