<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ─────────────────────────────────────────────
//  Registrar menú en el admin
// ─────────────────────────────────────────────
function water_effect_admin_menu() {
    add_options_page(
        'Water Effect',
        'Water Effect',
        'manage_options',
        'water-effect',
        'water_effect_settings_page'
    );
}
add_action( 'admin_menu', 'water_effect_admin_menu' );

// ─────────────────────────────────────────────
//  Registrar ajustes
// ─────────────────────────────────────────────
function water_effect_register_settings() {
    register_setting(
        'water_effect_group',
        'water_effect_options',
        array(
            'sanitize_callback' => 'water_effect_sanitize_options',
            'default'           => array(
                'enabled'     => '1',
                'selector'    => '.water-effect',
                'resolution'  => '512',
                'drop_radius' => '20',
                'perturbance' => '0.04',
            ),
        )
    );
}
add_action( 'admin_init', 'water_effect_register_settings' );

// ─────────────────────────────────────────────
//  Sanitizar antes de guardar
// ─────────────────────────────────────────────
function water_effect_sanitize_options( $input ) {
    $output = array();
    $output['enabled']     = isset( $input['enabled'] ) ? '1' : '0';
    $output['selector']    = sanitize_text_field( $input['selector'] ?? '.water-effect' );
    $output['resolution']  = min( 1024, max( 128, intval( $input['resolution'] ?? 512 ) ) );
    $output['drop_radius'] = min( 100, max( 5, intval( $input['drop_radius'] ?? 20 ) ) );
    $output['perturbance'] = min( 0.5, max( 0.001, floatval( $input['perturbance'] ?? 0.04 ) ) );
    return $output;
}

// ─────────────────────────────────────────────
//  CSS del admin
// ─────────────────────────────────────────────
function water_effect_admin_styles( $hook ) {
    if ( $hook !== 'settings_page_water-effect' ) {
        return;
    }
    echo '<style>
        .we-wrap { max-width: 860px; margin-top: 20px; }
        .we-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 24px 28px; margin-bottom: 24px; }
        .we-card h2 { margin-top: 0; font-size: 16px; color: #1d2327; border-bottom: 1px solid #f0f0f1; padding-bottom: 12px; margin-bottom: 18px; }
        .we-card h2 span { font-size: 20px; margin-right: 6px; }
        .we-steps { counter-reset: step; }
        .we-step { display: flex; gap: 16px; margin-bottom: 20px; align-items: flex-start; }
        .we-step-num { counter-increment: step; background: #0073aa; color: #fff; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; margin-top: 2px; }
        .we-step-body h3 { margin: 0 0 6px; font-size: 14px; }
        .we-step-body p { margin: 0 0 8px; color: #50575e; font-size: 13px; line-height: 1.6; }
        .we-code { background: #f6f7f7; border: 1px solid #ddd; border-radius: 4px; padding: 10px 14px; font-family: monospace; font-size: 13px; display: block; margin: 6px 0; color: #1d2327; }
        .we-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
        .we-badge-blue { background: #e8f4ff; color: #0073aa; }
        .we-badge-green { background: #edfaef; color: #1a7f35; }
        .we-badge-orange { background: #fff3e0; color: #c45f00; }
        .we-tip { background: #f0f6fc; border-left: 4px solid #0073aa; padding: 10px 14px; border-radius: 0 4px 4px 0; font-size: 13px; color: #1d2327; margin-top: 8px; }
        .we-warning { background: #fff8e5; border-left: 4px solid #dba617; padding: 10px 14px; border-radius: 0 4px 4px 0; font-size: 13px; color: #5c4813; margin-top: 8px; }
        .we-form-table td { padding: 12px 0; }
        .we-form-table label { font-weight: 600; font-size: 13px; }
        .we-form-table input[type=text], .we-form-table input[type=number] { width: 200px; }
        .we-form-table .description { font-size: 12px; color: #646970; margin-top: 4px; display: block; }
        .we-toggle { display: flex; align-items: center; gap: 10px; }
        .we-status { font-size: 13px; }
        .we-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 600px) { .we-grid { grid-template-columns: 1fr; } }
        .we-compat-item { display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .we-compat-item::before { content: "✓"; color: #1a7f35; font-weight: 700; }
    </style>';
}
add_action( 'admin_head', 'water_effect_admin_styles' );

// ─────────────────────────────────────────────
//  Renderizar la página de ajustes
// ─────────────────────────────────────────────
function water_effect_settings_page() {
    $options = get_option( 'water_effect_options', array() );
    $enabled     = $options['enabled']     ?? '1';
    $selector    = $options['selector']    ?? '.water-effect';
    $resolution  = $options['resolution']  ?? '512';
    $drop_radius = $options['drop_radius'] ?? '20';
    $perturbance = $options['perturbance'] ?? '0.04';
    ?>
    <div class="wrap we-wrap">
        <h1>💧 Water Effect</h1>
        <p style="color:#50575e; margin-bottom:0">Efecto de ondas de agua animadas con WebGL para tu WordPress.</p>

        <?php settings_errors( 'water_effect_options' ); ?>

        <div style="display:grid; grid-template-columns: 1fr 340px; gap: 24px; margin-top: 20px; align-items: start;">

            <!-- Columna izquierda -->
            <div>

                <!-- INSTRUCCIONES -->
                <div class="we-card">
                    <h2><span>📋</span> Cómo usar el plugin</h2>
                    <div class="we-steps">

                        <div class="we-step">
                            <div class="we-step-num">1</div>
                            <div class="we-step-body">
                                <h3>Activa el plugin</h3>
                                <p>Ya está hecho si estás viendo esta página. El plugin está cargando la librería jQuery Ripples automáticamente en todas las páginas.</p>
                            </div>
                        </div>

                        <div class="we-step">
                            <div class="we-step-num">2</div>
                            <div class="we-step-body">
                                <h3>Añade una imagen de fondo a tu sección</h3>
                                <p>El efecto de agua <strong>necesita obligatoriamente una imagen de fondo</strong> en el elemento. Sin ella, el efecto no es visible.</p>
                                <span class="we-badge we-badge-blue">WPBakery</span>
                                <p>Edita la fila → pestaña <em>Diseño</em> → <em>Imagen de fondo</em>.</p>
                                <span class="we-badge we-badge-blue">Elementor</span>
                                <p>Edita la sección → pestaña <em>Estilo</em> → <em>Fondo</em> → tipo <em>Clásico</em> → sube una imagen.</p>
                                <span class="we-badge we-badge-orange">CSS manual</span>
                                <p>También puedes añadirlo con CSS personalizado:</p>
                                <code class="we-code">.water-effect {
  background-image: url('URL-DE-TU-IMAGEN.jpg') !important;
  background-size: cover;
  background-position: center;
}</code>
                            </div>
                        </div>

                        <div class="we-step">
                            <div class="we-step-num">3</div>
                            <div class="we-step-body">
                                <h3>Añade la clase CSS a la sección</h3>
                                <p>El selector por defecto es <code>.water-effect</code>. Añade esa clase al elemento que quieres animar.</p>
                                <span class="we-badge we-badge-blue">WPBakery</span>
                                <p>Edita la fila → pestaña <em>Diseño</em> → campo <em>Clase CSS extra</em> → escribe:</p>
                                <code class="we-code">water-effect</code>
                                <span class="we-badge we-badge-blue">Elementor</span>
                                <p>Edita la sección → pestaña <em>Avanzado</em> → campo <em>Clase CSS</em> → escribe:</p>
                                <code class="we-code">water-effect</code>
                                <div class="we-tip">💡 Puedes usar cualquier clase o selector CSS. Cámbialo en los <strong>Ajustes</strong> de abajo.</div>
                            </div>
                        </div>

                        <div class="we-step">
                            <div class="we-step-num">4</div>
                            <div class="we-step-body">
                                <h3>Verifica que funciona</h3>
                                <p>Visita la página y mueve el ratón sobre la sección. Deberías ver ondas de agua en la imagen de fondo.</p>
                                <p>Si no funciona, abre la consola del navegador (F12 → Console) y ejecuta:</p>
                                <code class="we-code">jQuery('.water-effect').length</code>
                                <p>Debe devolver <strong>1</strong> o más. Si devuelve <strong>0</strong>, la clase no está aplicada correctamente.</p>
                                <div class="we-warning">⚠️ El efecto requiere WebGL. No funcionará en navegadores muy antiguos ni en algunos móviles. Se degrada silenciosamente (sin errores).</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- AJUSTES -->
                <div class="we-card">
                    <h2><span>⚙️</span> Ajustes</h2>
                    <form method="post" action="options.php">
                        <?php settings_fields( 'water_effect_group' ); ?>
                        <table class="we-form-table" style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="width:200px; vertical-align:top; padding-top:14px;">
                                    <label>Estado del plugin</label>
                                </td>
                                <td>
                                    <div class="we-toggle">
                                        <input type="checkbox" id="we-enabled" name="water_effect_options[enabled]" value="1" <?php checked( $enabled, '1' ); ?> />
                                        <label for="we-enabled" class="we-status">
                                            <?php echo $enabled === '1' ? '✅ Activo — el efecto se carga en el frontend' : '⏸️ Desactivado'; ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top; padding-top:14px;">
                                    <label for="we-selector">Selector CSS</label>
                                </td>
                                <td>
                                    <input type="text" id="we-selector" name="water_effect_options[selector]" value="<?php echo esc_attr( $selector ); ?>" />
                                    <span class="description">Clase o selector del elemento donde se aplica el efecto.<br>Ejemplo: <code>.water-effect</code> o <code>#mi-hero</code> o <code>.l-section.hero</code></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top; padding-top:14px;">
                                    <label for="we-resolution">Resolución</label>
                                </td>
                                <td>
                                    <input type="number" id="we-resolution" name="water_effect_options[resolution]" value="<?php echo esc_attr( $resolution ); ?>" min="128" max="1024" step="128" />
                                    <span class="description">Calidad del efecto. Valores: 128, 256, 512, 1024.<br>Mayor resolución = más calidad pero más lento. <strong>Recomendado: 512</strong></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top; padding-top:14px;">
                                    <label for="we-drop-radius">Radio de las ondas</label>
                                </td>
                                <td>
                                    <input type="number" id="we-drop-radius" name="water_effect_options[drop_radius]" value="<?php echo esc_attr( $drop_radius ); ?>" min="5" max="100" />
                                    <span class="description">Tamaño de las ondas al mover el ratón. (5–100)<br><strong>Recomendado: 20</strong></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top; padding-top:14px;">
                                    <label for="we-perturbance">Intensidad</label>
                                </td>
                                <td>
                                    <input type="text" id="we-perturbance" name="water_effect_options[perturbance]" value="<?php echo esc_attr( $perturbance ); ?>" />
                                    <span class="description">Fuerza de la distorsión de la imagen. (0.001–0.5)<br>0.01 = muy sutil · 0.04 = normal · 0.1 = intenso. <strong>Recomendado: 0.04</strong></span>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button( 'Guardar ajustes', 'primary', 'submit', true, array( 'style' => 'margin-top: 10px;' ) ); ?>
                    </form>
                </div>

            </div>

            <!-- Columna derecha -->
            <div>

                <!-- COMPATIBILIDAD -->
                <div class="we-card">
                    <h2><span>✅</span> Compatibilidad</h2>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div class="we-compat-item">WPBakery Page Builder</div>
                        <div class="we-compat-item">Elementor</div>
                        <div class="we-compat-item">Gutenberg (bloques)</div>
                        <div class="we-compat-item">Tema Impreza</div>
                        <div class="we-compat-item">Cualquier tema WordPress</div>
                        <div class="we-compat-item">Chrome, Firefox, Edge</div>
                        <div class="we-compat-item">XAMPP / local</div>
                        <div class="we-compat-item">Servidor de producción</div>
                    </div>
                    <div class="we-warning" style="margin-top:14px;">⚠️ Requiere WebGL en el navegador. Safari y móviles pueden tener soporte limitado.</div>
                </div>

                <!-- CÓMO FUNCIONA -->
                <div class="we-card">
                    <h2><span>🔬</span> Cómo funciona</h2>
                    <p style="font-size:13px; color:#50575e; line-height:1.7; margin:0">
                        El plugin carga la librería <strong>jQuery Ripples</strong> (WebGL) y la inicializa sobre el selector configurado. Al detectar movimiento del ratón, genera ondas de agua que distorsionan la imagen de fondo mediante shaders GLSL en tiempo real.
                    </p>
                    <p style="font-size:13px; color:#50575e; line-height:1.7; margin-top:10px; margin-bottom:0">
                        Si el navegador no soporta WebGL, el efecto simplemente no aparece — sin errores ni rotura del diseño.
                    </p>
                </div>

                <!-- VERSIÓN -->
                <div class="we-card">
                    <h2><span>📦</span> Información</h2>
                    <table style="width:100%; font-size:13px; color:#50575e;">
                        <tr><td style="padding: 4px 0;"><strong>Versión</strong></td><td>1.0.0</td></tr>
                        <tr><td style="padding: 4px 0;"><strong>jQuery Ripples</strong></td><td>0.6.3</td></tr>
                        <tr><td style="padding: 4px 0;"><strong>WordPress mín.</strong></td><td>5.5</td></tr>
                        <tr><td style="padding: 4px 0;"><strong>PHP mín.</strong></td><td>7.4</td></tr>
                        <tr><td style="padding: 4px 0;"><strong>Licencia</strong></td><td>GPL v2+</td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <?php
}
