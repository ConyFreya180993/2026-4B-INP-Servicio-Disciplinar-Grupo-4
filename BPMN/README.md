# Sistema de Gestión del Ciclo de Vida del Cliente - Peluditos Pet Chile

Este repositorio contiene la documentación de procesos, especificaciones de datos y la arquitectura de software preliminar para el proyecto académico de **Peluditos Pet Chile**, una pastelería artesanal para mascotas. El objetivo del proyecto es diseñar e implementar una solución basada en **BPMN 2.0, Flowable, Java (REST) y MariaDB** para ordenar la gestión comercial y potenciar la recompra.

---

## 1. Módulo 1: Gestión de Leads (Problema 1)

### 1.1. Proceso Actual (AS-IS)
El proceso opera actualmente de forma informal y descentralizada a través de redes sociales (Instagram, Facebook, TikTok) y WhatsApp. 

* **Flujo:** Un usuario realiza una consulta sobre pastelería, precios o personalizaciones. La emprendedora responde de forma manual en la aplicación respectiva y la interacción finaliza ahí.
* **Problema:** No existen registros históricos, auditorías, ni herramientas de seguimiento comercial. La información queda atrapada en los chats.
* **Supuestos Críticos por Validar:**
  * Mecanismo exacto utilizado hoy para confirmar que una conversación derivó en una venta real (ej. envío de comprobante de transferencia por chat).
  * Disponibilidad de la emprendedora para alimentar manualmente un formulario web mientras atiende al cliente en la red social.

### 1.2. Proceso Propuesto (TO-BE)
El nuevo diseño introduce el control de flujo y la persistencia obligatoria de datos mediante tareas gobernadas por el motor:
1. **Inicio:** Evento de inicio básico (`Se recibe consulta a traves de canales online`) al recibir una consulta digital.
2. **Registro de Consulta:** Tarea de usuario (`responder consulta registrando datos de posible cliente`) vinculada al formulario `registroConsulta`.
3. **Agendamiento Comercial:** Tarea de usuario obligatoria (`Agendar seguimiento comercial`) vinculada al formulario `seguimientoComercial`.
4. **Evaluación Comercial:** Compuerta exclusiva (`Venta completada?`) que valida la condición de negocio a través de la variable `${seConcretoLaVenta}`.
5. **Cierre de Flujo por Worker Externo:**
   * **Si la venta se concreta (`true`):** Una *External Worker Task* invoca al tópico `actualizar datos de ultima consulta` para sincronizar la información limpia en la base de datos MariaDB. El flujo finaliza en `Lead convertido en cliente`.
   * **Si la venta NO se concreta (`false`):** Una *External Worker Task* invoca al tópico `registrar resultado lead` y el flujo finaliza de forma controlada en `Lead Perdido`.

---

## 2. Módulo 2: Generación de Oportunidades Comerciales (Problema 2)

### 2.1. Proceso Propuesto (TO-BE)
Para evitar instancias "eternas" en el motor de procesos esperando fechas anuales, se optó por un **proceso independiente** especializado en marketing y fidelización reactiva.

* **Inicio por Mensaje:** El flujo se gatilla mediante un *Start Message Event* (`iniciar campaña`) que responde a la señal o mensaje `iniciarCampana`.
* **Configuración de Campaña:** La emprendedora ejecuta la tarea de usuario inicial `Crear campaña Cumpleaños / celebracion`.
* **Persistencia de Campaña:** Una *External Worker Task* (`Registrar campaña`) envía la información al tópico `registrar datos campaña iniciada` para guardar el contexto en el sistema.
* **Segmentación:** Tarea de usuario (`Generar segmento de clientes objetivo`) para delimitar manualmente el listado de clientes que cumplen con el criterio.
* **Contacto Humano:** El motor genera la tarea de usuario `Contactar clientes correspondientes al segmento generado`. La emprendedora realiza el contacto manual, asegurando que ninguna oportunidad de recompra se pierda.
* **Registro de Resultados:** Una *External Worker Task* (`Registrar respuesta del cliente`) procesa en el tópico `registrar si cliente muestra interes en la oferta` antes de dar por cerrado el flujo en el evento `Oportunidad gestionada`.

---

## 3. Matriz Comparativa de Procesos (AS-IS vs. TO-BE)

| Proceso / Problema | Situación Actual (AS-IS) | Situación Propuesta (TO-BE) | Beneficio Esperado |
| :--- | :--- | :--- | :--- |
| **Captura de Leads** | Las consultas quedan atrapadas en los historiales de chats de cada red social. | Registro centralizado mediante formularios de Flowable (`registroConsulta`). | Trazabilidad completa de las consultas y control del embudo de ventas. |
| **Seguimiento Comercial** | El olvido o la falta de tiempo causa que no se vuelva a contactar a los posibles clientes. | Flujo guiado que obliga al agendamiento y evaluación del seguimiento antes del cierre. | Incremento en la tasa de conversión de leads a clientes reales. |
| **Fechas Clave e Hitos** | No existe una forma sistemática de identificar oportunidades asociadas a fechas relevantes. | Activación por eventos de mensaje para campañas específicas (Cumpleaños, Navidad, etc.). | Estímulo constante de la recompra y fidelización activa del cliente. |
| **Métricas del Negocio** | Imposibilidad absoluta de medir cuántas consultas terminan efectivamente en ventas. | Clasificación e integración obligatoria del resultado del lead (`Venta completada?`) en MariaDB. | Obtención de estadísticas reales para la toma de decisiones. |

---

## 4. Arquitectura de Datos y Formularios (Preliminar)

### 4.1. Formulario de Registro de Leads (`tabla: leads`)
* `nombre_interesado` (String) - *Confirmado*
* `canal_origen` (String / Enum) - *Confirmado* [Instagram, WhatsApp, Facebook, TikTok, Otro]
* `tipo_mascota` (String) - *Confirmado* [Perro, Gato, Otro]
* `detalle_consulta` (Text) - *Confirmado*
* `contacto_directo` (String) - *Pendiente de validación con el cliente (Teléfono/Email)*

### 4.2. Formulario de Seguimiento Comercial (`tabla: seguimientos` / FormKey: `seguimientoComercial`)
* `id_lead` (Integer - FK) - *Confirmado*
* `fecha_accion` (Date) - *Confirmado*
* `comentarios_seguimiento` (Text) - *Confirmado*
* `seConcretoLaVenta` (Boolean) - *Variable crítica de control de flujo* [True, False]

### 4.3. Formulario de Oportunidades (`tabla: oportunidades_comerciales`)
* `id_oportunidad` (Integer - PK) - *Confirmado*
* `id_cliente` (Integer - FK) - *Confirmado*
* `tipo_evento` (String) - *Confirmado* [Cumpleaños, Navidad, Halloween, Día del Perro, Día del Gato, Aniversario, Lanzamiento]
* `estado_oportunidad` (String) - *Confirmado* [Abierta, Contactada, Interesado, Cerrada sin éxito]

---

## 5. Estado de los Componentes Técnicos

El proyecto se encuentra en una etapa académica intermedia de diseño y desarrollo de software:

* **Modelamiento de Procesos (BPMN 2.0 en Flowable):** Completado e Importado (Resguardado mediante estándar XML).
* **Formularios Dinámicos de Tareas (Flowable Forms):** Claves de formularios definidas (`registroConsulta` y `seguimientoComercial`).
* **Base de Datos (MariaDB - Tablas leads/seguimientos):** Propuesto (Diseño de esquemas preliminares).
* **Servicio Web REST (Java):** Propuesto (Codificación de controladores y endpoints básicos).
* **Workers Externos de Invocación:** Diseñados e integrados en el flujo BPMN mediante la arquitectura de suscripción a los siguientes tópicos específicos de Flowable:
  * `actualizar datos de ultima consulta`
  * `registrar resultado lead`
  * `registrar datos campaña iniciada`
  * `registrar si cliente muestra interes en la oferta`

---

## 6. Dudas y Decisiones Pendientes

1. **Estrategia de Desacoplamiento:** Confirmada la arquitectura orientada a **Workers Externos** asíncronos mediante suscripción a tópicos específicos en el motor, descartando las tareas de servicio HTTP REST directas de Flowable.
2. **Ciclo de Transición de Datos:** Establecer las reglas estrictas de negocio para definir cuándo un registro de la tabla `leads` se convierte oficialmente en un registro de la tabla `clientes` para empezar a ser considerado en el proceso de fidelización.
3. **Gatillo de Campañas Automáticas:** Determinar si el Evento de Mensaje (`iniciar campaña`) será despertado de forma programada por un componente externo en Java (ej. Spring Boot Scheduler con `@Scheduled`) que analice la base de datos MariaDB diariamente en busca de cumpleaños de mascotas.

---
## Evidencia de Contingencia (Indisponibilidad de Flowable)

Se deja registro de la caída del servicio de Flowable al momento de preparar la entrega, razón por la cual los modelos AS-IS y TO-BE fueron resguardados en estándar BPMN 2.0 mediante bpmn.io:

![Evidencia Flowable Caído](./Captura%20de%20pantalla%202026-10-24%20193442.png)

Se deja registro de la caída del servicio de Flowable Trial lo que interfirio con las pruebas al no poder acceder a Flowable de manera local como fue explicado en los videos. esta captura corresponde al dia Domingo 08 de Noviembre del año en curso:

![Evidencia Flowable Trial Caído](./Evidencia/servicio%20trial%20flowable%20en%20mantenimiento.png)

