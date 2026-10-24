package com.example.peluditoswebservice;


import java.util.Map;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class PruebaController {

    @GetMapping("/api/consultas/prueba")
    public Map<String, String> probarServicio() {
        return Map.of(
            "estado", "OK",
            "mensaje", "Web Service Peluditos funcionando"
        );
    }
}
