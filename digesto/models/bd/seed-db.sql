INSERT INTO emisores (nombre)
VALUES ('Consejo superior'),
       ('Rector'),
       ('Tu mama en tanga');

INSERT INTO pdfs (contenido, path)
VALUES ('algo', '/nose/nose/nose'),
       ('algo', '/nose/nose/nose'),
       ('algo', '/nose/nose/nose'),
       ('algo', '/nose/nose/nose');

INSERT INTO usuarios (email, nombre, apellido)
VALUES ('prueba@prueba.com', 'Prueba', 'Prueba'),
       ('prueba2@prueba2.com', 'Prueba 2', 'Prueba 2');

INSERT INTO documentos (numero_expediente, titulo, descripcion, tipo, fecha_emision, descargable, publico, pdf_id,
                        emisor_id, usuario_id)
VALUES ('01/2020', 'Titulo 1', 'Descripcion 1', 'Tipo 1', '2020/01/01', false, true, 1, 1, 1),
       ('02/2020', 'Titulo 2', 'Descripcion 2', 'Tipo 2', '2020/01/02', false, true, 1, 1, 1),
       ('03/2020', 'Titulo 3', 'Descripcion 3', 'Tipo 3', '2020/01/03', false, true, 1, 1, 1),
       ('04/2020', 'Titulo 4', 'Descripcion 4', 'Tipo 4', '2020/01/04', false, true, 1, 1, 1),
       ('05/2020', 'Titulo 5', 'Descripcion 5', 'Tipo 5', '2020/01/05', false, true, 1, 1, 1),
       ('06/2020', 'Titulo 6', 'Descripcion 6', 'Tipo 6', '2020/01/06', false, true, 1, 1, 1),
       ('07/2020', 'Titulo 7', 'Descripcion 7', 'Tipo 7', '2020/01/07', false, true, 1, 1, 1),
       ('08/2020', 'Titulo 8', 'Descripcion 9', 'Tipo 8', '2020/01/08', false, true, 1, 1, 1);

INSERT INTO tags (nombre)
VALUES ('Normativa'),
       ('Informatica'),
       ('Bla bla bla');

INSERT INTO documentos_tags (usuario_id, tag_id) 
VALUES (1 ,1),
	   (1 ,2),
	   (2 ,1),
	   (3 ,1),
   	   (4 ,1),
	   (5 ,1),
	   (6 ,1),
	   (7 ,1),
	   (8 ,1);

INSERT INTO permisos (nombre, descripcion)
VALUES ('usuarios_create', ''),
       ('usuarios_update', ''),
       ('usuarios_delete', ''),
       ('documentos_create', ''),
       ('documentos_update', ''),
       ('documentos_delete', ''),
       ('tags_create', ''),
       ('tags_update', ''),
       ('tags_delete', ''),
       ('emisores_create', ''),
       ('emisores_update', ''),
       ('emisores_delete', '');
   
INSERT INTO usuarios_permisos (usuario_id, permiso_id)
VALUES (3,1),
       (3,2),
       (3,3),
       (3,4),
       (3,5),
       (3,6),
       (3,7),
       (3,8),
       (3,9),
       (3,10),
       (3,11),
       (3,12);