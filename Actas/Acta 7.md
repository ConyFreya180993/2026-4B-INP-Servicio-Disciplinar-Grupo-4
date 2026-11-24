# Acta de Coordinación N° 7 — Cierre de Integración, Pruebas y Contingencias (Entrega 3)

> **Estado del documento:** Acta de cierre para la Entrega 3 (23/11/2026). Consolida la cronología semanal de ajustes tras la retroalimentación de la Entrega 2, los avances integrados en `main`, la publicación de cápsulas técnicas, las contingencias registradas y el plan de cierre hacia la Entrega 4 (Examen Final).

## 1. Datos Generales
- **Proyecto:** Peluditos Pet Chile — Integración de Plataformas (Grupo 4)
- **Fecha de cierre:** 23 de noviembre de 2026
- **Modalidad:** Coordinación asíncrona, revisión y fusión de Pull Requests en GitHub y consolidación de evidencias
- **Integrantes y rol en la Entrega 3:**
  - **Constanza Núñez Sánchez:** Coordinación general, plan de ajustes post-E2, fusión del `PR #2` en `main`, actualización del diagrama UML de arquitectura y despliegue (`Arquitectura-entrega-3-final`), redacción del `README.md` principal (configuración externa, despliegue en limpio, pruebas y plan E4), redacción de actas y grabación del Bloque 1 del video técnico (incluyendo respaldo de evidencias de Gabriel).
  - **Gabriel Eduardo Antonio Arellano Vera:** Desacoplamiento e integración de `Proyecto-2-ExternalWorker` con el servicio JAX-RS, manejo de errores/reintentos y variables de entorno, incorporación de identidad visual en `Proyecto-3-WebApp` y actualización documental de `Base-de-Datos`.
  - **Camila Vega:** Migración de consultas reales a Jakarta REST (JAX-RS) en `Proyecto-1-WebServices` (`PR #2`), control de errores HTTP `400`/`404`, externalización en `application.properties`, capturas de evidencia y grabación del Bloque 3 del video técnico.
  - **José Antonio Loyola Ibarra:** Modelo base utilizado en las pruebas de integración (`BPMN/modelo to-be+fidelizacion.bpmn`), publicación de la versión paralela ajustada de Fidelización 1:1 (`BPMN/modelo to-be + fidelizacion arreglos.bpmn`) y grabación del Bloque 2 del video técnico.

---

## 2. Cronología de Coordinación de la Semana (16/11 al 23/11)

- **16/11/2026 — Planificación post-retroalimentación E2:** Constanza Núñez publica el detalle de ajustes priorizados en base a la retroalimentación de la Entrega 2 (migración a JAX-RS, desacoplamiento del Worker, configuración externa, separación de puertos y ajuste de Fidelización 1:1).
- **17/11 al 21/11/2026 — Desarrollo y alineamiento técnico:** Coordinación asíncrona por canal grupal; Camila Vega valida nombres de columnas, estructura de `peluditos_db` y configuración de puertos para integrar el servicio Jakarta REST.
- **22/11/2026 — Pull Request e integración de módulos:** Camila Vega abre el `PR #2` con los endpoints JAX-RS y capturas de prueba; Gabriel Arellano sube a `main` la actualización de `Base-de-Datos`, la identidad visual de `Proyecto-3-WebApp` y `Proyecto-2-ExternalWorker` ya desacoplado de MariaDB.
- **23/11/2026 — Fusión, contingencias y cierre:**
  - Constanza Núñez revisa y fusiona `PR #2` en `main`, actualiza `Arquitectura/` y el `README.md` raíz, y graba el Bloque 1 del video técnico cubriendo también el recorrido de respaldo de los módulos de Gabriel (ausente en grabación por turno laboral).
  - Camila Vega publica el enlace de su Bloque 3 en `Videos/Entrega-3/Tecnico/README.md`.
  - Tras resolver inconvenientes y fallas de equipo ,  José Loyola sube su Bloque 2 del video técnico y publica la versión paralela `BPMN/modelo to-be + fidelizacion arreglos.bpmn` (`Process_1pot1gs00`) sin sobrescribir el modelo integrado en pruebas.

---

## 3. Avances Integrados y Verificados en `main`

1. **Servicios Web Jakarta REST (`Proyecto-1-WebServices` — PR #2 fusionado):**
   - Implementación real con Jersey (JAX-RS) y `JdbcTemplate` en `ClientesResource.java`, exponiendo `GET /jakarta/clientes` y `GET /jakarta/clientes/{id_lead}` con validaciones HTTP `400` y `404`.
   - Externalización de parámetros en `application.properties` mediante variables de entorno (`PELUDITOS_*`), operando en puerto `8081` para evitar colisión con Flowable (`8080`).

2. **External Worker Desacoplado y Manejo de Errores (`Proyecto-2-ExternalWorker`):**
   - Eliminación completa de la conexión directa a MariaDB en `worker.php`: el Worker consume el servicio web JAX-RS (`puerto 8081`), atiende el tópico `actualizar datos de ultima consulta` del modelo `Process_1pot1gs` y lee parámetros desde variables de entorno `PELUDITOS_*`.
   - Registro en `worker.log` y notificación de fallos a Flowable vía `/fail` (3 reintentos, intervalo `PT30S`), verificando caso exitoso, cliente inexistente (`id_lead = 999`) y caída/recuperación automática del servicio web.

3. **Identidad Visual y Consistencia de Base de Datos (`Proyecto-3-WebApp` y `Base-de-Datos`):**
   - Incorporación del logotipo oficial de Peluditos Pet Chile (`img/logo-peluditos-chile.webp`) y paleta institucional naranjo en variables CSS en la Bandeja Web.
   - Actualización de `Base-de-Datos/README.md` reflejando las 4 tablas reales (`leads`, `pet`, `seguimientos`, `fidelizacion`) con 3 claves foráneas y 6 capturas en `Base-de-Datos/Evidencia/`.

4. **Modelos BPMN (`BPMN/`):**
   - Se conserva intacto `modelo to-be+fidelizacion.bpmn` (`Process_1pot1gs`) como modelo de referencia desplegado y probado con el External Worker.
   - Se incorpora como archivo paralelo `modelo to-be + fidelizacion arreglos.bpmn` (`Process_1pot1gs00`), que redefine el flujo de Fidelización a **1 instancia por cliente individual** (*Detectar cliente con fecha importante → preparar oferta personalizada → Obtener historial de cliente → validar beneficio aplicable → enviar oferta a cliente → Registrar respuesta del cliente → Oportunidad gestionada*), quedando sujeto a limpieza técnica y validación de ejecución en la Entrega 4.

5. **Arquitectura, Despliegue y Repositorio (`Arquitectura/` y `README.md` raíz):**
   - Diagrama UML actualizado (`Arquitectura-entrega-3-final` en `.drawio`, `.png` y `.svg`) con límites de entorno configurable, separación de puertos (`8080`/`8081`) y flujo `Worker → JAX-RS → MariaDB`.
   - Manual de despliegue en entorno limpio, tabla de variables de entorno, matriz de pruebas/errores y plan hacia la Entrega 4 en el `README.md` principal.

---

## 4. Contingencias Registradas al Cierre de la Entrega 3

- **Bloque técnico de Gabriel Arellano :** El integrante dejó publicados en `main` todos sus commits, código integrado y evidencias, pero no pudo grabar su cápsula individual por cruce con turno laboral. Como respaldo de coordinación, Constanza Núñez recorrió y presentó el resumen de sus avances y evidencias dentro del Bloque 1 del video técnico.
- **Validación pendiente del BPMN paralelo (`Process_1pot1gs00`):** Debido a la falla de equipo de José Loyola durante la jornada, el modelo ajustado se subió en archivo separado sin pruebas locales integradas. En la revisión de cierre se identificó que los tópicos externos de la nueva versión incluyen expresiones junto al nombre (ej. `registrar-resultado-lead, resultadoConsulta='venta completada'`), por lo que deberán depurarse a identificadores simples y sincronizarse con `worker.php` en la Entrega 4.
- **Video para la Emprendedora:** Debido a las contingencias simultáneas de turno laboral y fallas de hardware ocurridas en la jornada de cierre, la cápsula comercial dirigida a la emprendedora no alcanzó a consolidarse antes del cierre de esta acta y se priorizará al inicio de la semana final.

---

## 5. Plan de Cierre hacia la Entrega 4 (Examen Final)

| Prioridad | Compromiso Pendiente | Módulo(s) | Responsable |
| :--- | :--- | :--- | :--- |
| **Alta** | Depurar los nombres de `flowable:topic` en `modelo to-be + fidelizacion arreglos.bpmn` (quitar expresiones/comas, separar variables de proceso), evaluar el orden *Obtener historial → preparar oferta* y validar despliegue en Flowable. | `BPMN` | José Loyola |
| **Alta** | Externalizar credenciales y rutas de `conexion.php` y `flowable_config.php` mediante variables de entorno / `.env.example`. | `Proyecto-3-WebApp` | Gabriel Arellano |
| **Alta** | Actualizar `buscarCliente()` en `worker.php` para consumir `GET /jakarta/clientes/{id_lead}` y alinear los tópicos depurados del nuevo BPMN. | `Proyecto-2-ExternalWorker` | Gabriel Arellano |
| **Media** | Implementar endpoints de escritura (`POST`/`PATCH`) en JAX-RS para habilitar los tópicos de resultado de lead y fidelización. | `Proyecto-1-WebServices` | Camila Vega |
| **Media** | Actualizar diagrama definitivo, guía de despliegue final, grabar video para la emprendedora y consolidar defensa de Examen. | `Arquitectura` / `Actas` / `Videos` | Equipo Grupo 4 (Coord. Constanza Núñez) |

---

## 6. Cierre Administrativo
- Enlaces de videos técnicos publicados en `Videos/Entrega-3/Tecnico/README.md`: Bloque 1 (Constanza Núñez + respaldo módulos de Gabriel), Bloque 2 (José Loyola) y Bloque 3 (Camila Vega).
- Envío individual obligatorio del enlace del repositorio en el buzón del aula virtual antes de las 23:59 hrs del 23/11/2026.
