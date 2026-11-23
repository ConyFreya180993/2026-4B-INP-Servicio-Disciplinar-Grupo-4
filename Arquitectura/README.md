# Arquitectura y Seguimiento — Entrega 3

> **Estado del documento:** Versión de cierre de Entrega 3 (23/11/2026). El diagrama y la matriz reflejan la evolución hacia una integración desacoplada (`Worker` → `Servicio Web Jakarta REST` → `MariaDB`), la separación de puertos (`8080` y `8081`) y la configuración externalizada mediante variables de entorno.

## 1. Arquitectura actual y límites de despliegue



El sistema se organiza en cuatro capas funcionales, diferenciando el nodo cliente (navegador web) del entorno de ejecución y despliegue configurable:

1. **Capa de Presentación y Bandeja Web (`Proyecto-3-WebApp` — Verde):** Interfaz web desarrollada en HTML, CSS, JavaScript y PHP, ejecutada mediante Apache/XAMPP. Incorpora la identidad visual de Peluditos Pet Chile, incluyendo el logotipo `img/logo-peluditos-chile.webp` y la paleta de colores institucionales definida mediante variables CSS. Permite registrar información comercial, persistir datos en `peluditos_db` e iniciar instancias del proceso `Process_1pot1gs` mediante la API REST de Flowable (**Flujo 1**).
   * *Limitación documentada para E4:* externalizar las credenciales y configuraciones actualmente gestionadas en `conexion.php` y `flowable_config.php`.

2. **Capa de Orquestación BPMN (`BPMN` — Naranjo):** Flowable OSS desplegado sobre Apache Tomcat, en el puerto `8080`, conectado a su propia base de datos relacional `flowable_db` (**Flujo 5**). La configuración del entorno contempla la carga de parámetros externos mediante `-Dspring.config.additional-location`.
   * *Modelo en ejecución:* el archivo utilizado para las pruebas de integración es `modelo to-be+fidelizacion.bpmn` (`Process_1pot1gs`), que publica tareas externas en el tópico `actualizar datos de ultima consulta`.
   * *Pendientes para E4:* ajustar el proceso de fidelización para que cada instancia corresponda a un cliente individual y completar la integración de los tópicos de escritura.

3. **Capa de Automatización y Servicios Web Jakarta REST (`Proyecto-2-ExternalWorker` y `Proyecto-1-WebServices` — Verde):**
   * **External Worker (`worker.php`):** Se encuentra desacoplado del acceso directo a MariaDB. Realiza HTTP Polling contra Flowable (**Flujo 3**), consume el servicio Jakarta REST en el puerto `8081` (**Flujo 4**), registra su actividad en `worker.log` y reporta los fallos mediante el endpoint `/fail` de Flowable, utilizando un intervalo de reintento de 30 segundos (`PT30S`). Su configuración contempla variables de entorno con prefijo `PELUDITOS_*`.
   * **Servicios Web Jakarta REST (`Proyecto-1-WebServices`):** Servicio desarrollado en Java 21 con Spring Boot y Jersey (JAX-RS), ejecutado en un puerto configurable (`8081` en el entorno de integración, para evitar la colisión con Flowable en `8080`). Expone los endpoints `GET /jakarta/clientes` y `GET /jakarta/clientes/{id_lead}`, incluyendo validaciones HTTP `400` y `404`. Accede a MariaDB mediante `JdbcTemplate` (**Flujo 6**) y utiliza configuración externalizada mediante `application.properties` y variables de entorno.

4. **Capa de Persistencia Relacional (`Base-de-Datos` — Azul):** MariaDB, puerto `3306`, motor `InnoDB` y codificación `utf8mb4`. Se mantiene la separación entre las bases de datos `peluditos_db`, correspondiente a la información de negocio, y `flowable_db`, utilizada por el motor BPMN.
   * *Esquema de negocio:* contempla cuatro tablas (`leads`, `pet`, `seguimientos` y `fidelizacion`), relacionadas mediante tres claves foráneas.

## 2. Evolución respecto a la Entrega 2

| Aspecto | Entrega 2 | Entrega 3 (actual) |
| :--- | :--- | :--- |
| **Servicios Web (`Proyecto-1`)** | Consulta de BD mediante `@RestController` (Spring MVC); servicio JAX-RS estático en puerto `8080`. | Lógica de consulta implementada mediante JAX-RS (`ClientesResource.java`), con `GET /jakarta/clientes` y `GET /jakarta/clientes/{id_lead}` (respuestas `400` y `404`) en puerto configurable (`8081`). |
| **External Worker (`Proyecto-2`)** | Conexión directa a MariaDB mediante `mysqli`, asociada al tópico `sincronizar-cliente`. | Sin conexión directa a MariaDB: consume el servicio JAX-RS (`8081`), atiende el tópico `actualizar datos de ultima consulta`, registra actividad en `worker.log` y reporta fallos a Flowable para su reintento. |
| **Configuración externa** | Externalización parcial de la contraseña de MariaDB mediante `PELUDITOS_DB_PASSWORD`; otros parámetros permanecían definidos directamente en la configuración. | Variables de entorno incorporadas en `application.properties` (Java) y `worker.php` (Worker). La externalización de la configuración de la WebApp queda pendiente para E4. |
| **Interfaz Web (`Proyecto-3`)** | Interfaz con paleta genérica verde, sin logotipo institucional. | Incorporación del logotipo de Peluditos Pet Chile y de la paleta institucional naranjo mediante variables CSS. |
| **Base de Datos (`Base-de-Datos`)** | Documentación desactualizada, que hacía referencia a tres tablas. | README y documentación actualizados para reflejar el esquema de cuatro tablas: `leads`, `pet`, `seguimientos` y `fidelizacion`. |
