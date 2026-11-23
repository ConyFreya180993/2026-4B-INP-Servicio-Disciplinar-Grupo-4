package com.example.peluditoswebservice;

import java.util.List;
import java.util.Map;

import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.stereotype.Component;

import jakarta.ws.rs.GET;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.PathParam;
import jakarta.ws.rs.Produces;
import jakarta.ws.rs.WebApplicationException;
import jakarta.ws.rs.core.MediaType;

@Component
@Path("/clientes")
public class ClientesResource {

    private final JdbcTemplate jdbcTemplate;

    public ClientesResource(JdbcTemplate jdbcTemplate) {
        this.jdbcTemplate = jdbcTemplate;
    }

    // Consultar todos los clientes
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public List<Map<String, Object>> listarClientes() {
        String sql = "SELECT * FROM leads";
        return jdbcTemplate.queryForList(sql);
    }

    // Consultar un cliente por su ID
    @GET
    @Path("/{id_lead}")
    @Produces(MediaType.APPLICATION_JSON)
    public Map<String, Object> buscarCliente(
            @PathParam("id_lead") int idLead) {

        if (idLead <= 0) {
            throw new WebApplicationException(
                "ID de cliente invalido", 400);
        }

        String sql = "SELECT * FROM leads WHERE id_lead = ?";

        List<Map<String, Object>> resultados =
                jdbcTemplate.queryForList(sql, idLead);

        if (resultados.isEmpty()) {
            throw new WebApplicationException(
                "Cliente no encontrado", 404);
        }

        return resultados.get(0);
    }
}