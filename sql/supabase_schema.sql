-- ========================================================
-- Supabase PostgreSQL Database Schema
-- Project: Photography Portfolio & Admin Panel
-- ========================================================

-- 1. Create Categories Table
CREATE TABLE IF NOT EXISTS categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  slug VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 2. Create Photos Table
CREATE TABLE IF NOT EXISTS photos (
  id SERIAL PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  category_id INT NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
  image_path TEXT NOT NULL,
  camera VARCHAR(100) DEFAULT 'Canon EOS R5',
  lens VARCHAR(100) DEFAULT 'RF 24-70mm f/2.8L',
  iso VARCHAR(50) DEFAULT '100',
  shutter_speed VARCHAR(50) DEFAULT '1/250s',
  aperture VARCHAR(50) DEFAULT 'f/2.8',
  location VARCHAR(150) DEFAULT 'Sylhet, Bangladesh',
  is_featured BOOLEAN DEFAULT FALSE,
  views_count INT DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 3. Create Messages Table
CREATE TABLE IF NOT EXISTS messages (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  subject VARCHAR(150),
  message TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Disable Row Level Security (RLS) for public access
ALTER TABLE categories DISABLE ROW LEVEL SECURITY;
ALTER TABLE photos DISABLE ROW LEVEL SECURITY;
ALTER TABLE messages DISABLE ROW LEVEL SECURITY;

-- Seed Default Categories
INSERT INTO categories (id, name, slug) VALUES
(1, 'Nature', 'nature'),
(2, 'Portrait', 'portrait'),
(3, 'Street', 'street'),
(4, 'Landscape', 'landscape'),
(5, 'Architecture', 'architecture'),
(6, 'Wildlife', 'wildlife')
ON CONFLICT (slug) DO NOTHING;

-- Fix identity sequence
SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));

-- Seed Default Photos
INSERT INTO photos (id, title, description, category_id, image_path, camera, lens, iso, shutter_speed, aperture, location, is_featured, views_count) VALUES
(1, 'Golden Hour Mountain Peak', 'A breathtaking view of sunrise over misty mountain ridges.', 4, 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'Sony A7 IV', 'FE 16-35mm f/2.8 GM', '100', '1/500s', 'f/8.0', 'Bandarban, Bangladesh', true, 142),
(2, 'Serene Forest Path', 'Morning light filtering through dense green canopy leaves.', 1, 'https://images.unsplash.com/photo-1448375240586-882707db888b?q=80&w=1200&auto=format&fit=crop', 'Canon EOS R6', 'RF 50mm f/1.2L', '200', '1/160s', 'f/2.0', 'Sreemangal, Sylhet', false, 98),
(3, 'Urban Night Life', 'Vibrant light trails of city traffic under neon lights.', 3, 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1200&auto=format&fit=crop', 'Fujifilm X-T4', 'XF 23mm f/1.4', '800', '2s', 'f/5.6', 'Dhaka City, Bangladesh', true, 215),
(4, 'Expressive Soul Portrait', 'A deep mood portrait capturing natural shadows and emotion.', 2, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1200&auto=format&fit=crop', 'Sony A7R V', 'FE 85mm f/1.4 GM', '100', '1/320s', 'f/1.8', 'Studio Art, Chittagong', true, 310),
(5, 'Modern Glass Facade', 'Geometric symmetry in modern skyscraper architecture.', 5, 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop', 'Nikon Z7 II', 'NIKKOR Z 14-30mm f/4', '100', '1/200s', 'f/7.1', 'Gulshan, Dhaka', false, 75),
(6, 'Wild Kingfisher Focus', 'A colorful kingfisher waiting patiently on a wooden twig.', 6, 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?q=80&w=1200&auto=format&fit=crop', 'Canon EOS R5', 'RF 100-500mm f/4.5-7.1', '1600', '1/2000s', 'f/5.6', 'Sundarbans, Khulna', false, 189)
ON CONFLICT (id) DO NOTHING;

-- Fix identity sequence
SELECT setval('photos_id_seq', (SELECT MAX(id) FROM photos));
