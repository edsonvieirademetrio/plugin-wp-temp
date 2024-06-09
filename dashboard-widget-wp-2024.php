<?php
/**
 * Plugin Name: Dashboard Widget 2024
 * Description: Add a custom dashboard widget in WordPress Admin with datas API REST and React.js.
 * Version: 1.0
 * Author: Edson Vieira Demetrio
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Include the most important plugin file
$template = plugin_dir_path( __FILE__ ) . 'inc/custom-dashboard-widget.php';
if (file_exists($template)) {
    include_once $template;
} else {
    echo '<p>Template is not exist.</p>';
}

function react_plugin_shortcode()

{

wp_enqueue_script("react_plugin_js", plugin_dir_url(__FILE__) . "/build/index.js", [ "wp-element"], "0.1.0", true);

wp_enqueue_style("react_plugin_css", plugin_dir_url(__FILE__) . "/build/index.css");

 return "<div class='react-plugin'></div>";

}

add_shortcode("react-plugin", "react_plugin_shortcode");

function react_plugin_scripts_admin()

{

wp_enqueue_script("react_plugin_js", plugin_dir_url(__FILE__) . "/build/index.js", [ "wp-element"], "0.1.0", true);

wp_enqueue_style("react_plugin_css", plugin_dir_url(__FILE__) . "/build/index.css");

return "<div class='react-plugin'></div>";

}

add_shortcode("admin_enqueue_scripts", "react_plugin_scripts_admin");