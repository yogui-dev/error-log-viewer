# Changelog

Todos los cambios notables de este proyecto serán documentados en este archivo.

## [1.3.0] - 2024-07-29

### Añadido
- Plugin completo de WordPress para gestión de debug.log
- Visualización de logs con resaltado de sintaxis por tipo de error (Fatal, Warning, Notice)
- Sistema de descarga de archivos de log
- Archivado automático de logs con timestamp
- Búsqueda y filtrado en tiempo real
- Notificaciones automáticas para nuevos errores en el admin
- Gestión y visualización de logs archivados
- Interfaz de administración intuitiva con iconos
- Control de acceso con permisos de WordPress
- Sanitización y escape de datos para seguridad
- Nonces de seguridad para todas las acciones
- Prefijos de funciones `yd_ve_` para evitar conflictos
- Documentación completa en README.md
- Archivo de licencia GPL v2
- Archivo de seguridad index.php

### Características técnicas
- Compatible con WordPress 5.0+
- Requiere PHP 7.4+
- Permisos de administrador (`manage_options`)
- Detección automática de cambios en el tamaño del log
- Reversión cronológica de logs (más recientes primero)
- Manejo seguro de archivos y directorios

### Seguridad
- Verificación de capacidades de usuario
- Sanitización de inputs con `sanitize_text_field()` y `sanitize_file_name()`
- Escape de outputs con `esc_html()`, `esc_attr()`, `esc_url()`
- Protección con nonces de WordPress
- Prevención de acceso directo con `ABSPATH`
