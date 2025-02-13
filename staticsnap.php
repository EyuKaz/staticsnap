<?php  
/**
 * Plugin Name: StaticSnap  
 * Plugin URI: https://github.com/EyuKaz/staticsnap  
 * Description: Automatically generates static HTML files for WordPress posts/pages to improve speed and security.  
 * Version: 1.0.0  
 * Author: EKAZ  
 * Author URI: https://yourwebsite.com  
 * License: GPLv2  
 * Text Domain: staticsnap  
 */  

// Prevent direct access  
if (!defined('ABSPATH')) {  
    exit; // Exit if accessed directly  
}  

// Define plugin constants  
define('STATICSNAP_VERSION', '1.0.0');  
define('STATICSNAP_DIR', WP_CONTENT_DIR . '/static/');  
define('STATICSNAP_EXCLUDED_META_KEY', '_staticsnap_excluded');  

// Load core functionality  
require_once plugin_dir_path(__FILE__) . 'includes/class-core.php';  
StaticSnap_Core::initialize();  

// Load WP-CLI commands (if CLI is available)  
if (defined('WP_CLI') && WP_CLI) {  
    require_once plugin_dir_path(__FILE__) . 'includes/class-cli.php';  
    WP_CLI::add_command('staticsnap', 'StaticSnap_CLI');  
}  

// Register activation/deactivation hooks  
register_activation_hook(__FILE__, ['StaticSnap_Core', 'activate']);  
register_deactivation_hook(__FILE__, ['StaticSnap_Core', 'deactivate']);  