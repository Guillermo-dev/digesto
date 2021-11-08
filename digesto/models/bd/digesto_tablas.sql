CREATE TABLE permisos
(
    permiso_id  SERIAL PRIMARY KEY,
    nombre      VARCHAR(25) UNIQUE NOT NULL,
    descripcion VARCHAR(255)       NOT NULL
);

CREATE TABLE usuarios
(
    usuario_id SERIAL PRIMARY KEY,
    email      VARCHAR(45) UNIQUE NOT NULL,
    nombre     VARCHAR(45)        NOT NULL,
    apellido   VARCHAR(45)        NOT NULL
);

CREATE TABLE usuarios_permisos
(
    usuario_id INTEGER NOT NULL,
    permiso_id INTEGER NOT NULL,
    CONSTRAINT usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT permiso_fk FOREIGN KEY (permiso_id) REFERENCES permisos (permiso_id),
    CONSTRAINT usuarios_permisos_pk PRIMARY KEY (usuario_id, permiso_id)
);

CREATE TABLE pdfs
(
    pdf_id    SERIAL PRIMARY KEY,
    contenido TEXT         NOT NULL,
    path      VARCHAR(255) NOT NULL
);

CREATE TABLE emisores
(
    emisor_id SERIAL PRIMARY KEY,
    nombre    VARCHAR(25) UNIQUE NOT NULL
);

CREATE TABLE tags
(
    tag_id SERIAL PRIMARY KEY,
    nombre VARCHAR(25) UNIQUE NOT NULL
);

CREATE TABLE documentos
(
    documento_id      SERIAL PRIMARY KEY,
    numero_expediente VARCHAR(25) NOT NULL,
    titulo            VARCHAR(45) NOT NULL,
    descripcion       TEXT,
    tipo              VARCHAR(25) NOT NULL,
    fecha_emision     DATE        NOT NULL,
    descargable       BOOLEAN     NOT NULL,
    publico           BOOLEAN     NOT NULL,
    pdf_id            INTEGER     NOT NULL,
    emisor_id         INTEGER     NOT NULL,
    usuario_id        INTEGER     NOT NULL,
    CONSTRAINT pdf_fk FOREIGN KEY (pdf_id) REFERENCES pdfs (pdf_id),
    CONSTRAINT emisor_fk FOREIGN KEY (emisor_id) REFERENCES emisores (emisor_id),
    CONSTRAINT usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id)
);

CREATE TABLE documentos_tags
(
    documento_id INTEGER NOT NULL,
    tag_id       INTEGER NOT NULL,
    CONSTRAINT documento_fk FOREIGN KEY (documento_id) REFERENCES documentos (documento_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT tag_fk FOREIGN KEY (tag_id) REFERENCES tags (tag_id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT documentos_tags_pk PRIMARY KEY (documento_id, tag_id)
);

/*******************/

INSERT INTO permisos (nombre, descripcion)
VALUES ('documentos_create', ''),
       ('documentos_update', ''),
       ('documentos_delete', ''),
       ('tags_create', ''),
       ('tags_update', ''),
       ('tags_delete', ''),
       ('emisores_create', ''),
       ('emisores_update', ''),
       ('emisores_delete', ''),
       ('usuarios_create', ''),
       ('usuarios_update', ''),
       ('usuarios_delete', '');