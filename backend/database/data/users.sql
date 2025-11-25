-- -------------------------------
-- 1️⃣ Création des utilisateurs
-- -------------------------------
INSERT INTO "user" (email, username, password, roles, credits, is_active, created_at, updated_at)
VALUES
    ('admin@example.com', 'admin', '$2y$12$EXAMPLEHASHEDPASSWORD', ARRAY['ROLE_ADMIN'], 0, true, NOW(), NOW()),
    ('employe@example.com', 'employe', '$2y$12$EXAMPLEHASHEDPASSWORD', ARRAY['ROLE_EMPLOYE'], 0, true, NOW(), NOW()),
    ('chauffeur@example.com', 'chauffeur', '$2y$12$EXAMPLEHASHEDPASSWORD', ARRAY['ROLE_CHAUFFEUR'], 20, true, NOW(), NOW()),
    ('passager@example.com', 'passager', '$2y$12$EXAMPLEHASHEDPASSWORD', ARRAY['ROLE_PASSAGER'], 20, true, NOW(), NOW());

WITH chauffeur AS (
    SELECT id FROM "user" WHERE username = 'chauffeur'
)

INSERT INTO preference (
    user_id,
    animals,
    smoke,
    food,
    is_custom,
    options,
    created_at,
    updated_at
)
SELECT 
    id,
    false, -- animaux autorisés ?
    false, -- fumer autorisé ?
    false, -- nourriture autorisée ?
    false, -- custom
    '[]'::json,
    NOW(),
    NOW()
FROM chauffeur;

-- -------------------------------
-- 3️⃣ Création d'un véhicule pour le chauffeur
-- -------------------------------
INSERT INTO vehicle (
    owner_id,
    plate,
    brand,
    model,
    release_year,
    energy,
    seats_total,
    seats_available,
    created_at,
    updated_at
)
SELECT 
    id,
    'AB-123-CD',
    'Peugeot',
    '208',
    2020,
    'Essence',
    4,
    4,
    NOW(),
    NOW()
FROM chauffeur;
