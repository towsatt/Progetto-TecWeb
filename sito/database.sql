
CREATE TABLE users (
    nome_utente    VARCHAR(50) PRIMARY KEY,
    email          VARCHAR(255) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    descrizione    TEXT
);


CREATE TABLE campagna (
    id_campagna     VARCHAR(20) PRIMARY KEY,
    password        VARCHAR(20),
    descrizione     TEXT,
    dungeon_master  INTEGER NOT NULL REFERENCES users(id_user),
    pubblica        BOOLEAN DEFAULT FALSE
);


CREATE TABLE partecipanti (
    id_campagna  INTEGER NOT NULL REFERENCES campagna(id_campagna) ON DELETE CASCADE,
    id_user      INTEGER NOT NULL REFERENCES users(id_user)        ON DELETE CASCADE,
    PRIMARY KEY (id_campagna, id_user)
);


CREATE TABLE sessione (
    id_campagna  INTEGER NOT NULL REFERENCES campagna(id_campagna) ON DELETE CASCADE,
    id_sessione  SERIAL,
    riassunto    TEXT,
    PRIMARY KEY (id_campagna, id_sessione)
);


CREATE TABLE logbook (
    id_campagna   INTEGER NOT NULL REFERENCES campagna(id_campagna) ON DELETE CASCADE,
    id_sessione   INTEGER NOT NULL,
    id_user       INTEGER NOT NULL REFERENCES users(id_user)        ON DELETE CASCADE,
    descr_pubblica  TEXT,
    descr_privata   TEXT,
    PRIMARY KEY (id_campagna, id_sessione, id_user),
    FOREIGN KEY (id_campagna, id_sessione)
        REFERENCES sessione(id_campagna, id_sessione)
        ON DELETE CASCADE
);
