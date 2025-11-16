-- ------------------------------
-- Création de la base PostgreSQL
-- ------------------------------
CREATE DATABASE ecoride_db
WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'fr_FR.UTF-8'
    LC_CTYPE = 'fr_FR.UTF-8'
    TEMPLATE = template0;

\c ecoride_db;

-- ------------------------------
-- TYPES ENUM pour PostgreSQL
-- ------------------------------
CREATE TYPE energy_type AS ENUM ('gasoline','diesel','hybrid','electric','other');
CREATE TYPE trip_status AS ENUM ('planned','ongoing','completed','canceled');
CREATE TYPE reservation_status AS ENUM ('pending','accepted','declined','canceled');
CREATE TYPE review_status AS ENUM ('pending','published','blocked');

-- ------------------------------
-- TABLE user
-- ------------------------------
CREATE TABLE "user" (
    id_user SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    roles JSON NOT NULL,
    bio TEXT,
    phone VARCHAR(20),
    credits INT DEFAULT 20,
    is_active BOOLEAN DEFAULT TRUE,
    photo VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP
);

-- ------------------------------
-- TABLE vehicle
-- ------------------------------
CREATE TABLE vehicle (
    id_vehicle SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES "user"(id_user),
    plate VARCHAR(20) UNIQUE,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    release_year INT,
    color VARCHAR(30),
    energy energy_type NOT NULL,
    seats_total INT NOT NULL,
    seats_available INT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP
);

-- ------------------------------
-- TABLE preference
-- ------------------------------
CREATE TABLE preference (
    id_pref SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE REFERENCES "user"(id_user),
    animals BOOLEAN DEFAULT FALSE,
    smoke BOOLEAN DEFAULT FALSE,
    food BOOLEAN DEFAULT FALSE,
    is_custom BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP
);


-- ------------------------------
-- TABLE trip
-- ------------------------------
CREATE TABLE trip (
    id_trip SERIAL PRIMARY KEY,
    driver_id INT NOT NULL REFERENCES "user"(id_user),
    vehicle_id INT NOT NULL REFERENCES vehicle(id_vehicle),
    address_id INT NOT NULL REFERENCES address(id_address),
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    arrival_date DATE NOT NULL,
    arrival_time TIME NOT NULL,
    price INT NOT NULL,
    seats_total INT NOT NULL,
    seats_remaining INT NOT NULL,
    is_ecological BOOLEAN DEFAULT FALSE,
    status trip_status DEFAULT 'planned',
    description TEXT,
    luggage TEXT,
    start_address TEXT NOT NULL,
    start_city VARCHAR(100) NOT NULL,
    end_address TEXT NOT NULL,
    arrival_city VARCHAR(100) NOT NULL
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP
);

-- ------------------------------
-- TABLE reservation
-- ------------------------------
CREATE TABLE reservation (
    id_reservation SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip(id_trip),
    passenger_id INT NOT NULL REFERENCES "user"(id_user),
    seats_booked INT DEFAULT 1,
    price INT NOT NULL,
    status reservation_status DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP,
    CONSTRAINT uq_res UNIQUE (trip_id, passenger_id)
);

-- ------------------------------
-- TABLE review
-- ------------------------------
CREATE TABLE review (
    id_review SERIAL PRIMARY KEY,
    trip_id INT NOT NULL REFERENCES trip(id_trip),
    author_id INT NOT NULL REFERENCES "user"(id_user),
    driver_id INT NOT NULL REFERENCES "user"(id_user),
    rating INT NOT NULL,
    comment TEXT,
    status review_status DEFAULT 'pending',
    moderated_by INT REFERENCES "user"(id_user),
    moderated_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP
);

-- ------------------------------
-- TABLE contact
-- ------------------------------
CREATE TABLE contact (
    id_msg SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
