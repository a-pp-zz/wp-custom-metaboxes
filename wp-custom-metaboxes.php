<?php
/*
  Plugin Name: WP Custom Metaboxes
  Description: Create Dynamic Metaboxes on admin pages
  Author: CoolSwitcher
  Version: 1.0.0
  License: MIT
*/
require_once __DIR__ . '/src/wp-custom-metaboxes.php';
add_action ('admin_footer', array ('\AppZz\WP\Plugins\Metaboxes', 'footer'));
