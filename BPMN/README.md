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
El nuevo diseño introduce el control de flujo y la persistencia obligatoria de datos:
1. **Inicio:** Evento de mensaje al recibir una consulta digital.
2. **Registro de Lead:** Tarea de usuario donde se atienden las dudas y se guardan los datos en un formulario de Flowable.
3. **Evaluación de Seguimiento:** Compuerta exclusiva (`¿Requiere seguimiento?`) que decide si el lead requiere un contacto posterior o si finaliza inmediatamente por compra directa o rechazo.
4. **Seguimiento Comercial:** Tarea de usuario programada para evitar la pérdida de potenciales clientes.
5. **Sincronización Técnica:** Si la venta se concreta, una *Service Task* invoca al **Worker externo** para registrar la información de manera limpia en la base de datos MariaDB a través de servicios REST en Java.

---

## 2. Módulo 2: Generación de Oportunidades Comerciales (Problema 2)

### 2.1. Proceso Propuesto (TO-BE)
Para evitar instancias "eternas" en el motor de procesos esperando fechas anuales, se optó por un **proceso independiente** especializado en marketing y fidelización.

* **Inicio Múltiple:** El flujo se puede gatillar por un *Timer Start Event* (automatización basada en fechas como el cumpleaños de la mascota) o por un *Message/Manual Start Event* (cuando la emprendedora lanza una campaña extraordinaria como Navidad o el Aniversario del emprendimiento).
* **Segmentación:** Una *Service Task* consulta la base de datos mediante la API REST para extraer la lista de clientes que cumplen con el criterio del evento.
* **Contacto Humano:** El motor genera tareas de usuario en Flowable con los datos del cliente y el beneficio a ofrecer. La emprendedora realiza el contacto manual, asegurando que ninguna oportunidad de recompra se pierda.

---

## 3. Matriz Comparativa de Procesos (AS-IS vs. TO-BE)

| Proceso / Problema | Situación Actual (AS-IS) | Situación Propuesta (TO-BE) | Beneficio Esperado |
| :--- | :--- | :--- | :--- |
| **Captura de Leads** | Las consultas quedan atrapadas en los historiales de chats de cada red social. | Registro centralizado mediante formularios de Flowable en el inicio del proceso. | Trazabilidad completa de las consultas y control del embudo de ventas. |
| **Seguimiento Comercial** | El olvido o la falta de tiempo causa que no se vuelva a contactar a los posibles clientes. | Tareas explícitas de seguimiento gobernadas y alertadas por el motor de procesos. | Incremento en la tasa de conversión de leads a clientes reales. |
| **Fechas Clave e Hitos** | No existe una forma sistemática de identificar oportunidades asociadas a fechas relevantes. | Activacion automática y manual de campañas según datos de la mascota y calendario. | Estímulo constante de la recompra y fidelización activa del cliente. |
| **Métricas del Negocio** | Imposibilidad absoluta de medir cuántas consultas terminan efectivamente en ventas. | Clasificación obligatoria del resultado del lead y de la oportunidad al cierre del flujo. | Obtención de estadísticas reales para la toma de decisiones. |

---

## 4. Arquitectura de Datos y Formularios (Preliminar)

### 4.1. Formulario de Registro de Leads (`tabla: leads`)
* `nombre_interesado` (String) - *Confirmado*
* `canal_origen` (String / Enum) - *Confirmado* [Instagram, WhatsApp, Facebook, TikTok, Otro]
* `tipo_mascota` (String) - *Confirmado* [Perro, Gato, Otro]
* `detalle_consulta` (Text) - *Confirmado*
* `contacto_directo` (String) - *Pendiente de validación con el cliente (Teléfono/Email)*

### 4.2. Formulario de Seguimiento Comercial (`tabla: seguimientos`)
* `id_lead` (Integer - FK) - *Confirmado*
* `fecha_accion` (Date) - *Confirmado*
* `comentarios_seguimiento` (Text) - *Confirmado*
* `estado_resultado` (String) - *Confirmado* [Interesado, No responde, Rechazado, Venta Concretada]

### 4.3. Formulario de Oportunidades (`tabla: oportunidades_comerciales`)
* `id_oportunidad` (Integer - PK) - *Confirmado*
* `id_cliente` (Integer - FK) - *Confirmado*
* `tipo_evento` (String) - *Confirmado* [Cumpleaños, Navidad, Halloween, Día del Perro, Día del Gato, Aniversario, Lanzamiento]
* `estado_oportunidad` (String) - *Confirmado* [Abierta, Contactada, Interesado, Cerrada sin éxito]

---

## 5. Estado de los Componentes Técnicos

El proyecto se encuentra en una etapa académica intermedia de diseño y desarrollo de software:

* **Modelamiento de Procesos (BPMN 2.0 en Flowable):** Propuesto / En Diseño.
* **Formularios Dinámicos de Tareas (Flowable Forms):** Propuesto.
* **Base de Datos (MariaDB - Tablas leads/seguimientos):** Propuesto (Diseño de esquemas preliminares).
* **Servicio Web REST (Java):** Propuesto (Codificación de controladores y endpoints básicos).
* **Worker Externo de Invocación:** Pendiente de Integración (Sujeto a la validación de la arquitectura final de comunicación del equipo).

---

## 6. Dudas y Decisiones Pendientes

1. **Estrategia de Desacoplamiento:** Definir si Flowable invocará al servicio Java mediante una tarea de servicio HTTP nativa de Flowable (REST directo) o si se mantendrá la arquitectura del Worker Externo mediante colas/suscripción.
2. **Ciclo de Transición de Datos:** Establecer las reglas estrictas de negocio para definir cuándo un registro de la tabla `leads` se convierte oficialmente en un registro de la tabla `clientes` para empezar a ser considerado en el proceso de fidelización.
3. **Lógica de los Temporizadores:** Determinar si el motor Flowable controlará el calendario de alertas de forma interna (Timer Start Events basados en expresiones cron) o si un componente externo en Java despertará el proceso diariamente analizando la base de datos MariaDB.

---
## Evidencia de Contingencia (Indisponibilidad de Flowable)

Se deja registro de la caída del servicio de Flowable al momento de preparar la entrega, razón por la cual los modelos AS-IS y TO-BE fueron resguardados en estándar BPMN 2.0 mediante bpmn.io:

![Evidencia Flowable Caído](./Captura%20de%20pantalla%202026-10-24%20193442.png)
