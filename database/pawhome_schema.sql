-- ============================================================
--  PawHome Pet Adoption Database Schema
--  Compatible with: MySQL 8.0+ / MariaDB 10.5+
--  Run: mysql -u root -p < pawhome_schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS pawhome CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pawhome;

-- ------------------------------------------------------------
-- 1. USERS
-- ------------------------------------------------------------
CREATE TABLE users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)         NOT NULL,
    email         VARCHAR(180)         NOT NULL UNIQUE,
    password      VARCHAR(255)         NOT NULL,
    role          ENUM('user','admin') NOT NULL DEFAULT 'user',
    phone         VARCHAR(30)          NULL,
    address       TEXT                 NULL,
    avatar        VARCHAR(255)         NULL,
    email_verified_at TIMESTAMP        NULL,
    remember_token VARCHAR(100)        NULL,
    created_at    TIMESTAMP            DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP            DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 2. SHELTERS
-- ------------------------------------------------------------
CREATE TABLE shelters (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)    NOT NULL,
    address     TEXT            NOT NULL,
    city        VARCHAR(100)    NOT NULL,
    province    VARCHAR(100)    NULL,
    phone       VARCHAR(30)     NULL,
    email       VARCHAR(180)    NULL,
    website     VARCHAR(255)    NULL,
    logo        VARCHAR(255)    NULL,
    description TEXT            NULL,
    is_active   TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 3. PETS
-- ------------------------------------------------------------
CREATE TABLE pets (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shelter_id    BIGINT UNSIGNED      NOT NULL,
    name          VARCHAR(80)          NOT NULL,
    species       ENUM('dog','cat','rabbit','bird','other') NOT NULL,
    breed         VARCHAR(100)         NULL,
    gender        ENUM('male','female','unknown') NOT NULL DEFAULT 'unknown',
    age_years     TINYINT UNSIGNED     NOT NULL DEFAULT 0,
    age_months    TINYINT UNSIGNED     NOT NULL DEFAULT 0,
    size          ENUM('small','medium','large','extra_large') NOT NULL DEFAULT 'medium',
    color         VARCHAR(80)          NULL,
    weight_kg     DECIMAL(5,2)         NULL,
    description   TEXT                 NULL,
    health_notes  TEXT                 NULL,
    is_vaccinated     TINYINT(1) NOT NULL DEFAULT 0,
    is_neutered       TINYINT(1) NOT NULL DEFAULT 0,
    is_microchipped   TINYINT(1) NOT NULL DEFAULT 0,
    good_with_kids    TINYINT(1) NOT NULL DEFAULT 1,
    good_with_dogs    TINYINT(1) NOT NULL DEFAULT 1,
    good_with_cats    TINYINT(1) NOT NULL DEFAULT 1,
    status        ENUM('available','pending','adopted','removed') NOT NULL DEFAULT 'available',
    featured      TINYINT(1)           NOT NULL DEFAULT 0,
    cover_image   VARCHAR(255)         NULL,
    created_at    TIMESTAMP            DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP            DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shelter_id) REFERENCES shelters(id) ON DELETE CASCADE,
    INDEX idx_species  (species),
    INDEX idx_status   (status),
    INDEX idx_size     (size),
    INDEX idx_featured (featured)
);

-- ------------------------------------------------------------
-- 4. PET IMAGES
-- ------------------------------------------------------------
CREATE TABLE pet_images (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pet_id     BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255)    NOT NULL,
    caption    VARCHAR(200)    NULL,
    sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- 5. PET TAGS  (e.g. "Playful", "House-trained", "Lap cat")
-- ------------------------------------------------------------
CREATE TABLE tags (
    id   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE pet_tag (
    pet_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (pet_id, tag_id),
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- 6. ADOPTIONS
-- ------------------------------------------------------------
CREATE TABLE adoptions (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pet_id          BIGINT UNSIGNED NOT NULL,
    user_id         BIGINT UNSIGNED NOT NULL,
    shelter_id      BIGINT UNSIGNED NOT NULL,
    -- Applicant details (snapshot in case user updates profile)
    applicant_name  VARCHAR(100)    NOT NULL,
    applicant_email VARCHAR(180)    NOT NULL,
    applicant_phone VARCHAR(30)     NOT NULL,
    address         TEXT            NOT NULL,
    housing_type    ENUM('house','apartment','condo','other') NOT NULL DEFAULT 'house',
    has_yard        TINYINT(1)      NOT NULL DEFAULT 0,
    other_pets      TEXT            NULL,
    reason          TEXT            NOT NULL,
    experience      TEXT            NULL,
    status          ENUM('pending','reviewing','approved','rejected','completed') NOT NULL DEFAULT 'pending',
    admin_notes     TEXT            NULL,
    reviewed_at     TIMESTAMP       NULL,
    reviewed_by     BIGINT UNSIGNED NULL,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pet_id)      REFERENCES pets(id)  ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shelter_id)  REFERENCES shelters(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status  (status),
    INDEX idx_user    (user_id),
    INDEX idx_pet     (pet_id)
);

-- ------------------------------------------------------------
-- 7. FAVORITES
-- ------------------------------------------------------------
CREATE TABLE favorites (
    user_id    BIGINT UNSIGNED NOT NULL,
    pet_id     BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, pet_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id)  REFERENCES pets(id)  ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- 8. CONTACT MESSAGES
-- ------------------------------------------------------------
CREATE TABLE contact_messages (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL,
    subject    VARCHAR(200) NOT NULL,
    message    TEXT         NOT NULL,
    is_read    TINYINT(1)   NOT NULL DEFAULT 0,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
--  SEED DATA
-- ============================================================

INSERT INTO shelters (name, address, city, phone, email, description) VALUES
('Happy Paws Shelter',   'Katipunan Ave, Loyola Heights',  'Quezon City', '02-8123-4567', 'info@happypaws.ph',   'A no-kill shelter serving Metro Manila since 2005.'),
('Second Chance Rescue', '123 Ayala Ave, Makati',          'Makati City', '02-8234-5678', 'rescue@secondchance.ph','Dedicated to rescuing street animals and finding them loving homes.'),
('PAWS Philippines',     'Aurora Blvd, Cubao',             'Quezon City', '02-8345-6789', 'adopt@pawsph.org',    'Philippines Animal Welfare Society — over 30 years of advocacy.');

INSERT INTO tags (name) VALUES
('Friendly'),('Playful'),('Calm'),('Trained'),('Good w/ Kids'),
('Good w/ Dogs'),('Good w/ Cats'),('Indoor'),('Lap Cat'),
('Energetic'),('Shy'),('Loyal'),('Cuddly'),('Vocal'),('Low-maintenance');

INSERT INTO pets (shelter_id, name, species, breed, gender, age_years, age_months, size, color, weight_kg, description, is_vaccinated, is_neutered, good_with_kids, cover_image, status, featured) VALUES
(1, 'Buddy',  'dog',    'Golden Retriever', 'male',   3, 0, 'large',  'Golden',      28.5, 'Buddy is a lovable, energetic Golden Retriever who thrives in active households. He knows basic commands, loves fetch, and adores cuddles after playtime.',           1, 1, 1, 'pets/buddy.jpg',  'available', 1),
(1, 'Luna',   'cat',    'Tabby',            'female', 2, 0, 'small',  'Orange tabby', 3.8, 'Luna is a quiet, affectionate tabby who loves sunny windowsills and gentle strokes. Perfect for apartment living.',                                                    1, 1, 1, 'pets/luna.jpg',   'available', 1),
(2, 'Max',    'dog',    'Labrador Mix',     'male',   5, 0, 'large',  'Chocolate',   30.0, 'Max is a well-trained, gentle lab mix who is great with all ages. He is house-trained, leash-trained, and just looking for a couch to call his own.',                   1, 1, 1, 'pets/max.jpg',    'available', 0),
(2, 'Cleo',   'cat',    'Persian',          'female', 4, 0, 'medium', 'White',        4.2, 'Cleo is a beautiful Persian with a silky coat and a gentle heart. She takes time to warm up but is incredibly loving once she trusts you.',                             1, 0, 0, 'pets/cleo.jpg',   'available', 1),
(3, 'Clover', 'rabbit', 'Holland Lop',      'female', 1, 0, 'small',  'White & grey', 1.8, 'Clover is a curious Holland Lop with floppy ears and a big personality. She loves to explore and binkies when happy.',                                                 1, 1, 1, 'pets/clover.jpg', 'available', 0),
(3, 'Rio',    'bird',   'Sun Conure',       'male',   2, 0, 'small',  'Yellow/green', 0.1, 'Rio is a vibrant Sun Conure who loves to talk and sing. He is hand-tamed and steps up easily — he will brighten any home.',                                            1, 0, 1, 'pets/rio.jpg',    'available', 1),
(1, 'Daisy',  'dog',    'Beagle',           'female', 0, 8, 'medium', 'Tri-color',   7.0,  'Daisy is an adorable Beagle pup with a nose for adventure. Currently in basic training and super food-motivated.',                                                     1, 0, 1, 'pets/daisy.jpg',  'available', 0),
(2, 'Mochi',  'other',  'Hamster',          'male',   0, 6, 'small',  'Golden',       0.12,'Mochi is a fluffy hamster who loves running on his wheel. He is gentle when handled regularly and makes a great starter pet.',                                          0, 0, 1, 'pets/mochi.jpg',  'available', 0);

-- Assign tags to pets (pet_id, tag_id)
INSERT INTO pet_tag (pet_id, tag_id) VALUES
(1,1),(1,2),(1,5),(1,6),  -- Buddy: Friendly, Playful, Good w/ Kids, Good w/ Dogs
(2,3),(2,8),(2,9),         -- Luna: Calm, Indoor, Lap Cat
(3,4),(3,3),(3,11),        -- Max: Trained, Calm, Loyal
(4,3),(4,11),(4,13),       -- Cleo: Calm, Shy, Cuddly
(5,2),(5,10),(5,14),       -- Clover: Playful, Energetic, Vocal
(6,1),(6,14),(6,7),        -- Rio: Friendly, Vocal, Good w/ Cats
(7,2),(7,10),(7,14),       -- Daisy: Playful, Energetic, Vocal
(8,3),(8,15);              -- Mochi: Calm, Low-maintenance

-- Admin user  (password: "password" hashed with bcrypt)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@pawhome.ph', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
