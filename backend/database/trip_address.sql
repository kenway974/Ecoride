-- =========================
-- 10 ADRESSES (5 villes différentes)
-- =========================
INSERT INTO address (start_address, start_city, end_address, arrival_city)
VALUES 
('10 Rue de Rivoli', 'Paris', '50 Avenue de Lyon', 'Lyon'),
('20 Boulevard Saint-Germain', 'Paris', '12 Rue de Marseille', 'Marseille'),
('5 Rue Victor Hugo', 'Lyon', '100 Rue de Bordeaux', 'Bordeaux'),
('8 Avenue Jean Jaurès', 'Marseille', '30 Rue de Lille', 'Lille'),
('15 Rue Lafayette', 'Bordeaux', '7 Rue de Nantes', 'Nantes'),
('50 Rue de Lille', 'Lille', '22 Rue de Paris', 'Paris'),
('3 Rue de Nantes', 'Nantes', '40 Avenue de Lyon', 'Lyon'),
('18 Avenue de Bordeaux', 'Bordeaux', '12 Boulevard Saint-Germain', 'Paris'),
('9 Rue de Marseille', 'Marseille', '25 Rue Victor Hugo', 'Lyon'),
('27 Boulevard Saint-Michel', 'Paris', '8 Avenue Jean Jaurès', 'Marseille');

-- =========================
-- 10 TRIPS
-- =========================
INSERT INTO trip (
    driver_id, vehicle_id, departure_date, departure_time, arrival_date, arrival_time,
    price, seats_total, seats_remaining, status, description, luggage,
    created_at, updated_at, address_id
)
VALUES
(23, 4, '2025-11-08', '08:00', '2025-11-08', '12:00', 50, 4, 4, 'planned', 'Trip Paris → Lyon', 'Bagage cabine', NOW(), NOW(), 1),
(23, 4, '2025-11-09', '09:30', '2025-11-09', '13:30', 60, 4, 4, 'planned', 'Trip Paris → Marseille', 'Bagage soute', NOW(), NOW(), 2),
(23, 4, '2025-11-10', '07:00', '2025-11-10', '11:00', 45, 4, 4, 'planned', 'Trip Lyon → Bordeaux', 'Bagage cabine', NOW(), NOW(), 3),
(23, 4, '2025-11-11', '10:00', '2025-11-11', '14:00', 55, 4, 4, 'planned', 'Trip Marseille → Lille', 'Bagage soute', NOW(), NOW(), 4),
(23, 4, '2025-11-12', '06:30', '2025-11-12', '11:00', 70, 4, 4, 'planned', 'Trip Bordeaux → Nantes', 'Bagage cabine', NOW(), NOW(), 5),
(23, 4, '2025-11-13', '08:15', '2025-11-13', '12:15', 65, 4, 4, 'planned', 'Trip Lille → Paris', 'Bagage soute', NOW(), NOW(), 6),
(23, 4, '2025-11-14', '09:00', '2025-11-14', '13:00', 50, 4, 4, 'planned', 'Trip Nantes → Lyon', 'Bagage cabine', NOW(), NOW(), 7),
(23, 4, '2025-11-15', '07:30', '2025-11-15', '12:00', 60, 4, 4, 'planned', 'Trip Bordeaux → Paris', 'Bagage soute', NOW(), NOW(), 8),
(23, 4, '2025-11-16', '08:00', '2025-11-16', '12:30', 55, 4, 4, 'planned', 'Trip Marseille → Lyon', 'Bagage cabine', NOW(), NOW(), 9),
(23, 4, '2025-11-17', '06:45', '2025-11-17', '11:30', 70, 4, 4, 'planned', 'Trip Paris → Marseille', 'Bagage soute', NOW(), NOW(), 10);
