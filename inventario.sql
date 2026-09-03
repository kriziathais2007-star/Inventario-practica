-- ============================================================
--  BASE DE DATOS - SISTEMA DE INVENTARIO
--  Ejecuta este archivo completo en phpMyAdmin:
--  1. Abre phpMyAdmin → pestaña "SQL"
--  2. Pega todo el contenido y haz clic en "Ejecutar"
-- ============================================================

DROP DATABASE IF EXISTS senai_asistencia;
CREATE DATABASE senai_asistencia
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE senai_asistencia;

-- ── TABLA: usuario ────────────────────────────────────────────
CREATE TABLE usuario (
    id_usuario     INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(150) NOT NULL UNIQUE,
    clave          VARCHAR(255) NOT NULL,
    roles          ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuario superadmin por defecto  /  contraseña: admin123
-- NOTA: la contraseña se guarda en texto plano aquí para compatibilidad.
-- El sistema también acepta contraseñas con hash (password_hash de PHP).
-- Para mayor seguridad, usa el script crear_admin.php después de importar.
INSERT INTO usuario (nombre_usuario, clave, roles) VALUES
('admin', 'admin123', 'superadmin');

-- ── TABLA: producto ───────────────────────────────────────────
CREATE TABLE producto (
    id_producto     INT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(100)   NOT NULL UNIQUE COMMENT 'Codigo de barras o SKU',
    nombre_producto VARCHAR(200)   NOT NULL,
    descripcion     VARCHAR(500)   DEFAULT '',
    stock           INT            NOT NULL DEFAULT 0,
    precio          DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    imagen          VARCHAR(300)   DEFAULT NULL COMMENT 'Nombre del archivo en public/image/productos/',
    fecha_registro  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Productos de ejemplo
INSERT INTO producto (codigo, nombre_producto, descripcion, stock, precio) VALUES
('7751234000001', 'Cuaderno A4 100 hojas',  'Cuaderno universitario rayado',  50,  3.50),
('7751234000002', 'Lapicero azul Bic',       'Lapicero punta fina color azul', 200, 0.80),
('7751234000003', 'Corrector liquido Pilot', 'Corrector 20ml',                 30,  4.20),
('7751234000004', 'Resaltador amarillo',      'Resaltador fluorescente',        80,  2.50),
('7751234000005', 'Folder manila A4',         'Folder de cartulina',            100, 0.50);
