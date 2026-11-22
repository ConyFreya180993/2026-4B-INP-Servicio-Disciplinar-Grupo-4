package com.example.peluditoswebservice;

import java.util.List;
import java.util.Map;

import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.stereotype.Component;

import jakarta.ws.rs.GET;
import jakarta.ws.rs.Path;
import jakarta.ws.rs.Produces;
import jakarta.ws.rs.core.MediaType;

@Component
@Path("/clientes")
public class ClientesResource {

    private final JdbcTemplate jdbcTemplate;

    public ClientesResource(JdbcTemplate jdbcTemplate) {
        this.jdbcTemplate = jdbcTemplate;
    }

    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public List<Map<String, Object>> listarClientes() {
        String sql = "SELECT * FROM leads";
        return jdbcTemplate.queryForList(sql);
    }
}
