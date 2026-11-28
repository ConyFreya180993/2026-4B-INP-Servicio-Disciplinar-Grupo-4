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
│   ├── config.php
│   ├── config.example.php
│   ├── conexion.php
│   ├── flowable_config.php
│   ├── registrar_lead.php
│   ├── listar_procesos.php
│   ├── listar_seguimientos.php
│   ├── actualizar_seguimiento.php
│   └── gestionar_fidelizacion.php
├── img/
│   └── logo-peluditos-chile.webp
├── evidencia/
├── index.html
├── script.js
├── style.css
└── README.md
```

Los archivos de interfaz HTML, CSS y JavaScript forman parte de la aplicación web y consumen los servicios correspondientes.

## 5. Descripción de los servicios PHP

### `config.php`

Lee la configuración de la aplicación desde variables de entorno o desde el archivo `config.local.php`. Si existen las dos, se usa la variable de entorno.

El archivo `config.local.php` contiene los datos reales (usuarios y contraseñas) y no se sube a GitHub. Para crearlo se copia `config.example.php` y se completan los valores.

### `conexion.php`

Establece la conexión entre PHP y MariaDB, usando los datos de la configuración externa.

Permite que los demás servicios consulten y actualicen los datos almacenados en `peluditos_db`.

### `flowable_config.php`

Define la dirección y las credenciales para comunicarse con Flowable mediante su API REST, leyéndolas de la configuración externa.

Centraliza los parámetros utilizados para iniciar y consultar procesos BPMN.

### `registrar_lead.php`

Permite registrar un nuevo cliente potencial junto con su mascota y su consulta.

El servicio valida los datos recibidos y realiza dos operaciones principales:

1. Guarda en MariaDB, en una sola operación, el cliente (`leads`), su mascota (`pet`) y un seguimiento en estado `Pendiente` (`seguimientos`). Si alguno de los tres registros falla, no se guarda ninguno.
2. Inicia una instancia del proceso BPMN correspondiente en Flowable.

La respuesta incluye los identificadores del cliente, de la mascota y del seguimiento y, cuando el inicio es correcto, el identificador de la instancia del proceso.

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

La aplicación permite ingresar, en un solo formulario, los datos del cliente potencial (nombre, teléfono y canal de origen), los de su mascota (nombre, tipo y cumpleaños) y los de su consulta (detalle y tamaño del pastel).

El servicio PHP registra el cliente, la mascota y el seguimiento en MariaDB e inicia el proceso BPMN en Flowable.

Al finalizar, se muestra un mensaje de confirmación con los identificadores generados y el nuevo seguimiento aparece de inmediato en la tabla de seguimientos comerciales, en estado `Pendiente`.

### Gestión de seguimientos comerciales

La interfaz presenta los seguimientos registrados y permite modificar su estado.

Los cambios se almacenan en MariaDB mediante el servicio PHP correspondiente.

### Gestión de fidelización

La aplicación permite consultar campañas de fidelización y actualizar sus estados.

Esta funcionalidad facilita el seguimiento de las acciones comerciales realizadas con los clientes.

### Integración con Flowable

La WebApp inicia procesos BPMN mediante solicitudes HTTP a la API REST de Flowable.

Cada proceso puede recibir variables que permiten relacionarlo con los registros almacenados en MariaDB.

### Identidad visual

Se agregó el logo de Peluditos Pet Chile en el encabezado de la página y como icono de la pestaña del navegador. El archivo está en la carpeta `img/`.

También se cambiaron los colores de la interfaz por tonos naranjos, tomados del logo. Los colores se guardaron en variables al inicio de `style.css`, así se pueden cambiar fácilmente desde un solo lugar.

## 8. Funcionamiento de la integración

El flujo general del sistema es el siguiente:

1. El usuario abre la aplicación web.
2. Ingresa los datos de un cliente potencial, de su mascota y de su consulta.
3. JavaScript envía la solicitud al servicio PHP.
4. PHP registra al cliente, su mascota y su seguimiento en MariaDB.
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

### Configuración

La aplicación no tiene credenciales escritas en el código. Antes de usarla por primera vez se debe copiar `api/config.example.php` como `api/config.local.php` y reemplazar los valores `CAMBIAR_...` por los del entorno propio. Ese archivo no se sube a GitHub.

Los valores también se pueden definir con variables de entorno, que tienen prioridad sobre el archivo:

| Variable de entorno | Clave en `config.local.php` | Descripción | Valor por defecto |
|---|---|---|---|
| `PELUDITOS_DB_HOST` | `db_host` | Servidor de MariaDB | `127.0.0.1` |
| `PELUDITOS_DB_PORT` | `db_puerto` | Puerto de MariaDB | `3306` |
| `PELUDITOS_DB_NAME` | `db_nombre` | Base de datos | `peluditos_db` |
| `PELUDITOS_DB_USER` | `db_usuario` | Usuario de MariaDB | ninguno (obligatorio) |
| `PELUDITOS_DB_PASSWORD` | `db_password` | Contraseña de MariaDB | ninguna (obligatoria) |
| `PELUDITOS_FLOWABLE_SERVICE_URL` | `flowable_url` | URL de Flowable REST | `http://127.0.0.1:8080/flowable-rest/service` |
| `PELUDITOS_FLOWABLE_USER` | `flowable_usuario` | Usuario de Flowable | ninguno |
| `PELUDITOS_FLOWABLE_PASSWORD` | `flowable_password` | Contraseña de Flowable | ninguna |

Si falta la configuración de la base de datos, los servicios responden con un error 500 y el mensaje "Falta la configuración de la base de datos".

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

### Prueba 6. Registro de cliente con mascota y consulta

Se registró un cliente de prueba con su mascota y su consulta desde el servicio de registro.

**Resultado:** Los tres registros se guardaron en MariaDB, el seguimiento apareció en la lista de seguimientos en estado `Pendiente` y se inició el proceso BPMN en Flowable. Al avanzar el proceso, el External Worker completó la tarea externa y el proceso terminó correctamente.

También se comprobó que los datos incorrectos (tipo de mascota o tamaño no válidos, fecha de cumpleaños futura o inexistente, textos demasiado largos) se rechazan con error 400 sin guardar nada, y que si falla el último paso del guardado no queda ningún registro a medias.

## 11. Evidencias de funcionamiento

Las siguientes capturas documentan las pruebas realizadas:

| Archivo | Descripción |
|---|---|
| `WebApp-Pantalla-Principal.png.png` | Pantalla principal de la aplicación (versión anterior a la incorporación del logo y los colores institucionales) |
| `Integracion-Registro-Cliente-Flowable.png.png` | Registro de cliente e inicio de instancia BPMN |
| `WebApp-Seguimientos-y-Fidelizacion.png.png` | Visualización de seguimientos y campañas |
| `Actualizacion-Fidelizacion-MariaDB.png.png` | Confirmación de actualización de fidelización |
| `Actualizacion-Seguimiento-MariaDB.png.png` | Confirmación de actualización de seguimiento comercial |

Estas evidencias permiten demostrar las funcionalidades implementadas y su comunicación con los componentes del sistema.

## 12. Estado del proyecto

### Funcionalidades completadas

- [x] Implementación de la aplicación web.
- [x] Conexión entre PHP y MariaDB.
- [x] Registro de clientes potenciales, con su mascota y su consulta.
- [x] Integración con Flowable REST.
- [x] Inicio de instancias BPMN.
- [x] Consulta de seguimientos comerciales.
- [x] Actualización de estados de seguimiento.
- [x] Consulta de campañas de fidelización.
- [x] Actualización de estados de fidelización.
- [x] Pruebas de integración en entorno local.
- [x] Configuración y credenciales externalizadas (`config.local.php` y variables de entorno, con plantilla `config.example.php`).
- [x] Capturas de las funcionalidades principales.

### Mejoras futuras

- [ ] Permitir registrar más de una mascota por cliente.
- [ ] Actualizar el proceso de Flowable cuando se cambia el resultado de un seguimiento (hoy ese cambio solo se guarda en MariaDB).
- [ ] Incorporar autenticación y roles de usuario.
- [ ] Ampliar las validaciones de los formularios.
- [ ] Fortalecer el manejo de errores.
- [ ] Preparar el despliegue en un entorno de producción.

## 13. Conclusión

La aplicación web de Peluditos Pet Chile permite administrar información comercial mediante una interfaz conectada con servicios PHP y MariaDB.

Además, integra el inicio de procesos BPMN en Flowable, estableciendo una conexión entre las operaciones de la aplicación y la automatización de procesos de negocio.

Las pruebas realizadas comprobaron el registro de clientes, el inicio de procesos y la actualización de información comercial, demostrando el funcionamiento de la integración en el entorno local del proyecto.