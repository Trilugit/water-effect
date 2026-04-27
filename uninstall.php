<?php
/**
 * Se ejecuta solo cuando el usuario desinstala el plugin
 * desde el panel de WordPress (Plugins → Eliminar).
 * Borra las opciones guardadas en la base de datos.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'water_effect_options' );
