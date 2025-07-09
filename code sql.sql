-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS bia_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- استخدام قاعدة البيانات
USE bia_db;

-- إنشاء جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;