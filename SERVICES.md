# JoinMe - Servicios para Producción

## Stack Completo Recomendado

Este documento detalla todos los servicios externos recomendados para JoinMe en producción, con costos aproximados mensuales.

---

## 1. Hosting & Infrastructure

### Laravel Forge (Gestión de Servidores)
- **Proveedor**: https://forge.laravel.com
- **Función**: Gestión automatizada de servidores
- **Precio**: $19/mes (hasta 5 servidores)
- **Incluye**:
  - Deploy automático
  - SSL certificates
  - Configuración de queues
  - Scheduler management
  - Database backups
  - Site isolation

### Servidor VPS
**Opción 1: DigitalOcean** (Recomendado)
- **URL**: https://digitalocean.com
- **Precio**: $24-48/mes
  - Basic: 2GB RAM / 2 vCPU / 60GB SSD ($24/mes)
  - Production: 4GB RAM / 2 vCPU / 80GB SSD ($48/mes)
- **Pros**: Simple, confiable, bien integrado con Forge

**Opción 2: Hetzner** (Más económico)
- **URL**: https://hetzner.com
- **Precio**: €4.90-€13.90/mes
  - CX21: 2 vCPU / 4GB RAM / 40GB SSD (€5.83/mes)
  - CX31: 2 vCPU / 8GB RAM / 80GB SSD (€11.88/mes)
- **Pros**: Muy económico, buena calidad
- **Contras**: Soporte en inglés/alemán

**Opción 3: AWS Lightsail**
- **URL**: https://aws.amazon.com/lightsail/
- **Precio**: $12-40/mes
  - 2GB RAM: $12/mes
  - 4GB RAM: $24/mes
  - 8GB RAM: $40/mes

**Recomendación**: DigitalOcean para empezar, migrar a AWS cuando escales.

---

## 2. Base de Datos

### MySQL Gestionado (Opcional)
Si prefieres base de datos separada del servidor principal:

**DigitalOcean Managed Database**
- **Precio**: $15/mes (mínimo)
  - 1GB RAM / 10GB Storage / 1 Node
- **Pros**: Backups automáticos, alta disponibilidad
- **Cuándo usarlo**: Cuando tengas >1000 usuarios activos

**PlanetScale** (MySQL Serverless)
- **URL**: https://planetscale.com
- **Precio**:
  - Hobby: Gratis (1 database)
  - Scaler: $39/mes (production-ready)
- **Pros**: Auto-scaling, branching
- **Contras**: No soporta foreign keys

**Recomendación Inicial**: Usar MySQL del mismo servidor hasta tener 500+ usuarios.

---

## 3. Email Transaccional

### Postmark (Recomendado)
- **URL**: https://postmarkapp.com
- **Precio**:
  - 100 emails/mes: Gratis
  - 10,000 emails/mes: $15
  - 50,000 emails/mes: $65
- **Pros**:
  - Mejor deliverability
  - Muy fácil de configurar
  - Excelente soporte
  - Templates incluidos
- **Cuándo**: Desde día 1

### SendGrid (Alternativa)
- **URL**: https://sendgrid.com
- **Precio**:
  - 100 emails/día: Gratis
  - 40,000 emails/mes: $15
  - 100,000 emails/mes: $60
- **Pros**: Más barato para alto volumen
- **Contras**: Deliverability algo inferior

### Amazon SES (Para Escala)
- **URL**: https://aws.amazon.com/ses/
- **Precio**: $0.10 por 1,000 emails
- **Pros**: Extremadamente económico a escala
- **Contras**: Requiere más configuración técnica

**Recomendación**: Postmark para empezar.

---

## 4. Almacenamiento de Archivos

### DigitalOcean Spaces
- **URL**: https://digitalocean.com/products/spaces
- **Precio**: $5/mes (250GB + 1TB transfer)
- **Compatible con**: S3 API
- **Incluye**: CDN
- **Ideal para**: Avatares, imágenes de conversaciones

### Amazon S3 + CloudFront
- **URL**: https://aws.amazon.com/s3/
- **Precio**:
  - S3: ~$0.023 por GB/mes
  - CloudFront: ~$0.085 por GB transfer
  - Primeros 50GB transfer/mes gratis
- **Pros**: Más escalable
- **Contras**: Más complejo

**Estimación de uso**:
- 1,000 usuarios × 500KB avatar = 500MB → $0.12/mes
- 500 conversation images × 1MB = 500MB → $0.12/mes
- Transfer: 50GB/mes (gratis con CloudFront)

**Recomendación**: DigitalOcean Spaces para empezar.

---

## 5. Monitoreo y Logs

### Sentry (Error Tracking)
- **URL**: https://sentry.io
- **Precio**:
  - Developer: Gratis (5,000 events/mes)
  - Team: $26/mes (50,000 events/mes)
  - Business: $80/mes (100,000 events/mes)
- **Función**: Track errores en tiempo real
- **Imprescindible**: ✅ Sí, desde día 1

### Oh Dear (Site Monitoring)
- **URL**: https://ohdear.app
- **Precio**: $10/mes (10 sites)
- **Incluye**:
  - Uptime monitoring
  - SSL certificate monitoring
  - Broken links checking
  - Performance monitoring
  - Cron job monitoring
- **Integración**: Nativa con Laravel

### Logtail / BetterStack (Log Management)
- **URL**: https://betterstack.com/logtail
- **Precio**:
  - Free: 1GB/mes retention 3 días
  - Starter: $5/mes (5GB, 7 días)
  - Growth: $25/mes (25GB, 30 días)
- **Función**: Logs centralizados, búsqueda, alertas

**Recomendación**: Sentry (gratis) + Oh Dear ($10).

---

## 6. Videollamadas

### Jitsi Meet (Self-hosted)
- **URL**: https://jitsi.org
- **Precio**: Gratis (self-hosted)
- **Servidor**: Necesitas un VPS adicional ($10-20/mes)
- **Pros**:
  - Totalmente gratis
  - Open source
  - Control total
- **Contras**: Requiere mantenimiento

### Daily.co
- **URL**: https://daily.co
- **Precio**:
  - Free: 10,000 minutos/mes
  - Starter: $99/mes (50,000 minutos)
  - Scale: $299/mes (150,000 minutos)
- **Pros**:
  - API muy simple
  - Buena calidad
  - No requiere infraestructura
- **Recomendado**: ✅ Para producción

### Whereby
- **URL**: https://whereby.com
- **Precio**:
  - Free: 1 sala, 100 participantes
  - Business: $12.99/host/mes
- **Pros**: Embed muy fácil
- **Contras**: Menos flexible que Daily.co

### Zoom API (Avanzado)
- **URL**: https://marketplace.zoom.us/
- **Precio**: Variable según plan
- **Pros**: Todo el mundo conoce Zoom
- **Contras**: Complejo de integrar

**Recomendación Fase 1**: Permite que usuarios pongan sus propios enlaces de Zoom/Meet
**Recomendación Fase 2**: Daily.co (plan free → $99/mes cuando escalas)

---

## 7. Notificaciones Push (App Móvil)

### Firebase Cloud Messaging
- **URL**: https://firebase.google.com/
- **Precio**: Gratis (ilimitado)
- **Función**: Push notifications iOS/Android
- **Cuándo**: Solo cuando hagas app móvil

### OneSignal (Alternativa)
- **URL**: https://onesignal.com
- **Precio**:
  - Free: Unlimited
  - Growth: $9/mes (features avanzadas)
- **Más fácil** que Firebase

**Recomendación**: Firebase (cuando necesites)

---

## 8. Búsqueda Avanzada (Opcional)

### Algolia
- **URL**: https://algolia.com
- **Precio**:
  - Free: 10,000 búsquedas/mes
  - Essential: $35/mes (100,000 búsquedas)
- **Función**: Búsqueda instantánea de conversaciones
- **Cuándo**: Cuando tengas >100 conversaciones

### Meilisearch (Self-hosted)
- **URL**: https://meilisearch.com
- **Precio**: Gratis (self-hosted)
- **Función**: Búsqueda rápida, typo-tolerant
- **Integración**: Excelente con Laravel Scout

**Recomendación**: MySQL full-text search al inicio, Meilisearch después.

---

## 9. Analytics

### Plausible Analytics
- **URL**: https://plausible.io
- **Precio**: $9/mes (10,000 page views)
- **Pros**:
  - Privacy-friendly
  - GDPR compliant
  - Sin cookies
- **Recomendado**: ✅ Mejor que Google Analytics

### Google Analytics
- **Precio**: Gratis
- **Contras**: Privacy concerns, bloqueado por ad-blockers

**Recomendación**: Plausible ($9/mes)

---

## 10. Backups

### SnapShooter
- **URL**: https://snapshooter.com
- **Precio**: $7/mes (1 servidor)
- **Función**:
  - Backups automáticos servidor + BD
  - Retención configurable
  - Restore con 1 click

### Laravel Forge Backups (Incluido)
- Ya viene con Forge
- Backups a S3
- Configuración manual

**Recomendación**: Usar backups de Forge + S3.

---

## 11. Pagos (Para Premium)

### Stripe
- **URL**: https://stripe.com
- **Comisión**: 1.5% + €0.25 por transacción (Europa)
- **Pros**:
  - Fácil integración (Laravel Cashier)
  - Suscripciones incluidas
  - Excelente documentación
- **Recomendado**: ✅ Standard en Laravel

### Paddle
- **URL**: https://paddle.com
- **Comisión**: 5% + fees
- **Pros**:
  - Merchant of record (ellos manejan impuestos)
  - Menos problemas legales
- **Contras**: Más caro

**Recomendación**: Stripe con Laravel Cashier

---

## 12. CDN (Opcional)

### Cloudflare
- **URL**: https://cloudflare.com
- **Precio**:
  - Free: Básico (suficiente para empezar)
  - Pro: $20/mes
- **Función**:
  - CDN global
  - DDoS protection
  - Caching
  - SSL
- **Recomendado**: ✅ Free plan desde día 1

---

## Resumen de Costos

### Fase 1: MVP (0-100 usuarios)
| Servicio | Proveedor | Costo/mes |
|----------|-----------|-----------|
| Servidor | DigitalOcean | $24 |
| Gestión | Laravel Forge | $19 |
| Email | Postmark | $0 (gratis) |
| Storage | DO Spaces | $5 |
| Error tracking | Sentry | $0 (gratis) |
| Uptime monitoring | Oh Dear | $10 |
| Analytics | Plausible | $9 |
| CDN | Cloudflare | $0 (gratis) |
| **TOTAL FASE 1** | | **$67/mes** |

### Fase 2: Growth (100-1,000 usuarios)
| Servicio | Proveedor | Costo/mes |
|----------|-----------|-----------|
| Servidor | DigitalOcean | $48 |
| Gestión | Laravel Forge | $19 |
| Email | Postmark | $15 |
| Storage | DO Spaces | $5 |
| Error tracking | Sentry | $26 |
| Uptime monitoring | Oh Dear | $10 |
| Analytics | Plausible | $9 |
| Videollamadas | Daily.co | $99 |
| CDN | Cloudflare | $20 |
| **TOTAL FASE 2** | | **$251/mes** |

### Fase 3: Scale (1,000-10,000 usuarios)
| Servicio | Proveedor | Costo/mes |
|----------|-----------|-----------|
| Servidor | DigitalOcean (múltiples) | $200 |
| Load Balancer | DO | $20 |
| Database | Managed DB | $60 |
| Gestión | Laravel Forge | $39 |
| Email | Postmark | $65 |
| Storage | S3 + CloudFront | $50 |
| Error tracking | Sentry | $80 |
| Uptime monitoring | Oh Dear | $10 |
| Analytics | Plausible | $19 |
| Videollamadas | Daily.co | $299 |
| CDN | Cloudflare Pro | $20 |
| Búsqueda | Meilisearch VPS | $20 |
| **TOTAL FASE 3** | | **$882/mes** |

---

## Optimización de Costos

### Año 1: Ahorra usando planes gratuitos
- Sentry: Plan gratis (5K events)
- Postmark: 100 emails/mes gratis
- Cloudflare: Plan gratis
- Daily.co: 10K minutos gratis
- Firebase: Gratis ilimitado

**Total ahorrado Año 1**: ~$150/mes

### Descuentos Startup
- **GitHub Student Pack**: Incluye créditos DigitalOcean, Stripe, etc.
- **AWS Activate**: $5,000 créditos para startups
- **DigitalOcean**: $200 crédito inicial

---

## Próximos Pasos

1. **Día 1**: Configurar servidor + Forge ($43/mes)
2. **Semana 1**: Añadir Sentry + Oh Dear ($10/mes)
3. **Mes 1**: Configurar Postmark + Spaces + Cloudflare ($14/mes)
4. **Mes 3**: Añadir Daily.co cuando tengas usuarios ($99/mes)
5. **Mes 6**: Upgrade servidor según tráfico

---

**Última actualización**: 2025-11-06
