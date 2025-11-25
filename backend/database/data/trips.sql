-- -------------------------------
-- 4️⃣ Création de 3 trips pour le chauffeur
-- -------------------------------
INSERT INTO trip (
    driver_id,
    vehicle_id,
    start_city,
    arrival_city,
    start_address,
    arrival_address,
    departure_date,
    departure_time,
    arrival_date,
    arrival_time,
    seats_remaining,
    price,
    is_ecological,
    description,
    luggage,
    status,
    created_at,
    updated_at
) VALUES
-- Trip 1
(
    3, 1,
    'Paris',
    'Lyon',
    '10 Rue de Rivoli, Paris',
    '5 Place Bellecour, Lyon',
    '2025-11-25',
    '08:00:00',
    '2025-11-25',
    '12:00:00',
    3,
    25.50,
    true,
    'Trip direct, confortable',
    '1 bag maximum',
    'active',
    NOW(),
    NOW()
),
-- Trip 2
(
    3, 1,
    'Lyon',
    'Marseille',
    '3 Quai Saint-Vincent, Lyon',
    '12 Vieux-Port, Marseille',
    '2025-11-26',
    '09:30:00',
    '2025-11-26',
    '14:00:00',
    2,
    30.00,
    false,
    'Voyage agréable avec arrêts possibles',
    '2 bagages maximum',
    'active',
    NOW(),
    NOW()
),
-- Trip 3
(
    3, 1,
    'Marseille',
    'Nice',
    '12 Vieux-Port, Marseille',
    '15 Promenade des Anglais, Nice',
    '2025-11-27',
    '07:45:00',
    '2025-11-27',
    '11:30:00',
    4,
    20.00,
    true,
    'Trip matinal, rapide et écologique',
    '1 bagage',
    'active',
    NOW(),
    NOW()
);
