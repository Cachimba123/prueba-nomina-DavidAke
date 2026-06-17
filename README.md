# API de Nómina Municipal

Proyecto Laravel para administrar empleados, departamentos y cálculos de nómina quincenal. El sistema expone una API autenticada con Laravel Sanctum, permite calcular nóminas, consultar historial, revisar métricas por departamento y operar empleados bajo roles.

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- PostgreSQL
- Docker y Docker Compose, si se prefiere ejecutar el entorno local en contenedores
- Extensiones PHP requeridas por Laravel 12

## Instalación local

1. Clonar el repositorio y entrar al proyecto:

```bash
git clone <url-del-repositorio>
cd prueba-nomina-DavidAke
```

2. Instalar dependencias de PHP:

```bash
composer install
```

3. Instalar dependencias de frontend:

```bash
npm install
```

4. Crear el archivo de ambiente:

```bash
cp .env.example .env
```

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

5. Generar la llave de Laravel:

```bash
php artisan key:generate
```

6. Configurar la base de datos en `.env`. Ejemplo:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nomina-prueba
DB_USERNAME=postgres
DB_PASSWORD=
```

7. Ejecutar migraciones y seeders:

```bash
php artisan migrate --seed
```

8. Levantar el servidor local:

```bash
php artisan serve
```

La API queda disponible en:

```text
http://127.0.0.1:8000/api
```

Si se necesita compilar assets:

```bash
npm run dev
```

## Instalación local con Docker

El proyecto incluye una configuración Docker para levantar la API, Nginx y PostgreSQL sin instalar PHP o PostgreSQL directamente en la máquina.

Archivos principales:

- `docker-compose.local.yml`: entorno local.
- `docker/php/Dockerfile.local`: imagen PHP-FPM para desarrollo local.
- `docker/nginx/local.conf`: configuración Nginx local.
- `.env.local`: variables usadas por los contenedores locales.

1. Construir y levantar los contenedores:

```bash
docker compose -f docker-compose.local.yml up -d --build
```

2. Instalar dependencias dentro del contenedor de la aplicación:

```bash
docker compose -f docker-compose.local.yml exec app composer install
```

3. Generar la llave de Laravel si fuera necesario:

```bash
docker compose -f docker-compose.local.yml exec app php artisan key:generate
```

4. Ejecutar migraciones y seeders:

```bash
docker compose -f docker-compose.local.yml exec app php artisan migrate --seed
```

5. Consultar la API:

```text
http://127.0.0.1:8000/api
```

El servicio local usa estos contenedores:

- `nomina_app_local`: aplicación Laravel con PHP-FPM.
- `nomina_nginx_local`: servidor web Nginx expuesto en el puerto `8000`.
- `nomina_postgres_local`: base de datos PostgreSQL expuesta en el puerto `5433`.

La conexión interna de Laravel a base de datos se configura en `.env.local`:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=nomina_prueba
DB_USERNAME=nomina_user
DB_PASSWORD=nomina_password
```

Comandos útiles:

```bash
docker compose -f docker-compose.local.yml ps
docker compose -f docker-compose.local.yml logs -f
docker compose -f docker-compose.local.yml exec app php artisan test
docker compose -f docker-compose.local.yml down
```

Si se quiere reiniciar también la base de datos y los volúmenes:

```bash
docker compose -f docker-compose.local.yml down -v
```

## Docker para producción

También existe una base para producción:

- `docker-compose.prod.yml`
- `docker/php/Dockerfile.prod`
- `docker/nginx/prod.conf`
- `.env.prod`

La intención de esta configuración es separar una imagen optimizada sin dependencias de desarrollo y servir la API con Nginx + PHP-FPM. Antes de usarla en un ambiente real se debe revisar y probar con más cuidado:

- Generar un `APP_KEY` real.
- Configurar dominio o IP final en `APP_URL` y `SANCTUM_STATEFUL_DOMAINS`.
- Cambiar credenciales de PostgreSQL.
- Revisar permisos de `storage` y `bootstrap/cache`.
- Validar que las migraciones, seeders necesarios y cachés de Laravel funcionen correctamente.
- Ajustar `docker-compose.prod.yml`, porque actualmente espera `.env.production`, mientras el archivo incluido en el repositorio es `.env.prod`.
- Probar build, arranque, logs, conectividad y comportamiento de la API dentro del contenedor.

## Usuarios de prueba

Los seeders generan usuarios listos para probar en Postman o cualquier cliente HTTP:

```json
{
  "correo": "admin@nomina.test",
  "password": "password"
}
```

```json
{
  "correo": "sistema.externo@nomina.test",
  "password": "password"
}
```

También se generan usuarios tipo consulta vinculados a empleados:

```json
{
  "correo": "empleado1@nomina.test",
  "password": "password"
}
```

## Autenticación

El sistema usa Laravel Sanctum. Primero se consume `POST /api/login` y la respuesta entrega un token Bearer. Las demás rutas se consumen enviando el token:

```http
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

## Cómo se conecta el sistema

El flujo principal es:

1. `departamentos` funciona como catálogo.
2. Cada `empleado` pertenece a un departamento.
3. Un `usuario` puede estar relacionado con un empleado, aunque también puede existir sin empleado para permitir usuarios externos o integraciones.
4. Sanctum autentica al usuario y los roles controlan qué operaciones puede realizar.
5. Al calcular una nómina, el sistema toma el empleado activo, determina la quincena, calcula percepciones y deducciones, y guarda un registro en `historial_nominas`.
6. El historial conserva datos congelados del empleado, departamento, periodo, usuario que calculó y montos, lo que facilita auditoría aunque el empleado cambie después.

Relaciones principales:

- `departamentos` 1 a muchos `empleados`
- `empleados` 1 a 1 `usuarios`
- `empleados` 1 a muchos `historial_nominas`
- `departamentos` 1 a muchos `historial_nominas`
- `usuarios` 1 a muchos `historial_nominas` mediante `calculado_por_usuario_id`

## Roles

Roles disponibles:

- `admin`
- `nomina`
- `recursos_humanos`
- `consulta`
- `sistema_externo`

Permisos generales:

- `admin`: administra empleados, calcula nómina, consulta historial y métricas.
- `recursos_humanos`: crea y actualiza empleados.
- `nomina`: calcula nómina y consulta información operativa.
- `consulta`: consulta información permitida; si está ligado a empleado, solo ve su propio registro en ciertas operaciones.
- `sistema_externo`: pensado para integraciones API.

## Endpoints

### Login

`POST /api/login`

Request:

```json
{
  "correo": "admin@nomina.test",
  "password": "password"
}
```

Response:

```json
{
  "success": true,
  "message": "Inicio de sesión correcto.",
  "data": {
    "usuario": {
      "id": 1,
      "empleado_id": null,
      "nombre_completo": "Administrador General",
      "correo": "admin@nomina.test",
      "rol": "admin",
      "activo": true,
      "empleado": null
    },
    "token": "1|token_generado_por_sanctum",
    "tipo_token": "Bearer"
  }
}
```

### Perfil autenticado

`GET /api/perfil`

Response:

```json
{
  "success": true,
  "message": "Usuario autenticado obtenido correctamente.",
  "data": {
    "id": 1,
    "empleado_id": null,
    "nombre_completo": "Administrador General",
    "correo": "admin@nomina.test",
    "rol": "admin",
    "activo": true
  }
}
```

### Cerrar sesión

`POST /api/logout`

Response:

```json
{
  "success": true,
  "message": "Sesión cerrada correctamente."
}
```

### Cerrar todas las sesiones

`POST /api/logout-all`

Response:

```json
{
  "success": true,
  "message": "Todas las sesiones fueron cerradas correctamente."
}
```

### Listar empleados

`GET /api/empleados`

Filtros opcionales:

- `departamento_id`
- `activo`
- `buscar`
- `tipo_contrato`: `base`, `confianza` o `eventual`
- `per_page`: de 1 a 100

Ejemplo:

```http
GET /api/empleados?departamento_id=1&activo=true&per_page=10
```

Response:

```json
{
  "success": true,
  "message": "Empleados obtenidos correctamente.",
  "data": [
    {
      "id": 1,
      "departamento": {
        "id": 1,
        "clave": "TES",
        "nombre": "Tesorería"
      },
      "nombre_completo": "Juan Pérez López",
      "rfc": "PELJ900101ABC",
      "puesto": "Analista",
      "salario_base": "18000.00",
      "tipo_contrato": "base",
      "fecha_ingreso": "2024-01-15",
      "activo": true,
      "fechas": {
        "creado_en": "2026-06-17 10:00:00",
        "actualizado_en": "2026-06-17 10:00:00"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 1,
    "last_page": 1
  }
}
```

### Crear empleado

`POST /api/empleados`

Request:

```json
{
  "departamento_id": 1,
  "nombre_completo": "Juan Pérez López",
  "rfc": "PELJ900101ABC",
  "puesto": "Analista",
  "salario_base": 18000,
  "tipo_contrato": "base",
  "fecha_ingreso": "2024-01-15",
  "activo": true
}
```

Response:

```json
{
  "success": true,
  "message": "Empleado creado correctamente.",
  "data": {
    "id": 1,
    "departamento": {
      "id": 1,
      "clave": "TES",
      "nombre": "Tesorería"
    },
    "nombre_completo": "Juan Pérez López",
    "rfc": "PELJ900101ABC",
    "puesto": "Analista",
    "salario_base": "18000.00",
    "tipo_contrato": "base",
    "fecha_ingreso": "2024-01-15",
    "activo": true
  }
}
```

### Ver empleado

`GET /api/empleados/{empleado}`

Response:

```json
{
  "success": true,
  "message": "Empleado obtenido correctamente.",
  "data": {
    "id": 1,
    "departamento": {
      "id": 1,
      "clave": "TES",
      "nombre": "Tesorería"
    },
    "nombre_completo": "Juan Pérez López",
    "rfc": "PELJ900101ABC",
    "puesto": "Analista",
    "salario_base": "18000.00",
    "tipo_contrato": "base",
    "fecha_ingreso": "2024-01-15",
    "activo": true
  }
}
```

### Actualizar empleado

`PUT /api/empleados/{empleado}` o `PATCH /api/empleados/{empleado}`

Request:

```json
{
  "puesto": "Coordinador",
  "salario_base": 22000,
  "tipo_contrato": "confianza"
}
```

Response:

```json
{
  "success": true,
  "message": "Empleado actualizado correctamente.",
  "data": {
    "id": 1,
    "puesto": "Coordinador",
    "salario_base": "22000.00",
    "tipo_contrato": "confianza",
    "activo": true
  }
}
```

### Desactivar empleado

`DELETE /api/empleados/{empleado}`

Response:

```json
{
  "success": true,
  "message": "Empleado desactivado correctamente."
}
```

### Reactivar empleado

`PATCH /api/empleados/{empleado}/reactivar`

Response:

```json
{
  "success": true,
  "message": "Empleado reactivado correctamente.",
  "data": {
    "id": 1,
    "activo": true
  }
}
```

### Listar departamentos

`GET /api/departamentos`

Response:

```json
{
  "success": true,
  "message": "Departamentos obtenidos correctamente.",
  "data": [
    {
      "id": 1,
      "clave_departamento": "TES",
      "nombre_departamento": "Tesorería",
      "activo": true
    }
  ]
}
```

### Ver departamento

`GET /api/departamentos/{departamento}`

Response:

```json
{
  "success": true,
  "message": "Departamento obtenido correctamente.",
  "data": {
    "id": 1,
    "clave_departamento": "TES",
    "nombre_departamento": "Tesorería",
    "activo": true
  }
}
```

### Calcular nómina

`POST /api/nomina/calcular`

Request:

```json
{
  "empleado_id": 1,
  "fecha_referencia": "2026-06-17",
  "horas_extra": 4,
  "bono_puntualidad": true
}
```

Response:

```json
{
  "success": true,
  "message": "Nómina calculada correctamente.",
  "data": {
    "id": 1,
    "empleado": {
      "id": 1,
      "nombre": "Juan Pérez López",
      "rfc": "PELJ900101ABC",
      "puesto": "Analista"
    },
    "departamento": {
      "id": 1,
      "nombre": "Tesorería"
    },
    "periodo": {
      "anio": 2026,
      "quincena": 12,
      "inicio": "2026-06-16",
      "fin": "2026-06-30"
    },
    "percepciones": {
      "salario_base": 9000,
      "salario_base_mensual": 18000,
      "horas_extra": {
        "cantidad": 4,
        "valor_hora_ordinaria": 75,
        "factor": 1.5,
        "total": 450
      },
      "bono_puntualidad": {
        "solicitado": true,
        "aplicado": true,
        "monto": 500
      },
      "total": "9950.00"
    },
    "deducciones": {
      "imss": {
        "porcentaje": 0.03,
        "monto": 270
      },
      "isr": {
        "porcentaje": 0.064,
        "monto": 576
      },
      "total": "846.00"
    },
    "neto_a_pagar": "9104.00",
    "calculado_por": {
      "id": 1,
      "nombre": "Administrador General",
      "correo": "admin@nomina.test"
    },
    "calculado_en": "2026-06-17 14:30:00"
  }
}
```

Notas:

- Si no se envía `fecha_referencia`, se usa la fecha actual.
- La quincena se calcula automáticamente: días 1 al 15 son primera quincena del mes, del 16 al fin de mes son segunda quincena.
- No se permite repetir la combinación `empleado_id`, `anio` y `quincena`.
- Solo se calcula nómina para empleados activos.

### Consultar historial de nómina

`GET /api/nomina/historial`

Filtros opcionales:

- `anio`
- `quincena`
- `departamento_id`
- `empleado_id`
- `per_page`

Si se envía `anio`, también debe enviarse `quincena`, y viceversa.

Ejemplo:

```http
GET /api/nomina/historial?anio=2026&quincena=12&departamento_id=1
```

Response:

```json
{
  "success": true,
  "message": "Historial de nóminas obtenido correctamente.",
  "data": {
    "periodo": {
      "anio": 2026,
      "quincena": 12,
      "periodo_inicio": null,
      "periodo_fin": null
    },
    "historial": [
      {
        "id": 1,
        "empleado": {
          "id": 1,
          "nombre": "Juan Pérez López",
          "rfc": "PELJ900101ABC",
          "puesto": "Analista"
        },
        "departamento": {
          "id": 1,
          "nombre": "Tesorería"
        },
        "periodo": {
          "anio": 2026,
          "quincena": 12,
          "inicio": "2026-06-16",
          "fin": "2026-06-30"
        },
        "percepciones": {
          "salario_base": 9000,
          "salario_base_mensual": 18000,
          "horas_extra": {
            "cantidad": 4,
            "valor_hora_ordinaria": 75,
            "factor": 1.5,
            "total": 450
          },
          "bono_puntualidad": {
            "solicitado": true,
            "aplicado": true,
            "monto": 500
          },
          "total": "9950.00"
        },
        "deducciones": {
          "imss": {
            "porcentaje": 0.03,
            "monto": 270
          },
          "isr": {
            "porcentaje": 0.064,
            "monto": 576
          },
          "total": "846.00"
        },
        "neto_a_pagar": "9104.00"
      }
    ],
    "meta": {
      "current_page": 1,
      "per_page": 10,
      "total": 1,
      "last_page": 1
    }
  }
}
```

### Métricas de nómina por departamentos

`GET /api/nomina/metricas/departamentos`

Filtros opcionales:

- `anio`
- `quincena`

Ejemplo:

```http
GET /api/nomina/metricas/departamentos?anio=2026&quincena=12
```

Response:

```json
{
  "success": true,
  "message": "Métricas de nómina por departamento obtenidas correctamente.",
  "data": {
    "periodo": {
      "anio": 2026,
      "quincena": 12,
      "periodo_inicio": null,
      "periodo_fin": null
    },
    "metricas": [
      {
        "departamento": {
          "id": 1,
          "clave": "TES",
          "nombre": "Tesorería"
        },
        "total_empleados_activos": 5,
        "total_empleados_con_nomina": 3,
        "suma_total_percepciones": "28500.00",
        "suma_total_deducciones": "2300.00",
        "suma_total_neto_a_pagar": "26200.00",
        "empleado_mayor_salario_neto": {
          "empleado_id": 1,
          "nombre": "Juan Pérez López",
          "rfc": "PELJ900101ABC",
          "neto_a_pagar": "9104.00"
        }
      }
    ]
  }
}
```

### Métricas de nómina de un departamento

`GET /api/nomina/metricas/departamentos/{departamento}`

Ejemplo:

```http
GET /api/nomina/metricas/departamentos/1?anio=2026&quincena=12
```

Response:

```json
{
  "success": true,
  "message": "Métricas de nómina del departamento obtenidas correctamente.",
  "data": {
    "periodo": {
      "anio": 2026,
      "quincena": 12,
      "periodo_inicio": null,
      "periodo_fin": null
    },
    "metricas": [
      {
        "departamento": {
          "id": 1,
          "clave": "TES",
          "nombre": "Tesorería"
        },
        "total_empleados_activos": 5,
        "total_empleados_con_nomina": 3,
        "suma_total_percepciones": "28500.00",
        "suma_total_deducciones": "2300.00",
        "suma_total_neto_a_pagar": "26200.00",
        "empleado_mayor_salario_neto": {
          "empleado_id": 1,
          "nombre": "Juan Pérez López",
          "rfc": "PELJ900101ABC",
          "neto_a_pagar": "9104.00"
        }
      }
    ]
  }
}
```

## Reglas de cálculo de nómina

El cálculo toma el salario base mensual del empleado y genera:

- Salario quincenal: `salario_base / 2`
- Horas extra: valor de hora ordinaria por `1.5`
- Bono de puntualidad: `$500` solo si se solicita y el empleado tiene contrato `base` o `confianza`
- IMSS: `3%` del salario quincenal
- ISR:
  - Hasta `7735`: `1.92%`
  - Mayor a `7735` y hasta `18000`: `6.40%`
  - Mayor a `18000`: `10.88%`

Para el tramo 3 de ISR se decidió no usar un límite superior. Todo salario mensual mayor a `18000` usa la última lógica de deducción.

## Decisiones de diseño

- Se creó una tabla de `departamentos` como catálogo principal. Esto permite normalizar la información y establecer una relación 1 a muchos entre departamento y empleados.
- Se separó la tabla de `usuarios` de la tabla de `empleados`. Un usuario puede estar relacionado con un empleado, pero también puede existir como usuario externo. Esto ayuda a autenticar con Sanctum, manejar roles y facilitar integraciones con otros servicios que consuman la API.
- Se agregó la tabla `historial_nominas` para separar el cálculo de nómina de los datos vivos del empleado. Esta tabla guarda el empleado, departamento, usuario que calculó, periodo, percepciones, deducciones y neto a pagar.
- El historial mejora la auditoría porque conserva la información usada al momento del cálculo, aunque después cambien datos del empleado.
- En `historial_nominas` existe una restricción única para evitar repetir una nómina del mismo empleado en el mismo año y quincena: `empleado_id`, `anio`, `quincena`.
- La lógica de nómina se separó en servicios y acciones para mantener controllers ligeros y una API más fácil de extender.
- Se agregaron rutas de consulta para departamentos, historial y métricas con filtros, buscando que la información sea cómoda de revisar desde clientes como Postman o desde una futura interfaz.

## Herramientas de IA utilizadas

- ChatGPT 5.4
- GitHub Copilot para autocompletado

## Mejoras futuras

Con más tiempo se agregaría una capa visual para operar el sistema desde una interfaz web.

También se estandarizarían mejor las conexiones externas de la API usando protocolos más robustos para integraciones, por ejemplo OAuth.

Otra mejora sería agregar cron jobs para ejecutar o validar cortes de nómina puntualmente cada quincena, siempre que la lógica de negocio quede confirmada para automatizar esos cortes.

De haber tenido más tiempo, también habría mejorado y probado con mayor profundidad la imagen y el contenedor de producción. La configuración base ya existe, pero faltaría validar el build final, variables de entorno, permisos, logs, conectividad con la base de datos, ejecución de migraciones, seguridad de credenciales y comportamiento real de la API en un ambiente productivo.

Finalmente, se podría aplicar un patrón tipo Factory o una arquitectura más extensible para aislar mejor la creación de usuarios, tipos de contrato, restricciones, deducciones y percepciones. Esto haría el sistema más escalable y menos acoplado al agregar nuevas reglas.
