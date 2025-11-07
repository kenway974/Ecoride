-- =========================
-- USERS
-- =========================
INSERT INTO "user" (pseudo, email, password_hash, roles, credits, is_active, created_at)
VALUES
('driver_user', 'driver@example.com', '$2y$10$examplehashdriver', '{"ROLE_DRIVER"}', 20, true, NOW()),
('passenger_user', 'passenger@example.com', '$2y$10$examplehashpassenger', '{"ROLE_PASSENGER"}', 20, true, NOW()),
('driver_passenger_user', 'driverpass@example.com', '$2y$10$examplehashdp', '{"ROLE_DRIVER","ROLE_PASSENGER"}', 20, true, NOW());

-- Récupérer les IDs générés (PostgreSQL RETURNING)
-- Si ton outil SQL ne permet pas, tu peux les chercher via SELECT
-- Supposons : driver_user = 1, passenger_user = 2, driver_passenger_user = 3

-- =========================
-- VEHICLE pour driver_user
-- =========================
INSERT INTO vehicle (user_id, plate, brand, model, year, color, energy, seats_total, seats_available, created_at)
VALUES
(1, 'AB-123-CD', 'Peugeot', '208', 2020, 'Bleu', 'gasoline', 4, 4, NOW());

-- =========================
-- PREFERENCE pour driver_user
-- =========================
INSERT INTO preference (user_id, animals, smoke, food, is_custom, created_at)
VALUES
(1, false, false, false, false, NOW());
