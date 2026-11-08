# Proyecto 3 — Aplicación Web

## Peluditos Pet Chile

**Asignatura:** Integración de Plataformas  
**Equipo:** Grupo 4  
**Estado:** Implementado y probado en entorno local.

---

## 1. Descripción del proyecto

Este componente corresponde a la aplicación web de **Peluditos Pet Chile**, desarrollada para apoyar la gestión de clientes potenciales, seguimientos comerciales y campañas de fidelización.

La aplicación integra tres tecnologías principales:

- **PHP:** Procesamiento de solicitudes y lógica del servidor.
- **MariaDB:** Almacenamiento y actualización de información.
- **Flowable:** Ejecución de procesos de negocio mediante BPMN.

La WebApp permite realizar operaciones comerciales desde una interfaz web, comunicándose con los servicios PHP y la base de datos.

## 2. Objetivo

Implementar una aplicación web que permita registrar clientes potenciales, consultar información comercial y actualizar estados de seguimiento y fidelización, integrando las operaciones con MariaDB y el motor BPMN Flowable.

## 3. Tecnologías utilizadas

| Tecnología | Función |
|---|---|
| HTML | Estructura de la interfaz |
| CSS | Presentación visual |
| JavaScript | Interacción con el usuario |
| PHP | Servicios y lógica del servidor |
| MariaDB | Base de datos |
| XAMPP | Entorno local de ejecución |
| Flowable 8.0.0 | Motor de procesos BPMN |
| Apache Tomcat | Servidor de Flowable |
| Git y GitHub | Control de versiones |

## 4. Estructura de la API

La carpeta `api` contiene los servicios PHP utilizados por la aplicación web.

```text
Proyecto-3-WebApp/
├── api/
│   ├── conexion.php
│   ├── flowable_config.php
│   ├── registrar_lead.php
│   ├── listar_procesos.php
│   ├── listar_seguimientos.php
│   ├── actualizar_seguimiento.php
│   └── gestionar_fidelizacion.php
└── README.md
```

Los archivos de interfaz HTML, CSS y JavaScript forman parte de la aplicación web y consumen los servicios correspondientes.

## 5. Descripción de los servicios PHP

### `conexion.php`

Establece la conexión entre PHP y MariaDB.

Permite que los demás servicios consulten y actualicen los datos almacenados en `peluditos_db`.

### `flowable_config.php`

Contiene la configuración necesaria para comunicarse con Flowable mediante su API REST.

Centraliza los parámetros utilizados para iniciar y consultar procesos BPMN.

### `registrar_lead.php`

Permite registrar un nuevo cliente potencial.

El servicio realiza dos operaciones principales:

1. Inserta la información del cliente en MariaDB.
2. Inicia una instancia del proceso BPMN correspondiente en Flowable.

La respuesta incluye el identificador del cliente y, cuando el inicio es correcto, el identificador de la instancia del proceso.

### `listar_procesos.php`

Permite consultar información relacionada con los procesos utilizados por la aplicación.

Forma parte de la integración entre la WebApp y Flowable.

### `listar_seguimientos.php`

Consulta los registros de seguimiento comercial almacenados en MariaDB.

Permite mostrar la información correspondiente en la interfaz web.

### `actualizar_seguimiento.php`

Permite modificar el estado de un seguimiento comercial.

La operación actualiza el registro correspondiente en MariaDB y devuelve una respuesta para informar al usuario.

### `gestionar_fidelizacion.php`

Gestiona la información relacionada con las campañas de fidelización.

Permite consultar registros y actualizar sus estados desde la aplicación web.

## 6. Base de datos

La aplicación utiliza la base de datos:

```text
peluditos_db
```

Las tablas principales son:

| Tabla | Función |
|---|---|
| `leads` | Registro de clientes potenciales |
| `pet` | Información de mascotas |
| `seguimientos` | Seguimientos comerciales |
| `fidelizacion` | Campañas y acciones de fidelización |

La información se administra mediante MariaDB y puede inspeccionarse desde phpMyAdmin.

## 7. Funcionalidades implementadas

### Registro de clientes potenciales

La aplicación permite ingresar los datos de un cliente potencial y enviarlos al servidor.

El servicio PHP registra los datos en MariaDB e inicia el proceso BPMN en Flowable.

Al finalizar, se muestra un mensaje de confirmación con los identificadores generados.

### Gestión de seguimientos comerciales

La interfaz presenta los seguimientos registrados y permite modificar su estado.

Los cambios se almacenan en MariaDB mediante el servicio PHP correspondiente.

### Gestión de fidelización

La aplicación permite consultar campañas de fidelización y actualizar sus estados.

Esta funcionalidad facilita el seguimiento de las acciones comerciales realizadas con los clientes.

### Integración con Flowable

La WebApp inicia procesos BPMN mediante solicitudes HTTP a la API REST de Flowable.

Cada proceso puede recibir variables que permiten relacionarlo con los registros almacenados en MariaDB.

## 8. Funcionamiento de la integración

El flujo general del sistema es el siguiente:

1. El usuario abre la aplicación web.
2. Ingresa los datos de un cliente potencial.
3. JavaScript envía la solicitud al servicio PHP.
4. PHP registra al cliente en MariaDB.
5. El servicio inicia una instancia del proceso BPMN en Flowable.
6. Flowable recibe las variables correspondientes, incluida `id_lead`.
7. El sistema informa al usuario que el registro fue realizado y el proceso iniciado.
8. Las demás secciones de la WebApp permiten consultar y actualizar información comercial.

Cuando el proceso BPMN alcanza la tarea externa configurada, el componente **Proyecto 2 — External Worker** puede adquirirla y procesarla.

## 9. Ejecución local

### Requisitos

- Windows con XAMPP instalado.
- Apache y MariaDB iniciados desde XAMPP.
- Base de datos `peluditos_db` disponible.
- PHP configurado.
- Apache Tomcat y Flowable disponibles para las operaciones BPMN.
- Archivos de la WebApp ubicados en el directorio correspondiente de XAMPP.

### Acceso

Con Apache iniciado, abrir en el navegador:

```text
http://localhost/peluditos/
```

La aplicación debe mostrar su pantalla principal con las opciones de registro de clientes, seguimientos comerciales y fidelización.

## 10. Pruebas realizadas

### Prueba 1. Acceso a la aplicación web

Se abrió la aplicación desde el navegador utilizando el servidor Apache de XAMPP.

**Resultado:** La pantalla principal se mostró correctamente.

### Prueba 2. Registro de cliente e inicio de Flowable

Se registró un cliente de prueba mediante el formulario de la aplicación.

**Resultado:** El sistema confirmó el registro en MariaDB y mostró el identificador de la instancia iniciada en Flowable.

Esta prueba permitió comprobar la integración entre la interfaz web, PHP, MariaDB y Flowable.

### Prueba 3. Consulta de seguimientos y fidelización

Se revisaron las tablas de seguimiento comercial y campañas de fidelización.

**Resultado:** La información almacenada en MariaDB se visualizó correctamente en la WebApp.

### Prueba 4. Actualización de fidelización

Se modificó el estado de una campaña asociada a Ana Torres y su mascota Luna.

**Resultado:** La aplicación confirmó la actualización al estado `Enviado`.

### Prueba 5. Actualización de seguimiento comercial

Se modificó el estado de un seguimiento correspondiente a Carlos Rojas.

**Resultado:** La aplicación confirmó la actualización al estado `Pendiente`.

## 11. Evidencias de funcionamiento

Las siguientes capturas documentan las pruebas realizadas:

| Archivo | Descripción |
|---|---|
| `04-WebApp-Pantalla-Principal.png` | Pantalla principal de la aplicación |
| `05-Integracion-Registro-Cliente-Flowable.png` | Registro de cliente e inicio de instancia BPMN |
| `06-WebApp-Seguimientos-y-Fidelizacion.png` | Visualización de seguimientos y campañas |
| `07-API-Actualizacion-Fidelizacion-MariaDB.png` | Confirmación de actualización de fidelización |
| `08-API-Actualizacion-Seguimiento-MariaDB.png` | Confirmación de actualización de seguimiento comercial |

Estas evidencias permiten demostrar las funcionalidades implementadas y su comunicación con los componentes del sistema.

## 12. Estado del proyecto

### Funcionalidades completadas

- [x] Implementación de la aplicación web.
- [x] Conexión entre PHP y MariaDB.
- [x] Registro de clientes potenciales.
- [x] Integración con Flowable REST.
- [x] Inicio de instancias BPMN.
- [x] Consulta de seguimientos comerciales.
- [x] Actualización de estados de seguimiento.
- [x] Consulta de campañas de fidelización.
- [x] Actualización de estados de fidelización.
- [x] Pruebas de integración en entorno local.
- [x] Capturas de las funcionalidades principales.

### Mejoras futuras

- [ ] Incorporar autenticación y roles de usuario.
- [ ] Ampliar las validaciones de los formularios.
- [ ] Fortalecer el manejo de errores.
- [ ] Externalizar las credenciales de conexión.
- [ ] Preparar el despliegue en un entorno de producción.

## 13. Conclusión

La aplicación web de Peluditos Pet Chile permite administrar información comercial mediante una interfaz conectada con servicios PHP y MariaDB.

Además, integra el inicio de procesos BPMN en Flowable, estableciendo una conexión entre las operaciones de la aplicación y la automatización de procesos de negocio.

Las pruebas realizadas comprobaron el registro de clientes, el inicio de procesos y la actualización de información comercial, demostrando el funcionamiento de la integración en el entorno local del proyecto.