INSERT INTO emisores (nombre) VALUES ('Rector');

INSERT INTO usuarios (email, nombre, apellido) VALUES ('pao@pao', 'Pao', 'Santinelli');

INSERT INTO pdfs (contenido, path) VALUES ('Aca va lo mismo que en el pdf', 'Aca esta el archivo');

INSERT INTO documentos (numero_expediente, titulo, descripcion, tipo, fecha_emision, descargable, publico, pdf_id, emisor_id, usuario_id) 
VALUES ('1234', 'ArchivoPrueba', 'no va a haber', 'Normativa', '1212/12/12', true, true, 1, 2, 2);