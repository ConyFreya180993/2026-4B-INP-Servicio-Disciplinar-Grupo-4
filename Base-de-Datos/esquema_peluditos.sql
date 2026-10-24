-- Proyecto Peluditos Pet Chile
-- Creado por: [Gabriel Arellano]

CREATE database IF NOT EXISTS peluditos_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- seleccionar la base de datos
USE peluditos_db;


-- crear tabla clientes potenciales
CREATE TABLE IF NOT EXISTS leads 
(
    id_lead INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(50) NOT NULL, -- nombre del cliente
    contacto_cliente VARCHAR (100) NOT NULL, -- número de teléfono
    canal_origen ENUM('WhatsApp', 'Instagram', 'Facebook') NOT NULL, -- wps, instagram, facebook, etc.
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- crear la tabla de seguimientos de clientes potenciales
CREATE TABLE IF NOT EXISTS seguimientos
(
    id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_lead INT NOT NULL,
    nombre_mascota VARCHAR (50) NOT NULL, -- nombre de la mascota
    tipo_mascota ENUM('Gato', 'Perro') NOT NULL, -- gato, perro
    consulta TEXT NOT NULL, -- consulta del cliente
    tamano_pastel ENUM('Pequeño', 'Mediano', 'Grande') NOT NULL, -- tamaño del pastel
    resultado_consulta ENUM('Venta Concretada', 'Venta No Concretada') NOT NULL, -- resultado de la consulta
    fecha_registro_consulta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- fecha de registro de la consulta
    fecha_entrega DATE,


    -- relacionar cada seguimiento con un cliente potencial
    CONSTRAINT fk_seguimientos_leads
        FOREIGN KEY (id_lead)
        REFERENCES leads(id_lead)
) ENGINE=InnoDB;



-- Insertar tres clientes potenciales ficticios.
INSERT INTO leads
(nombre_cliente, contacto_cliente, canal_origen)
VALUES
('Ana Torres', '912345678', 'Instagram'),
('Carlos Rojas', '923456789', 'WhatsApp'),
('Sofia Perez', '934567890', 'Facebook');





-- Insertar tres seguimientos ficticios.
-- Cada id_lead corresponde a un cliente de leads.

INSERT INTO seguimientos
(
    id_lead, nombre_mascota, tipo_mascota, consulta, tamano_pastel, resultado_consulta, fecha_entrega
)
VALUES
(
    1, 'Luna', 'Gato', 'Consulta por un pastel de cumpleaños para Luna.', 'Pequeño', 'Venta Concretada', '2026-11-15'
),
(
    2, 'Max', 'Perro', 'Consulta por un pastel de cumpleaños para Max.', 'Grande', 'Venta No Concretada', NULL
),
(
    3, 'Milo', 'Perro', 'Consulta por un pastel personalizado para Milo.', 'Mediano', 'Venta Concretada', '2026-11-20'
);


-- Consultar los clientes y sus seguimientos.
-- INNER JOIN relaciona ambas tablas mediante id_lead.

SELECT
    l.nombre_cliente,
    l.contacto_cliente,
    l.canal_origen,
    s.nombre_mascota,
    s.tipo_mascota,
    s.consulta,
    s.tamano_pastel,
    s.resultado_consulta,
    s.fecha_registro_consulta,
    s.fecha_entrega
FROM leads AS l
INNER JOIN seguimientos AS s
    ON l.id_lead = s.id_lead
ORDER BY l.id_lead;
