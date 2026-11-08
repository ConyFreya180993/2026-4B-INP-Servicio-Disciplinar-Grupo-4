# Proyecto 2 — External Worker

## Peluditos Pet Chile

**Proyecto:** Integración de Plataformas — Grupo 4  
**Responsable:** Gabriel Arellano (avockato)  
**Estado:** Implementado y probado en entorno local.

---

## 1. Descripción

Este componente implementa un **External Worker desarrollado en PHP** para integrar el motor de procesos Flowable con la base de datos MariaDB del proyecto Peluditos Pet Chile.

Su función es atender las tareas externas generadas durante la ejecución del proceso BPMN de gestión de clientes potenciales.

El Worker consulta periódicamente Flowable, obtiene las tareas disponibles para el tópico `sincronizar-cliente`, identifica al cliente correspondiente en MariaDB y confirma la finalización de la tarea.

De esta manera, permite conectar la ejecución de procesos de negocio con la información comercial almacenada en la base de datos.

## 2. Tecnologías utilizadas

- **Lenguaje:** PHP.
- **Motor BPMN:** Flowable 8.0.0.
- **Base de datos:** MariaDB.
- **Entorno local:** XAMPP.
- **Servidor de Flowable:** Apache Tomcat.
- **Comunicación:** API REST mediante HTTP y JSON.
- **Editor:** Visual Studio Code.
- **Control de versiones:** Git y GitHub.

## 3. Estructura del componente

```text
Proyecto-2-ExternalWorker/
├── worker.php
└── README.md
```

### Archivo `worker.php`

Contiene la implementación del External Worker.

Sus responsabilidades principales son:

1. Establecer la conexión con MariaDB.
2. Configurar la comunicación con Flowable.
3. Solicitar trabajos externos pendientes.
4. Obtener las variables asociadas a cada trabajo.
5. Identificar al cliente mediante `id_lead`.
6. Consultar la información comercial del cliente.
7. Confirmar la finalización del trabajo en Flowable.
8. Continuar escuchando nuevas tareas.

## 4. Configuración

### Conexión con Flowable

El Worker utiliza la dirección:

```text
http://127.0.0.1:8080/flowable-rest
```

La comunicación se realiza mediante solicitudes HTTP autenticadas con las credenciales del entorno local de Flowable.

El tópico configurado es:

```text
sincronizar-cliente
```

El identificador del Worker es:

```text
peluditos-worker-1
```

### Conexión con MariaDB

El Worker consulta la base de datos:

```text
peluditos_db
```

Esta base almacena la información comercial del emprendimiento.

La conexión se realiza utilizando las extensiones de PHP disponibles en XAMPP.

Las credenciales de conexión deben configurarse según el entorno donde se ejecute el proyecto.

## 5. Integración con el proceso BPMN

El External Worker está vinculado al proceso:

```text
BPMN/TO-BE.bpmn
```

Dentro del proceso existe una tarea de servicio denominada:

**Sincronizar datos de cliente en base de datos**

Esta actividad utiliza la configuración de trabajo externo de Flowable:

```xml
flowable:type="external-worker"
flowable:topic="sincronizar-cliente"
```

Cuando el proceso alcanza esta actividad, Flowable genera un trabajo externo.

El Worker identifica dicho trabajo por su tópico y realiza el procesamiento correspondiente.

### Variable `id_lead`

La variable `id_lead` permite relacionar una instancia del proceso BPMN con un cliente registrado en MariaDB.

Esta variable se genera durante el registro del cliente desde la aplicación web.

Posteriormente, el Worker utiliza su valor para consultar la información comercial correspondiente.

## 6. Funcionamiento del External Worker

El funcionamiento general es el siguiente:

1. El usuario registra un cliente desde la aplicación web.
2. PHP almacena los datos del cliente en MariaDB.
3. La aplicación inicia una instancia del proceso BPMN en Flowable.
4. Flowable ejecuta las actividades correspondientes.
5. Cuando se cumple la condición de venta concretada, el proceso alcanza la tarea externa.
6. Flowable genera un trabajo con el tópico `sincronizar-cliente`.
7. El External Worker adquiere el trabajo disponible.
8. El Worker obtiene la variable `id_lead`.
9. Consulta la información del cliente en MariaDB.
10. Si la consulta se realiza correctamente, confirma la finalización del trabajo en Flowable.
11. Flowable continúa el proceso hasta su evento de finalización.

**Importante:** En esta implementación, la aplicación web registra inicialmente los datos del cliente en MariaDB. El Worker verifica la información existente y completa la tarea externa sin duplicar el registro.

## 7. Ejecución local

### Requisitos

Para ejecutar el componente se necesita:

- XAMPP instalado.
- MariaDB iniciado.
- Base de datos `peluditos_db` disponible.
- PHP configurado.
- Apache Tomcat iniciado.
- Flowable REST disponible.
- Proceso BPMN desplegado en Flowable.

### Procedimiento

1. Iniciar MariaDB desde XAMPP.
2. Iniciar Apache Tomcat con Flowable.
3. Abrir Visual Studio Code.
4. Abrir la carpeta del proyecto.
5. Abrir una terminal integrada.
6. Ejecutar el archivo `worker.php` utilizando PHP.
7. Mantener la terminal abierta mientras se procesan las tareas.

### Resultado esperado

Al iniciar el Worker se muestra información similar a:

```text
EXTERNAL WORKER - PELUDITOS PET CHILE

Conectado a MariaDB: peluditos_db
Tema: sincronizar-cliente
Worker: peluditos-worker-1

Presiona Ctrl+C para detenerlo.
```

Esto indica que el componente se ha iniciado y está preparado para recibir trabajos externos.

## 8. Pruebas realizadas

Se realizaron pruebas locales para comprobar la comunicación entre PHP, MariaDB y Flowable.

### Prueba 1. Inicio del External Worker

Se ejecutó el archivo `worker.php` desde la terminal integrada de Visual Studio Code.

**Resultado:** El Worker inició correctamente, estableció conexión con MariaDB y quedó esperando tareas del tópico configurado.

### Prueba 2. Adquisición de trabajo externo

Se ejecutó una instancia del proceso BPMN hasta alcanzar la tarea de servicio externa.

**Resultado:** Flowable generó un trabajo externo que pudo ser adquirido mediante la API REST.

### Prueba 3. Consulta de información comercial

El Worker recibió una tarea que contenía la variable `id_lead` con valor `1`.

Se consultó la información del cliente correspondiente en MariaDB.

**Resultado:** El Worker encontró correctamente el registro de Ana Torres y recuperó sus datos comerciales.

### Prueba 4. Finalización del trabajo

Después de consultar los datos del cliente, el Worker confirmó la finalización del trabajo externo.

**Resultado:** Flowable aceptó la finalización y continuó la ejecución del proceso BPMN.

### Prueba 5. Verificación del proceso

Se consultó el historial de la instancia BPMN mediante la API REST de Flowable.

**Resultado:** La instancia finalizó correctamente con estado `completed`, alcanzando el evento **Lead convertido en cliente**.

## 9. Evidencias

Las evidencias de funcionamiento del External Worker incluyen:

| Evidencia | Descripción |
|---|---|
| External Worker iniciado | Terminal