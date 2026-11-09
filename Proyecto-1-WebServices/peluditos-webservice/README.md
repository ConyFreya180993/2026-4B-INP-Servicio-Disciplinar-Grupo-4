:::writing{variant="document" id="62841"}
# Servicios Web – Peluditos Pet Chile

## 1. Descripción del proyecto

Este proyecto corresponde al desarrollo de servicios web REST en Java para Peluditos Pet Chile, como parte de la asignatura Integración de Plataformas del Grupo 4.

Su objetivo es implementar servicios que permitan consultar información de clientes potenciales (leads), utilizando una base de datos MariaDB y preparando su futura integración con procesos BPMN mediante Flowable.

## 2. Tecnologías utilizadas

- Java JDK 21
- Spring Boot 4.1.2
- Spring Web
- Jakarta REST (JAX-RS)
- Jersey
- MariaDB
- Maven
- Visual Studio Code
- Git y GitHub

## 3. Servicios web implementados

### Servicio de prueba

**Método HTTP:** GET

**Endpoint original:** `/api/consultas/prueba`

Este servicio fue desarrollado inicialmente para comprobar el funcionamiento de la aplicación. Su disponibilidad debe verificarse con la configuración actual.

### Servicio de consulta de clientes

**Método HTTP:** GET

**Endpoint:** `/api/clientes`

**Objetivo:** Consultar los clientes potenciales registrados en la base de datos MariaDB.

**Datos de entrada:** No requiere parámetros.

**Datos de salida:** Respuesta JSON con los registros obtenidos de la tabla `leads`.

**Funcionamiento:** El controlador `ClientesController.java` utiliza `JdbcTemplate` para ejecutar una consulta SQL y recuperar los registros almacenados.

**Relación con el proceso de negocio:** Permite acceder a información de clientes potenciales para apoyar el seguimiento comercial y la futura integración con Flowable.

## 4. Conexión a la base de datos

Se configuró una conexión local a MariaDB mediante el archivo `application.properties`.

- Base de datos: `peluditos_db`
- Servidor: `127.0.0.1`
- Puerto: `3306`
- Tabla consultada: `leads`
- Contraseña: configurada mediante la variable de entorno `PELUDITOS_DB_PASSWORD`

Por seguridad, no se incluyen contraseñas reales en el repositorio.

## 5. Servicios web propuestos

| Servicio | Método y endpoint | Objetivo |
|---|---|---|
| Registrar consulta | POST `/api/consultas` | Registrar una nueva consulta |
| Consultar solicitud | GET `/api/consultas/{id}` | Obtener información de una solicitud |
| Registrar resultado | PATCH `/api/consultas/{id}/resultado` | Actualizar el resultado de una consulta |

Estos servicios se encuentran propuestos para futuras etapas y deberán validarse con el equipo.

## 6. Ejecución del proyecto

Desde la carpeta `peluditos-webservice`, ejecutar en PowerShell:

`.\mvnw.cmd spring-boot:run`

Antes de iniciar, se requiere disponer de MariaDB, la base de datos correspondiente y la variable de entorno con la contraseña configurada.

Una vez iniciado el servidor, abrir:

`http://localhost:8080/api/clientes`

## 7. Resultados obtenidos

Se ejecutó correctamente la aplicación Spring Boot utilizando Java 21.

El servidor Tomcat integrado inició en el puerto 8080.

Se comprobó el funcionamiento del endpoint `GET /api/clientes`, obteniendo una respuesta JSON con registros almacenados en MariaDB.

## 8. Evidencias

En la carpeta `Evidencia` se incorporaron capturas correspondientes a:

- Ejecución del servicio web REST.
- Prueba del servicio Jakarta REST.
- Consulta de clientes desde MariaDB mediante la API REST.

## 9. Estado del desarrollo

**Implementado y probado:**
- Configuración del entorno Java y Spring Boot.
- Implementación de servicios REST.
- Configuración de Jersey y Jakarta REST.
- Conexión a MariaDB.
- Consulta de registros de la tabla `leads`.
- Obtención de respuestas JSON.
- Incorporación de evidencias en GitHub.

**Pendiente de integración o desarrollo:**
- Validación de los endpoints propuestos.
- Integración con External Workers.
- Integración funcional con Flowable.
- Desarrollo de servicios adicionales para el seguimiento y fidelización de clientes.

El proyecto continuará desarrollándose colaborativamente por los integrantes del Grupo 4.
:::
