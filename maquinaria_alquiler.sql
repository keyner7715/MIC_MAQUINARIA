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
    total DECIMAL(10,2) DEFAULT 0,
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


-- INSERTAR DATOS DE LAS TABLAS 

-- Insertar clientes
INSERT INTO clientes (cliente_nombre, cliente_ruc_cedula, cliente_direccion, cliente_telefono, cliente_correo) VALUES
('Constructora Andes', '1790012345001', 'Av. Amazonas y Colón', '0999999999', 'contacto@andes.com'),
('Inversiones Sierra', '1790098765001', 'Av. República y 10 de Agosto', '0988888888', 'info@sierra.ec'),
('Alquiladora Eléctrica', '1790054321001', 'Panamericana Norte km 12', '0977777777', 'ventas@electrica.ec');

-- Insertar maquinarias
INSERT INTO maquinarias (maquinaria_nombre, maquinaria_tipo, maquinaria_marca, maquinaria_modelo, maquinaria_anio, maquinaria_estado, maquinaria_precio_dia) VALUES
('Excavadora 320', 'Excavadora', 'Caterpillar', '320D', 2018, 'disponible', 350.00),
('Montacargas 5T', 'Montacargas', 'Toyota', 'GENEO-5', 2020, 'disponible', 180.00),
('Retroexcavadora JCB', 'Retroexcavadora', 'JCB', '3CX', 2017, 'mantenimiento', 300.00);

-- Insertar técnicos
INSERT INTO tecnicos (tecnico_nombre, tecnico_especialidad, tecnico_telefono, tecnico_correo) VALUES
('Carlos Pérez', 'Mecánica pesada', '0981122334', 'cperez@empresa.com'),
('María Gómez', 'Electromecánica', '0982233445', 'mgomez@empresa.com');

-- Insertar órdenes de alquiler
INSERT INTO ordenes_alquiler (orden_cliente_id, orden_fecha_inicio, orden_fecha_fin, orden_estado, orden_total) VALUES
(1, '2025-08-01', '2025-08-05', 'activa', 1400.00),
(2, '2025-08-02', '2025-08-04', 'pendiente', 360.00);

-- Insertar detalles de alquiler
INSERT INTO detalle_alquiler (detalle_orden_id, detalle_maquinaria_id, detalle_dias, detalle_subtotal) VALUES
(1, 1, 4, 1400.00), -- Excavadora por 4 días
(2, 2, 2, 360.00),  -- Montacargas por 2 días
(2, 1, 2, 700.00);  -- Excavadora por 2 días (en otra orden si se permite compartir)

-- Insertar disponibilidad de maquinaria
INSERT INTO disponibilidad_maquinaria (disponibilidad_maquinaria_id, disponibilidad_fecha, disponibilidad_estado) VALUES
(1, '2025-08-01', FALSE),
(1, '2025-08-02', FALSE),
(2, '2025-08-02', FALSE),
(3, '2025-08-01', FALSE),
(3, '2025-08-03', FALSE);

-- Insertar mantenimientos
INSERT INTO mantenimiento (mantenimiento_maquinaria_id, mantenimiento_fecha, mantenimiento_descripcion, mantenimiento_costo, mantenimiento_tecnico_id) VALUES
(3, '2025-07-28', 'Cambio de sistema hidráulico', 520.00, 1),
(3, '2025-08-01', 'Revisión eléctrica', 250.00, 2);

-- Insertar tenico_maquinaria
INSERT INTO tecnico_maquinaria (tm_tecnico_id, tm_maquinaria_id, tm_fecha_asignacion) VALUES
(1, 3, '2025-07-28'),
(2, 3, '2025-08-01'),
(1, 1, '2025-08-01'); -- técnico también asignado a maquinaria 1


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


