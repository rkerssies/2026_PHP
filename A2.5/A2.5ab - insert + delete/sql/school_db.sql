-- A2.2cd: oefendatabase voor phpMyAdmin (MySQL/MariaDB).
-- Fictieve lesdata: sweetness is een indicatieve score van 1 tot 10.
-- season is een vereenvoudigde leswaarde; kleur en smaak verschillen per ras.
-- Importeer één keer in een nieuwe oefendatabase. Er worden geen tabellen verwijderd.

CREATE DATABASE IF NOT EXISTS school_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_db;

CREATE TABLE fruits (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    color VARCHAR(80) NOT NULL,
    sweetness TINYINT UNSIGNED NOT NULL,
    season VARCHAR(40) NOT NULL,
    price DECIMAL(6,2) NULL DEFAULT NULL,
    description TEXT NULL,
    is_available BOOLEAN NOT NULL DEFAULT TRUE,
    harvested_on DATE NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- id wordt bewust niet meegegeven: AUTO_INCREMENT vult 1 tot en met 50 in.
-- created_at ligt verspreid tussen 2025-10-08 en 2026-10-08.
INSERT INTO fruits (name, color, sweetness, season, created_at) VALUES
    ('Appel', 'Rood/groen', 6, 'Herfst', '2025-10-08 08:15:00'),
    ('Peer', 'Groen/geel', 7, 'Herfst', '2025-10-15 11:40:00'),
    ('Banaan', 'Geel', 9, 'Jaarrond', '2025-10-23 14:05:00'),
    ('Sinaasappel', 'Oranje', 7, 'Winter', '2025-10-30 16:30:00'),
    ('Mandarijn', 'Oranje', 8, 'Winter', '2025-11-07 09:50:00'),
    ('Citroen', 'Geel', 1, 'Jaarrond', '2025-11-14 08:15:00'),
    ('Limoen', 'Groen', 1, 'Jaarrond', '2025-11-21 11:40:00'),
    ('Grapefruit', 'Geel/roze', 3, 'Winter', '2025-11-29 14:05:00'),
    ('Pomelo', 'Groen/geel', 4, 'Winter', '2025-12-06 16:30:00'),
    ('Kumquat', 'Oranje', 4, 'Winter', '2025-12-14 09:50:00'),
    ('Aardbei', 'Rood', 8, 'Zomer', '2025-12-21 08:15:00'),
    ('Framboos', 'Rood', 6, 'Zomer', '2025-12-28 11:40:00'),
    ('Braam', 'Zwart/paars', 6, 'Zomer', '2026-01-05 14:05:00'),
    ('Blauwe bes', 'Blauw', 7, 'Zomer', '2026-01-12 16:30:00'),
    ('Rode bes', 'Rood', 3, 'Zomer', '2026-01-20 09:50:00'),
    ('Zwarte bes', 'Zwart', 3, 'Zomer', '2026-01-27 08:15:00'),
    ('Kruisbes', 'Groen', 4, 'Zomer', '2026-02-03 11:40:00'),
    ('Cranberry', 'Rood', 2, 'Herfst', '2026-02-11 14:05:00'),
    ('Druif', 'Groen/paars', 9, 'Herfst', '2026-02-18 16:30:00'),
    ('Kers', 'Rood', 8, 'Zomer', '2026-02-26 09:50:00'),
    ('Pruim', 'Paars', 7, 'Zomer', '2026-03-05 08:15:00'),
    ('Abrikoos', 'Oranje', 7, 'Zomer', '2026-03-12 11:40:00'),
    ('Perzik', 'Oranje/rood', 8, 'Zomer', '2026-03-20 14:05:00'),
    ('Nectarine', 'Rood/geel', 8, 'Zomer', '2026-03-27 16:30:00'),
    ('Mango', 'Oranje/groen', 9, 'Jaarrond', '2026-04-04 09:50:00'),
    ('Ananas', 'Geel/bruin', 8, 'Jaarrond', '2026-04-11 08:15:00'),
    ('Kiwi', 'Bruin/groen', 6, 'Winter', '2026-04-18 11:40:00'),
    ('Watermeloen', 'Groen/rood', 8, 'Zomer', '2026-04-26 14:05:00'),
    ('Galia-meloen', 'Geel/groen', 8, 'Zomer', '2026-05-03 16:30:00'),
    ('Cantaloupe', 'Groen/oranje', 8, 'Zomer', '2026-05-11 09:50:00'),
    ('Honingmeloen', 'Geel', 9, 'Zomer', '2026-05-18 08:15:00'),
    ('Papaja', 'Oranje', 7, 'Jaarrond', '2026-05-25 11:40:00'),
    ('Passievrucht', 'Paars/geel', 5, 'Jaarrond', '2026-06-02 14:05:00'),
    ('Granaatappel', 'Rood', 6, 'Herfst', '2026-06-09 16:30:00'),
    ('Vijg', 'Paars', 9, 'Herfst', '2026-06-17 09:50:00'),
    ('Dadel', 'Bruin', 10, 'Herfst', '2026-06-24 08:15:00'),
    ('Kaki', 'Oranje', 9, 'Herfst', '2026-07-01 11:40:00'),
    ('Lychee', 'Rood/wit', 8, 'Jaarrond', '2026-07-09 14:05:00'),
    ('Rambutan', 'Rood', 8, 'Jaarrond', '2026-07-16 16:30:00'),
    ('Longan', 'Bruin/wit', 8, 'Jaarrond', '2026-07-24 09:50:00'),
    ('Dragonfruit', 'Roze/wit', 5, 'Jaarrond', '2026-07-31 08:15:00'),
    ('Guave', 'Groen/roze', 7, 'Jaarrond', '2026-08-07 11:40:00'),
    ('Carambola', 'Geel', 4, 'Jaarrond', '2026-08-15 14:05:00'),
    ('Jackfruit', 'Groen/geel', 8, 'Jaarrond', '2026-08-22 16:30:00'),
    ('Doerian', 'Groen/geel', 7, 'Jaarrond', '2026-08-30 09:50:00'),
    ('Mangosteen', 'Paars/wit', 8, 'Jaarrond', '2026-09-06 08:15:00'),
    ('Cherimoya', 'Groen/wit', 9, 'Jaarrond', '2026-09-13 11:40:00'),
    ('Kweepeer', 'Geel', 3, 'Herfst', '2026-09-21 14:05:00'),
    ('Mirabel', 'Geel', 8, 'Zomer', '2026-09-28 16:30:00'),
    ('Moerbei', 'Paars/zwart', 8, 'Zomer', '2026-10-08 09:50:00');

-- Controleer na de import in de SQL-tab:
-- SELECT COUNT(*) AS total FROM fruits;
-- SELECT * FROM fruits ORDER BY id LIMIT 10;
