<?php

/**
 * Plugin Name: Error Log Viewer
 * Description: Muestra, descarga y limpia el archivo debug.log desde el panel de administración, con filtro y resaltado.
 * Version: 1.3.0
 * Author: Yogui Dev
 * Author URI: https://github.com/yogui-dev
 */

if (!defined('ABSPATH')) exit;

define('YD_VE_ERROR_LOG_PATH', ABSPATH . 'wp-content/debug.log');

add_action('admin_menu', 'yd_ve_error_log_viewer_menu');

function yd_ve_error_log_viewer_menu()
{
    add_menu_page(
        'Error Log Viewer',
        'Error Log Viewer',
        'manage_options',
        'em-error-log-viewer',
        'yd_ve_error_log_viewer_page',
        'dashicons-warning',
        80
    );
}

function yd_ve_error_log_viewer_page()
{
    if (!current_user_can('manage_options')) wp_die('No tienes permisos para ver esto.');

    // Descargar el archivo
    if (isset($_GET['download']) && file_exists(YD_VE_ERROR_LOG_PATH)) {
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=debug.log');
        header('Content-Length: ' . filesize(YD_VE_ERROR_LOG_PATH));
        readfile(YD_VE_ERROR_LOG_PATH);
        exit;
    }

    // Limpiar el archivo (renombrar y crear nuevo vacío)
    if (isset($_POST['clear_log']) && check_admin_referer('yd_ve_clear_log')) {
        $timestamp = date('Y-m-d_H-i-s');
        $old_log_path = dirname(YD_VE_ERROR_LOG_PATH) . "/debug.old.$timestamp.log";

        if (file_exists(YD_VE_ERROR_LOG_PATH)) {
            // Renombrar el log
            rename(YD_VE_ERROR_LOG_PATH, $old_log_path);
            // Crear archivo nuevo vacío
            file_put_contents(YD_VE_ERROR_LOG_PATH, '');
            echo '<div class="notice notice-success"><p>Log archivado como <code>' . esc_html(basename($old_log_path)) . '</code> y nuevo archivo creado.</p></div>';
        } else {
            echo '<div class="notice notice-warning"><p>No se encontró el archivo debug.log para archivar.</p></div>';
        }
    }


    echo '<div class="wrap"><h1>Error Log Viewer (debug.log)</h1>';

    if (!file_exists(YD_VE_ERROR_LOG_PATH)) {
        echo '<p><strong>No se encontró el archivo debug.log.</strong></p></div>';
        return;
    }

    $selected_log = isset($_GET['logfile']) ? sanitize_file_name($_GET['logfile']) : '';
    $log_path = $selected_log ? dirname(YD_VE_ERROR_LOG_PATH) . '/' . $selected_log : YD_VE_ERROR_LOG_PATH;

    if (!file_exists($log_path)) {
        echo '<p><strong>No se encontró el archivo seleccionado.</strong></p></div>';
        return;
    }

    $log_content = file_get_contents($log_path);
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    echo '<form method="get" style="margin-bottom:10px;">';
    echo '<input type="hidden" name="page" value="em-error-log-viewer" />';
    echo '<input type="text" name="s" value="' . esc_attr($search_term) . '" placeholder="Buscar en el log..." />';
    echo '<button class="button">Buscar</button>';
    echo '</form>';

    echo '<p><a class="button button-primary" href="' . admin_url('admin.php?page=em-error-log-viewer&download=1') . '">📥 Descargar log</a></p>';

    echo '<form method="post" style="margin-top:10px;">';
    wp_nonce_field('yd_ve_clear_log');
    echo '<button type="submit" name="clear_log" class="button">🧹 Limpiar log</button>';
    echo '</form>';

    echo '<div style="background:#fff; border:1px solid #ccc; padding:10px; max-height:600px; overflow:auto; margin-top:20px; font-family:monospace;">';

    $lines = explode(PHP_EOL, $log_content);
    $lines = array_reverse($lines); // Muestra últimos errores arriba

    foreach ($lines as $line) {
        if ($search_term && stripos($line, $search_term) === false) continue;

        $style = '';
        if (stripos($line, 'Fatal error') !== false) $style = 'color:red;';
        elseif (stripos($line, 'Warning') !== false) $style = 'color:orange;';
        elseif (stripos($line, 'Notice') !== false) $style = 'color:blue;';

        echo '<div style="' . esc_attr($style) . '">' . esc_html($line) . '</div>';
    }

    // Mostrar lista de logs antiguos
    yd_ve_list_old_logs($selected_log);
    echo '</div></div>';
}

add_action('admin_notices', 'yd_ve_check_debug_settings');

function yd_ve_check_debug_settings()
{
    if (!current_user_can('manage_options')) return;

    $debug_enabled     = defined('WP_DEBUG') && WP_DEBUG;
    $debug_log_enabled = defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;

    if (!$debug_enabled || !$debug_log_enabled) {
        echo '<div class="notice notice-error">';
        echo '<p><strong>Atención:</strong> El log de errores de WordPress no está activo.</p>';
        echo '<p>Asegúrate de tener estas líneas en tu <code>wp-config.php</code>:</p>';
        echo '<pre style="background:#f1f1f1; padding:10px; border-radius:5px;">define(\'WP_DEBUG\', true);
define(\'WP_DEBUG_LOG\', true);</pre>';
        echo '</div>';
    }
}


// Hook para mostrar aviso en el admin si hay nuevos errores
add_action('admin_notices', 'yd_ve_show_new_errors_notice');

function yd_ve_show_new_errors_notice()
{
    if (!current_user_can('manage_options')) return;

    $log_path = YD_VE_ERROR_LOG_PATH;
    if (!file_exists($log_path)) return;

    $current_size = filesize($log_path);
    $stored_size = get_option('yd_ve_error_log_last_size', 0);

    // Si hay nuevo contenido
    if ($current_size > $stored_size) {
        $size_diff = $current_size - $stored_size;
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>Nuevo error detectado:</strong> El archivo <code>debug.log</code> ha aumentado en <code>' . size_format($size_diff) . '</code>. ';
        echo '<a href="' . admin_url('admin.php?page=em-error-log-viewer') . '">Revisar log</a>.</p>';
        echo '</div>';
    }
}

// Al visitar el visor, actualizar tamaño del log
add_action('admin_init', function () {
    if (!current_user_can('manage_options')) return;

    if (isset($_GET['page']) && $_GET['page'] === 'em-error-log-viewer') {
        if (file_exists(YD_VE_ERROR_LOG_PATH)) {
            update_option('yd_ve_error_log_last_size', filesize(YD_VE_ERROR_LOG_PATH));
        }
    }
});

function yd_ve_list_old_logs($selected_file = '')
{
    $dir = dirname(YD_VE_ERROR_LOG_PATH);
    $files = glob($dir . '/debug.old.*.log');

    if (!$files) {
        echo '<p><em>No hay logs antiguos.</em></p>';
        return;
    }

    echo '<h2 style="margin-top:40px;">Logs Archivados</h2>';
    echo '<ul style="list-style-type:disc; padding-left:20px;">';
    foreach ($files as $file) {
        $basename = basename($file);
        $link = admin_url('admin.php?page=em-error-log-viewer&logfile=' . urlencode($basename));
        $active = ($basename === $selected_file) ? 'font-weight:bold;' : '';
        echo '<li><a href="' . esc_url($link) . '" style="' . $active . '">' . esc_html($basename) . '</a></li>';
    }
    echo '</ul>';
}
