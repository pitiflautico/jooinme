# JoinMe - Guía de Deployment en Laravel Forge

## Requisitos del Servidor

### Especificaciones Mínimas
- **CPU**: 2 vCPUs
- **RAM**: 4GB (recomendado 8GB para producción)
- **Almacenamiento**: 40GB SSD
- **Sistema Operativo**: Ubuntu 22.04 LTS
- **PHP**: 8.3 o superior
- **MySQL**: 8.0 o superior
- **Node.js**: 20.x LTS
- **Redis**: 7.x (para caché y colas)

### Software Requerido
```bash
- PHP 8.3+ con extensiones:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - PDO_MySQL
  - Tokenizer
  - XML
  - GD
  - CURL
  - ZIP
- Composer 2.x
- Node.js 20.x + NPM
- MySQL 8.0
- Redis 7.x
- Supervisor (para queues)
```

---

## 1. Configuración Inicial en Laravel Forge

### 1.1. Crear Servidor
1. Login en Laravel Forge (https://forge.laravel.com)
2. Click en "Create Server"
3. Seleccionar proveedor (DigitalOcean, AWS, Linode, etc.)
4. Configuración recomendada:
   - **Region**: Elegir la más cercana a tus usuarios
   - **Server Size**: 4GB RAM / 2 CPU (mínimo)
   - **PHP Version**: 8.3
   - **Database**: MySQL 8.0
   - **Server Name**: `joinme-production`

### 1.2. Configurar Base de Datos
Una vez creado el servidor:
1. Ir a "Database" en el panel de Forge
2. Crear nueva base de datos:
   - **Name**: `joinme`
   - **User**: `joinme_user`
   - **Password**: (generar password seguro)
3. Anotar las credenciales para el archivo `.env`

### 1.3. Configurar Redis
1. En el servidor de Forge, ir a "Network"
2. Verificar que Redis esté instalado
3. Si no está instalado:
```bash
sudo apt-get install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

---

## 2. Despliegue del Proyecto

### 2.1. Crear Sitio en Forge
1. Click en "Sites" → "New Site"
2. Configuración:
   - **Root Domain**: `joinme.com` (o tu dominio)
   - **Project Type**: General PHP / Laravel
   - **Web Directory**: `/public`
   - **PHP Version**: 8.3

### 2.2. Conectar Repositorio Git
1. En el sitio creado, ir a "Git Repository"
2. Seleccionar GitHub
3. Configurar:
   - **Repository**: `pitiflautico/jooinme`
   - **Branch**: `main` (o la rama de producción)
   - **Deploy on push**: ✅ Activar

### 2.3. Configurar Variables de Entorno
1. Ir a "Environment" en el sitio
2. Completar el archivo `.env`:

```bash
APP_NAME=JoinMe
APP_ENV=production
APP_KEY=         # Se genera automáticamente
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://joinme.com

# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=joinme
DB_USERNAME=joinme_user
DB_PASSWORD=     # Tu password de MySQL

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache
CACHE_STORE=redis
CACHE_PREFIX=joinme_cache

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Queue
QUEUE_CONNECTION=redis

# Mail (Configura tu servicio de email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io   # Cambiar a tu proveedor
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="hello@joinme.com"
MAIL_FROM_NAME="${APP_NAME}"

# Broadcasting (opcional)
BROADCAST_CONNECTION=log

# Filesystem
FILESYSTEM_DISK=local
```

### 2.4. SSL Certificate
1. En Forge, ir a "SSL" en tu sitio
2. Click en "LetsEncrypt"
3. Forge instalará automáticamente un certificado SSL gratuito
4. ✅ Activar "Force HTTPS"

---

## 3. Script de Deployment

### 3.1. Configurar Deploy Script
En Forge, ir a "Deployment Script" y reemplazar con:

```bash
cd /home/forge/joinme.com

# Enable maintenance mode
php artisan down || true

# Update codebase
git pull origin $FORGE_SITE_BRANCH

# Install/update composer dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Install NPM dependencies and build assets
npm ci
npm run build

# Restart queue workers
php artisan queue:restart

# Optimize application
php artisan optimize

# Storage link
php artisan storage:link

# Disable maintenance mode
php artisan up
```

### 3.2. Activar Auto Deployment
1. Click en "Enable Quick Deploy"
2. Ahora cada push a la rama configurada desplegará automáticamente

---

## 4. Configurar Queue Workers

### 4.1. Supervisor para Colas
1. En Forge, ir a "Daemons"
2. Click en "New Daemon"
3. Configurar:

```bash
Command: php8.3 artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
User: forge
Directory: /home/forge/joinme.com
Processes: 2
```

4. Guardar y Forge configurará automáticamente Supervisor

---

## 5. Configurar Scheduled Tasks

### 5.1. Cron Job
Forge configura automáticamente el scheduler de Laravel. Verificar que existe en "Scheduler":

```bash
* * * * * cd /home/forge/joinme.com && php8.3 artisan schedule:run >> /dev/null 2>&1
```

Si no existe, añadirlo manualmente.

---

## 6. Servicios Externos Recomendados

### 6.1. Email Transaccional
**Opciones recomendadas:**
- **Postmark** (https://postmarkapp.com/)
  - 100 emails/mes gratis
  - Excelente deliverability
  - Configuración simple

- **SendGrid** (https://sendgrid.com/)
  - 100 emails/día gratis
  - Muy usado

- **Amazon SES**
  - Muy económico
  - Requiere más configuración

**Configuración para Postmark:**
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.postmarkapp.com
MAIL_PORT=587
MAIL_USERNAME=your-server-token
MAIL_PASSWORD=your-server-token
MAIL_ENCRYPTION=tls
```

### 6.2. Almacenamiento de Archivos
**Amazon S3 o DigitalOcean Spaces**

Para avatares, imágenes de conversaciones, etc.

```bash
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=joinme-uploads
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 6.3. Monitoreo y Logs
**Sentry** (https://sentry.io/)
- Tracking de errores
- 5000 eventos/mes gratis

```bash
composer require sentry/sentry-laravel
```

Configurar en `.env`:
```bash
SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/project-id
```

### 6.4. Backup
Usar paquete Spatie Laravel Backup:
```bash
composer require spatie/laravel-backup
```

Configurar backups automáticos en Forge:
1. Ir a "Backups"
2. Configurar backup diario de base de datos
3. Almacenar en S3

---

## 7. Optimizaciones de Producción

### 7.1. OPcache
Verificar que OPcache esté habilitado en PHP:
```bash
php -i | grep opcache
```

En Forge, esto viene habilitado por defecto.

### 7.2. MySQL Optimization
```sql
-- Aumentar límites si es necesario
SET GLOBAL max_connections = 200;
SET GLOBAL innodb_buffer_pool_size = 2G;
```

### 7.3. Redis MaxMemory
```bash
# /etc/redis/redis.conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

---

## 8. Seguridad

### 8.1. Firewall
Forge configura automáticamente UFW. Verificar:
```bash
sudo ufw status
```

Solo deben estar abiertos:
- 22 (SSH)
- 80 (HTTP)
- 443 (HTTPS)

### 8.2. Fail2Ban
Forge instala Fail2Ban automáticamente para proteger contra ataques de fuerza bruta SSH.

### 8.3. Actualizaciones
Configurar actualizaciones automáticas de seguridad:
```bash
sudo apt install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

---

## 9. Monitoreo Post-Deployment

### 9.1. Health Check
Crear endpoint de health check en `routes/web.php`:
```php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()
    ]);
});
```

### 9.2. Uptime Monitoring
Servicios recomendados:
- **Oh Dear** (https://ohdear.app/) - Integrado con Forge
- **Pingdom** (https://www.pingdom.com/)
- **UptimeRobot** (https://uptimerobot.com/) - Gratis

---

## 10. Checklist de Deployment

- [ ] Servidor creado en Forge
- [ ] Base de datos MySQL configurada
- [ ] Redis instalado y configurado
- [ ] Repositorio Git conectado
- [ ] Variables de entorno configuradas
- [ ] SSL Certificate instalado
- [ ] Deploy script configurado
- [ ] Auto-deployment activado
- [ ] Queue workers configurados
- [ ] Scheduler configurado
- [ ] Servicios externos configurados (email, storage)
- [ ] Backups automáticos configurados
- [ ] Sentry para monitoreo de errores
- [ ] Health check endpoint creado
- [ ] Uptime monitoring activado
- [ ] Firewall verificado
- [ ] Primera migración ejecutada correctamente

---

## 11. Comandos Útiles

### Desplegar Manualmente
```bash
cd /home/forge/joinme.com
bash ./deploy.sh
```

### Ver Logs
```bash
tail -f /home/forge/joinme.com/storage/logs/laravel.log
```

### Reiniciar Queue Workers
```bash
php artisan queue:restart
```

### Limpiar Caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Ver Queue Jobs
```bash
php artisan queue:work --once
```

---

## Soporte

Para problemas con Forge: https://forge.laravel.com/support
Para problemas con la aplicación: https://github.com/pitiflautico/jooinme/issues

---

**Última actualización**: 2025-11-06
**Versión de Laravel**: 11.x
**Versión de PHP**: 8.3+
