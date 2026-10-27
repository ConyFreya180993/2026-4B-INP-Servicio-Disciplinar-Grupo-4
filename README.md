# Peluditos Pet Chile — Integración de Plataformas

- **Servicio Disciplinar:** Grupo 4
- **Asignatura:** Integración de Plataformas — IPLACEX
- **Etapa:** Entrega 1 — Diagnóstico, propuesta de solución y avances iniciales del prototipo

---

## 1. Descripción y Alcance Inicial
Este repositorio reúne los avances del proyecto académico para **Peluditos Pet Chile** (repostería artesanal para mascotas), cuyo objetivo es ordenar el registro de consultas comerciales (*leads*), su seguimiento y la detección de oportunidades de fidelización mediante **BPMN 2.0 (Flowable)**, **Java REST (Spring Boot)** y **MariaDB**.

Esta primera entrega aborda el levantamiento del proceso actual (AS-IS), el diseño de procesos propuestos (TO-BE), el esquema de arquitectura preliminar y la preparación de los componentes técnicos base de manera modular e independiente, previo a su conexión general en las siguientes etapas.

---

## 2. Modalidad de Trabajo Horizontal y Asincrónica
El equipo funciona bajo un esquema de **colaboración horizontal entre pares**, donde la distribución de carpetas responde a una división práctica de tareas técnicas y no a una jerarquía formal. 

Debido a las realidades individuales de los integrantes —que combinan turnos laborales, responsabilidades familiares/de cuidado y carga académica paralela—, el desarrollo se realiza de forma **principalmente asincrónica**, utilizando este repositorio y acuerdos puntuales de coordinación para consolidar los avances de cada área.

| Integrante | Usuario GitHub | Área de Contribución (Entrega 1) |
| :--- | :--- | :--- |
| **Constanza Núñez** | `ConyFreya180993` | Documentación de actas y diseño de arquitectura preliminar. |
| **Gabriel Arellano** | `avockato` | Diseño del modelo de datos y script SQL inicial en MariaDB. |
| **Camila Vega** | `Camila678` | Desarrollo, documentación y prueba local del servicio web Java REST. |
| **José Loyola** | `jloyola97` | Modelamiento BPMN 2.0 (AS-IS y TO-BE) y definición preliminar de formularios. |

---

## 3. Estructura del Repositorio y Avances por Componente

- **`Actas/`:** Registro de reuniones, acuerdos de coordinación y seguimiento del equipo.
- **`Arquitectura/`:** Diagrama conceptual de primera versión (`.png`), archivo editable (`.pptx`) y detalle de los 5 bloques del sistema (Encargada, Flowable, Worker externo, Servicio Java REST y MariaDB).
- **`BPMN/`:** Modelos `AS-IS`, `TO-BE` (gestión de leads) y `TO-BE` (fidelización) en formatos `.bpmn` y `.svg` elaborados en `bpmn.io`, junto con su `README.md` descriptivo.
- **`Base-de-Datos/`:** Script `esquema_peluditos.sql` para MariaDB (`peluditos_db`) con las tablas relacionadas `leads` y `seguimientos`, datos de prueba y consulta `INNER JOIN`.
- **`Proyecto-1-WebServices/`:** Proyecto Spring Boot (`peluditos-webservice`) en Java 21 con el endpoint de prueba `GET /api/consultas/prueba` operativo en el puerto `8080`, su `README.md` y captura de evidencia en `/Evidencia`.
- **`Proyecto-2-ExternalWorker/` y `Proyecto-3-WebApp/`:** Directorios reservados en la estructura para las fases posteriores de conexión y aplicación web.
- **`Videos/`:** Directorio destinado a las presentaciones audiovisuales de cierre de etapa.

---

## 4. Contingencia Técnica (Disponibilidad de Flowable)
Durante la preparación de esta entrega, la plataforma **Flowable Cloud** presentó indisponibilidad temporal por mantenimiento del proveedor (evidencia resguardada en `BPMN/`). Para no detener el avance asincrónico del grupo, se acordó resguardar el modelado en estándar BPMN 2.0 mediante `bpmn.io` y validar localmente el servicio REST y la base de datos de forma independiente.

---

## 5. Guía Rápida de Revisión
1. **Arquitectura, Actas y BPMN:** Visualizar directamente desde GitHub los archivos Markdown, imágenes `.png` y diagramas `.svg` en sus respectivas carpetas (o abrir los archivos `.bpmn` en `bpmn.io`).
2. **Servicio Web Java REST (requiere JDK 21):**
   - Ubicarse en `Proyecto-1-WebServices/peluditos-webservice/` y ejecutar en terminal: `.\mvnw.cmd spring-boot:run`
   - Consultar en el navegador: `http://localhost:8080/api/consultas/prueba` (respuesta esperada: `{"mensaje":"Web Service Peluditos funcionando","estado":"OK"}`).
3. **Base de Datos MariaDB:**
   - Importar o ejecutar `Base-de-Datos/esquema_peluditos.sql` en un entorno local (ej. XAMPP / phpMyAdmin) para generar las tablas y comprobar el `INNER JOIN`.

---

## 6. Estimación General de Próximos Pasos
Tras el cierre de esta primera entrega, el equipo estima abordar de manera progresiva las siguientes tareas según la disponibilidad de las plataformas y los tiempos del bimestre:
- Grabación y subida de los videos de presentación una vez consolidada la revisión de esta entrega.
- Importación de los diagramas `.bpmn` y configuración de formularios cuando el servicio de Flowable se encuentre estable.
- Alineación de campos entre los módulos e inicio de las pruebas de conexión (External Worker, API REST y MariaDB) para las entregas siguientes.
