# Acta de Coordinación N° 5 — Cierre de Integración y Evidencias (Entrega 2)

> **Estado del documento:** Acta definitiva de cierre para la Entrega 2 (09/11/2026). Consolida los avances técnicos verificados en el monorepo, la organización de evidencias audiovisuales y la justificación de contingencias técnicas del equipo.

## 1. Datos Generales
- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4)
- **Fecha:** 09 de noviembre de 2026
- **Modalidad:** Coordinación asíncrona, consolidación en GitHub y grabación de defensas técnicas
- **Participantes y roles en este hito:**
  - **Constanza Núñez Sánchez:** Arquitectura UML de despliegue y componentes, trazabilidad documental, respaldo de evolución de borradores y coordinación general.
  - **Gabriel Eduardo Antonio Arellano Vera:** Base de datos relacional (`peluditos_db`), External Worker en PHP (`worker.php`), aplicación web (`Proyecto-3-WebApp`) y pruebas de ejecución BPMN local (`TO-BE.bpmn`).
  - **Camila Vega:** Desarrollo y pruebas locales del servicio web en Java 21 con soporte Jakarta REST (Jersey) y conexión JDBC a MariaDB (`Proyecto-1-WebServices`).
  - **José Antonio Loyola Ibarra:** Corrección de modelos BPMN de negocio (Leads y Fidelización) en entorno Cloud y reporte documentado de bloqueos de instalación local.

---

## 2. Avances Logrados y Consolidación en el Monorepo

1. **Arquitectura y Evolución del Diseño (`Arquitectura` — Completado):**
   - Se publicó la versión definitiva del diagrama UML de despliegue y componentes (`Arquitectura-entrega-2-final` en formatos `.png`, `.svg` y `.drawio`), aplicando el código de colores (Verde = Integrado, Naranjo = Implementación parcial / Pruebas locales, Azul = Persistencia, Amarillo = Proyección Entrega 3).
   - Se resubió la presentación con los borradores previos para dejar trazabilidad completa de la evolución del diseño a partir de la retroalimentación docente de la Entrega 1.
   - Se actualizó el enlace del video de defensa arquitectónica con el recorrido actualizado por capas y flujos.

2. **Persistencia, External Worker y Bandeja Web (`Base-de-Datos`, `Proyecto-2-ExternalWorker`, `Proyecto-3-WebApp` — Implementado e Integrado):**
   - **MariaDB (`peluditos_db`):** Se implementó `esquema_peluditos.sql` con las tablas relacionales `leads`, `pet`, `seguimientos` y `fidelizacion`, manteniendo su independencia respecto a `flowable_db`.
   - **Bandeja de Tareas Web:** Se habilitó la interfaz web (HTML/CSS/JS) conectada a servicios PHP (`api/registrar_lead.php`, `listar_seguimientos.php`, `actualizar_seguimiento.php`, `gestionar_fidelizacion.php` y `listar_procesos.php`), logrando registrar clientes en MariaDB e iniciar instancias del proceso `Process_1pot1gs` en Flowable REST pasando la variable `id_lead`.
   - **External Worker (`worker.php`):** Se implementó y probó localmente el worker `peluditos-worker-1` haciendo *HTTP Polling* sobre `/external-job-api/acquire/jobs` para el tópico `sincronizar-cliente`, validando los datos del cliente en `peluditos_db` y completando la tarea externa en Flowable.

3. **Servicios Web en Java 21 (`Proyecto-1-WebServices` — Probado Localmente):**
   - Se configuró y verificó en el puerto `8080` el proyecto con Java 21 incorporando **Jakarta RESTful Web Services (`spring-boot-starter-jersey`)** mediante la clase `ClientesResource` (`/clientes`), junto con los endpoints `/api/clientes` (consulta SQL sobre la tabla `leads` en MariaDB vía JDBC) y `/api/consultas/prueba`. Queda planificada para la Entrega 3 su invocación directa desde el External Worker.

---

## 3. Contingencias Técnicas Documentadas (Módulo BPMN)

- **Situación con Flowable OSS 7.2 local:** José Loyola realizó la corrección de los diagramas BPMN (incluyendo los flujos de *Leads* y *Fidelización*) libre de errores de validación en *Flowable Cloud* (`Design Production`). Sin embargo, presentó errores persistentes durante la instalación local de la versión **Flowable OSS 7.2** desde GitHub, sumado a que el portal de descarga/prueba indicado en clases se mantuvo en mantenimiento técnico (`We'll be back soon! - Flowable Cloud Ops Team`).
- **Medida de mitigación y respaldo:** Siguiendo el protocolo del equipo ("propuesto no significa implementado"), se habilitó la subcarpeta `BPMN/Evidencia` para resguardar las capturas del bloqueo técnico y del trabajo en la nube. Paralelamente, Gabriel Arellano dispuso el archivo ejecutable `BPMN/TO-BE.bpmn` (`Process_1pot1gs`) sobre Apache Tomcat local para no detener las pruebas de integración del External Worker y la WebApp.

---

## 4. Organización de Carpetas de Video y Decisiones de Entrega

1. **Estandarización de rutas por hito:** Con el fin de mantener el orden del repositorio de cara a la Entrega 3 y el Examen Final, se reestructuró el directorio `Videos/` creando dos subcarpetas específicas para este hito:
   - `Videos/Entrega-2/Tecnico/` (con un archivo `README.md` estructurado por bloques para alojar los enlaces de cada integrante con permisos de lectura verificados).
   - `Videos/Entrega-2/Emprendedora/` (destinada a la cápsula de negocio orientada a la clienta).
2. **Decisión sobre grabaciones audiovisuales:**
   - **Modalidad modular (bloques individuales):** Dado el trabajo asíncrono y los tiempos de cierre, se acordó que cada integrante grabe y enlace su propio segmento técnico explicando su módulo (~2 minutos por bloque), asegurando la defensa individual y grupal sin depender de una edición unificada de última hora.
   - **Priorización del puntaje técnico:** Se definió dar prioridad obligatoria a los videos técnicos para el docente (donde se concentra la evaluación de arquitectura, código e integración de plataformas), dejando la grabación del video comercial para la emprendedora sujeta a disponibilidad de tiempo una vez asegurados todos los enlaces técnicos antes del corte de las 22:00 hrs.

---

## 5. Resumen de Estado al Cierre

| Carpeta / Entregable | Responsable | Estado Final E2 | Ubicación de Evidencia |
| :--- | :--- | :--- | :--- |
| `Arquitectura` | Constanza Núñez | **Completado** | Diagrama final, PPT de evolución y `README.md` |
| `Base-de-Datos` | Gabriel Arellano | **Implementado e Integrado** | `esquema_peluditos.sql`, `README.md` y `/Evidencia` |
| `Proyecto-2-ExternalWorker` | Gabriel Arellano | **Implementado e Integrado** | `worker.php`, `README.md` y `/Evidencia` |
| `Proyecto-3-WebApp` | Gabriel Arellano | **Implementado e Integrado** | Interfaz web, `api/`, `README.md` y `/Evidencia` |
| `Proyecto-1-WebServices` | Camila Vega | **Probado localmente** | Código Java 21 / Jersey, `README.md` y `/Evidencia` |
| `BPMN` | José Loyola / Gabriel Arellano | **Implementación Parcial** | `TO-BE.bpmn` local y capturas de bloqueo en `BPMN/Evidencia` |
| `Videos/Entrega-2/` | Equipo Grupo 4 | **En consolidación (tope idóneo 22:00 hrs)** | `Videos/Entrega-2/Tecnico/README.md` |
