# Sistema de Control de Asistencia de Empleados
### Employee Attendance System
Aplicación web para el registro y gestión de asistencia del personal, desarrollada en **PHP puro con arquitectura MVC desde cero**, **Programación Orientada a Objetos (POO)**, **PDO** y **MariaDB** como base de datos.

## 1. Descripción del Negocio

Las organizaciones modernas necesitan gestionar la asistencia de su personal de forma precisa y centralizada (Solo de un turno). Este sistema reemplaza los registros manuales en papel o planillas físicas, eliminando problemas como:

- Registros incompletos o manipulados
- Alto costo administrativo por procesar asistencias manualmente
- Imposibilidad de generar reportes históricos de forma automática
- Falta de trazabilidad y auditoría sobre las marcaciones
- Dependencia de personal para consolidar información

## 2. Problema y Solución

### Problema Identificado
Las empresas carecen de un sistema digital accesible para registrar, monitorear y gestionar la asistencia de sus empleados. El control manual genera imprecisiones, pérdidas de información y dificulta la toma de decisiones basadas en datos confiables.

### Causas
- Ausencia de una herramienta digital centralizada para marcar asistencia
- Los registros en papel se pierden, deterioran o se alteran fácilmente
- No existe diferenciación de roles entre quién administra y quién solo consulta
- Es imposible generar reportes históricos de forma automática

### Efectos
- Pérdida económica por pago incorrecto de horas trabajadas
- Incapacidad de detectar patrones de ausentismo a tiempo
- Mayor carga operativa para el área de Recursos Humanos

### Solución Propuesta

Desarrollar una aplicación web con **PHP + POO + MVC** que permita:

- Autenticar usuarios con roles diferenciados (administrador / empleado)
- Registrar asistencia con fecha y hora exactas usando PDO y MariaDB
- Gestionar el catálogo de empleados y departamentos (CRUD completo)
- Consultar y filtrar el historial de asistencias por empleado y fecha
- Visualizar un dashboard con el estado de asistencia del día en curso
- 
## 3. Preanálisis

### Necesidades Identificadas

1. Registrar quién entra y sale, con fecha y hora exacta
2. Panel de control con el estado de asistencia del día
3. Administrar el catálogo de empleados (crear, editar, eliminar)
4. Organizar empleados por departamentos
5. Consultar historial de asistencias filtrado por empleado y período
6. Autenticar usuarios para proteger la información del sistema
7. Diferenciar permisos entre administrador y empleado

### Estudio de Viabilidad

#### Viabilidad Técnica
- PHP 8+ disponible en prácticamente cualquier servidor web
- MariaDB es un gestor gratuito, robusto y ampliamente documentado
- Apache con `mod_rewrite` disponible en XAMPP para desarrollo local
- La POO permite estructurar el sistema con clases, herencia y encapsulamiento
- El patrón MVC está documentado en [`CONCEPTS.md`](./CONCEPTS.md)

#### Viabilidad Económica
- Stack completamente open source y gratuito (PHP, MariaDB, Apache, Git)
- Entorno de desarrollo levantable localmente con XAMPP sin costo
- No se requieren licencias de software adicionales

#### Viabilidad Operacional
- Los usuarios solo necesitan un navegador web para acceder
- Administrable de forma remota una vez desplegado
- La separación en módulos facilita la capacitación del personal

### Alcance del Sistema

#### Dentro del alcance
- Autenticación con sesiones PHP y roles (administrador / empleado)
- Módulo de empleados: CRUD completo
- Módulo de departamentos: gestión de áreas
- Módulo de asistencia: registro de entrada/salida e historial
- Dashboard con resumen de asistencias del día
- Layouts reutilizables (header, footer, navbar) — principio DRY

#### Fuera del alcance
- Integración con dispositivos biométricos
- Módulo de nómina o cálculo de salarios
- Aplicación móvil nativa (iOS / Android)
- Notificaciones por correo o SMS
- Integración con sistemas ERP externos

---

## 4. Análisis de Requisitos

### 4.1 Requisitos Funcionales
Falta
### 4.2 Requisitos No Funcionales
Falta
## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| **Backend** | PHP 8+ — POO (Programación Orientada a Objetos) — MVC desde cero |
| **Base de datos** | MariaDB — PDO (PHP Data Objects) con prepared statements |
| **Frontend** | HTML5, CSS3, JavaScript — Vistas PHP con layouts reutilizables |
| **Servidor web** | Apache — Reescritura de URLs vía `.htaccess` |
| **Control de versiones** | Git + GitHub |
| **Configuración** | Variables de entorno (`.env`) para credenciales |
---

## Arquitectura del Proyecto

El sistema aplica **POO** y **MVC** implementado desde cero. Los 4 pilares de POO en el proyecto:

### Flujo de una Petición


### Estructura del Proyecto

## Instalación

### Requisitos previos
- PHP 8+
- Servidor web local o hosting
- MariaDB / MySQL

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/ojitoslanda/employee-attendance-system.git
cd employee-attendance-system

# 2. Configurar variables de entorno
cp .env.example .env
# Editar .env con tus credenciales de base de datos

# 3. Crear la base de datos


# 4. Apuntar el servidor web a la carpeta public/

```

## TRELLO
Falta integrar

### DIAGRAMA DE FIGMA UI/UX

## Base de datos

> Copia el bloque SQL de abajo y ejecútalo completo en **phpMyAdmin → pestaña SQL**.
> Esto elimina la base de datos anterior y crea todo desde cero.

```sql
-- Eliminar y recrear la base de datos
DROP DATABASE IF EXISTS senai_asistencia;
CREATE DATABASE senai_asistencia
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE senai_asistencia;

-- ─────────────────────────────────────────
--  TABLA: usuario
--  roles: 'superadmin' acceso total
--         'admin'      solo operaciones de inventario
-- ─────────────────────────────────────────
CREATE TABLE usuario (
    id_usuario     INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(150) NOT NULL UNIQUE,
    clave          VARCHAR(255) NOT NULL,
    roles          ENUM('admin', 'superadmin') NOT NULL DEFAULT 'admin',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Usuario superadmin por defecto
-- Contraseña: admin123
INSERT INTO usuario (nombre_usuario, clave, roles) VALUES
('admin', '$2y$12$wSBNmcCqiDfzh3Sb2.YoMOxbFZrGKe7cUzjLEnYIuXjNEj0zRjxBq', 'superadmin');

-- ─────────────────────────────────────────
--  TABLA: producto
--  Catálogo de productos con control de stock
-- ─────────────────────────────────────────
CREATE TABLE producto (
    id_producto     INT AUTO_INCREMENT PRIMARY KEY,
    codigo          VARCHAR(100)   NOT NULL UNIQUE COMMENT 'Código de barras o SKU',
    nombre_producto VARCHAR(200)   NOT NULL,
    descripcion     VARCHAR(500)   DEFAULT '',
    stock           INT            NOT NULL DEFAULT 0,
    precio          DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    fecha_registro  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Productos de ejemplo
INSERT INTO producto (codigo, nombre_producto, descripcion, stock, precio) VALUES
('7751234000001', 'Cuaderno A4 100 hojas',  'Cuaderno universitario rayado',  50,  3.50),
('7751234000002', 'Lapicero azul Bic',       'Lapicero punta fina color azul', 200, 0.80),
('7751234000003', 'Corrector líquido Pilot', 'Corrector 20ml',                 30,  4.20),
('7751234000004', 'Resaltador amarillo',      'Resaltador fluorescente',        80,  2.50),
('7751234000005', 'Folder manila A4',         'Folder de cartulina',            100, 0.50);
```

**Credenciales por defecto tras ejecutar el SQL:**

| Campo    | Valor      |
|----------|------------|
| Usuario  | `admin`    |
| Contraseña | `admin123` |
| Rol      | superadmin |

### Cardinalidades

La base de datos tiene 2 tablas independientes sin relaciones entre sí:

**usuario** — Cuentas de acceso al sistema (administradores).

**producto** — Catálogo de productos con stock y precio.




