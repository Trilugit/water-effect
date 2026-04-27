=== Water Effect ===
Contributors: watereffect
Tags: water, ripple, effect, animation, webgl, background, hero
Requires at least: 5.5
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Añade un efecto de agua animada (ondas WebGL) a cualquier sección de tu página WordPress.

== Descripción ==

Water Effect es un plugin ligero que añade un efecto de ondas de agua realista sobre la imagen de fondo de cualquier sección de tu página. Funciona con WPBakery, Elementor, Gutenberg y cualquier tema WordPress.

El efecto usa WebGL y shaders GLSL para simular agua en tiempo real. Cuando el visitante mueve el ratón sobre la sección, se generan ondas que distorsionan la imagen de fondo de forma fluida y realista.

= Características =

* Efecto de agua WebGL de alta calidad
* Compatible con WPBakery, Elementor y Gutenberg
* Panel de ajustes intuitivo con instrucciones paso a paso
* Selector CSS configurable (aplica a cualquier elemento)
* Controles de resolución, tamaño de ondas e intensidad
* Degradación elegante: si el navegador no soporta WebGL, simplemente no aparece
* Sin dependencias externas de CDN — todo se sirve localmente
* Licencia GPL v2+

= Cómo usar =

1. Activa el plugin
2. Añade una imagen de fondo a la sección que quieras animar
3. Añade la clase CSS `water-effect` a esa sección
4. ¡Listo! Mueve el ratón sobre la sección para ver el efecto

Instrucciones detalladas disponibles en **Ajustes → Water Effect**.

= Requisitos =

* WordPress 5.5 o superior
* PHP 7.4 o superior
* jQuery (incluido en WordPress)
* Navegador con soporte WebGL (Chrome, Firefox, Edge modernos)

== Instalación ==

1. Sube la carpeta `water-effect` al directorio `/wp-content/plugins/`
2. Activa el plugin desde el menú **Plugins** en WordPress
3. Ve a **Ajustes → Water Effect** para ver las instrucciones y configurar el efecto

== Preguntas frecuentes ==

= El efecto no aparece. ¿Qué hago? =

Comprueba estos puntos en orden:
1. La sección tiene que tener una **imagen de fondo** configurada (es obligatorio)
2. La clase `water-effect` (o el selector que hayas configurado) está aplicada a esa sección
3. Tu navegador soporta WebGL — prueba en Chrome o Firefox

Puedes verificar desde la consola del navegador (F12):
`jQuery('.water-effect').length` debe devolver 1 o más.

= ¿Funciona en móviles? =

El efecto responde a eventos táctiles (`touchstart`, `touchmove`), pero el soporte de WebGL en móviles varía. En dispositivos sin soporte el efecto simplemente no aparece, sin romper el diseño.

= ¿Puedo aplicarlo a más de una sección? =

Sí. El selector `.water-effect` afecta a todos los elementos con esa clase en la página.

= ¿Funciona con Elementor? =

Sí. Edita la sección → pestaña **Avanzado** → campo **Clase CSS** → escribe `water-effect`.

= ¿Funciona con WPBakery? =

Sí. Edita la fila → pestaña **Diseño** → campo **Clase CSS extra** → escribe `water-effect`.

= ¿Puedo cambiar el selector CSS? =

Sí, desde **Ajustes → Water Effect** puedes poner cualquier selector CSS válido.

= ¿Borra datos al desinstalar? =

Sí. Al desinstalar el plugin (no solo desactivar) se borran todas las opciones guardadas en la base de datos.

== Capturas de pantalla ==

1. Panel de ajustes con instrucciones paso a paso
2. Efecto de agua sobre una imagen de fondo en el hero de una página

== Registro de cambios ==

= 1.0.0 =
* Versión inicial
* Integración de jQuery Ripples 0.6.3 con corrección de compatibilidad WordPress (noConflict)
* Panel de ajustes con instrucciones, selector CSS, resolución, radio de ondas e intensidad configurables
* Soporte para WPBakery, Elementor y Gutenberg
* Limpieza de base de datos al desinstalar

== Créditos ==

Este plugin incluye la librería [jQuery Ripples](https://github.com/sirxemic/jquery.ripples) de sirxemic, licenciada bajo MIT.
