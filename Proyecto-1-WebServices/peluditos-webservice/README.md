# Servicios Web REST – Peluditos Pet Chile

**Integración de Plataformas – Servicio Disciplinar – Grupo 4**

## 1. Descripción del componente

El módulo de Servicios Web tiene como objetivo desarrollar y proporcionar servicios REST que permitan consultar información de clientes potenciales (leads) de Peluditos Pet Chile, apoyando la gestión comercial y el seguimiento de oportunidades de venta.

Este proyecto corresponde al desarrollo de servicios web en Java, realizado como parte del Servicio Disciplinar del Grupo 4.

El componente forma parte de una arquitectura orientada a procesos de negocio BPMN, cuya orquestación se contempla mediante Flowable OSS 7.2 y External Workers desarrollados por otros integrantes del equipo.

Durante el desarrollo se implementó un servicio de prueba inicial y posteriormente un servicio de consulta de clientes conectado a una base de datos MariaDB.

El servicio de consulta permite recuperar información almacenada en la base de datos y entregar los resultados en formato JSON.

### Tecnologías utilizadas

- Java JDK 21.
- Spring Boot 4.1.2.
- Spring Web y Spring Web MVC.
- Jakarta REST (JAX-RS).
- Jersey.
- Spring JDBC (JdbcTemplate).
- MariaDB.
- Apache Maven.
- Visual Studio Code.
- Git y GitHub.

## 2. Estado de avance y pruebas

**Estado actual del módulo: IMPLEMENTADO Y PROBADO LOCALMENTE.**

Durante el desarrollo se realizaron las siguientes actividades:

- Configuración del entorno de desarrollo Java.
- Creación y configuración del proyecto Spring Boot.
- Implementación de un servicio REST de prueba inicial.
- Incorporación de Jakarta REST mediante Jersey.
- Configuración de la conexión con MariaDB.
- Implementación de un servicio REST para consultar clientes potenciales.
- Ejecución de pruebas locales desde Google Chrome.
- Verificación de respuestas en formato JSON.
- Incorporación del código fuente y evidencias al repositorio GitHub.
- Actualización de la documentación técnica del módulo.

### 2.1. Servicio REST de prueba inicial

**Nombre:** Servicio de prueba Peluditos Pet Chile.

**Método HTTP:** GET.

**Endpoint:** `/api/consultas/prueba`

**Objetivo:** Comprobar el funcionamiento inicial del servicio web mediante una solicitud HTTP.

**Datos de entrada:** No requiere parámetros.

**Datos de salida:** Respuesta JSON con un estado y un mensaje.

**Respuesta documentada inicialmente:**

```json
{
  "estado": "OK",
  "mensaje": "Web Service Peluditos funcionando"
}
```

**Resultado registrado en la documentación original:**

Se informó que la aplicación Spring Boot se ejecutó correctamente utilizando Java 21 y que el servidor Tomcat integrado inició en el puerto 8080.

También se documentó que el servicio respondió a una solicitud realizada desde Google Chrome.

**Relación con el proceso de negocio:** Permite verificar la conectividad del servicio antes de integrarlo con un External Worker de Flowable.

**Observación:** Este servicio corresponde a la etapa inicial del proyecto. Su funcionamiento en la configuración actual debe verificarse nuevamente, debido a los cambios posteriores en las dependencias y rutas de la aplicación.

### 2.2. Servicio Jakarta REST

**Archivo:** `ClientesResource.java`

**Método HTTP:** GET.

**Endpoint de recurso:** `/clientes`

**Objetivo:** Comprobar el funcionamiento de un servicio desarrollado utilizando Jakarta REST.

**Datos de entrada:** No requiere parámetros.

**Respuesta de prueba:**

```json
{
  "estado": "OK",
  "mensaje": "Servicio Jakarta REST Peluditos funcionando"
}
```

Durante el desarrollo se obtuvo una respuesta satisfactoria del servicio Jakarta REST.

Con la configuración:

`spring.jersey.application-path=/jakarta`

La ruta completa prevista es:

`http://localhost:8080/jakarta/clientes`

Esta dirección debe verificarse nuevamente con la configuración actual antes de declararla como prueba definitiva.

### 2.3. Servicio REST de consulta de clientes

**Archivo:** `ClientesController.java`

**Método HTTP:** GET.

**Endpoint:** `/api/clientes`

**Objetivo:** Consultar los registros almacenados en la tabla `leads` de MariaDB.

**Datos de entrada:** No requiere parámetros.

**Datos de salida:** Listado de clientes potenciales en formato JSON.

**Consulta SQL utilizada:**

```sql
SELECT * FROM leads;
```

El servicio utiliza `JdbcTemplate` para recuperar la información almacenada en la base de datos.

Durante las pruebas locales se obtuvo una respuesta JSON con tres registros de clientes potenciales.

Esta prueba permitió comprobar el funcionamiento de la consulta desde el servicio REST hacia MariaDB.

**Resultado: IMPLEMENTADO Y PROBADO LOCALMENTE.**

### 2.4. Configuración de base de datos

Se configuró la conexión local mediante el archivo `application.properties`.

**Datos de configuración:**

- Motor de base de datos: MariaDB.
- Base de datos: `peluditos_db`.
- Servidor: `127.0.0.1`.
- Puerto: `3306`.
- Tabla consultada: `leads`.
- Usuario de aplicación: `peluditos_app`.

La contraseña de la base de datos se obtiene mediante la variable de entorno:

`PELUDITOS_DB_PASSWORD`

Esto permite evitar la publicación directa de la contraseña en el repositorio GitHub.

### 2.5. Ejecución del proyecto

Para ejecutar el servicio se requiere Java 21, MariaDB y la base de datos correspondiente.

Desde la carpeta `peluditos-webservice`, abrir una terminal PowerShell y ejecutar:

```powershell
.\mvnw.cmd clean compile
.\mvnw.cmd spring-boot:run
```

Una vez iniciado el servidor Tomcat en el puerto 8080, comprobar los servicios desde el navegador.

**Servicio de consulta de clientes probado:**

`http://localhost:8080/api/clientes`

**Servicio de prueba inicial documentado:**

`http://localhost:8080/api/consultas/prueba`

**Servicio Jakarta REST con la configuración actual prevista:**

`http://localhost:8080/jakarta/clientes`

Las rutas correspondientes a los servicios de prueba deben verificarse nuevamente después de los cambios de configuración.

## 3. Evidencias del desarrollo

Las capturas de respaldo del módulo se encuentran en la carpeta:

`./Evidencia`

### Evidencias incorporadas

**Prueba_Jakarta_REST.png**

Captura que respalda la respuesta obtenida durante las pruebas del servicio Jakarta REST.

**Prueba_API_REST_MariaDB.png**

Captura que respalda la consulta de clientes potenciales almacenados en MariaDB mediante el endpoint `/api/clientes`.

Las evidencias permiten demostrar el avance técnico y las pruebas locales realizadas durante el desarrollo.

No se considera demostrada una integración completa con Flowable mientras no existan pruebas conjuntas que la respalden.

## 4. Dificultades técnicas y observaciones

Durante el desarrollo del módulo se presentaron dificultades relacionadas con la configuración de rutas, dependencias y ejecución de los servicios.

Estas situaciones requirieron revisar y ajustar la configuración del proyecto para lograr su funcionamiento local.

### 4.1. Error HTTP 404 en los servicios REST

Durante las primeras pruebas se presentaron errores HTTP 404 al intentar acceder a determinadas rutas del servicio.

Se revisaron los controladores y la configuración de Jersey y Spring MVC.

**Solución aplicada:**

Se realizaron ajustes en `application.properties`, configurando Jersey como filtro y estableciendo una ruta independiente para sus recursos.

Posteriormente, se reinició la aplicación y se comprobó que el endpoint `/api/clientes` respondía correctamente con información almacenada en MariaDB.

**Resultado:** Servicio de consulta de clientes funcionando correctamente.

**Observación:** El servicio inicial `/api/consultas/prueba` requiere una nueva verificación con la configuración actual.

### 4.2. Configuración de dependencias Java

Durante el desarrollo fue necesario revisar las dependencias del proyecto para permitir el funcionamiento de Jakarta REST, Jersey, Spring JDBC y MariaDB.

Se realizaron ajustes en el archivo `pom.xml` y se verificó la ejecución de la aplicación mediante Maven.

**Resultado:** Aplicación ejecutada correctamente durante las pruebas locales.

### 4.3. Ejecución desde Visual Studio Code

Se presentaron dificultades relacionadas con la ejecución de comandos Maven desde una ubicación distinta a la carpeta del proyecto.

Se revisó la ruta de trabajo de la terminal y posteriormente se logró ejecutar la aplicación Spring Boot.

**Resultado:** Servidor iniciado en el puerto 8080.

### 4.4. Integración con Flowable y External Workers

El módulo de Servicios Web forma parte de una arquitectura que contempla su comunicación con Flowable OSS 7.2 y External Workers.

Estos componentes corresponden a otros módulos desarrollados por integrantes del Grupo 4.

El servicio REST de consulta de clientes se encuentra implementado y probado de manera local.

**Estado:** Integración entre módulos pendiente de validación conjunta.

No se declara una integración completa mientras no existan pruebas y evidencias que permitan comprobar la comunicación entre los componentes.

### 4.5. Observaciones generales

El módulo cuenta con avances funcionales que pueden ser utilizados como base para las siguientes etapas.

Las dificultades encontradas permitieron realizar ajustes técnicos y mejorar la configuración de los servicios.

Los problemas resueltos y los avances comprobados se documentan para facilitar la continuidad del desarrollo por parte del equipo.

## 5. Servicios web implementados y propuestos

Se consideran los siguientes servicios para apoyar la gestión de consultas y clientes potenciales de Peluditos Pet Chile.

| Servicio | Método y endpoint | Entrada | Salida | Estado |
|---|---|---|---|---|
| Servicio de prueba inicial | GET `/api/consultas/prueba` | Sin parámetros | Estado y mensaje JSON | Documentado inicialmente; requiere nueva verificación |
| Consultar clientes | GET `/api/clientes` | Sin parámetros | Listado de leads JSON | Probado localmente |
| Registrar consulta | POST `/api/consultas` | Datos del cliente y consulta | Identificador y estado | Propuesto |
| Consultar solicitud | GET `/api/consultas/{id}` | Identificador | Datos y estado | Propuesto |
| Registrar resultado | PATCH `/api/consultas/{id}/resultado` | Identificador y resultado | Estado actualizado | Propuesto |

Los servicios propuestos deberán validarse con el equipo y podrán modificarse de acuerdo con las necesidades del proceso BPMN.

## 6. Relación con la arquitectura del proyecto

El módulo de Servicios Web permite consultar información de clientes potenciales almacenada en MariaDB.

La arquitectura contempla que los procesos BPMN ejecutados mediante Flowable OSS 7.2 puedan interactuar con estos servicios a través de External Workers.

**Componentes considerados:**

- **Flowable OSS 7.2:** Motor de ejecución de procesos BPMN.
- **External Workers:** Componentes encargados de ejecutar tareas automáticas y realizar integraciones.
- **Servicios Web REST:** Exposición de operaciones para consultar y gestionar información.
- **MariaDB:** Almacenamiento de datos del proyecto.
- **Aplicación web:** Interfaz prevista para la interacción con los usuarios.

La comunicación completa entre estos componentes deberá comprobarse mediante pruebas de integración.

## 7. Estado general del desarrollo

| Actividad | Estado |
|---|---|
| Configuración del entorno Java | Completado |
| Creación del proyecto Spring Boot | Completado |
| Servicio REST de prueba inicial | Documentado inicialmente |
| Incorporación de Jakarta REST | Implementado |
| Configuración de MariaDB | Completado |
| Consulta de clientes desde MariaDB | Probado localmente |
| Evidencias del servicio REST | Incorporadas |
| Documentación técnica del módulo | Actualizada |
| Integración con External Workers | Pendiente de validación conjunta |
| Integración con Flowable | Pendiente de validación conjunta |
| Servicios REST adicionales | Propuestos |

## 8. Pendientes para la siguiente etapa

- Coordinar las pruebas de integración con el módulo External Worker.
- Validar la comunicación con Flowable OSS 7.2.
- Verificar nuevamente los endpoints de prueba después de los cambios de configuración.
- Implementar los servicios REST adicionales requeridos por el proceso.
- Realizar pruebas integrales del flujo BPMN.
- Incorporar nuevas evidencias de funcionamiento.
- Completar la documentación técnica de integración.

Estas actividades deberán coordinarse con los integrantes responsables de cada componente.

## 9. Conclusión

Durante el desarrollo del proyecto Peluditos Pet Chile se logró avanzar en la implementación de un módulo de Servicios Web REST utilizando Java y Spring Boot.

Inicialmente se documentó un servicio de prueba destinado a comprobar la conectividad de la aplicación.

Posteriormente, se incorporó Jakarta REST mediante Jersey y se implementó un servicio de consulta de clientes potenciales conectado a MariaDB.

Las pruebas locales permitieron comprobar la recuperación de registros desde la base de datos y su entrega en formato JSON.

Las dificultades relacionadas con las rutas y configuraciones fueron abordadas mediante ajustes técnicos que permitieron obtener un servicio funcional.

El módulo constituye un avance para la integración posterior con Flowable OSS 7.2 y los External Workers, cuya validación se realizará en coordinación con los demás integrantes del Grupo 4.

**Estado final del módulo de Servicios Web: IMPLEMENTADO Y PROBADO LOCALMENTE.**
