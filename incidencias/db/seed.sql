USE incidencias_db;


INSERT INTO users (username, password, role) VALUES
('Admin', MD5('123456'), 'admin'),
('juan',  MD5('abc123'), 'user'),
('maria', MD5('pass123'), 'user');


INSERT INTO incidents (title, description, user_id) VALUES
('Servidor caído', 'El servidor principal dejó de responder a las 3:45 PM', 1),
('Error de login', 'Usuarios no pueden iniciar sesión', 2);
