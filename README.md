# Visor de Errores - Plugin de WordPress

![WordPress Plugin](https://img.shields.io/badge/WordPress-Plugin-blue.svg)
![Version](https://img.shields.io/badge/Version-1.3-green.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)

Un plugin completo para WordPress que permite visualizar, gestionar y monitorear el archivo `debug.log` directamente desde el panel de administración.

## 📋 Características

- **Visualización de logs**: Muestra el contenido del archivo `debug.log` con resaltado de sintaxis por tipo de error
- **Descarga de archivos**: Permite descargar el archivo de log completo
- **Sistema de archivado**: Limpia y archiva automáticamente los logs con timestamp
- **Búsqueda en tiempo real**: Filtra el contenido del log por términos específicos
- **Notificaciones automáticas**: Alerta cuando se detectan nuevos errores
- **Gestión de logs antiguos**: Visualiza y accede a logs archivados previamente
- **Interfaz intuitiva**: Panel de administración fácil de usar con iconos y botones claros

## 🚀 Instalación

### Instalación Manual

1. Descarga el archivo `error-log-viewer.php`
2. Sube el archivo a la carpeta `/wp-content/plugins/error-log-viewer/` de tu sitio WordPress
3. Activa el plugin desde el panel de administración de WordPress en **Plugins > Plugins Instalados**
4. El plugin aparecerá en el menú lateral como **"Visor de Errores"**

### Instalación vía FTP

```bash
# Crear directorio del plugin
mkdir wp-content/plugins/error-log-viewer

# Subir archivo principal
upload error-log-viewer.php wp-content/plugins/error-log-viewer/
```

## 📖 Uso

### Acceso al Plugin

1. Ve al panel de administración de WordPress
2. Busca **"Visor de Errores"** en el menú lateral (icono de advertencia)
3. Haz clic para acceder al visor de logs

### Funcionalidades Principales

#### 🔍 Visualización de Logs
- Los errores se muestran con colores diferenciados:
  - **Rojo**: Fatal errors
  - **Naranja**: Warnings
  - **Azul**: Notices
- Los logs más recientes aparecen primero

#### 📥 Descargar Log
- Haz clic en **"📥 Descargar log"** para obtener una copia del archivo `debug.log`

#### 🧹 Limpiar Log
- El botón **"🧹 Limpiar log"** archiva el log actual con timestamp y crea uno nuevo vacío
- Los logs archivados se guardan como `debug.old.YYYY-MM-DD_HH-mm-ss.log`

#### 🔎 Búsqueda
- Utiliza el campo de búsqueda para filtrar errores específicos
- La búsqueda es insensible a mayúsculas y minúsculas

#### 📚 Logs Archivados
- Accede a logs anteriores desde la sección "Logs Archivados"
- Cada log archivado mantiene su timestamp de creación

## ⚙️ Requisitos del Sistema

- **WordPress**: 5.0 o superior
- **PHP**: 7.4 o superior
- **Permisos**: Capacidad `manage_options` (Administrador)
- **Archivo debug.log**: Debe existir en `wp-content/debug.log`

## 🔧 Configuración

### Habilitar Debug Log en WordPress

Para que el plugin funcione correctamente, asegúrate de tener habilitado el debug log en WordPress:

```php
// En wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Permisos de Archivos

Asegúrate de que WordPress pueda escribir en el directorio `wp-content/`:

```bash
chmod 755 wp-content/
chmod 644 wp-content/debug.log
```

## 🛡️ Seguridad

- **Control de acceso**: Solo usuarios con capacidad `manage_options` pueden acceder
- **Nonces de seguridad**: Todas las acciones están protegidas con nonces de WordPress
- **Sanitización**: Todos los inputs son sanitizados antes del procesamiento
- **Escape de salida**: Todo el contenido mostrado es escapado para prevenir XSS

## 📁 Estructura de Archivos

```
error-log-viewer/
├── error-log-viewer.php    # Archivo principal del plugin
├── README.md              # Este archivo
└── logs/                  # Logs archivados (generados automáticamente)
    ├── debug.old.2024-01-15_10-30-45.log
    └── debug.old.2024-01-14_09-15-22.log
```

## 🐛 Solución de Problemas

### El plugin no muestra logs
- Verifica que `WP_DEBUG_LOG` esté habilitado en `wp-config.php`
- Asegúrate de que el archivo `wp-content/debug.log` existe
- Comprueba los permisos de escritura en `wp-content/`

### No aparecen notificaciones de nuevos errores
- Las notificaciones solo aparecen para usuarios con permisos de administrador
- Visita la página del plugin al menos una vez para inicializar el sistema de monitoreo

### Error de permisos
- Verifica que tu usuario tenga la capacidad `manage_options`
- Solo administradores pueden acceder al plugin

## 🔄 Changelog

### Versión 1.3
- ✅ Visualización completa del debug.log con resaltado de sintaxis
- ✅ Sistema de descarga de archivos
- ✅ Archivado automático con timestamp
- ✅ Búsqueda y filtrado en tiempo real
- ✅ Notificaciones de nuevos errores
- ✅ Gestión de logs archivados
- ✅ Interfaz de usuario mejorada
- ✅ Prefijos de funciones actualizados a `yd_ve_`

## 👨‍💻 Autor

**Yogui Dev**
- GitHub: [https://github.com/yogui-dev](https://github.com/yogui-dev)
- Versión: 1.3

## 📄 Licencia

Este plugin es software libre y se distribuye bajo los términos de la Licencia Pública General de GNU (GPL) v2 o posterior.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Añadir nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📞 Soporte

Si encuentras algún problema o tienes sugerencias:

1. Revisa la sección de **Solución de Problemas**
2. Crea un issue en el repositorio de GitHub
3. Incluye detalles sobre tu versión de WordPress y PHP

---

**¿Te gusta este plugin?** ⭐ ¡Dale una estrella en GitHub!
