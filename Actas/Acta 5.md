# Acta de Coordinación N° 5 — Cierre de Integración y Evidencias (Entrega 2)

> **Estado del documento:** Acta definitiva de cierre para la Entrega 2 (09/11/2026 — 22:20 hrs). Consolida los avances técnicos verificados en el monorepo, las soluciones aplicadas a problemas de configuración, la organización de evidencias audiovisuales y la justificación de contingencias técnicas del equipo.

## 1. Datos Generales
- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4)
- **Fecha de cierre:** 09 de noviembre de 2026
- **Modalidad:** Coordinación asíncrona, consolidación en GitHub y grabación de defensas técnicas modulares
- **Participantes y roles en este hito:**
  - **Constanza Núñez Sánchez:** Arquitectura UML de despliegue y componentes, trazabilidad documental, respaldo de evolución de borradores y coordinación general.
  - **Gabriel Eduardo Antonio Arellano Vera:** Base de datos relacional (`peluditos_db`), External Worker en PHP (`worker.php`), aplicación web (`Proyecto-3-WebApp`) y pruebas de ejecución BPMN local (`TO-BE.bpmn`).
  - **Camila Vega:** Desarrollo y pruebas locales del servicio web en Java 21 con Jakarta REST (Jersey), Spring JDBC (`JdbcTemplate`) y conexión a MariaDB (`Proyecto-1-WebServices`).
  - **José Antonio Loyola Ibarra:** Modelamiento de procesos BPMN 2.0 (Leads y Fidelización), documentación funcional y reporte respaldado de bloqueos de entorno en Flowable.

---

## 2. Avances Logrados y Consolidación por Módulo

1. **Arquitectura y Evolución del Diseño (`Arquitectura` — Completado):**
   - Se publicó la versión definitiva del diagrama UML de despliegue y componentes (`Arquitectura-entrega-2-final` en formatos `.png`, `.svg` y `.drawio`), aplicando el código de colores (Verde = Integrado, Naranjo = Implementación parcial / Pruebas locales, Azul = Persistencia, Amarillo = Proyección Entrega 3).
   - Se respaldó la presentación con los borradores previos para demostrar la evolución del diseño a partir de la retroalimentación docente de la Entrega 1 y se enlazó el video de defensa arquitectónica.

2. **Persistencia, External Worker y Bandeja Web (`Base-de-Datos`, `Proyecto-2-ExternalWorker`, `Proyecto-3-WebApp` — Implementado e Integrado):**
   - **MariaDB (`peluditos_db`):** Se implementó `esquema_peluditos.sql` con las tablas relacionales `leads`, `pet`, `seguimientos` y `fidelizacion`, manteniendo su estricta separación respecto a `flowable_db`.
   - **Bandeja de Tareas Web:** Se habilitó la interfaz web (HTML/CSS/JS) conectada a servicios PHP (`api/registrar_lead.php`, `listar_seguimientos.php`, `actualizar_seguimiento.php`, `gestionar_fidelizacion.php` y `listar_procesos.php`), permitiendo registrar clientes en MariaDB e iniciar instancias del proceso `Process_1pot1gs` en Flowable REST pasando la variable `id_lead` (respaldado con las evidencias `04` a `08`).
   - **External Worker (`worker.php`):** Se implementó y probó localmente el worker `peluditos-worker-1` haciendo *HTTP Polling* sobre `/external-job-api/acquire/jobs` para el tópico `sincronizar-cliente`, validando los datos del cliente en `peluditos_db` y completando la tarea externa en Flowable.

3. **Servicios Web REST en Java 21 (`Proyecto-1-WebServices` — Implementado y Probado Localmente):**
   - Se implementó y verificó en el puerto `8080` el servicio sobre Java 21 y Spring Boot 4.1.2, resolviendo errores iniciales de rutas HTTP 404 mediante la configuración de **Jersey (Jakarta REST)** como filtro bajo el prefijo `spring.jersey.application-path=/jakarta` (`http://localhost:8080/jakarta/clientes` en `ClientesResource.java`).
   - Se implementó la consulta de prospectos hacia la tabla `leads` de MariaDB utilizando `JdbcTemplate` en el endpoint `GET /api/clientes` (`ClientesController.java`), externalizando la contraseña de base de datos mediante la variable de entorno `PELUDITOS_DB_PASSWORD` en `application.properties`.
   - Se incorporaron las capturas de respaldo `Prueba_Jakarta_REST.png` y `Prueba_API_REST_MariaDB.png` en `Proyecto-1-WebServices/Evidencia`, dejando documentados como propuestos los endpoints adicionales (`POST /api/consultas`, `GET /api/consultas/{id}`, `PATCH /api/consultas/{id}/resultado`) para su integración conjunta con el External Worker en la Entrega 3.

---

## 3. Contingencias Técnicas Documentadas (Módulo BPMN)

- **Indisponibilidad de Flowable Trial y bloqueo en OSS 7.2 local:** José Loyola actualizó la especificación de los módulos de *Gestión de Leads* y *Generación de Oportunidades Comerciales (Fidelización)* en `BPMN/README.md`, incorporando el modelo unificado libre de errores (`modelo to-be+fidelizacion.bpmn` y su vista en `BPMN/Evidencia/modelo to-be+fidelizacion.png`). Durante la preparación de las pruebas locales se presentaron errores persistentes al instalar **Flowable OSS 7.2**, sumado a la caída por mantenimiento del portal *Flowable Trial* el domingo 08 de noviembre de 2026 (respaldado en `BPMN/Evidencia/servicio trial flowable en mantenimiento.png`).
- **Mitigación técnica del equipo:** Siguiendo la regla de transparencia del grupo ("propuesto no significa implementado"), se documentó la contingencia con evidencia en el repositorio y se ejecutaron las pruebas locales de integración utilizando el proceso ejecutable `BPMN/TO-BE.bpmn` (`Process_1pot1gs`) sobre el entorno Apache Tomcat/XAMPP configurado por Gabriel Arellano.

---

## 4. Organización de Carpetas de Video y Decisiones de Entrega

1. **Estandarización de rutas por hito:** Para mantener el orden del monorepo hacia la Entrega 3 y el Examen Final, se estructuró el directorio `Videos/Entrega-2/` en dos subcarpetas:
   - `Videos/Entrega-2/Tecnico/` (centralizando en su `README.md` los enlaces a las defensas técnicas por bloque).
   - `Videos/Entrega-2/Emprendedora/` (destinada a la cápsula comercial).
2. **Defensas técnicas modulares:** Se priorizó la grabación y carga de los videos técnicos individuales ( de 2 a 3 minutos promedio por integrante) para asegurar el puntaje de arquitectura, BPMN y servicios web antes del cierre. Al momento de cierre de esta acta, se encuentran cargados y verificados en `Videos/Entrega-2/Tecnico/README.md` los enlaces de **Bloque 1 (Arquitectura — Constanza Núñez)**, **Bloque 2 (Flowable — José Loyola)** y **Bloque 3 (WebServices — Camila Vega)**, quedando el **Bloque 4 (Base de Datos, Worker y WebApp — Gabriel Arellano)** respaldado por la documentación exhaustiva y las capturas de ejecución en sus respectivas carpetas `/Evidencia` (a la espera de enlace de video si logra subirlo antes del cierre del buzón a la hora de editar esta acta).

---

## 5. Resumen de Estado Final al Cierre

| Carpeta / Entregable | Responsable | Estado Final E2 | Respaldo Verificable en Monorepo |
| :--- | :--- | :--- | :--- |
| `Arquitectura` | Constanza Núñez | **Completado** | Diagrama final (`.png`, `.svg`, `.drawio`), PPT de evolución, `README.md` y video Bloque 1. |
| `Base-de-Datos` | Gabriel Arellano | **Implementado e Integrado** | `esquema_peluditos.sql`, `README.md` y capturas en `/Evidencia`. |
| `Proyecto-2-ExternalWorker` | Gabriel Arellano | **Implementado e Integrado** | `worker.php`, `README.md` y capturas en `/evidencia`. |
| `Proyecto-3-WebApp` | Gabriel Arellano | **Implementado e Integrado** | Interfaz web, `api/`, `README.md` y capturas `04` a `08` en `/evidencia`. |
| `Proyecto-1-WebServices` | Camila Vega | **Implementado y Probado Localmente** | Código Java 21 / Jersey (`/jakarta/clientes`) y JDBC (`/api/clientes`), `README.md`, `Prueba_Jakarta_REST.png`, `Prueba_API_REST_MariaDB.png` y video Bloque 3. |
| `BPMN` | José Loyola / Gabriel Arellano | **Implementación Parcial** | `TO-BE.bpmn` local, `modelo to-be+fidelizacion.bpmn`, `README.md`, capturas en `BPMN/Evidencia` y video Bloque 2. |
| `Videos/Entrega-2/Tecnico` | Equipo Grupo 4 | **Completado (Bloques 1, 2 y 3 enlazados)** | `Videos/Entrega-2/Tecnico/README.md` con enlaces activos. |
