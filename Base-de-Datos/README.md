# Base de Datos — Peluditos Pet Chile

- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4).
- **Responsable del componente:** Gabriel Arellano (avockato).
- **Estado:** Desarrollo y pruebas locales completados. Esquema de cuatro tablas (`leads`, `pet`, `seguimientos` y `fidelizacion`) implementado. Integración PHP–MariaDB–Flowable implementada y probada mediante servicios REST y External Worker.

---

## 1. Descripción

Este módulo corresponde al diseño e implementación de la base de datos relacional para **Peluditos Pet Chile**.

Su objetivo es proporcionar la estructura de persistencia para almacenar información de clientes potenciales (*leads*), las mascotas asociadas a cada cliente (*pet*), registrar sus seguimientos comerciales y gestionar campañas de fidelización relacionadas con cumpleaños o celebraciones de mascotas.

La base de datos comercial se denomina `peluditos_db` y se mantiene independiente de la base de datos interna utilizada por Flowable.

## 2. Tecnologías Utilizadas

- **Motor de Base de Datos:** MariaDB (InnoDB, codificación utf8mb4).
- **Lenguaje:** SQL.
- **Entorno de Pruebas Locales:** XAMPP y phpMyAdmin.
- **Editor:** Visual Studio Code.
- **Lenguaje de integración:** PHP.
- **Conexión a la base de datos:** MySQLi.
- **Motor de procesos:** Flowable BPMN.
- **Control de Versiones:** Git y GitHub.

## 3. Archivo Principal y Estructura (`peluditos_db`)

El archivo `esquema_peluditos.sql` contiene las instrucciones necesarias para crear la base de datos, definir sus tablas relacionadas. El script solo crea la estructura y no inserta datos de prueba. Las consultas de verificación se encuentran en la sección 4 de este documento.

### Tabla `leads` (Clientes Potenciales)

Almacena los antecedentes básicos de contacto de quienes realizan consultas al emprendimiento.

- `id_lead`: INT, AUTO_INCREMENT, clave primaria.
- `nombre_cliente`: VARCHAR(50), NOT NULL.
- `contacto_cliente`: VARCHAR(100), NOT NULL.
- `canal_origen`: ENUM('WhatsApp', 'Instagram', 'Facebook'), NOT NULL.
- `fecha_registro`: DATETIME, NOT NULL, por defecto CURRENT_TIMESTAMP.

### Tabla `pet` (Mascotas)

Almacena las mascotas asociadas a cada cliente potencial. Es la tabla central para relacionar a un cliente con sus consultas y campañas.

- `id_pet`: INT, AUTO_INCREMENT, clave primaria.
- `id_lead`: INT, NOT NULL, clave foránea que referencia `leads.id_lead`.
- `nombre_mascota`: VARCHAR(50), NOT NULL.
- `tipo_pet`: ENUM('Gato', 'Perro'), NOT NULL.
- `cumpleanos`: DATE, NOT NULL.

Un cliente puede tener varias mascotas. El campo `cumpleanos` permite identificar la fecha de celebración recurrente de cada mascota.

### Tabla `seguimientos` (Gestión de Consultas y Pedidos)

Almacena el detalle comercial y el resultado de las consultas asociadas a cada mascota.

- `id_seguimiento`: INT, AUTO_INCREMENT, clave primaria.
- `id_pet`: INT, NOT NULL, clave foránea que referencia `pet.id_pet`.
- `consulta`: TEXT, NOT NULL.
- `tamano_pastel`: ENUM('Pequeño', 'Mediano', 'Grande'), NOT NULL.
- `resultado_consulta`: ENUM('Venta Concretada', 'Venta No Concretada', 'Pendiente'), NOT NULL.
- `fecha_registro_consulta`: DATETIME, NOT NULL, por defecto CURRENT_TIMESTAMP.
- `fecha_entrega`: DATE, permite valores NULL.

El estado `Pendiente` permite identificar consultas comerciales que todavía no tienen una resolución definitiva.

### Tabla `fidelizacion` (Campañas de Fidelización)

Almacena información de campañas asociadas a cumpleaños y celebraciones de cada mascota.

- `id_fidelizacion`: INT, AUTO_INCREMENT, clave primaria.
- `id_pet`: INT, NOT NULL, clave foránea que referencia `pet.id_pet`.
- `fecha_evento`: DATE, NOT NULL.
- `tipo_campana`: ENUM('Cumpleaños', 'Celebración'), NOT NULL.
- `estado_envio`: ENUM('Programado', 'Enviado'), NOT NULL.

Aunque `fecha_evento` almacena una fecha completa, las consultas pueden utilizar `DATE_FORMAT` para mostrar solamente el mes y el día (`MM-DD`), permitiendo trabajar con celebraciones recurrentes.

**Nota:** El campo `fecha_evento` puede derivarse de `pet.cumpleanos` para campañas de cumpleaños. Queda pendiente definir con el grupo el mecanismo que actualizará automáticamente `estado_envio` cuando se realice un envío real.

### Relaciones entre Tablas

La integridad referencial se establece mediante tres claves foráneas:

- `fk_pet_leads`: relaciona `pet.id_lead` con `leads.id_lead`.
- `fk_seguimientos_pet`: relaciona `seguimientos.id_pet` con `pet.id_pet`.
- `fk_fidelizacion_pet`: relaciona `fidelizacion.id_pet` con `pet.id_pet`.

La cadena de relaciones es:

```text
leads (1) ──< pet (N) ──< seguimientos (N)
                    └──< fidelizacion (N)
```

Un cliente potencial puede tener varias mascotas, y cada mascota puede tener varios seguimientos comerciales y varias campañas de fidelización.

### Separación entre `peluditos_db` y `flowable_db`

El proyecto contempla dos bases de datos con responsabilidades diferentes:

**`peluditos_db`:** almacena la información comercial del emprendimiento, incluyendo las tablas `leads`, `pet`, `seguimientos` y `fidelizacion`.

**`flowable_db`:** está destinada a la persistencia interna del motor Flowable, encargado de administrar procesos BPMN, tareas y estados de ejecución.

Las tablas internas de Flowable deben ser administradas por el propio motor y no forman parte del archivo `esquema_peluditos.sql`.

La comunicación entre ambas plataformas se realiza mediante PHP, servicios REST y un External Worker, sin necesidad de mezclar sus respectivas bases de datos.

## 4. Instrucciones de Ejecución Local

Para reproducir el esquema y las pruebas en un entorno local:

1. Abrir XAMPP e iniciar los módulos MySQL (MariaDB) y Apache.
2. Ingresar desde el navegador a http://localhost
3. Abrir phpMyAdmin (http://localhost/phpmyadmin).
4. Ir a la pestaña **Importar** y seleccionar el archivo `esquema_peluditos.sql`.
5. Ejecutar la importación. Se creará la base `peluditos_db` con sus cuatro tablas, sin datos.
6. Verificar que existan las tablas `leads`, `pet`, `seguimientos` y `fidelizacion`:

```sql
USE peluditos_db;
SHOW TABLES;
```

7. Verificar que las tablas se crearon vacías (cada consulta debe devolver 0 en una base nueva):

```sql
SELECT COUNT(*) FROM leads;
SELECT COUNT(*) FROM pet;
SELECT COUNT(*) FROM seguimientos;
SELECT COUNT(*) FROM fidelizacion;
```

## 5. Evidencias

Las capturas se encuentran en la carpeta `Evidencia/`:

| Evidencia | Descripción |
|---|---|
| `listado-tablas-peluditos_db.png` | Listado de las tablas de `peluditos_db`. |
| `estructura-tabla-leads.png` | Estructura de la tabla `leads`. |
| `estructura-tabla-pet.png` | Estructura de la tabla `pet`. |
| `structura-tabla-seguimientos.png` | Estructura de la tabla `seguimientos`. |
| `estructura-tabla-fidelizacion.png` | Estructura de la tabla `fidelizacion`. |
| `verificacion-integracion-flowable.png` | Verificación de la integración con Flowable. |

## 6. Observaciones de la revisión

Se revisó la base de datos `peluditos_db` en funcionamiento y se comparó con el archivo `esquema_peluditos.sql` y con este documento.

**Lo que está correcto:**

- Existen las cuatro tablas (`leads`, `pet`, `seguimientos` y `fidelizacion`) con las mismas columnas y tipos descritos en este README.
- Las tres claves foráneas (`fk_pet_leads`, `fk_seguimientos_pet` y `fk_fidelizacion_pet`) están creadas y usan la regla `RESTRICT`: no se puede borrar un cliente que tenga mascotas, ni una mascota que tenga seguimientos o campañas.

**Limitaciones conocidas:**

- **Una mascota por registro:** el formulario de la WebApp guarda el cliente, una mascota y un seguimiento en `Pendiente` en una sola operación. Si se necesita más de una mascota por cliente, hay que agregarla directamente en la base, ya que la WebApp todavía no lo permite.
- **Campañas de fidelización:** la WebApp no crea campañas al registrar un cliente. Las filas de `fidelizacion` deben cargarse aparte.
- **Base de datos vacía:** el script solo crea la estructura y no inserta datos de prueba. Las pruebas del Worker y de la WebApp necesitan clientes existentes, por lo que hay que registrarlos antes desde la WebApp o directamente en la base. Las pruebas del Worker usaron los `id_lead` 1, 2 y 3, que no existen en una base recién creada.
- **Formato de teléfono:** la WebApp valida y guarda los teléfonos como `+569XXXXXXXX`. Si se insertan datos manualmente, conviene usar ese mismo formato.
- **Script repetible:** como el script no tiene `INSERT` y usa `CREATE ... IF NOT EXISTS`, se puede ejecutar más de una vez sin duplicar datos ni borrar los existentes.
- **Fecha del evento:** `fidelizacion.fecha_evento` repite información que ya existe en `pet.cumpleanos`. Se mantiene así mientras se define con el grupo el origen de esa fecha.