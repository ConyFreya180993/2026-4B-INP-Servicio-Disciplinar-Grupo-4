# Web Services – Peluditos Pet Chile

## 1. Descripción del proyecto

Este proyecto corresponde al desarrollo inicial de un Web Service REST en Java para Peluditos Pet Chile, realizado como parte del Servicio Disciplinar del Grupo 4.

Su objetivo es comprobar el funcionamiento de un servicio web que posteriormente podrá integrarse con el proceso BPMN y el motor Flowable.

## 2. Tecnologías utilizadas

- Java JDK 21
- Spring Boot 4.1.2
- Spring Web
- Maven
- Visual Studio Code
- GitHub

## 3. Web Service implementado

**Nombre:** Servicio de prueba Peluditos Pet Chile.

**Método HTTP:** GET

**Endpoint:** `/api/consultas/prueba`

**Objetivo:** Comprobar que el servicio web funciona correctamente y responde a una solicitud HTTP.

**Datos de entrada:** No requiere parámetros.

**Datos de salida:** Respuesta JSON con un estado y un mensaje.

**Respuesta obtenida:**

```json
{
  "estado": "OK",
  "mensaje": "Web Service Peluditos funcionando"
}
```

**Relación con el proceso de negocio:** Permite verificar la conectividad del servicio antes de integrarlo con un External Worker de Flowable.

## 4. Web Services propuestos

Estos servicios se proponen inicialmente para apoyar la gestión de consultas de clientes.

| Servicio | Método y endpoint | Entrada | Salida |
|---|---|---|---|
| Registrar consulta | POST /api/consultas | Datos del cliente y consulta | Identificador y estado |
| Consultar solicitud | GET /api/consultas/{id} | Identificador | Datos y estado |
| Registrar resultado | PATCH /api/consultas/{id}/resultado | Identificador y resultado | Estado actualizado |

Estos endpoints deberán validarse con el equipo y podrán modificarse durante el desarrollo.

## 5. Ejecución del proyecto

Desde la carpeta `peluditos-webservice`, ejecutar en Windows:

```powershell
.\mvnw.cmd spring-boot:run
```

Después abrir en el navegador:

http://localhost:8080/api/consultas/prueba

## 6. Resultado obtenido

Se ejecutó correctamente la aplicación Spring Boot utilizando Java 21.

El servidor Tomcat integrado inició en el puerto 8080 y el Web Service respondió correctamente a una solicitud realizada desde Google Chrome.

Se comprobó que la respuesta contiene un mensaje de funcionamiento y el estado OK.

## 7. Estado del desarrollo

- [x] Configuración del entorno Java.
- [x] Creación del proyecto Spring Boot.
- [x] Implementación del Web Service REST de prueba.
- [x] Ejecución y verificación del endpoint.
- [x] Documentación inicial de los servicios propuestos.
- [ ] Integración con External Worker.
- [ ] Integración con Flowable.
- [ ] Desarrollo de los servicios definitivos.

Las integraciones pendientes se realizarán en coordinación con el equipo del Grupo 4.