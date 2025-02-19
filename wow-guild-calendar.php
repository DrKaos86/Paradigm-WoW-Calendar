<?php
/*
Plugin Name: WoW Guild Calendar
Plugin URI: https://www.guildparadigm.it
Description: Un plugin per visualizzare e gestire il calendario degli eventi di gilda di WoW con autenticazione Battle.net.
Version: 1.0
Author: Dr.Kaos
*/

if (!defined('ABSPATH')) {
    exit; // Impedisce l'accesso diretto
}

// Definizione costante per il percorso del plugin
define('WOW_GC_PLUGIN_DIR', plugin_dir_path(__FILE__));

define('WOW_GC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Inclusione dei file necessari
require_once WOW_GC_PLUGIN_DIR . 'includes/admin-page.php';
require_once WOW_GC_PLUGIN_DIR . 'includes/battlenet-auth.php';
require_once WOW_GC_PLUGIN_DIR . 'includes/calendar-sync.php';
require_once WOW_GC_PLUGIN_DIR . 'includes/event-actions.php';

// Attivazione del plugin
function wowgc_activate() {
    require_once WOW_GC_PLUGIN_DIR . 'includes/database-setup.php';
    wowgc_create_tables();
}
register_activation_hook(__FILE__, 'wowgc_activate');

// Caricamento di script e stili
function wowgc_enqueue_scripts() {
    wp_enqueue_style('wowgc-style', WOW_GC_PLUGIN_URL . 'assets/style.css');
    wp_enqueue_script('wowgc-script', WOW_GC_PLUGIN_URL . 'assets/script.js', array('jquery'), null, true);
    wp_localize_script('wowgc-script', 'wowgc_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'wowgc_enqueue_scripts');

// Shortcode per visualizzare il calendario
define('WOWGC_CALENDAR_SHORTCODE', 'wow_guild_calendar');
function wowgc_add_shortcode() {
    ob_start();
    include WOW_GC_PLUGIN_DIR . 'templates/calendar.php';
    return ob_get_clean();
}
add_shortcode(WOWGC_CALENDAR_SHORTCODE, 'wowgc_add_shortcode');
