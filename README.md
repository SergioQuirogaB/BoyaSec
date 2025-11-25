## BoyaSec SIEM Lite

Proyecto SIEM liviano construido con PHP puro, Tailwind CSS (CDN) y MySQL. Permite cargar logs, normalizarlos, aplicar reglas básicas y generar alertas con un dashboard operativo.

### Características
- Ingreso con usuario administrador y sesiones PHP.
- Carga de archivos `.log`, `.txt` o `.csv`.
- Normalización de logs Apache/Nginx o simples.
- Motor de reglas con detección de fuerza bruta y escaneo.
- Dashboard con métricas (top IPs, métodos, status, alertas recientes).
- CRUD de reglas y servicio de alertas listo para reportes PDF futuros.

### Requisitos
- PHP 8.1+
- MySQL 8+
- Extensión PDO MySQL habilitada
- Servidor local (XAMPP/WAMP/Laragon)

### Instalación en XAMPP
1. Clonar o copiar este repositorio en `htdocs/BoyaSec`.
2. Crear una base de datos llamada `boya_siem`.
3. Importar `database/migrations.sql` y luego los datos de ejemplo `database/qa.sql` (opcional).
4. Actualizar credenciales en `config/env.php` si es necesario.
5. Iniciar Apache y MySQL desde el panel de XAMPP.
6. Acceder a `http://localhost/BoyaSec/index.php`.

Usuario por defecto: `admin`  
Contraseña: `admin123`

### Configuración de la base de datos
`config/env.php` centraliza host, puerto, nombre de base y credenciales. `config/database.php` expone la función `get_pdo()` para toda la app.

Tablas principales:
- `users` – autenticación.
- `logs_raw` – almacenamiento del archivo original.
- `logs_normalized` – eventos normalizados.
- `rules` – reglas del motor.
- `alerts` – alertas creadas por el motor.

### Flujo para subir logs
1. Iniciar sesión y navegar a `admin/upload.php`.
2. Seleccionar archivo `.log/.txt/.csv` y la fuente (Apache/Nginx/Custom).
3. El controlador guarda el log crudo, normaliza cada línea y ejecuta el motor de reglas.
4. Los nuevos eventos se visualizan en `admin/logs.php` y las alertas en `admin/alerts.php`.

### Arquitectura por módulos
- `config/` – `env.php` centraliza el entorno y `database.php` expone `get_pdo()` con PDO configurado.
- `src/Controllers/` – Capa de orquestación (`AuthController`, `LogController`, `RuleController`, `AlertController`) que conecta vistas con modelos/servicios.
- `src/Models/` – Acceso a datos para `User`, `LogRaw`, `LogNormalized`, `Rule` y `Alert` con consultas PDO tipadas.
- `src/Services/` – Lógica de negocio clave:
  - `LogNormalizer` interpreta formatos Apache/Nginx/CSV y genera el esquema común.
  - `RuleEngine` evalúa reglas activas (fuerza bruta y scanning) y dispara alertas.
  - `AlertService` centraliza la inserción de alertas y deja listo el hook `preparePdfReport()`.
- `src/Helpers/utils.php` – Autoload automático por namespaces, helpers de sesión y flashes.
- `components/` – UI reusable (`navbar`, `sidebar`, `card`) con Tailwind y estados activos.
- `layouts/` – `auth.php` (login) y `main.php` (panel) con glassmorphism y gradientes.
- `admin/` – Vistas funcionales: panel, carga de logs, listados de logs/alertas y CRUD de reglas.
- `database/` – `migrations.sql` crea todo el esquema y `qa.sql` agrega datos de prueba.
- `assets/` – Carpeta lista para CSS/JS personalizados si se necesitan extensiones futuras.

### Motor de alertas
- **Brute Force:** cuenta respuestas 401/403 por IP en una ventana temporal y genera alerta al superar el threshold.
- **Scanning:** detecta múltiples 404 o accesos a rutas sensibles (`/.env`, `/admin`, `/wp-login`, etc.).
- Las reglas se administran desde `admin/rules.php` con activación/desactivación rápida.

### Reportes PDF
`src/Services/AlertService.php` incluye el método `preparePdfReport()` como punto de extensión para futuras exportaciones usando bibliotecas como TCPDF o Dompdf.

### Cómo presentar el proyecto en un CV
- Resalta que se trata de un SIEM lite desarrollado con PHP puro y arquitectura MVC.
- Menciona la normalización de logs, el motor de reglas y la generación automática de alertas.
- Enfatiza el uso de PDO (prepared statements), sesiones seguras y un dashboard responsive con Tailwind.
