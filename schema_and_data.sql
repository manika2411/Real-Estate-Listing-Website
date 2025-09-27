CREATE DATABASE IF NOT EXISTS estateease_db;
USE estateease_db;

CREATE TABLE IF NOT EXISTS listings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2) NOT NULL,
  city VARCHAR(100),
  state VARCHAR(100),
  postcode VARCHAR(20),
  property_type ENUM('House','Apartment','Unit','Land','Other') DEFAULT 'House',
  bedrooms INT DEFAULT 0,
  bathrooms INT DEFAULT 0,
  area_sq_m INT DEFAULT 0,
  image_url VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO listings (title, description, price, city, state, postcode, property_type, bedrooms, bathrooms, area_sq_m, image_url)
VALUES
('Spacious 3-BHK in Pune', 'A well-maintained family home with a spacious garden and parking.', 12500000.00, 'Pune', 'Maharashtra', '411001', 'House', 3, 2, 180, 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTKywHnTjQj8Tomef8jg17P5qSr6BgB1ix06fK8O3CrzR2cRSsRDvCqj1oIjtrG'),
('Modern 1-BHK Apartment - City View', 'Cozy apartment with a great view and a gym.', 6500000.00, 'Mumbai', 'Maharashtra', '400001', 'Apartment', 1, 1, 55, 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTj0jkD5w8Lea6NJyTnN1YDXsKw1nzUttfaDoTK_QvBzmlgDo9Ly1ooI9lEM3w1'),
('Large Plot of Land - Green Area', 'Ready-to-build residential plot in a quiet, green locality.', 950000.00, 'Indore', 'Madhya Pradesh', '452001', 'Land', 0, 0, 500, 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcS_vljUZhT2WGv14d_TdV3F4cmqIxM5zDAxl1q98wa_YOfNnqxrfO8t36a9PgcL');