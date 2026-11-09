Servicios Web REST – Peluditos Pet Chile

Integración de Plataformas – Servicio Disciplinar – Grupo 4

1. Descripción del componente

El módulo de Servicios Web tiene como objetivo desarrollar y proporcionar servicios REST que permitan consultar información de clientes potenciales (leads) de Peluditos Pet Chile, apoyando la gestión comercial y el seguimiento de oportunidades de venta.

Este componente forma parte de una arquitectura orientada a procesos de negocio BPMN, cuya orquestación se realizará mediante Flowable OSS 7.2 y External Workers desarrollados por otros integrantes del equipo.

El servicio implementado permite consultar información almacenada en una base de datos MariaDB y obtener los resultados en formato JSON.

Tecnologías utilizadas

Java JDK 21.

Spring Boot 4.1.2.

Jakarta REST (JAX-RS).

Jersey.

Spring Web MVC.

Spring JDBC (JdbcTemplate).

MariaDB.

Apache Maven.

Visual Studio Code.

Git y GitHub.

2. Estado de avance y pruebas

Estado actual: IMPLEMENTADO Y PROBADO LOCALMENTE.

Durante el desarrollo del segundo hito se realizaron las siguientes actividades:

Configuración del proyecto Java utilizando Maven.

Implementación de servicios web REST.

Incorporación de Jakarta REST mediante Jersey.

Configuración del acceso a MariaDB.

Implementación de un servicio de consulta de clientes potenciales.

Ejecución de pruebas locales desde Google Chrome.

Verificación de respuestas JSON.

Incorporación del código fuente y evidencias al repositorio GitHub.

2.1. Servicio Jakarta REST

Archivo: ClientesResource.java

Método HTTP: GET

Endpoint: /clientes

Objetivo: Comprobar el funcionamiento de un servicio desarrollado utilizando la especificación Jakarta REST.

Datos de entrada: No requiere parámetros.

Respuesta de prueba:

{
  "estado": "OK",
  "mensaje": "Servicio Jakarta REST Peluditos funcionando"
}

Este servicio fue probado localmente y respondió correctamente.

2.2. Servicio REST de consulta de clientes

Archivo: ClientesController.java

Método HTTP: GET

Endpoint: /api/clientes

Objetivo: Consultar los registros almacenados en la tabla leads de MariaDB.

Datos de entrada: No requiere parámetros.

Datos de salida: Listado de registros en formato JSON.

Consulta SQL utilizada:

SELECT * FROM leads;

El servicio utiliza JdbcTemplate para recuperar la información almacenada en la base de datos.

Durante las pruebas locales se obtuvo una respuesta JSON con tres registros de clientes potenciales, comprobando el funcionamiento de la consulta.

2.3. Configuración de base de datos

Se configuró la conexión local mediante el archivo application.properties.

Motor: MariaDB.

Base de datos: peluditos_db.

Servidor: 127.0.0.1.

Puerto: 3306.

Tabla consultada: leads.

La contraseña de la base de datos se obtiene mediante la variable de entorno PELUDITOS_DB_PASSWORD, evitando publicar credenciales directamente en GitHub.

2.4. Ejecución del proyecto

Para ejecutar el servicio, se requiere Java 21, MariaDB y la base de datos correspondiente.

Desde la carpeta peluditos-webservice, ejecutar en PowerShell:

.\mvnw.cmd clean compile
.\mvnw.cmd spring-boot:run

Una vez iniciado el servidor Tomcat en el puerto 8080, comprobar los servicios desde el navegador.

Consulta de clientes:

http://localhost:8080/api/clientes

Servicio Jakarta REST:

http://localhost:8080/jakarta/clientes

La segunda dirección corresponde a la configuración actual de spring.jersey.application-path=/jakarta y debe verificarse nuevamente después del cambio de configuración.

3. Evidencias del desarrollo

Las capturas de respaldo se encuentran en la carpeta:

./Evidencia

Archivos incorporados:

Prueba_Jakarta_REST.png: evidencia de la respuesta del servicio Jakarta REST.

Prueba_API_REST_MariaDB.png: evidencia de la consulta de clientes almacenados en MariaDB.

Estas evidencias respaldan las pruebas locales realizadas durante el segundo hito.

4. Dificultades técnicas y observaciones

Durante el desarrollo del módulo se presentaron dificultades técnicas que requirieron realizar ajustes en la configuración y ejecución del proyecto.

4.1. Error HTTP 404 en los servicios REST

Durante las primeras pruebas, el endpoint /api/clientes devolvía un error HTTP 404, aunque la aplicación Spring Boot iniciaba correctamente.

Se revisaron los controladores, las rutas y la configuración de Jersey y Spring MVC.

Solución aplicada:

Se realizaron ajustes en application.properties para configurar Jersey como filtro y establecer una ruta de aplicación independiente.

Posteriormente, se reinició la aplicación y se comprobó que /api/clientes respondía correctamente con los registros almacenados en MariaDB.

Resultado: Dificultad solucionada y servicio probado localmente.

4.2. Configuración de dependencias Java

Se realizaron ajustes en pom.xml para incorporar las dependencias necesarias para el funcionamiento de Jakarta REST, Jersey, Spring JDBC y MariaDB.

Se verificó la compilación del proyecto mediante Maven.

Resultado: Compilación exitosa.

4.3. Ejecución del proyecto desde Visual Studio Code

Durante las pruebas se presentaron dificultades al ejecutar Maven desde una carpeta distinta a la ubicación del proyecto.

Se corrigió la ruta de trabajo de la terminal y posteriormente se ejecutó correctamente la aplicación.

Resultado: Aplicación iniciada en el puerto 8080.

4.4. Integración con Flowable y External Workers

La integración del módulo de Servicios Web con Flowable OSS 7.2 y los External Workers corresponde a otros componentes desarrollados por integrantes del Grupo 4.

El servicio de consulta de clientes se encuentra implementado y probado de manera local.

Estado: Integración entre módulos pendiente de validación conjunta.

No se declara una integración completa con Flowable mientras no existan pruebas y evidencias que la respalden.

5. Servicios web propuestos

Se consideran los siguientes servicios para futuras etapas del proyecto:

Servicio

Método y endpoint

Objetivo

Estado

Consultar clientes

GET /api/clientes

Obtener registros de leads

Probado localmente

Registrar consulta

POST /api/consultas

Registrar una consulta comercial

Propuesto

Consultar solicitud

GET /api/consultas/{id}

Consultar una solicitud específica

Propuesto

Registrar resultado

PATCH /api/consultas/{id}/resultado

Actualizar el resultado de una consulta

Propuesto

Los servicios propuestos deberán validarse con el equipo de acuerdo con el proceso BPMN y los requerimientos del negocio.

6. Relación con la arquitectura del proyecto

El módulo de Servicios Web permite consultar información de clientes potenciales almacenada en MariaDB.

La arquitectura contempla que los procesos BPMN ejecutados mediante Flowable OSS 7.2 puedan interactuar con estos servicios a través de External Workers.

Componentes considerados:

Flowable OSS 7.2: motor de procesos BPMN.

External Workers: ejecución de tareas automáticas e integración.

Servicios Web REST: exposición de operaciones para consultar información.

MariaDB: almacenamiento de datos.

Aplicación web: interfaz de interacción con los usuarios.

La comunicación completa entre estos componentes deberá comprobarse mediante pruebas de integración.

7. Pendientes para la siguiente etapa

Coordinar las pruebas de integración con el módulo External Worker.

Validar la comunicación con Flowable OSS 7.2.

Implementar los endpoints adicionales requeridos por el proceso.

Realizar pruebas integrales del flujo BPMN.

Incorporar nuevas evidencias de funcionamiento.

Completar la documentación técnica de integración.

8. Conclusión

Durante el segundo hito del proyecto Peluditos Pet Chile se logró implementar y probar localmente un módulo de Servicios Web REST desarrollado en Java.

Se comprobó el funcionamiento de Jakarta REST mediante Jersey y la consulta de registros almacenados en MariaDB.

Las dificultades de configuración y ejecución fueron abordadas mediante ajustes técnicos, permitiendo obtener resultados funcionales y verificables.

El módulo constituye un avance para la integración posterior con Flowable OSS 7.2 y los External Workers, cuya validación se realizará de manera colaborativa con los integrantes del Grupo 4.

Estado final del módulo: IMPLEMENTADO Y PROBADO LOCALMENTE.
