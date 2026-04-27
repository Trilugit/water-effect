<?php
/**
 * Plugin Name:       Water Effect
 * Plugin URI:        https://github.com/watereffect/water-effect-wp
 * Description:       Añade un efecto de agua animada (ondas WebGL) a cualquier sección de tu página. Compatible con WPBakery, Elementor y cualquier tema WordPress.
 * Version:           1.0.0
 * Requires at least: 5.5
 * Requires PHP:      7.4
 * Author:            Water Effect Plugin
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       water-effect
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WATER_EFFECT_VERSION', '1.0.0' );
define( 'WATER_EFFECT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WATER_EFFECT_URL', plugin_dir_url( __FILE__ ) );

// Cargar la página de ajustes del admin
require_once WATER_EFFECT_PATH . 'admin/settings-page.php';

// ─────────────────────────────────────────────
//  Registrar y encolar scripts en el frontend
// ─────────────────────────────────────────────
function water_effect_enqueue_scripts() {
    $options = get_option( 'water_effect_options', array() );

    // No cargar si está desactivado globalmente
    if ( isset( $options['enabled'] ) && $options['enabled'] === '0' ) {
        return;
    }

    wp_enqueue_script(
        'jquery-ripples',
        WATER_EFFECT_URL . 'js/jquery.ripples.min.js',
        array( 'jquery' ),
        WATER_EFFECT_VERSION,
        true
    );

    // Pasar opciones de configuración al JS
    $selector    = ! empty( $options['selector'] )    ? $options['selector']           : '.water-effect';
    $resolution  = ! empty( $options['resolution'] )  ? intval( $options['resolution'] ) : 512;
    $drop_radius = ! empty( $options['drop_radius'] ) ? intval( $options['drop_radius'] ) : 20;
    $perturbance = ! empty( $options['perturbance'] ) ? floatval( $options['perturbance'] ) : 0.04;

    wp_add_inline_script( 'jquery-ripples', '
        jQuery(document).ready(function($) {
            var el = $("' . esc_js( $selector ) . '");
            if (el.length && el.css("background-image") !== "none") {
                el.ripples({
                    resolution:  ' . $resolution  . ',
                    dropRadius:  ' . $drop_radius . ',
                    perturbance: ' . $perturbance . '
                });
            } else if (el.length) {
                console.warn("[Water Effect] El selector \"' . esc_js( $selector ) . '\" fue encontrado pero no tiene imagen de fondo. El efecto requiere una imagen de fondo.");
            }
        });
    ' );
}
add_action( 'wp_enqueue_scripts', 'water_effect_enqueue_scripts' );

// ─────────────────────────────────────────────
//  CSS de apoyo
// ─────────────────────────────────────────────
function water_effect_enqueue_styles() {
    wp_enqueue_style(
        'water-effect-css',
        WATER_EFFECT_URL . 'css/water-effect.css',
        array(),
        WATER_EFFECT_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'water_effect_enqueue_styles' );

// ─────────────────────────────────────────────
//  Ajustes por defecto al activar el plugin
// ─────────────────────────────────────────────
function water_effect_activate() {
    $defaults = array(
        'enabled'     => '1',
        'selector'    => '.water-effect',
        'resolution'  => '512',
        'drop_radius' => '20',
        'perturbance' => '0.04',
    );
    if ( ! get_option( 'water_effect_options' ) ) {
        add_option( 'water_effect_options', $defaults );
    }
}
register_activation_hook( __FILE__, 'water_effect_activate' );

// ─────────────────────────────────────────────
//  Limpiar opciones al desinstalar
// ─────────────────────────────────────────────
function water_effect_deactivate() {
    // Las opciones se mantienen al desactivar,
    // solo se borran si el usuario desinstala desde uninstall.php
}
register_deactivation_hook( __FILE__, 'water_effect_deactivate' );
