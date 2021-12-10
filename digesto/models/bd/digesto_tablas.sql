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
    admin   BOOLEAN
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

CREATE TABLE tipos 
(
    tipo_id SERIAL PRIMARY KEY,
    nombre VARCHAR(25) UNIQUE NOT NULL
);

CREATE TABLE documentos
(
    documento_id      SERIAL PRIMARY KEY,
    numero_expediente VARCHAR(25) NOT NULL UNIQUE,
    titulo            VARCHAR(45) NOT NULL,
    descripcion       TEXT,
    fecha_emision     DATE        NOT NULL,
    descargable       BOOLEAN     NOT NULL,
    publico           BOOLEAN     NOT NULL,
    derogado          BOOLEAN     NOT NULL,
    pdf_id            INTEGER     NOT NULL,
    tipo_id           INTEGER     NOT NULL,
    emisor_id         INTEGER     NOT NULL,
    usuario_id        INTEGER             ,
    derogado_id       INTEGER             ,
    CONSTRAINT pdf_fk FOREIGN KEY (pdf_id) REFERENCES pdfs (pdf_id),
    CONSTRAINT tipo_fk FOREIGN KEY (tipo_id) REFERENCES tipos (tipo_id),
    CONSTRAINT emisor_fk FOREIGN KEY (emisor_id) REFERENCES emisores (emisor_id),
    CONSTRAINT derogado_fk FOREIGN KEY (derogado_id) REFERENCES documentos (documento_id),
    CONSTRAINT usuario_fk FOREIGN KEY (usuario_id) REFERENCES usuarios (usuario_id)
    ON UPDATE CASCADE
    ON DELETE SET DEFAULT
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

CREATE TABLE busquedas 
(
    busqueda_id SERIAL PRIMARY KEY,
    texto VARCHAR(255)
);

/*******************/

INSERT INTO permisos (nombre, descripcion)
VALUES ('documentos_create', 'Crear nuevos documentos'),
       ('documentos_update', 'Actualizar documentos'),
       ('documentos_delete', 'Eliminar documentos'),
       ('usuarios_access', 'Permite ver los usuarios'),
       ('usuarios_create', 'Crear nuevos usuarios'),
       ('usuarios_update', 'Actualizar usuarios'),
       ('usuarios_delete', 'Eliminar usuarios');
