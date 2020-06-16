<div class="d4p-group d4p-dashboard-card d4p-dashboard-card-polls">
    <h3><?php _e("Plugin Settings", "gd-forum-manager-for-bbpress"); ?></h3>
    <div class="d4p-group-header">
        <ul class="d4p-full-width">
            <li>
                <?php _e("From here you can control plugin settings. Plugin only has few settings you can change.", "gd-forum-manager-for-bbpress"); ?>
            </li>
        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php _e("Moderation", "gd-forum-manager-for-bbpress"); ?></h4>
        <?php echo _gdfar_display_option('moderators'); ?>
        <h4 style="margin-top: 20px;"><?php _e("Content Editing", "gd-forum-manager-for-bbpress"); ?></h4>
        <?php echo _gdfar_display_option('forum'); ?>
        <?php echo _gdfar_display_option('topic', __("Toggle option for topics editing", "gd-forum-manager-for-bbpress"), __("Edit Topics", "gd-forum-manager-for-bbpress")); ?>
    </div>
</div>