
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

    -- relacionar cada seguimiento con una mascota
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

    -- relacionar cada fidelización con una mascota
    CONSTRAINT fk_fidelizacion_pet
        FOREIGN KEY (id_pet)
        REFERENCES pet(id_pet)
) ENGINE=InnoDB;
