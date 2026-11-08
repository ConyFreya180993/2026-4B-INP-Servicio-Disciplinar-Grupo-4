# Acta 4 — Entrega 2 (Planificación y Asignación Técnica)

## 1. Datos Generales
- **Proyecto:** Peluditos Pet Chile (Hito 2)
- **Periodo de coordinación:** 26 de octubre al 03 de noviembre de 2026
- **Hito de cierre de acuerdos:** Martes 03 de noviembre de 2026 (11:15 – 19:00 hrs)
- **Modalidad:** Coordinación asíncrona vía canal oficial del equipo (adaptada por turnos laborales nocturnos y disponibilidad horaria)
- **Participantes:**
  - Constanza Núñez Sánchez (Coordinación técnica y Arquitectura)
  - José Antonio Loyola Ibarra (Procesos BPMN y Motor Flowable)
  - Gabriel Eduardo Antonio Arellano Vera (Base de Datos y Frontend)
  - Camila Vega (Servicios Web Jakarta REST y External Worker)

## 2. Temas Tratados
1. **Revisión de instrucciones de la Entrega 2 y factibilidad técnica (26/10):** Se compartieron los requerimientos del Hito 2 (plazo: lunes 09/11), enfocados en la integración sobre Flowable OSS 7.2 local, External Workers e interfaz propia, solicitando a cada integrante evaluar disponibilidad horaria y herramientas instaladas.
2. **Estandarización de datos y reglas de negocio (01/11):** Ante la necesidad de avanzar en paralelo con los procesos de *Gestión de Leads* y *Fidelización*, se propuso un contrato mínimo común de datos:
   - Definición del paso de *Lead* a *Cliente* tras concretar una venta y registro explícito cuando la oportunidad se pierde.
   - Homologación de campos entre BPMN (`nombre_interesado`, `detalle_consulta`, `contacto_directo`) y tablas relacionales (`leads`, `seguimientos`, `fidelizacion`).
   - Convención de nomenclatura: `camelCase` para variables en Flowable/Java/JSON y `snake_case` para columnas en MariaDB.
3. **Análisis de retroalimentación de Entrega 1 y reparto de tareas (02/11 – 03/11):** Tras recibir la evaluación docente, los integrantes definieron su ventana de disponibilidad (17:00 a 19:00 hrs y turno nocturno) y se entregaron pautas individuales detalladas para subsanar las observaciones técnicas y registrar evidencias para el monorepo y los dos videos obligatorios (docente y emprendedora).

## 3. Acuerdos y Responsabilidades Asignadas

| Responsable | Módulo | Tarea Comprometida | Plazo |
| :--- | :--- | :--- | :--- |
| Constanza Núñez | Arquitectura y Docs | Rediseñar diagrama  ahora en formato UML y documentar trazabilidad | 07/11/2026 |
| José Loyola | BPMN / Flowable | Corregir modelos TO-BE y ejecutar en Flowable OSS 7.2 | 07/11/2026 |
| Gabriel Arellano | MariaDB / WebApp | Separar esquemas SQL y armar Bandeja de Tareas | 07/11/2026 |
| Camila Vega | Jakarta REST / Worker | Migrar API a Jakarta EE, crear Worker y probar con curl | 07/11/2026 |
| Equipo completo | Evidencias y Videos | Subir capturas y comentarios de desarrollo al monorepo y grabar bloques de video | 08/11/2026 |
