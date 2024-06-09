<?php

// Impede o acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Função para registrar o widget no painel do WordPress
function cdw_register_dashboard_widget() {
    wp_add_dashboard_widget(
        'cdw_dashboard_widget',
        'Custom Dashboard Widget',
        'cdw_render_dashboard_widget',
        array(                           // Argumentos adicionais, incluindo a prioridade.
            'position' => 0              // Define a prioridade como 0 para exibir no topo.
        )
    );
}
add_action( 'wp_dashboard_setup', 'cdw_register_dashboard_widget' );

// Função para renderizar o conteúdo do widget
function cdw_render_dashboard_widget() {
    echo '<div id="cdw-react-app" class="cdw-widget-container"></div>';
    // echo do_shortcode( "[react-plugin]");
}

// Carrega os scripts do React e do plugin
function cdw_enqueue_admin_scripts($hook) {
    if ( 'index.php' !== $hook ) {
        return;
    }
  
    // Passa a URL da API REST para o script do plugin
    wp_localize_script( 'cdw-dashboard-widget', 'cdwSettings', array(
        'apiUrl' => esc_url_raw( rest_url( 'cdw/v1/data' ) ),
    ) );

    wp_enqueue_script("react_plugin_js", plugin_dir_url(__FILE__) . "../build/index.js", [ "wp-element"], "0.1.0", true);

    wp_enqueue_style("react_plugin_css", plugin_dir_url(__FILE__) . "../build/index.css");
}
add_action( 'admin_enqueue_scripts', 'cdw_enqueue_admin_scripts' );

// Registra o endpoint da API REST
function cdw_register_rest_route() {
    register_rest_route( 'cdw/v1', '/data', array(
        'methods'  => 'GET',
        'callback' => 'cdw_get_data',
    ) );
}
add_action( 'rest_api_init', 'cdw_register_rest_route' );

// Função que retorna os dados do banco de dados
function cdw_get_data() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM `wp_visits_table`", ARRAY_A );

    return rest_ensure_response( $results );
}
