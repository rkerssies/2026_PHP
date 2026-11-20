-- A2.4cd: oefendatabase voor phpMyAdmin (MySQL/MariaDB).
-- fruits: sweetness is een indicatieve score van 1 tot 10.
-- users: password is een SHA1-hash. Admin en user hebben allebei het wachtwoord password.
-- Relatie: één fruitsoort kan favoriet zijn van meerdere gebruikers (one-to-many).
-- favorite_fruit_id is een foreign key naar fruits.id, zodat het ERD in phpMyAdmin de relatie toont.
-- Importeer één keer in een nieuwe oefendatabase. Er worden geen tabellen verwijderd.

CREATE DATABASE IF NOT EXISTS school_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_db;

CREATE TABLE fruits (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    href VARCHAR(190) NOT NULL,
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
-- href wijst naar images/fruits/<fruitnaam>.png. De fruitnaam is klein en een spatie wordt een underscore.
INSERT INTO fruits (name, href, color, sweetness, season, created_at) VALUES
    ('appel', 'images/fruits/appel.png', 'Rood/groen', 6, 'Herfst', '2025-10-08 08:15:00'),
    ('peer', 'images/fruits/peer.png', 'Groen/geel', 7, 'Herfst', '2025-10-15 11:40:00'),
    ('banaan', 'images/fruits/banaan.png', 'Geel', 9, 'Jaarrond', '2025-10-23 14:05:00'),
    ('sinaasappel', 'images/fruits/sinaasappel.png', 'Oranje', 7, 'Winter', '2025-10-30 16:30:00'),
    ('mandarijn', 'images/fruits/mandarijn.png', 'Oranje', 8, 'Winter', '2025-11-07 09:50:00'),
    ('citroen', 'images/fruits/citroen.png', 'Geel', 1, 'Jaarrond', '2025-11-14 08:15:00'),
    ('limoen', 'images/fruits/limoen.png', 'Groen', 1, 'Jaarrond', '2025-11-21 11:40:00'),
    ('grapefruit', 'images/fruits/grapefruit.png', 'Geel/roze', 3, 'Winter', '2025-11-29 14:05:00'),
    ('pomelo', 'images/fruits/pomelo.png', 'Groen/geel', 4, 'Winter', '2025-12-06 16:30:00'),
    ('kumquat', 'images/fruits/kumquat.png', 'Oranje', 4, 'Winter', '2025-12-14 09:50:00'),
    ('aardbei', 'images/fruits/aardbei.png', 'Rood', 8, 'Zomer', '2025-12-21 08:15:00'),
    ('framboos', 'images/fruits/framboos.png', 'Rood', 6, 'Zomer', '2025-12-28 11:40:00'),
    ('braam', 'images/fruits/braam.png', 'Zwart/paars', 6, 'Zomer', '2026-01-05 14:05:00'),
    ('blauwe_bes', 'images/fruits/blauwe_bes.png', 'Blauw', 7, 'Zomer', '2026-01-12 16:30:00'),
    ('rode_bes', 'images/fruits/rode_bes.png', 'Rood', 3, 'Zomer', '2026-01-20 09:50:00'),
    ('zwarte_bes', 'images/fruits/zwarte_bes.png', 'Zwart', 3, 'Zomer', '2026-01-27 08:15:00'),
    ('kruisbes', 'images/fruits/kruisbes.png', 'Groen', 4, 'Zomer', '2026-02-03 11:40:00'),
    ('cranberry', 'images/fruits/cranberry.png', 'Rood', 2, 'Herfst', '2026-02-11 14:05:00'),
    ('druif', 'images/fruits/druif.png', 'Groen/paars', 9, 'Herfst', '2026-02-18 16:30:00'),
    ('kers', 'images/fruits/kers.png', 'Rood', 8, 'Zomer', '2026-02-26 09:50:00'),
    ('pruim', 'images/fruits/pruim.png', 'Paars', 7, 'Zomer', '2026-03-05 08:15:00'),
    ('abrikoos', 'images/fruits/abrikoos.png', 'Oranje', 7, 'Zomer', '2026-03-12 11:40:00'),
    ('perzik', 'images/fruits/perzik.png', 'Oranje/rood', 8, 'Zomer', '2026-03-20 14:05:00'),
    ('nectarine', 'images/fruits/nectarine.png', 'Rood/geel', 8, 'Zomer', '2026-03-27 16:30:00'),
    ('mango', 'images/fruits/mango.png', 'Oranje/groen', 9, 'Jaarrond', '2026-04-04 09:50:00'),
    ('ananas', 'images/fruits/ananas.png', 'Geel/bruin', 8, 'Jaarrond', '2026-04-11 08:15:00'),
    ('kiwi', 'images/fruits/kiwi.png', 'Bruin/groen', 6, 'Winter', '2026-04-18 11:40:00'),
    ('watermeloen', 'images/fruits/watermeloen.png', 'Groen/rood', 8, 'Zomer', '2026-04-26 14:05:00'),
    ('galia-meloen', 'images/fruits/galia-meloen.png', 'Geel/groen', 8, 'Zomer', '2026-05-03 16:30:00'),
    ('cantaloupe', 'images/fruits/cantaloupe.png', 'Groen/oranje', 8, 'Zomer', '2026-05-11 09:50:00'),
    ('honingmeloen', 'images/fruits/honingmeloen.png', 'Geel', 9, 'Zomer', '2026-05-18 08:15:00'),
    ('papaja', 'images/fruits/papaja.png', 'Oranje', 7, 'Jaarrond', '2026-05-25 11:40:00'),
    ('passievrucht', 'images/fruits/passievrucht.png', 'Paars/geel', 5, 'Jaarrond', '2026-06-02 14:05:00'),
    ('granaatappel', 'images/fruits/granaatappel.png', 'Rood', 6, 'Herfst', '2026-06-09 16:30:00'),
    ('vijg', 'images/fruits/vijg.png', 'Paars', 9, 'Herfst', '2026-06-17 09:50:00'),
    ('dadel', 'images/fruits/dadel.png', 'Bruin', 10, 'Herfst', '2026-06-24 08:15:00'),
    ('kaki', 'images/fruits/kaki.png', 'Oranje', 9, 'Herfst', '2026-07-01 11:40:00'),
    ('lychee', 'images/fruits/lychee.png', 'Rood/wit', 8, 'Jaarrond', '2026-07-09 14:05:00'),
    ('rambutan', 'images/fruits/rambutan.png', 'Rood', 8, 'Jaarrond', '2026-07-16 16:30:00'),
    ('longan', 'images/fruits/longan.png', 'Bruin/wit', 8, 'Jaarrond', '2026-07-24 09:50:00'),
    ('dragonfruit', 'images/fruits/dragonfruit.png', 'Roze/wit', 5, 'Jaarrond', '2026-07-31 08:15:00'),
    ('guave', 'images/fruits/guave.png', 'Groen/roze', 7, 'Jaarrond', '2026-08-07 11:40:00'),
    ('carambola', 'images/fruits/carambola.png', 'Geel', 4, 'Jaarrond', '2026-08-15 14:05:00'),
    ('jackfruit', 'images/fruits/jackfruit.png', 'Groen/geel', 8, 'Jaarrond', '2026-08-22 16:30:00'),
    ('doerian', 'images/fruits/doerian.png', 'Groen/geel', 7, 'Jaarrond', '2026-08-30 09:50:00'),
    ('mangosteen', 'images/fruits/mangosteen.png', 'Paars/wit', 8, 'Jaarrond', '2026-09-06 08:15:00'),
    ('cherimoya', 'images/fruits/cherimoya.png', 'Groen/wit', 9, 'Jaarrond', '2026-09-13 11:40:00'),
    ('kweepeer', 'images/fruits/kweepeer.png', 'Geel', 3, 'Herfst', '2026-09-21 14:05:00'),
    ('mirabel', 'images/fruits/mirabel.png', 'Geel', 8, 'Zomer', '2026-09-28 16:30:00'),
    ('moerbei', 'images/fruits/moerbei.png', 'Paars/zwart', 8, 'Zomer', '2026-10-08 09:50:00');

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password CHAR(40) NOT NULL,
    favorite_fruit_id INT UNSIGNED NULL DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    blocked_at DATETIME NULL DEFAULT NULL,
    updated_at DATETIME NULL DEFAULT NULL,
    deleted_at DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email),
    CONSTRAINT fk_users_favorite_fruit
        FOREIGN KEY (favorite_fruit_id) REFERENCES fruits (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wachtwoord van admin en user: password
-- Dummy-wachtwoorden staan als tekst in SHA1('...'), zodat de hash in MySQL wordt gemaakt.
-- Meerdere gebruikers kiezen Appel, zodat de one-to-many per fruit zichtbaar is.
-- Finn Bakker heeft geen favoriet.
INSERT INTO users (name, email, password, favorite_fruit_id, created_at) VALUES
    ('Admin', 'admin@school.nl', SHA1('password'), (SELECT id FROM fruits WHERE name = 'appel' LIMIT 1), '2026-01-06 09:00:00'),
    ('User', 'user@school.nl', SHA1('password'), (SELECT id FROM fruits WHERE name = 'appel' LIMIT 1), '2026-01-20 10:15:00'),
    ('Lisa Berg', 'lisa.berg@example.nl', SHA1('k7mP2q'), (SELECT id FROM fruits WHERE name = 'peer' LIMIT 1), '2026-02-03 08:40:00'),
    ('Omar Demir', 'omar.demir@example.nl', SHA1('wL9nR4'), (SELECT id FROM fruits WHERE name = 'appel' LIMIT 1), '2026-02-18 13:05:00'),
    ('Noor Visser', 'noor.visser@example.nl', SHA1('p3Qx8s'), (SELECT id FROM fruits WHERE name = 'banaan' LIMIT 1), '2026-03-04 11:20:00'),
    ('Bram de Jong', 'bram.dejong@example.nl', SHA1('tY6cH1'), (SELECT id FROM fruits WHERE name = 'appel' LIMIT 1), '2026-03-19 15:45:00'),
    ('Sara El Idrissi', 'sara.elidrissi@example.nl', SHA1('m2Bv7k'), (SELECT id FROM fruits WHERE name = 'sinaasappel' LIMIT 1), '2026-04-02 09:30:00'),
    ('Finn Bakker', 'finn.bakker@example.nl', SHA1('q8Zd3w'), NULL, '2026-04-17 12:10:00'),
    ('Aylin Kaya', 'aylin.kaya@example.nl', SHA1('h4Ns6e'), (SELECT id FROM fruits WHERE name = 'peer' LIMIT 1), '2026-05-06 16:25:00'),
    ('Daan Mulder', 'daan.mulder@example.nl', SHA1('r1Jc9u'), (SELECT id FROM fruits WHERE name = 'mango' LIMIT 1), '2026-05-21 08:55:00'),
    ('Esmee Vos', 'esmee.vos@example.nl', SHA1('b5Wt2y'), (SELECT id FROM fruits WHERE name = 'appel' LIMIT 1), '2026-06-09 14:35:00'),
    ('Milan Hendriks', 'milan.hendriks@example.nl', SHA1('g9Lp4a'), (SELECT id FROM fruits WHERE name = 'kiwi' LIMIT 1), '2026-06-24 10:05:00');

-- Controleer na de import in de SQL-tab:
-- SELECT COUNT(*) AS total FROM fruits;
-- SELECT * FROM fruits ORDER BY id LIMIT 10;
-- SELECT id, name, email, favorite_fruit_id FROM users ORDER BY id;
-- SELECT u.name, u.email FROM users AS u WHERE u.favorite_fruit_id = 1 ORDER BY u.name;
-- SELECT COUNT(*) AS favorite_total FROM users WHERE favorite_fruit_id = 1;
