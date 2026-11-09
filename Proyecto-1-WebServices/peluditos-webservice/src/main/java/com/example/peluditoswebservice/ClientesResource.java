package com.example.peluditoswebservice;

import jakarta.ws.rs.GET;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.Produces;
import jakarta.ws.rs.core.MediaType;

@Path("/clientes")
public class ClientesResource {

    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public String listarClientes() {
        return "{\"estado\":\"OK\",\"mensaje\":\"Servicio Jakarta REST Peluditos funcionando\"}";
    }
}
