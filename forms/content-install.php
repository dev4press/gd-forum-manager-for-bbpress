<div class="d4p-content">
    <div class="d4p-update-info">
        <?php

        gdfar_settings()->set('install', false, 'info');
        gdfar_settings()->set('update', false, 'info', true);

        ?>

        <div class="d4p-install-block">
            <h3>
                <?php _e("All Done", "gd-forum-manager-for-bbpress"); ?>
            </h3>
            <div>
                <?php _e("Installation completed.", "gd-forum-manager-for-bbpress"); ?>
            </div>
        </div>

        <div class="d4p-install-confirm">
            <a class="button-primary" href="<?php echo d4p_panel()->a()->panel_url('about') ?>&install"><?php _e("Click here to continue", "gd-forum-manager-for-bbpress"); ?></a>
        </div>
    </div>
</div>