# Proyecto 2 — External Worker

## Peluditos Pet Chile

**Proyecto:** Integración de Plataformas — Grupo 4  
**Responsable:** Gabriel Arellano (avockato)  
**Estado:** Implementado y probado en entorno local con el servicio web Jakarta REST.

---

## 1. Descripción

Este componente es un **External Worker desarrollado en PHP** que conecta el motor de procesos Flowable con el servicio web Jakarta REST del proyecto Peluditos Pet Chile.

El Worker consulta periódicamente a Flowable, obtiene las tareas externas del tópico configurado, consulta los datos del cliente en el **servicio web** (ya no directamente en MariaDB) y confirma la finalización de la tarea.

Si ocurre un error, el Worker lo registra, avisa a Flowable y deja que la tarea se reintente.

## 2. Tecnologías utilizadas

- **Lenguaje:** PHP (XAMPP).
- **Motor BPMN:** Flowable 8.0.0 (Flowable REST sobre Apache Tomcat 11, con Java 17).
- **Servicio web:** Java 21, Spring Boot y Jakarta REST (Jersey), proyecto `Proyecto-1-WebServices`.
- **Base de datos:** MariaDB (`peluditos_db`), accedida solo por el servicio web.
- **Comunicación:** API REST mediante HTTP y JSON.
- **Editor:** Visual Studio Code.
- **Control de versiones:** Git y GitHub.

## 3. Estructura del componente

```text
Proyecto-2-ExternalWorker/
├── worker.php
├── config.example.php
├── README.md
└── evidencia/
```

Al ejecutarse, el Worker crea el archivo `worker.log` en esta misma carpeta.

## 4. Cambios respecto a la versión anterior

| Antes | Ahora |
|---|---|
| El Worker se conectaba directamente a MariaDB con `mysqli` | El Worker consume el servicio web Jakarta REST |
| Un solo tópico: `sincronizar-cliente` | Tópico del modelo BPMN vigente: `registrar-resultado-lead` |
| Los errores solo se mostraban en pantalla | Los errores se guardan en `worker.log` y se reportan a Flowable con reintentos |
| Datos de conexión fijos en el código | Valores configurables mediante `config.local.php` o variables de entorno, sin credenciales en el código |

## 5. Funcionamiento

### Flujo del proceso BPMN

El Worker está vinculado al modelo:

```text
BPMN/modelo to-be + fidelizacion arreglos1.bpmn
```

con la clave de proceso `Process_1pot1gs00`. El recorrido relevante es:

1. Se recibe una consulta y se crea la instancia del proceso con la variable `id_lead`.
2. Tarea de usuario: **Responder Consulta Registrando Datos de Posible Cliente**.
3. Tarea de usuario: **Agendar Seguimiento Comercial**.
4. Compuerta de venta según la variable `seConcretoLaVenta`.
5. Si es verdadera, se ejecuta la tarea externa **Sincronizar Datos de Cliente en Base de Datos** y el proceso termina en **Lead Convertido**.
6. Si es falsa, se ejecuta la tarea externa **Registrar Venta no Realizada** y el proceso termina en **Lead Perdido**.

### Tópico atendido

La tarea externa se identifica en el BPMN con:

```xml
flowable:type="external-worker"
flowable:topic="registrar-resultado-lead"
```

Este mismo tópico lo usan dos tareas del modelo: **Sincronizar Datos de Cliente en Base de Datos** (venta concretada) y **Registrar Venta no Realizada** (venta no concretada).

El tópico del Worker debe coincidir **exactamente** con el del modelo. Si no coinciden, Flowable genera la tarea pero el Worker nunca la recibe.

### Pasos del Worker

1. Solicita a Flowable los trabajos del tópico configurado.
2. Obtiene las variables `id_lead` y `seConcretoLaVenta` del trabajo.
3. Consulta al servicio web `GET /jakarta/clientes` y busca el cliente por `id_lead`.
4. Si lo encuentra, completa la tarea en Flowable.
5. Si ocurre un error, lo registra y lo reporta a Flowable para que reintente.
6. Espera 5 segundos y vuelve a consultar.

### Consumo del servicio web

El servicio web ofrece hoy el endpoint:

```text
GET http://127.0.0.1:8081/jakarta/clientes
```

que devuelve todos los clientes de `peluditos_db` en JSON. El Worker recorre esa lista y selecciona el cliente cuyo `id_lead` coincide.

> **Nota:** Esta búsqueda es una **solución temporal**. Lo ideal es un endpoint `GET /clientes/{id_lead}` que devuelva solo el cliente pedido. Cuando exista, solo hay que cambiar la función `buscarCliente()` del Worker.

### Manejo de errores y reintentos

Cuando falla el procesamiento de una tarea (servicio caído, cliente inexistente, variable faltante, etc.):

1. El error se muestra en pantalla y se guarda en `worker.log` con fecha y hora.
2. El Worker avisa a Flowable con la operación `fail` del API de trabajos externos, indicando el mensaje de error, los reintentos restantes y un tiempo de espera de 30 segundos.
3. Pasado ese tiempo, Flowable vuelve a entregar la tarea y el Worker la intenta de nuevo.
4. Cuando los reintentos llegan a 0, la tarea queda como fallida en Flowable.

Si el Worker no logra ni siquiera reportar el fallo, lo registra y Flowable libera la tarea al vencer el bloqueo de 30 segundos.

## 6. Configuración

El Worker no tiene credenciales escritas en el código. Los parámetros se leen de dos lugares:

1. **Variables de entorno** (tienen prioridad).
2. **Archivo `config.local.php`**, que no se sube a GitHub.

Para crear el archivo se copia la plantilla `config.example.php` como `config.local.php` y se reemplazan los valores `CAMBIAR_...` por los del entorno propio.

| Variable de entorno | Clave en `config.local.php` | Descripción | Valor por defecto |
|---|---|---|---|
| `PELUDITOS_FLOWABLE_URL` | `flowable_url` | URL de Flowable REST | `http://127.0.0.1:8080/flowable-rest` |
| `PELUDITOS_FLOWABLE_USER` | `flowable_usuario` | Usuario de Flowable | ninguno (obligatorio) |
| `PELUDITOS_FLOWABLE_PASSWORD` | `flowable_password` | Contraseña de Flowable | ninguno (obligatoria) |
| `PELUDITOS_WS_URL` | `servicio_url` | URL base del servicio web | `http://127.0.0.1:8081/jakarta` |
| `PELUDITOS_WORKER_ID` | `worker_id` | Identificador del Worker | `peluditos-worker-1` |

Si faltan el usuario o la contraseña de Flowable, el Worker no se inicia y muestra un mensaje que explica cómo configurarlos.

Ejemplo en PowerShell, usando variables de entorno en vez del archivo:

```powershell
$env:PELUDITOS_FLOWABLE_USER = "usuario"
$env:PELUDITOS_FLOWABLE_PASSWORD = "contraseña"
$env:PELUDITOS_WS_URL = "http://127.0.0.1:8090/jakarta"
C:\xampp\php\php.exe worker.php
```

El tópico se define en la variable `$topicos` al inicio de `worker.php`.

## 7. Ejecución local

### Requisitos

- XAMPP con MySQL (MariaDB) iniciado y la base `peluditos_db` creada.
- Flowable REST desplegado en Tomcat (Java 17), con el modelo `modelo to-be + fidelizacion arreglos1.bpmn` desplegado.
- Servicio web de `Proyecto-1-WebServices` iniciado (Java 21).

### Procedimiento

1. Iniciar MySQL desde XAMPP.
2. Iniciar Tomcat con Flowable.
3. Iniciar el servicio web en el puerto 8081 (el puerto 8080 lo usa Flowable):

   ```powershell
   cd Proyecto-1-WebServices\peluditos-webservice
   $env:PELUDITOS_SERVER_PORT = "8081"
   .\mvnw.cmd spring-boot:run
   ```

   La conexión del servicio con la base de datos se configura en su archivo `application.properties`.
4. Comprobar que `http://127.0.0.1:8081/jakarta/clientes` muestre los clientes en JSON.
5. Crear el archivo `config.local.php` copiando `config.example.php` y completando las credenciales de Flowable (solo la primera vez).
6. Ejecutar el Worker desde la carpeta `Proyecto-2-ExternalWorker`:

   ```powershell
   C:\xampp\php\php.exe worker.php
   ```

7. Mantener la terminal abierta mientras se procesan tareas.

> **Nota de entorno:** en el equipo de pruebas, Flowable se inicia indicando su archivo de configuración con `-Dspring.config.additional-location`, que incluye `sslMode=disable` para conectarse a MariaDB sin SSL. Sin esa configuración, Flowable no despliega y responde 404.

### Resultado esperado

Al iniciar, el Worker muestra:

```text
========================================
EXTERNAL WORKER - PELUDITOS PET CHILE
========================================
Servicio web: http://127.0.0.1:8081/jakarta
Tópicos: registrar-resultado-lead
Worker: peluditos-worker-1
Presiona Ctrl+C para detenerlo.
```

## 8. Pruebas realizadas

Las pruebas se hicieron creando instancias del proceso mediante la API REST de Flowable, completando las tareas de usuario y observando al Worker.

| Prueba | Descripción | Resultado |
|---|---|---|
| 1. Venta concretada | `id_lead = 1`, `seConcretoLaVenta = true` | El Worker recibió la tarea, consultó el servicio web y la completó. El proceso terminó en **Lead Convertido**. |
| 2. Venta no concretada | `id_lead = 3`, `seConcretoLaVenta = false` | El Worker recibió la tarea de **Registrar Venta no Realizada**, consultó el servicio web y la completó. El proceso terminó en **Lead Perdido**. |
| 3. Servicio caído | Servicio web detenido, `id_lead = 2` | El Worker registró «Error al consultar el servicio web (HTTP 0)» y reportó el fallo a Flowable con 2 reintentos restantes. Al volver a levantar el servicio, el Worker reintentó solo y completó la tarea. |
| 4. Cliente inexistente | `id_lead = 999` | El Worker registró «No existe el cliente con id_lead 999» y los reintentos fueron bajando (2 y 1). El proceso no avanza hasta que se corrija el dato. |

Las pruebas 1 y 2 se hicieron con el modelo `arreglos1`. Las pruebas 3 y 4 se hicieron con la versión anterior del modelo, pero el manejo de errores del Worker no cambió.

## 9. Evidencias

Las evidencias se encuentran en la carpeta `evidencia/`:

| Evidencia | Descripción |
|---|---|
| `External-Worker-Iniciado.png.png` | Terminal con el External Worker iniciado (captura de la versión anterior del Worker, previa a la integración con el servicio web) |

## 10. Estado de la implementación

| Elemento | Estado |
|---|---|
| Worker sin conexión directa a MariaDB | Implementado y verificado |
| Consumo de `GET /jakarta/clientes` del servicio web | Implementado y verificado |
| Tópico `registrar-resultado-lead` (ramas de venta concretada y no concretada) | Implementado y verificado (el Worker verifica al cliente y completa la tarea) |
| Manejo de errores, reintentos y `worker.log` | Implementado y verificado |
| Configuración externa (`config.local.php` y variables de entorno, con plantilla `config.example.php`) | Implementado y verificado |
| Endpoint `GET /clientes/{id_lead}` en el servicio web | Pendiente (el Worker usa el listado completo mientras tanto) |
| Guardar en la base de datos el resultado del lead | Pendiente: requiere un endpoint de escritura en el servicio web |
| Tópico `procesar-fidelizacion` (Obtener Historial de Cliente) | Pendiente: el Worker aún no lo atiende |
| Tópico `registrar-resultado-oferta` (Registrar Respuesta) | Pendiente: el Worker aún no lo atiende |

Los dos tópicos pendientes pertenecen al modelo BPMN, pero el Worker aún no los atiende. Las tareas correspondientes quedan esperando en Flowable hasta que se implementen.

Por ahora, en las dos ramas de `registrar-resultado-lead` el Worker solo verifica que el cliente exista en el servicio web y completa la tarea. El registro del resultado de la venta en la base de datos queda pendiente.

## 11. Proyección para la siguiente entrega

- Reemplazar la búsqueda en el listado por el endpoint `GET /clientes/{id_lead}`.
- Implementar los endpoints de escritura en el servicio web para guardar el resultado del lead y activar los tópicos pendientes.
