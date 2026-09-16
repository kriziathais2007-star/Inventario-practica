
CREATE DATABASE Sistema_inventario;
USE Sistema_inventario;
-- ── 1. MODULO DE SEGURIDAD ─────────────────────────────────────
CREATE TABLE usuario (
    id_usuario     INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(150) NOT NULL UNIQUE,
    clave          VARCHAR(255) NOT NULL,
    roles          ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuario (nombre_usuario, clave, roles) VALUES
('admin', 'admin123', 'superadmin');

-- ── 2. MODULO DE INVENTARIO / PRODUCTOS ────────────────────────
CREATE TABLE producto (
    id_producto    INT AUTO_INCREMENT PRIMARY KEY,
    codigo         VARCHAR(255)   NOT NULL UNIQUE COMMENT 'Codigo de barras o SKU',
    nombre_producto VARCHAR(200)  NOT NULL,
    descripcion    VARCHAR(500)   DEFAULT '',
    stock          INT            NOT NULL DEFAULT 0,
    precio         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    imagen         VARCHAR(300)   DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO producto (codigo, nombre_producto, descripcion, stock, precio) VALUES
('7751234000001', 'Cuaderno A4 100 hojas',  'Cuaderno universitario rayado',  50,  3.50),
('7751234000002', 'Lapicero azul Bic',       'Lapicero punta fina color azul', 200, 0.80),
('7751234000003', 'Corrector liquido Pilot', 'Corrector 20ml',                 30,  4.20),
('7751234000004', 'Resaltador amarillo',      'Resaltador fluorescente',        80,  2.50),
('7751234000005', 'Folder manila A4',         'Folder de cartulina',           100, 0.50);

-- Kardex de Almacén
CREATE TABLE movimiento_almacen (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    -- id_venta INT NULL,
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(100) NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto),
    -- FOREIGN KEY (id_venta) REFERENCES venta(id_venta),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── 3. MODULO DE ASISTENCIA DE TRABAJADORES ────────────────────
CREATE TABLE empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    documento VARCHAR(20) NOT NULL UNIQUE COMMENT 'DNI del trabajador',
    cargo VARCHAR(100) NOT NULL COMMENT 'Ej: Cajero, Almacenero, Administrador',
    telefono VARCHAR(20) NULL,
    qr_codigo varchar(255) not null unique,
    estado TINYINT(1) DEFAULT 1 COMMENT '1 = Activo, 0 = Inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada datetime null,
    hora_salida datetime null,
    estado ENUM('Presente', 'Tarde', 'Falta', 'Permiso') NOT NULL DEFAULT 'Presente',
    observacion VARCHAR(255) NULL,
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ── 4. MODULO DE VENTAS ────────────────────────────────────────
CREATE TABLE cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    documento VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(200) NOT NULL,
    telefono VARCHAR(20) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE venta (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NULL,
    id_usuario INT NOT NULL,
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    -- tipo_comprobante ENUN('boleta','factura') default 'boleta',
    -- serie varchar(10),
    -- numero_comprobante INT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES venta(id_venta) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE proveedor (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    razon_social VARCHAR(150) NOT NULL,
    ruc_dni VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20) NULL,
    correo VARCHAR(100) NULL,
    direccion VARCHAR(200) NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--  MODIFICAR TU TABLA PRODUCTO ACTUAL PARA AGREGARLE LAS RELACIONES
ALTER TABLE producto 
    ADD COLUMN id_categoria INT NOT NULL AFTER imagen,
    ADD COLUMN id_marca INT NOT NULL AFTER id_categoria,
    ADD COLUMN id_proveedor INT NOT NULL AFTER id_marca;

-- . CREAR LAS LLAVES FORÁNEAS (FOREIGN KEYS) QUE CONECTAN TODO
ALTER TABLE producto
    ADD CONSTRAINT fk_prod_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    ADD CONSTRAINT fk_prod_marca FOREIGN KEY (id_marca) REFERENCES marca(id_marca),
    ADD CONSTRAINT fk_prod_proveedor FOREIGN KEY (id_proveedor) REFERENCES proveedor(id_proveedor);


INSERT INTO categoria (id_categoria, nombre) VALUES (1, 'General');
INSERT INTO marca (id_marca, nombre) VALUES (1, 'Genérico');
INSERT INTO proveedor (id_proveedor, razon_social, ruc_dni) VALUES (1, 'Proveedor General', '10000000001');
