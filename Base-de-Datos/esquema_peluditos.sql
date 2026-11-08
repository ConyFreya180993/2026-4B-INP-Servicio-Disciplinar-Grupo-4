
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
    contacto_cliente VARCHAR(100) NOT NULL, -- número de teléfono
    canal_origen ENUM('WhatsApp', 'Instagram', 'Facebook') NOT NULL, -- wps, instagram, facebook, etc.
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- crear tabla pet
CREATE TABLE IF NOT EXISTS pet
(
    id_pet INT AUTO_INCREMENT PRIMARY KEY,
    id_lead INT NOT NULL,
    nombre_mascota VARCHAR(50) NOT NULL, -- nombre de la mascota
    tipo_pet ENUM('Gato', 'Perro') NOT NULL, -- gato, perro
    cumpleanos DATE NOT NULL, -- cumpleaños de la mascota

    CONSTRAINT fk_pet_leads
        FOREIGN KEY (id_lead)
        REFERENCES leads(id_lead)
) ENGINE=InnoDB;


-- crear la tabla de seguimientos de clientes potenciales
CREATE TABLE IF NOT EXISTS seguimientos
(
    id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_pet INT NOT NULL,
    consulta TEXT NOT NULL, -- consulta del cliente
    tamano_pastel ENUM('Pequeño', 'Mediano', 'Grande') NOT NULL, -- tamaño del pastel
    resultado_consulta ENUM('Venta Concretada', 'Venta No Concretada', 'Pendiente') NOT NULL, -- resultado de la consulta
    fecha_registro_consulta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- fecha de registro de la consulta
    fecha_entrega DATE,

    -- relacionar cada seguimiento con un cliente potencial
    CONSTRAINT fk_seguimientos_pet
        FOREIGN KEY (id_pet)
        REFERENCES pet(id_pet)
) ENGINE=InnoDB;


-- crear tabla fidelización
CREATE TABLE IF NOT EXISTS fidelizacion
(
    id_fidelizacion INT AUTO_INCREMENT PRIMARY KEY,
    id_pet INT NOT NULL,
    fecha_evento DATE NOT NULL,
    tipo_campana ENUM('Cumpleaños', 'Celebración') NOT NULL,
    estado_envio ENUM('Programado', 'Enviado') NOT NULL,

    -- relacionar cada fidelización con un cliente potencial
    CONSTRAINT fk_fidelizacion_pet
        FOREIGN KEY (id_pet)
        REFERENCES pet(id_pet)
) ENGINE=InnoDB;


-- Insertar tres clientes potenciales ficticios.
INSERT INTO leads
(
    nombre_cliente, contacto_cliente, canal_origen
)
VALUES
    ('Ana Torres', '912345678', 'Instagram'),
    ('Carlos Rojas', '923456789', 'WhatsApp'),
    ('Sofia Perez', '934567890', 'Facebook');


-- Insertar tres mascotas ficticias.
-- Cada mascota pertenece a un cliente potencial.
INSERT INTO pet
    (id_lead, nombre_mascota, tipo_pet, cumpleanos)
VALUES
    (1, 'Luna', 'Gato', '2026-11-15'),
    (2, 'Max', 'Perro', '2026-03-08'),
    (3, 'Milo', 'Perro', '2026-12-20');


-- Insertar una campaña de fidelización ficticia.
-- La mascota se identifica mediante id_pet.
INSERT INTO fidelizacion
    (id_pet, fecha_evento, tipo_campana, estado_envio)
VALUES
    (1, '2026-11-15', 'Cumpleaños', 'Programado');


-- Insertar tres seguimientos ficticios.
-- Cada id_pet corresponde a una mascota registrada.
INSERT INTO seguimientos
    (id_pet, consulta, tamano_pastel, resultado_consulta, fecha_entrega)
VALUES
    (1, 'Consulta por un pastel de cumpleaños para Luna.', 'Pequeño', 'Venta Concretada', '2026-11-15'),
    (2, 'Consulta por un pastel de cumpleaños para Max.', 'Grande', 'Venta No Concretada', NULL),
    (3, 'Consulta por un pastel personalizado para Milo.', 'Mediano', 'Venta Concretada', NULL);
