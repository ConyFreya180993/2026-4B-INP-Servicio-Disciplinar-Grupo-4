# Acta de Coordinación N.º 6 — Planificación y Asignación de Tareas (Entrega 3)

> **Estado del documento:** Planificación inicial de la Entrega 3, con fecha 15/11/2026. Registra la distribución propuesta de responsabilidades, los criterios técnicos prioritarios y los plazos internos de coordinación, conforme a la rúbrica oficial del Hito 3. Su contenido queda sujeto a confirmación y actualización según los avances y acuerdos comunicados por cada integrante.

## 1. Datos generales

- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4).
- **Fecha:** 15 de noviembre de 2026.
- **Modalidad:** Coordinación asíncrona mediante mensajería grupal y repositorio GitHub.
- **Participantes y áreas de responsabilidad:**
  - Constanza Núñez Sánchez: arquitectura de despliegue, documentación general, trazabilidad y coordinación.
  - Gabriel Eduardo Antonio Arellano Vera: persistencia MariaDB, External Worker, WebApp y externalización de configuración PHP.
  - Camila Vega: servicios web Java 21 / Jakarta REST / JDBC y externalización de configuración Spring.
  - José Antonio Loyola Ibarra: consolidación de modelos BPMN 2.0 y coordinación de la presentación de negocio para la emprendedora.

## 2. Contexto y criterios prioritarios de la Entrega 3

1. **Inicio anticipado:** Considerando que aún se encuentra pendiente la retroalimentación y publicación de resultados de la Entrega 2, se propone iniciar inmediatamente el desarrollo de la Entrega 3, utilizando como referencia los criterios de evaluación publicados por el docente (100 puntos). Se considera también la necesidad de anticipar posibles ajustes derivados de la retroalimentación pendiente.

2. **Disponibilidad y modalidad de trabajo:** Se deja constancia de que la coordinadora tendrá disponibilidad reducida durante la semana del 16 al 22 de noviembre, debido a compromisos familiares y labores de cuidado previamente establecidos. Por este motivo, se priorizará el trabajo asíncrono y la comunicación oportuna de avances, cambios y dificultades por parte de cada responsable.

3. **Plazos internos de coordinación:** Se proponen las siguientes fechas para disponer de tiempo suficiente para revisar la integración, actualizar la arquitectura y consolidar la documentación:
   - **Domingo 22/11, 21:00 hrs:** plazo interno para disponer de los avances de código, configuración, BPMN, evidencias y documentación de cada módulo.
   - **Lunes 23/11, 20:00 hrs:** plazo interno para disponer de los videos y sus respectivos enlaces.
   - **Lunes 23/11, 23:59 hrs:** plazo oficial de entrega de la E3. Cada integrante deberá realizar el envío individual del enlace al repositorio, según las instrucciones del docente.

4. **Coherencia entre componentes:** Los diagramas, README y documentos generales deberán representar la implementación efectivamente realizada. Los cambios que afecten conexiones, endpoints, puertos, variables, dependencias o estructura de datos deberán ser comunicados por el responsable del módulo, indicando su estado de implementación y verificación.

## 3. Compromisos y distribución de tareas por módulo

| Integrante | Módulo(s) | Compromisos técnicos propuestos |
|---|---|---|
| **Camila Vega** | `Proyecto-1-WebServices` | 1. Externalizar la configuración de Spring Boot y MariaDB mediante variables de entorno y una plantilla de ejemplo sin credenciales reales.<br/>2. Definir e implementar un endpoint Jakarta REST (Jersey) que permita consultar un cliente específico mediante `id_lead`, obteniendo los datos reales desde `peluditos_db`.<br/>3. Coordinar con Gabriel el contrato del servicio: URL, método HTTP, parámetros y estructura de respuesta JSON.<br/>4. Documentar pruebas de funcionamiento y manejo de errores del servicio. |
| **Gabriel Arellano** | `Base-de-Datos`, `Proyecto-2-ExternalWorker`, `Proyecto-3-WebApp` | 1. Externalizar las conexiones de MariaDB y Flowable, además de la URL del WebService, mediante configuración externa y sus respectivas plantillas de ejemplo.<br/>2. Coordinar e implementar el consumo del endpoint Jakarta REST de Camila desde `worker.php`, utilizando `id_lead` como identificador de consulta y evitando consultas directas a MariaDB desde el Worker para sustituir el servicio web.<br/>3. Registrar y gestionar los errores de comunicación con el servicio externo, verificando el mecanismo de recuperación o reintento aplicable a las tareas de Flowable.<br/>4. Revisar y actualizar `Base-de-Datos/README.md` para mantener su correspondencia con el esquema SQL implementado, incluyendo las relaciones entre `leads`, `pet` y las demás tablas que correspondan.<br/>5. Incorporar evidencias de validación. |
| **José Loyola** | `BPMN`, `Videos/Entrega-3/Emprendedora` | 1. Revisar y alinear los modelos BPMN (`TO-BE` de Leads y Fidelización) con los tópicos, variables y tareas efectivamente implementados.<br/>2. Actualizar `BPMN/README.md`, diferenciando los elementos implementados de aquellos pendientes de verificación, e incorporar los pasos de despliegue correspondientes.<br/>3. Coordinar la preparación del video de presentación de avances para la emprendedora, considerando los recorridos que puedan demostrarse efectivamente. |
| **Constanza Núñez** | `Arquitectura`, `Actas`, `README.md` raíz | 1. Actualizar el diagrama UML de arquitectura y despliegue de la E3, reflejando los componentes, sus conexiones, la configuración externa y los puertos efectivamente definidos y verificados.<br/>2. Documentar el recorrido de integración esperado entre External Worker, WebService Jakarta REST y MariaDB, diferenciando los elementos implementados de los pendientes de validación.<br/>3. Preparar el borrador de instrucciones de despliegue limpio y el plan de continuidad hacia la Entrega 4 (Examen).<br/>4. Organizar las carpetas de evidencias audiovisuales y mantener la trazabilidad de los avances mediante actas y documentación general. |

## 4. Compromisos audiovisuales compartidos

- **Video técnico:** Cada integrante deberá aportar su segmento de explicación técnica, dentro del tiempo máximo establecido por la pauta, mostrando el desarrollo y las evidencias correspondientes a su área de responsabilidad. La presentación conjunta deberá reflejar el estado real de integración del proyecto.
- **Video para la emprendedora:** Se preparará una presentación orientada al negocio, demostrando los recorridos que se encuentren disponibles y diferenciando los avances comprobables de las funcionalidades pendientes.

## 5. Seguimiento y comunicación

Para mantener la coordinación asíncrona y evitar modificaciones de última hora:

1. Cada responsable deberá informar por el chat grupal los avances de su módulo y comunicar oportunamente cualquier dificultad que pueda afectar los plazos o la integración.
2. Los cambios que afecten la arquitectura, conexiones, endpoints, puertos, variables de configuración, dependencias o estructura de datos deberán acompañarse de una breve descripción y su correspondiente evidencia o estado de verificación.
3. Las propuestas de implementación deberán distinguirse de los cambios efectivamente realizados y probados.
4. La actualización del diagrama UML y de la documentación general se realizará sobre la base de la información técnica confirmada por cada responsable.

**Observación:** Esta acta corresponde a una planificación inicial de trabajo. Los compromisos y estados de avance podrán actualizarse conforme a las confirmaciones de los integrantes y a los resultados de las pruebas de integración.
