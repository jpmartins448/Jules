DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS chats;
DROP TABLE IF EXISTS service_images;



-- Create users table
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL, -- stored as hash
  email TEXT NOT NULL,
  name TEXT,
  role TEXT DEFAULT 'user' CHECK(role IN ('user', 'admin'))
);

-- Create table: Categories
CREATE TABLE categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE
);

-- Create table: Services
CREATE TABLE services (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  category_id INTEGER,
  title TEXT NOT NULL,
  description TEXT,
  price REAL NOT NULL,
  delivery_time INTEGER NOT NULL, -- days
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(user_id) REFERENCES users(id),
  FOREIGN KEY(category_id) REFERENCES categories(id)
);

-- Create table: Orders
CREATE TABLE orders (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  service_id INTEGER NOT NULL,
  client_id INTEGER NOT NULL,
  status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'completed')),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(service_id) REFERENCES services(id),
  FOREIGN KEY(client_id) REFERENCES users(id)
);

-- Create table: Ratings (optional)
CREATE TABLE ratings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  service_id INTEGER NOT NULL,
  client_id INTEGER NOT NULL,
  rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(service_id) REFERENCES services(id),
  FOREIGN KEY(client_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS service_images (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  service_id INTEGER NOT NULL,
  image_path TEXT NOT NULL,
  is_primary BOOLEAN DEFAULT 0,
  FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE CASCADE,
  UNIQUE(service_id, image_path)
);

CREATE TABLE IF NOT EXISTS service_videos (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  service_id INTEGER NOT NULL,
  video_path TEXT NOT NULL,
  FOREIGN KEY(service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE chats (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  client_id INTEGER NOT NULL,
  freelancer_id INTEGER NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(client_id, freelancer_id)
);

CREATE TABLE messages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  chat_id INTEGER NOT NULL,
  sender_id INTEGER NOT NULL,
  content TEXT NOT NULL,
  sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  is_read BOOLEAN DEFAULT 0,
  FOREIGN KEY (chat_id) REFERENCES chats(id)
);


