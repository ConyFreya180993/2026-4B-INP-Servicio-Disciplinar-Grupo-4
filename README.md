## 7. Avances de la Entrega 3 — Servicios Web Java

### 7.1. Implementación

Se actualizó el servicio web de Peluditos Pet Chile utilizando Java 21, Spring Boot 4.1.2, Jersey (JAX-RS), JDBC y MariaDB.

La implementación permite consultar los clientes registrados en la base de datos `peluditos_db` mediante servicios REST que devuelven información en formato JSON.

### 7.2. Endpoints implementados

| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/jakarta/clientes` | Consulta todos los clientes registrados. |
| GET | `/jakarta/clientes/{id_lead}` | Consulta un cliente específico mediante su identificador. |

El servicio de consulta individual utiliza una sentencia SQL parametrizada y devuelve HTTP 404 cuando el cliente solicitado no existe.

### 7.3. Configuración externa

El archivo `application.properties` permite configurar la conexión a la base de datos y el puerto del servidor mediante variables de entorno.

Variables disponibles:

- `PELUDITOS_DB_URL`: dirección JDBC de MariaDB.
- `PELUDITOS_DB_USER`: usuario de la base de datos.
- `PELUDITOS_DB_PASSWORD`: contraseña de MariaDB.
- `PELUDITOS_SERVER_PORT`: puerto del servidor.
- `PELUDITOS_JERSEY_PATH`: ruta base de los servicios JAX-RS.

No se deben almacenar contraseñas reales en el repositorio.

### 7.4. Instrucciones de ejecución

Requisitos:

- Java JDK 21.
- MariaDB con la base de datos `peluditos_db`.
- Maven Wrapper incluido en el proyecto.

Desde PowerShell, ingresar a la carpeta del servicio:

```powershell
cd "Proyecto-1-WebServices/peluditos-webservice"
```

Configurar las variables necesarias en la terminal antes de iniciar el servicio. Por ejemplo:

```powershell
$env:PELUDITOS_SERVER_PORT="8081"
$env:PELUDITOS_DB_PASSWORD="CONTRASENA_LOCAL"
.\mvnw.cmd spring-boot:run
```

`CONTRASENA_LOCAL` debe reemplazarse por la contraseña correspondiente al entorno, sin publicar su valor real.

Para comprobar el funcionamiento:

- `http://localhost:8081/jakarta/clientes`
- `http://localhost:8081/jakarta/clientes/1`
- `http://localhost:8081/jakarta/clientes/99999`

### 7.5. Pruebas realizadas

Se realizaron pruebas locales con los siguientes resultados:

| Prueba | Resultado |
|---|---|
| Compilación Maven | BUILD SUCCESS |
| Inicio de Spring Boot en puerto 8081 | Correcto |
| Consulta general de clientes | Correcta |
| Consulta individual del cliente ID 1 | Correcta |
| Consulta de cliente inexistente | HTTP 404 |

Las capturas correspondientes se encuentran en el directorio `Evidencia` del proyecto de servicios web.

### 7.6. Pendientes de integración

Se encuentra pendiente verificar el recorrido completo entre Flowable OSS, los External Workers, los servicios web JAX-RS y MariaDB.

También se debe comprobar la integración con la interfaz web, documentar escenarios adicionales de error y coordinar la configuración de los distintos componentes para su despliegue.

Los avances descritos corresponden al servicio web Java y no representan una validación completa del sistema integrado.