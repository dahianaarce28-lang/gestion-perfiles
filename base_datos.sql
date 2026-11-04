-- ============================================================
-- Base de datos: gestion_perfiles
-- Cómo usarlo (XAMPP):
-- 1. Iniciá Apache y MySQL en el panel de XAMPP
-- 2. Abrí http://localhost/phpmyadmin
-- 3. Tocá la pestaña "SQL", pegá todo este archivo y tocá "Continuar"
-- ============================================================

CREATE DATABASE IF NOT EXISTS gestion_perfiles
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE gestion_perfiles;

CREATE TABLE IF NOT EXISTS perfiles (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  nombre          VARCHAR(100) NOT NULL,
  correo          VARCHAR(120) NOT NULL UNIQUE,
  rol             ENUM('Estudiante', 'Docente', 'Administrativo') NOT NULL,
  estado          VARCHAR(20) NOT NULL DEFAULT 'Activo',
  fecha_registro  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
