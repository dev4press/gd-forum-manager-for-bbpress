<?php

namespace Dev4Press\Plugin\GDFAR\Basic;

use Dev4Press\Core\DateTime;
use Dev4Press\Core\Plugins\Core;
use Dev4Press\Core\Shared\Enqueue;
use Dev4Press\Plugin\GDFAR\bbPress\Integration;

if (!defined('ABSPATH')) {
    exit;
}

class Plugin extends Core {
    public $svg_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICBpZD0ic3ZnOCIgICBzdHlsZT0iY2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjIiICAgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgICB2ZXJzaW9uPSIxLjEiICAgdmlld0JveD0iMCAwIDYwIDYwIiAgIGhlaWdodD0iNjAiICAgd2lkdGg9IjYwIj48bWV0YWRhdGEgICBpZD0ibWV0YWRhdGExNCI+PHJkZjpSREY+PGNjOldvcmsgICAgICAgcmRmOmFib3V0PSIiPjxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PjxkYzp0eXBlICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz48ZGM6dGl0bGU+PC9kYzp0aXRsZT48L2NjOldvcms+PC9yZGY6UkRGPjwvbWV0YWRhdGE+PGRlZnMgICBpZD0iZGVmczEyIiAvPiAgICA8cGF0aCAgIGlkPSJwYXRoMiIgICBzdHlsZT0iZmlsbC1ydWxlOm5vbnplcm87c3Ryb2tlLXdpZHRoOjEuMDY4NTEiICAgZD0iTSA1OC4wMDg1ODYsMCBIIDIuMDEzNDUyMyBDIDAuOTE0ODE2OTYsMCAwLDAuODkyMDQ2MzcgMCwyLjAxMjcxMyBWIDU3LjcxMjcyOSBDIDAsNTguODMzMzk2IDEuMTQ0NTg5OSw2MCAyLjI2NTY2ODIsNjAgSCA1Ny42MTk1NzUgYyAxLjEyMTA3OSwwIDIuMzgwMDIxLC0xLjE4OTAzOSAyLjM4MDAyMSwtMi4zMTA3NzQgViAyLjAxMjcxMyBDIDYwLjAyMjAzOSwwLjg5MjA0NjM3IDU5LjEwNzIyMiwwIDU4LjAwODU4NiwwIFogbSAwLjE2MDMwNyw1Ni43NzU4MTMgYyAwLDAuNzA5MzY0IC0wLjU3MTc2MSwxLjI4MDkxNSAtMS4yNTg5NDIsMS4yODA5MTUgSCAzLjM4Njc0NjQgYyAtMC43MDk2MjQzLDAgLTEuMjU4OTQyLC0wLjU3MTU1MSAtMS4yNTg5NDIsLTEuMjgwOTE1IFYgMy4yNDg3NTgxIGMgMCwtMC43MDkzNjM4IDAuNTcxNzYwNiwtMS4yNTg0Nzk4IDEuMjU4OTQyLC0xLjI1ODQ3OTggSCA1Ni45MDk5NTEgYyAwLjcwOTYyNCwwIDEuMjU4OTQyLDAuNTcxNTUwNyAxLjI1ODk0MiwxLjI1ODQ3OTggeiIgLz4gICAgPHBhdGggICBzdHlsZT0ic3Ryb2tlLXdpZHRoOjAuMTAzNTQ2IiAgIGZpbGw9ImN1cnJlbnRDb2xvciIgICBkPSJNIDUzLjg5Njk1NCwxNC41MDQ4ODggMTQuMDAzOTIzLDYuNDYzOTMzMSBDIDEwLjMzNjY3Myw1LjcyNDc1MTkgNi43ODM3MDk4LDcuOTcwNTQ0MyA2LjA4MDkzNzksMTEuNDcxOTc1IEwgMC4zNDcyNzA5Myw0MC4wMzg4ODMgYyAtMC43MDI3NzE3OSwzLjUwMTQzMSAxLjcwNzQ0MDQ3LDYuOTQ5MTgyIDUuMzc0NjkwMTcsNy42ODgzNjQgbCA5Ljk3MzI1ODksMi4wMTAyMzggLTEuNjcyMzE5LDguMzMyMDE3IGMgLTAuMTk1MTA0LDAuOTcyMDY2IDAuODU0OTY1LDEuNzcxOTgxIDEuNzkxMTUsMS4zNjIxMDEgbCAxNC44NDEwNTcsLTYuNjc4NzYgMTQuOTU5ODg1LDMuMDE1MzU4IGMgMy42NjcyNTIsMC43MzkxODIgNy4yMjAyMTUsLTEuNTA2NjExIDcuOTIyOTg5LC01LjAwODA0MiBsIDUuNzMzNjY1LC0yOC41NjY5MTEgYyAwLjcwMjc3MywtMy41MDE0MjkgLTEuNzA3NDQxLC02Ljk0OTE3NyAtNS4zNzQ2OTMsLTcuNjg4MzYgeiBNIDMyLjAxOSwzNy4zNDA4MjMgYyAtMC4wODc1OSwwLjQzNjQzOCAtMC41MzMyNjYsMC43MTgxNDEgLTAuOTkwMzc0LDAuNjI2MDA1IEwgMTYuMDY4NzM5LDM0Ljk1MTQ2OSBjIC0wLjQ1NzExLC0wLjA5MjEzIC0wLjc1OTQzNCwtMC41MjQ2MDQgLTAuNjcxODM2LC0wLjk2MTA0NCBsIDAuMzE4NTM2LC0xLjU4NzA1MSBjIDAuMDg3NTksLTAuNDM2NDQxIDAuNTMzMjY1LC0wLjcxODE0MSAwLjk5MDM3MywtMC42MjYwMDYgbCAxNC45NTk4ODksMy4wMTUzNTkgYyAwLjQ1NzEwOCwwLjA5MjEzIDAuNzU5NDMzLDAuNTI0NjA0IDAuNjcxODM1LDAuOTYxMDQ3IHogbSAxMS44ODQ0NzksLTcuNTEyMDY0IGMgLTAuMDg3NTksMC40MzY0MzkgLTAuNTMzMjY3LDAuNzE4MTQxIC0wLjk5MDM3NSwwLjYyNjAwNSBMIDE3Ljk3OTk2LDI1LjQyOTE2NyBjIC0wLjQ1NzEwNiwtMC4wOTIxMyAtMC43NTk0MzIsLTAuNTI0NjA3IC0wLjY3MTgzNSwtMC45NjEwNDYgbCAwLjMxODUzNywtMS41ODcwNDkgYyAwLjA4NzU5LC0wLjQzNjQ0IDAuNTMzMjY0LC0wLjcxODE0MyAwLjk5MDM3MSwtMC42MjYwMDQgbCAyNC45MzMxNDgsNS4wMjU1OTUgYyAwLjQ1NzEwNiwwLjA5MjEzIDAuNzU5NDM0LDAuNTI0NjA1IDAuNjcxODM2LDAuOTYxMDQ1IHoiICAgaWQ9InBhdGgyLTAiIC8+PC9zdmc+';

    public $plugin = 'gd-forum-manager-for-bbpress';

    private $_datetime;
    private $_bbpress = null;

    public $theme_package = 'default';

    public function __construct() {
        $this->url = GDFAR_URL;

        $this->_datetime = new DateTime();

        parent::__construct();
    }

    public function s() {
        return gdfar_settings();
    }

    public function run() {
        define('GDFAR_WPV', intval($this->wp_version));
        define('GDFAR_WPV_MAJOR', substr($this->wp_version, 0, 3));

        do_action('gdfar_load_settings');

        if (is_user_logged_in()) {
            $this->_bbpress = new Integration();
        }

        if (get_option('_bbp_theme_package_id') == 'quantum') {
            $this->theme_package = 'quantum';
        }

        add_action('init', array($this, 'plugin_init'), 20);

        if (!is_admin()) {
            Enqueue::init(GDFAR_URL.'d4plib/');

            add_action('d4plib_shared_enqueue_prepare', array($this, 'register_css_and_js'));
        }
    }

    public function register_css_and_js() {
        Enqueue::i()->add_css('gdfar-micromodal', array(
            'lib' => false,
            'url' => GDFAR_URL.'css/',
            'file' => 'micromodal',
            'ver' => gdfar_settings()->file_version(),
            'ext' => 'css',
            'min' => true,
            'int' => array()
        ));

        Enqueue::i()->add_css('gdfar-manager', array(
            'lib' => false,
            'url' => GDFAR_URL.'css/',
            'file' => 'manager',
            'ver' => gdfar_settings()->file_version(),
            'ext' => 'css',
            'min' => true,
            'int' => array('gdfar-micromodal')
        ));

        Enqueue::i()->add_css('gdfar-manager-rtl', array(
            'lib' => false,
            'url' => GDFAR_URL.'css/',
            'file' => 'manager-rtl',
            'ver' => gdfar_settings()->file_version(),
            'ext' => 'css',
            'min' => true,
            'int' => array('gdfar-manager')
        ));

        Enqueue::i()->add_js('gdfar-micromodal', array(
            'lib' => false,
            'url' => GDFAR_URL.'js/',
            'file' => 'micromodal',
            'ver' => gdfar_settings()->file_version(),
            'ext' => 'js',
            'min' => true,
            'footer' => true,
            'localize' => true,
            'int' => array()
        ));

        Enqueue::i()->add_js('gdfar-manager', array(
            'lib' => false,
            'url' => GDFAR_URL.'js/',
            'file' => 'manager',
            'ver' => gdfar_settings()->file_version(),
            'ext' => 'js',
            'min' => true,
            'footer' => true,
            'localize' => true,
            'int' => array('gdfar-micromodal')
        ));
    }

    public function after_setup_theme() {
        do_action('gdfar_after_setup_theme');
    }

    public function plugin_init() {
        do_action('gdfar_plugin_init');
    }

    /** @return \Dev4Press\Core\DateTime */
    public function datetime() {
        return $this->_datetime;
    }

    /** @return \Dev4Press\Plugin\GDFAR\bbPress\Integration */
    public function bbpress() {
        return $this->_bbpress;
    }
}
