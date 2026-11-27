# Acta de Coordinación N° 8 — Planificación, Disponibilidad y Alcance (Entrega 4 / Examen Final)

> **Estado del documento:** Acta de planificación inicial para la Entrega 4 (26/11/2026). Registra la distribución de tareas sobre la base consolidada de la Entrega 3, la disponibilidad horaria informada por los integrantes, los riesgos tempranos identificados y los acuerdos pendientes para los tres videos obligatorios del Examen Final.

## 1. Datos Generales
- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4)
- **Fecha:** 26 de noviembre de 2026
- **Plazo oficial de entrega:** Lunes 30 de noviembre de 2026 (23:59 hrs)
- **Plazo interno de cierre del equipo:** **Domingo 29 de noviembre de 2026** (dejando el lunes 30 únicamente como margen de seguridad ante fallas de plataforma o contingencias personales)
- **Modalidad:** Coordinación asíncrona por canal grupal (WhatsApp) y seguimiento en GitHub

---
## 2. Disponibilidad Real Reportada (27/11 al 30/11)

| Integrante | Disponibilidad Informada | Restricciones Relevantes | Estado de Confirmación |
| :--- | :--- | :--- | :--- |
| **Constanza Núñez Sánchez** | Viernes 27 a domingo 29 (consolidación y documentación). | Lunes 30 con ventana acotada en horario nocturno; corte operativo de seguridad fijado a las 23:30 hrs. | Confirmada |
| **Gabriel Eduardo Antonio Arellano Vera** | Franja nocturna de **21:00 a 00:00 hrs** durante estos días. | Horario laboral diurno; requiere acuerdos y contrato de tópicos cerrados antes de las 21:00 hrs. | Confirmada (26/11, 19:37) |
| **Camila Vega** | Fin de semana (sábado 28 y domingo 29 hasta las **19:00 hrs**). | Lunes 30 trabaja. Indica dependencia del cierre del BPMN de José para definir eventuales ajustes en REST. | Confirmada (26/11, 19:43) |
| **José Antonio Loyola Ibarra** | Tardes libres hasta las **21:00 hrs**. Revisará y comparará el modelo BPMN el viernes 27. | Pendiente confirmar si persisten restricciones de hardware de la jornada anterior. | Confirmada (26/11, 20:14) |
---

## 3. Repartición de Responsabilidades y Plazos Internos

El enfoque acordado es priorizar una **solución mínima y funcional**, completamente integrada y verificable, resguardando lo que ya operó correctamente en la Entrega 3:

1. **José Loyola — BPMN y Flowable (Prioridad crítica | Plazo interno: viernes 27):**
   - Revisar los modelos de leads y fidelización y confirmar qué archivo quedará como versión definitiva.
   - Corregir identificadores, variables y tópicos (`flowable:topic`) para que coincidan con el External Worker bajo un contrato limpio y coherente.
   - Desplegar los procesos en Flowable OSS, entregar archivos `.bpmn`, imágenes `.png` y evidencia de ejecución.
   - Grabar su segmento del video técnico (máx. 5 min).

2. **Camila Vega — Servicio Java / Jakarta REST (Prioridad crítica | Plazo interno: viernes 27 / sábado 28):**
   - Revisar las operaciones REST requeridas para el alcance definitivo y completar únicamente las estrictamente necesarias.
   - Verificar comunicación con el External Worker y probar respuestas válidas, identificadores inexistentes y manejo de errores.
   - Documentar endpoints, parámetros y archivo de configuración de ejemplo sin secretos reales.
   - Grabar su segmento del video técnico (máx. 5 min).

3. **Gabriel Arellano — Worker, WebApp y MariaDB (Prioridad crítica | Plazo interno: viernes 27 integración / sábado 28 pruebas):**
   - Adaptar `worker.php` a los tópicos del BPMN definitivo y verificar el consumo del servicio REST y cierre de tareas externas.
   - Revisar la interfaz propia (`Proyecto-3-WebApp`) y los recorridos de registro y seguimiento.
   - Comprobar la persistencia real de los datos comerciales, **incluyendo verificación tras reiniciar los componentes**.
   - Externalizar la configuración PHP y conexiones (sin `localhost` fijo ni credenciales en código) y aportar ejemplos de configuración sin secretos reales.
   - Grabar su participación individual en el video técnico (máx. 5 min).

4. **Constanza Núñez — Arquitectura, Documentación e Integración (Coordinación | Plazo interno: domingo 29):**
   - Actualizar el diagrama de arquitectura final y verificar coherencia entre componentes y documentación.
   - Consolidar `README.md` principal, instrucciones de instalación y `Guia-de-Uso-Emprendedora.md`.
   - Preparar la estructura de `Videos/Entrega-4/` y mantener las actas y trazabilidad del monorepo.
   - Coordinar pruebas integradas, consolidar el repositorio y generar el tag `entrega-final` al cierre.

---

## 4. Videos Obligatorios de la Entrega 4 (25 pts) — Estado de Asignación

| Video | Puntaje | Responsable(s) | Estado |
| :--- | :---: | :--- | :--- |
| **Video técnico para el docente** (4 integrantes, máx. 5 min c/u, iniciando con arquitectura) | 10 pts | Constanza, José, Camila y Gabriel | Estructura creada en `Videos/Entrega-4/README.md` |
| **Videotutorial técnico de instalación y puesta en marcha** | 10 pts | *Pendiente de asignación* | Pendiente de confirmación en el chat |
| **Videotutorial de uso para la emprendedora** | 5 pts | *Pendiente de asignación* | Pendiente de confirmación en el chat |

---
## 5. Riesgos y Bloqueos Detectados Tempranamente

- **Cadena de dependencia BPMN → Worker / REST:** Camila depende del cierre del modelo de José y tiene tope el domingo a las 19:00 hrs; Gabriel opera de 21:00 a 00:00 hrs; José trabaja en las tardes hasta las 21:00 hrs.
- **Ventana de relevo acordada:**
  1. José debe cerrar la comparación y definir el contrato definitivo de tópicos/variables el **viernes 27 antes de las 21:00 hrs**, dejando paso directo al bloque nocturno de Gabriel (21:00–00:00) y destrabando a Camila desde el sábado temprano.
  2. En paralelo, Camila puede avanzar con el archivo de configuración de ejemplo y la documentación de los endpoints ya funcionales, sin quedar bloqueada si no se incorporan nuevas operaciones de escritura.
  3. Congelamiento técnico el **sábado 28 en la noche**: si un cambio nuevo arriesga la estabilidad del flujo ya integrado, se resguarda la versión funcional probada y se documentan las limitaciones conocidas con transparencia (Sección 3.1 de la pauta).
- **Asignación pendiente de los 2 videotutoriales adicionales:** Ningún integrante ha confirmado aún quién asumirá el videotutorial de instalación (10 pts) ni el de uso para la emprendedora (5 pts); requiere definición a más tardar el viernes 27.

---
## 6. Próximos Hitos
- **Viernes 27/11 (hasta 21:00 hrs):** José compara el BPMN con la implementación actual y cierra el contrato definitivo de tópicos/variables; el equipo confirma responsables para los 2 videotutoriales adicionales.
- **Viernes 27/11 (21:00 a 00:00 hrs):** Gabriel inicia la adaptación del Worker y la externalización de la configuración PHP.
- **Sábado 28/11:** Camila ajusta/valida JAX-RS y archivo `.example`; Gabriel completa pruebas integradas y verificación de persistencia tras reinicio; grabación de videotutoriales de instalación y uso.
- **Domingo 29/11:** Grabación y carga de los 4 bloques del video técnico (bloque de Camila antes de las 19:00 hrs), consolidación en `Videos/Entrega-4/README.md`, Acta final de cierre y generación del tag `entrega-final`.

