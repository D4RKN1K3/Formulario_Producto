-- Tabla sucursal
CREATE TABLE sucursal (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  bodega_id INTEGER NOT NULL REFERENCES bodega(id) ON DELETE CASCADE
);

-- Tabla bodega
CREATE TABLE bodega (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

-- Tabla moneda
CREATE TABLE moneda (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

-- Tabla material
CREATE TABLE material (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

-- Tabla producto
CREATE TABLE producto (
  codigo VARCHAR(15) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  precio NUMERIC(10, 2) NOT NULL ,
  descripcion TEXT NOT NULL ,
  bodega_id INTEGER NOT NULL REFERENCES bodega(id),
  sucursal_id INTEGER NOT NULL REFERENCES sucursal(id),
  moneda_id INTEGER NOT NULL REFERENCES moneda(id)
);

-- Tabla que refleja la relacion de producto y material .
CREATE TABLE producto_material (
  producto_codigo VARCHAR(15) REFERENCES producto(codigo) ON DELETE CASCADE,
  material_id INTEGER REFERENCES material(id) ON DELETE CASCADE,
  PRIMARY KEY (producto_codigo, material_id)
);


--INSERCION DE DATOS 

-- Inserción de Bodegas
INSERT INTO bodega (nombre) VALUES
('Bodega A'),
('Bodega B');

-- Inserción de Sucursales
INSERT INTO sucursal (nombre, bodega_id) VALUES
('Sucursal A1', 1),
('Sucursal A2', 1),
('Sucursal B1', 2),
('Sucursal B2', 2);

-- Inserción de Materiales
INSERT INTO material (nombre) VALUES
('Plástico'),
('Metal'),
('Madera'),
('Vidrio'),
('Textil');

-- Inserción de Monedas
INSERT INTO moneda (nombre) VALUES
('Dólar'),
('Peso Chileno');