INSERT INTO users (username, password, email, name, role) VALUES
('alice', '$2y$10$VDSer8nVk6U9lpzgBQs3hOyB32RJO1jTEzh0Byrn4j2AwwfxKoGUe', 'alice@example.com', 'Alice Cooper', 'user'),
('bob',   '$2y$10$e1RQh1RWJIH.Nf5h3N0GIuQ9D5JY0Z0Qo1Kob3nczdvHgP3FVzDFe', 'bob@example.com',   'Bob Smith',   'user'),
('carol', '$2y$10$0hi/WF9lTK7ImXWdAfCNv.DENAzQbycM07PXGUmnKcYBDI4HLjDA6', 'carol@example.com', 'Carol Jones', 'user'),
('dave',  '$2y$10$RbD8Y1MsNztWz6yUbR9F7eyWukfPb3Cr9cH4XuFus1aShHVuDxR/G', 'dave@example.com',  'Dave Grohl',  'user'),
('jarvan', '$2y$10$Qo58TEAo5fP2RyTt.D78KO1nOTuUQkiBt2oAXlIPwvghihd79aRXi', 'admin@example.com', 'Admin User',  'admin');

INSERT INTO categories (name) VALUES
('Graphic Design'),
('Web Development'),
('Content Writing'),
('Digital Marketing'),
('Video Editing'),
('Social Media Management'),
('Logo Design');