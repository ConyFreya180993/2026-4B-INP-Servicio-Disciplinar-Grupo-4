
package com.example.peluditoswebservice;

import java.util.List;
import java.util.Map;

import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class ClientesController {

    private final JdbcTemplate jdbcTemplate;

    public ClientesController(JdbcTemplate jdbcTemplate) {
        this.jdbcTemplate = jdbcTemplate;
    }

    @GetMapping("/api/clientes")
    public List<Map<String, Object>> listarClientes() {

        String sql = "SELECT * FROM leads";

        return jdbcTemplate.queryForList(sql);
    }
}
