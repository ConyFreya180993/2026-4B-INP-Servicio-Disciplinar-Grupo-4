# Base de Datos — Peluditos Pet Chile

- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4).
- **Responsable del componente:** Gabriel Arellano (avockato).
- **Estado:** Desarrollo y pruebas locales completados. Integración PHP–MariaDB–Flowable implementada y probada mediante servicios REST y External Worker.

---

## 1. Descripción

Este módulo corresponde al diseño e implementación de la base de datos relacional para **Peluditos Pet Chile**.

Su objetivo es proporcionar la estructura de persistencia para almacenar información de clientes potenciales (*leads*), registrar sus seguimientos comerciales y gestionar campañas de fidelización relacionadas con cumpleaños o celebraciones de mascotas.

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

El archivo `esquema_peluditos.sql` contiene las instrucciones necesarias para crear la base de datos, definir sus tablas relacionadas, insertar registros ficticios y realizar consultas de verificación.

### Tabla `leads` (Clientes Potenciales)

Almacena los antecedentes básicos de contacto de quienes realizan consultas al emprendimiento.

- `id_lead`: INT, AUTO_INCREMENT, clave primaria.
- `nombre_cliente`: VARCHAR(50).
- `contacto_cliente`: VARCHAR(100).
- `canal_origen`: ENUM('WhatsApp', 'Instagram', 'Facebook').
- `fecha_registro`: DATETIME, por defecto CURRENT_TIMESTAMP.

### Tabla `seguimientos` (Gestión de Consultas y Pedidos)

Almacena el detalle comercial y el resultado de las consultas asociadas a cada cliente potencial.

- `id_seguimiento`: INT, AUTO_INCREMENT, clave primaria.
- `id_lead`: INT, clave foránea que referencia `leads.id_lead`.
- `nombre_mascota`: VARCHAR(50).
- `tipo_mascota`: ENUM('Gato', 'Perro').
- `consulta`: TEXT.
- `tamano_pastel`: ENUM('Pequeño', 'Mediano', 'Grande').
- `resultado_consulta`: ENUM('Venta Concretada', 'Venta No Concretada', 'Pendiente').
- `fecha_registro_consulta`: DATETIME, por defecto CURRENT_TIMESTAMP.
- `fecha_entrega`: DATE, permite valores NULL.

El estado `Pendiente` permite identificar consultas comerciales que todavía no tienen una resolución definitiva.

### Tabla `fidelizacion` (Campañas de Fidelización)

Almacena información de campañas asociadas a cumpleaños y celebraciones de mascotas.

- `id_fidelizacion`: INT, AUTO_INCREMENT, clave primaria.
- `id_lead`: INT, clave foránea que referencia `leads.id_lead`.
- `nombre_mascota`: VARCHAR(50).
- `fecha_evento`: DATE.
- `tipo_campana`: ENUM('Cumpleaños', 'Celebración').
- `estado_envio`: ENUM('Programado', 'Enviado').

Aunque `fecha_evento` almacena una fecha completa, las consultas pueden utilizar `DATE_FORMAT` para mostrar solamente el mes y el día (`MM-DD`), permitiendo trabajar con celebraciones recurrentes.

**Nota:** La estructura de fidelización es provisional. Está pendiente definir con el grupo el origen de `fecha_evento` y el mecanismo que actualizará automáticamente `estado_envio` cuando se realice un envío real.

### Relaciones entre Tablas

La integridad referencial se establece mediante dos claves foráneas:

- `fk_seguimientos_leads`: relaciona `seguimientos.id_lead` con `leads.id_lead`.
- `fk_fidelizacion_leads`: relaciona `fidelizacion.id_lead` con `leads.id_lead`.

Un cliente potencial puede tener varios seguimientos comerciales y varias campañas de fidelización.

### Separación entre `peluditos_db` y `flowable_db`

El proyecto contempla dos bases de datos con responsabilidades diferentes:

**`peluditos_db`:** almacena la información comercial del emprendimiento, incluyendo las tablas `leads`, `seguimientos` y `fidelizacion`.

**`flowable_db`:** está destinada a la persistencia interna del motor Flowable, encargado de administrar procesos BPMN, tareas y estados de ejecución.

Las tablas internas de Flowable deben ser administradas por el propio motor y no forman parte del archivo `esquema_peluditos.sql`.

La comunicación entre ambas plataformas se realiza mediante PHP, servicios REST y un External Worker, sin necesidad de mezclar sus respectivas bases de datos.

## 4. Instrucciones de Ejecución Local

Para reproducir el esquema y las pruebas en un entorno local:

1. Abrir XAMPP e iniciar los módulos MySQL (MariaDB) y Apache.
2. Ingresar desde el navegador a http://localhost