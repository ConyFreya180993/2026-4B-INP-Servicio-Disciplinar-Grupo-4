- *Proyecto:* Peluditos Pet Chile — Integración de Plataformas (Grupo 4)
- *Responsable del componente:* Gabriel Arellano (avockato)
- *Estado:* Desarrollo y validación local completados (integración pendiente para siguiente etapa)

---

## 1. Descripción
Este módulo corresponde al diseño e implementación inicial de la base de datos relacional para *Peluditos Pet Chile. Su objetivo es proporcionar la estructura de persistencia preliminar para almacenar la información de posibles clientes (*leads) y el historial de sus seguimientos comerciales.

## 2. Tecnologías Utilizadas
- *Motor de Base de Datos:* MariaDB (motor transaccional InnoDB, codificación utf8mb4).
- *Lenguaje:* SQL.
- *Entorno de Pruebas Locales:* XAMPP y phpMyAdmin.
- *Control de Versiones:* GitHub.

## 3. Archivo Principal y Estructura (peluditos_db)
El archivo *esquema_peluditos.sql* contiene las sentencias para crear la base de datos peluditos_db, definir sus tablas relacionadas, insertar datos de prueba y comprobar su funcionamiento:

### Tabla leads (Clientes Potenciales)
Almacena los antecedentes básicos de contacto de quienes realizan consultas al emprendimiento:
- id_lead (INT, AUTO_INCREMENT, PK)
- nombre_cliente (VARCHAR 50)
- contacto_cliente (VARCHAR 100)
- canal_origen (ENUM: 'WhatsApp', 'Instagram', 'Facebook')
- fecha_registro (DATETIME, por defecto CURRENT_TIMESTAMP)

### Tabla seguimientos (Gestión de Consultas y Pedidos)
Almacena el detalle comercial y el resultado de las consultas asociadas a cada cliente potencial:
- id_seguimiento (INT, AUTO_INCREMENT, PK)
- id_lead (INT, FK referenciando a leads.id_lead)
- nombre_mascota (VARCHAR 50)
- tipo_mascota (ENUM: 'Gato', 'Perro')
- consulta (TEXT)
- tamano_pastel (ENUM: 'Pequeño', 'Mediano', 'Grande')
- resultado_consulta (ENUM: 'Venta Concretada', 'Venta No Concretada')
- fecha_registro_consulta (DATETIME, por defecto CURRENT_TIMESTAMP)
- fecha_entrega (DATE, admite valores nulos si no se concreta la venta)

### Relación entre Tablas
La integridad referencial se establece mediante la clave foránea fk_seguimientos_leads sobre el campo id_lead, permitiendo que un cliente potencial registre uno o varios seguimientos asociados.

---

## 4. Instrucciones de Ejecución Local
Para reproducir el esquema y las pruebas en un entorno local con XAMPP y phpMyAdmin:
1. Abrir el panel de control de XAMPP e iniciar los módulos *MySQL* (MariaDB) y *Apache*.
2. Ingresar en el navegador a http://localhost/phpmyadmin.
3. Seleccionar la pestaña *Importar*, cargar el archivo esquema_peluditos.sql y ejecutar la operación.
4. Verificar la creación de la base de datos peluditos_db y sus tablas leads y seguimientos.
5. Comprobar la inserción de los 3 registros de prueba por tabla y el resultado de la consulta INNER JOIN incluida al final del script.

> *Nota para pruebas:* Se recomienda ejecutar el script sobre un entorno limpio o una base de datos de prueba nueva, dado que incluye inserciones de registros iniciales de ejemplo.

---

## 5. Pruebas Realizadas y Evidencias
Se validó localmente la creación del esquema, la inserción de los lotes de datos ficticios y la ejecución de la consulta relacional (INNER JOIN) entre leads y seguimientos. Las capturas de pantalla que respaldan la creación de las tablas, los registros cargados y el resultado de la consulta se encuentran adjuntas en este mismo directorio.

---

## 6. Estado del Desarrollo y Próximos Pasos

*Completado en esta entrega:*
- [x] Diseño inicial del esquema relacional en SQL.
- [x] Creación de las tablas leads y seguimientos con su relación de clave foránea.
- [x] Carga de registros de prueba y validación mediante c
