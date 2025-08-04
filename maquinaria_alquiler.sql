-- 1. Tabla: clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cliente VARCHAR(100) NOT NULL,
    ruc_cedula VARCHAR(20) NOT NULL UNIQUE,
    direccion VARCHAR(150),
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- 2. Tabla: maquinarias
CREATE TABLE maquinarias (
    id_maquinaria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_maquinaria VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    marca VARCHAR(50),
    modelo VARCHAR(50),
    año YEAR,
    estado_maquinaria VARCHAR(20) DEFAULT 'disponible', -- disponible, alquilada, en_mantenimiento
    precio_diario DECIMAL(10,2)
);

-- 3. Tabla: ordenes de alquiler
CREATE TABLE ordenes_alquiler (
    id_orden INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado_orden VARCHAR(20) DEFAULT 'pendiente', -- pendiente, activa, finalizada, cancelada
    total_dias INT NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

-- 4. Tabla: detalle del alquiler (relación muchos a muchos orden ↔ maquinaria)
CREATE TABLE detalle_alquiler (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_orden INT NOT NULL,
    id_maquinaria INT NOT NULL,
    dias_alquiler INT NOT NULL,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_orden) REFERENCES ordenes_alquiler(id_orden),
    FOREIGN KEY (id_maquinaria) REFERENCES maquinarias(id_maquinaria)
);

-- 5. Tabla: disponibilidad por fecha
CREATE TABLE disponibilidad_maquinaria (
    id_disponibilidad INT AUTO_INCREMENT PRIMARY KEY,
    id_maquinaria INT NOT NULL,
    fecha DATE NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_maquinaria) REFERENCES maquinarias(id_maquinaria)
);

-- 6. Tabla: técnicos
CREATE TABLE tecnicos (
    id_tecnico INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tecnico VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100),
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- 7. Tabla: mantenimiento
CREATE TABLE mantenimiento (
    id_mantenimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_maquinaria INT NOT NULL,
    fecha DATE NOT NULL,
    descripcion TEXT,
    costo_mantenimiento DECIMAL(10,2),
    id_tecnico INT,
    FOREIGN KEY (id_maquinaria) REFERENCES maquinarias(id_maquinaria),
    FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id_tecnico)
);

-- 8. Tabla: técnico_maquinaria (relación muchos a muchos)
CREATE TABLE tecnico_maquinaria (
    id_tecnico INT NOT NULL,
    id_maquinaria INT NOT NULL,
    fecha_asignacion DATE NOT NULL,
    PRIMARY KEY (id_tecnico, id_maquinaria, fecha_asignacion),
    FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id_tecnico),
    FOREIGN KEY (id_maquinaria) REFERENCES maquinarias(id_maquinaria)
);

-- USUARIOS 

CREATE TABLE usuario (
  id_usuario INT(11) NOT NULL,
  nombre_usuario VARCHAR(50) DEFAULT NULL,
  contrasena VARCHAR(100) DEFAULT NULL,
  rol VARCHAR(20) DEFAULT NULL,
  estado VARCHAR(10) DEFAULT NULL
);


-- INSERTAR DATOS DE LAS TABLAS 

-- 1. Insertar clientes
INSERT INTO clientes (nombre_cliente, ruc_cedula, direccion, telefono, correo) VALUES
('Constructora Los Andes', '1790012345001', 'Av. de los Shyris y Naciones Unidas', '0991112233', 'contacto@losandes.com'),
('Maquinarias Sierra Azul', '1790023456001', 'Panamericana Norte Km 8', '0988776655', 'ventas@sierraazul.ec'),
('Grupo Constructor Eléctrico', '1790034567001', 'Av. 6 de Diciembre y Eloy Alfaro', '0977443322', 'info@electroconst.com');

-- 2. Insertar maquinarias
INSERT INTO maquinarias (nombre_maquinaria, tipo, marca, modelo, año, estado_maquinaria, precio_diario) VALUES
('Excavadora Hidráulica CAT 320D', 'Excavadora', 'Caterpillar', '320D', 2019, 'disponible', 400.00),
('Retroexcavadora JCB 3CX', 'Retroexcavadora', 'JCB', '3CX', 2018, 'alquilada', 350.00),
('Cargador Frontal Komatsu WA380', 'Cargador', 'Komatsu', 'WA380', 2020, 'mantenimiento', 500.00);

-- 3. Insertar técnicos
INSERT INTO tecnicos (nombre_tecnico, especialidad, telefono, correo) VALUES
('Carlos Pérez', 'Mecánica pesada', '0981122334', 'cperez@empresa.com'),
('María Gómez', 'Sistemas hidráulicos', '0982233445', 'mgomez@empresa.com');

-- 4. Insertar órdenes de alquiler
INSERT INTO ordenes_alquiler (id_cliente, fecha_inicio, fecha_fin, estado_orden, total) VALUES
(1, '2025-08-01', '2025-08-05', 'activa', 1600.00),
(2, '2025-08-03', '2025-08-06', 'pendiente', 1050.00);

-- 5. Insertar detalles de alquiler
INSERT INTO detalle_alquiler (id_orden, id_maquinaria, dias_alquiler, subtotal) VALUES
(1, 1, 4, 1600.00),  -- CAT 320D por 4 días
(2, 2, 3, 1050.00);  -- JCB 3CX por 3 días

-- 6. Insertar disponibilidad de maquinarias
INSERT INTO disponibilidad_maquinaria (id_maquinaria, fecha, disponible) VALUES
(1, '2025-08-01', FALSE),
(1, '2025-08-02', FALSE),
(1, '2025-08-03', FALSE),
(1, '2025-08-04', FALSE),
(2, '2025-08-03', FALSE),
(2, '2025-08-04', FALSE),
(2, '2025-08-05', FALSE),
(3, '2025-08-01', FALSE);

-- 7. Insertar mantenimiento
INSERT INTO mantenimiento (id_maquinaria, fecha, descripcion, costo_mantenimiento, id_tecnico) VALUES
(3, '2025-07-28', 'Revisión del sistema eléctrico y cambio de aceite hidráulico.', 300.00, 2),
(3, '2025-08-01', 'Cambio de válvula principal y ajuste del sistema de frenos.', 420.00, 1);

-- 8. Insertar relación técnico ↔ maquinaria
INSERT INTO tecnico_maquinaria (id_tecnico, id_maquinaria, fecha_asignacion) VALUES
(1, 3, '2025-07-28'),
(2, 3, '2025-08-01'),
(1, 1, '2025-08-02');


-- Insertar usuario administrador

INSERT INTO usuario (id_usuario, nombre_usuario, contrasena, rol, estado) VALUES
(1, 'admin', '$2y$10$QoRICDFmvF1y/JxkZ6VJbOWt6dHEbNFmiqEnmpaWHOqnU359aqfZW', 'Administrador', 'activo');


-- OTORGAR PERMISOS Y ROLES


-- Crear usuarios con contraseña
CREATE USER 'administrador'@'localhost' IDENTIFIED BY 'admin123';
CREATE USER 'develop'@'localhost' IDENTIFIED BY 'develop123';
CREATE USER 'supervisor'@'localhost' IDENTIFIED BY 'supervisor123';

-- Permisos para el Administrador (acceso total a toda la base de datos)
GRANT ALL PRIVILEGES ON empresa_maquinaria.* TO 'administrador'@'localhost';

-- Permisos para el Desarrollador
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.clientes TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.maquinarias TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.ordenes_alquiler TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.detalle_alquiler TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.disponibilidad_maquinaria TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.tecnicos TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.mantenimiento TO 'develop'@'localhost';
GRANT SELECT, INSERT, UPDATE ON empresa_maquinaria.tecnico_maquinaria TO 'develop'@'localhost';

-- Permisos para el Supervisor (solo SELECT e INSERT, sin UPDATE)
GRANT SELECT, INSERT ON empresa_maquinaria.clientes TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.maquinarias TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.ordenes_alquiler TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.detalle_alquiler TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.disponibilidad_maquinaria TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.tecnicos TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.mantenimiento TO 'supervisor'@'localhost';
GRANT SELECT, INSERT ON empresa_maquinaria.tecnico_maquinaria TO 'supervisor'@'localhost';

-- Aplicar cambios de privilegios
FLUSH PRIVILEGES;


