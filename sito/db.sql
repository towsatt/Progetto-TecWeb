drop table if exists Utente CASCADE ;
drop table if exists Campagna CASCADE ;
drop table if exists Membro CASCADE ;
drop table if exists Personaggio CASCADE ;
drop table if exists Sessione CASCADE ;
drop table if exists Progressi CASCADE ;

create table Utente (
    username varchar(255) not null,
    password varchar(255) not null,
    email varchar(255) not null unique,
    profile_picture_dir varchar(255) null,
    primary key (username)
)

create table Campagna (
    nome varchar(255) not null,
    tipologia varchar(50) not null,
    durata varchar(50) not null,
    descrizione text not null,
    dungeon_master varchar(255) not null,
    visibilita boolean not null,
    codice_campagna varchar(255),
    password varchar(255),
    primary key (codice_campagna),
    foreign key (dungeon_master) references Utente(username) on delete cascade
)

create table Membro (
    campagna int not null,
    utente varchar(255) not null,
    primary key (campagna, utente),
    foreign key (campagna) references Campagna(id) on delete cascade,
    foreign key (utente) references Utente(username) on delete cascade
)

create table Personaggio (
    campagna int not null,
    utente varchar(255) not null,
    nome varchar(255) not null,
    classe varchar(255) not null,
    razza varchar(255) not null,
    livello int not null default 1,
    eta int,
    altezza int,
    peso int,
    occhi varchar(255),
    carnagione varchar(255),
    capelli varchar(255),
    aspetto text,
    alleati_organizzazione text,
    storia text,
    descrizione_tesoro text,
    competenze_linguaggi text,
    forza int,
    destrezza int,
    costituzione int,
    intelligenza int,
    saggezza int,
    carisma int,
    ispirazione int,
    bonus_competenza int,
    percezione_passiva int,
    valuta varchar(255),
    tesoro int not null default 0,
    tesoro_iniziale varchar(255),
    classe_armatura varchar(255),
    velocita int,
    dadi_vita varchar(50),
    max_punti_ferita int,
    equipaggiamento text,
    incantesimi text,
    primary key (campagna, utente),
    foreign key (campagna, utente) references Membro(campagna, utente) on delete cascade
)

create table Sessione (
    id serial primary key,
    campagna int not null,
    data timestamp not null,
    descrizione text not null,
    foreign key (campagna) references Campagna(id) on delete cascade
)

create table Progressi (
    campagna int not null,
    utente varchar(255) not null,
    sessione int not null,
    descrizione text not null,
    livello int not null,
    tesoro int not null default 0,
    equipaggiamento text,
    incantesimi text,
    classe_armatura varchar(255),
    velocita int,
    dadi_vita varchar(50),
    max_punti_ferita int,
    forza int,
    destrezza int,
    costituzione int,
    intelligenza int,
    saggezza int,
    carisma int,
    ispirazione int,
    bonus_competenza int,
    percezione_passiva int,
    valuta varchar(255),
    primary key (campagna, utente, sessione),
    foreign key (campagna) references Campagna(id) on delete cascade,
    foreign key (utente) references Utente(username) on delete cascade,
    foreign key (sessione) references Sessione(id) on delete cascade
)