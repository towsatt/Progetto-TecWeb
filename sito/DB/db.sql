drop table if exists Progressi;
drop table if exists Sessione;
drop table if exists Personaggio;
drop table if exists Membro;
drop table if exists Campagna;
drop table if exists Utente;

create table Utente (
    username varchar(255) not null,
    password varchar(255) not null,
    email varchar(255) not null unique,
    profile_picture_path varchar(255) null,
    primary key (username)
) ENGINE=InnoDB;

create table Campagna (
    codice_campagna varchar(16) not null,
    nome varchar(255) not null,
    tipologia ENUM('Esistente', 'Originale') not null,
    durata ENUM('One Shot', 'A Sessioni') not null,
    descrizione text not null,
    dungeon_master varchar(255) not null,
    visibilita boolean not null,
    primary key (codice_campagna),
    foreign key (dungeon_master) references Utente(username) on delete cascade
) ENGINE=InnoDB;

create table Membro (
    codice_campagna varchar(16) not null,
    utente varchar(255) not null,
    primary key (codice_campagna, utente),
    foreign key (codice_campagna) references Campagna(codice_campagna) on delete cascade,
    foreign key (utente) references Utente(username) on delete cascade
) ENGINE=InnoDB;

create table Personaggio (
    codice_campagna varchar(16) not null,
    utente varchar(255) not null,
    nome varchar(255) not null,
    classe ENUM('Barbaro', 'Bardo', 'Chierico', 'Druido', 'Guerriero', 'Ladro', 'Mago', 'Monaco', 'Paladino', 'Ranger', 'Sacerdote', 'Warlock') not null,
    razza ENUM('Dragonide', 'Elfo', 'Gnomo', 'Hafling', 'Mezzelfo', 'Mezzorco', 'Tiefling', 'Umano') not null,
    livello int not null default 1,
    eta int,
    altezza int,
    peso int,
    occhi varchar(32),
    carnagione varchar(32),
    capelli varchar(32),
    aspetto text,
    alleati_organizzazione text,
    storia text,
    tesoro text,
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
    classe_armatura varchar(255),
    velocita int,
    dadi_vita varchar(50),
    max_punti_ferita int,
    equipaggiamento text,
    incantesimi text,
    primary key (codice_campagna, utente),
    foreign key (codice_campagna, utente) references Membro(codice_campagna, utente) on delete cascade
) ENGINE=InnoDB;

create table Sessione (
    id INT AUTO_INCREMENT,
    codice_campagna varchar(16) not null,
    data DATETIME not null,
    descrizione text not null,
    primary key (id),
    foreign key (codice_campagna) references Campagna(codice_campagna) on delete cascade
) ENGINE=InnoDB;

create table Progressi (
    codice_campagna varchar(16) not null,
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
    primary key (codice_campagna, utente, sessione),
    foreign key (codice_campagna) references Campagna(codice_campagna) on delete cascade,
    foreign key (utente) references Utente(username) on delete cascade,
    foreign key (sessione) references Sessione(id) on delete cascade
) ENGINE=InnoDB;

-- ============================================================================
-- 1. POPOLAMENTO TABELLA UTENTE (30 Utenti)
-- ============================================================================
INSERT INTO Utente (username, password, email, profile_picture_path) VALUES
('DungeonMaster1', '$2y$10$Nx76vX...', 'dm1@example.com', 'assets/img/avatar/dm1.png'),
('DungeonMaster2', '$2y$10$Nx76vX...', 'dm2@example.com', NULL),
('DungeonMaster3', '$2y$10$Nx76vX...', 'dm3@example.com', 'assets/img/avatar/dm3.png'),
('DungeonMaster4', '$2y$10$Nx76vX...', 'dm4@example.com', NULL),
('DungeonMaster5', '$2y$10$Nx76vX...', 'dm5@example.com', 'assets/img/avatar/dm5.png'),
('Player_Aragorn', '$2y$10$Nx76vX...', 'aragorn@example.com', 'assets/img/avatar/p1.png'),
('Player_Legolas', '$2y$10$Nx76vX...', 'legolas@example.com', NULL),
('Player_Gimli', '$2y$10$Nx76vX...', 'gimli@example.com', NULL),
('Player_Boromir', '$2y$10$Nx76vX...', 'boromir@example.com', 'assets/img/avatar/p4.png'),
('Player_Frodo', '$2y$10$Nx76vX...', 'frodo@example.com', NULL),
('Xandor_The_Great', '$2y$10$Nx76vX...', 'xandor@example.com', NULL),
('Eldrin_Shadow', '$2y$10$Nx76vX...', 'eldrin@example.com', 'assets/img/avatar/p6.png'),
('Thorgar_Iron', '$2y$10$Nx76vX...', 'thorgar@example.com', NULL),
('Lyra_Melody', '$2y$10$Nx76vX...', 'lyra@example.com', NULL),
('Seraphina_Light', '$2y$10$Nx76vX...', 'seraphina@example.com', 'assets/img/avatar/p9.png'),
('Grog_Smash', '$2y$10$Nx76vX...', 'grog@example.com', NULL),
('Varis_Wind', '$2y$10$Nx76vX...', 'varis@example.com', NULL),
('Zephyr_Storm', '$2y$10$Nx76vX...', 'zephyr@example.com', NULL),
('Kaelen_Dark', '$2y$10$Nx76vX...', 'kaelen@example.com', NULL),
('Bryn_Swift', '$2y$10$Nx76vX...', 'bryn@example.com', NULL),
('Sylas_Grey', '$2y$10$Nx76vX...', 'sylas@example.com', NULL),
('Morgana_Le', '$2y$10$Nx76vX...', 'morgana@example.com', NULL),
('Ulfric_Storm', '$2y$10$Nx76vX...', 'ulfric@example.com', NULL),
('Valerie_Val', '$2y$10$Nx76vX...', 'valerie@example.com', NULL),
('Cedric_Bold', '$2y$10$Nx76vX...', 'cedric@example.com', NULL),
('Kira_Nerys', '$2y$10$Nx76vX...', 'kira@example.com', NULL),
('Talon_V', '$2y$10$Nx76vX...', 'talon@example.com', NULL),
('Doran_D', '$2y$10$Nx76vX...', 'doran@example.com', NULL),
('Sariel_Elf', '$2y$10$Nx76vX...', 'sariel@example.com', NULL),
('Brokk_Deep', '$2y$10$Nx76vX...', 'brokk@example.com', NULL);

-- ============================================================================
-- 2. POPOLAMENTO TABELLA CAMPAGNA (5 Campagne)
-- ============================================================================
INSERT INTO Campagna (codice_campagna, nome, tipologia, durata, descrizione, dungeon_master, visibilita) VALUES
('CAMP01_CRIMSON', 'La Maledizione di Crimson', 'Originale', 'A Sessioni', 'Una campagna epica nel continente dimenticato di Crimson Valley.', 'DungeonMaster1', 1),
('CAMP02_DRAGON', 'Il Risveglio dei Draghi', 'Esistente', 'A Sessioni', 'Adattamento ufficiale della celebre avventura sui draghi cromatici.', 'DungeonMaster2', 1),
('CAMP03_UNDER', 'I Segreti del Sottosuolo', 'Originale', 'A Sessioni', 'Esplorazione e sopravvivenza nelle caverne più profonde del mondo.', 'DungeonMaster3', 0),
('CAMP04_SHADOW', 'L''Ombra di Vecna', 'Esistente', 'A Sessioni', 'I giocatori dovranno impedire il ritorno del potente arcilich.', 'DungeonMaster4', 1),
('CAMP05_EBERR', 'Incursione a Sharn', 'Originale', 'A Sessioni', 'Intrighi politici e investigazione cyberpunk-fantasy nella città delle torri.', 'DungeonMaster5', 1);

-- ============================================================================
-- 3. POPOLAMENTO TABELLA MEMBRO (5 Giocatori per Campagna)
-- ============================================================================
-- Campagna 1
INSERT INTO Membro (codice_campagna, utente) VALUES 
('CAMP01_CRIMSON', 'Player_Aragorn'), ('CAMP01_CRIMSON', 'Player_Legolas'), 
('CAMP01_CRIMSON', 'Player_Gimli'), ('CAMP01_CRIMSON', 'Player_Boromir'), ('CAMP01_CRIMSON', 'Player_Frodo');
-- Campagna 2
INSERT INTO Membro (codice_campagna, utente) VALUES 
('CAMP02_DRAGON', 'Xandor_The_Great'), ('CAMP02_DRAGON', 'Eldrin_Shadow'), 
('CAMP02_DRAGON', 'Thorgar_Iron'), ('CAMP02_DRAGON', 'Lyra_Melody'), ('CAMP02_DRAGON', 'Seraphina_Light');
-- Campagna 3
INSERT INTO Membro (codice_campagna, utente) VALUES 
('CAMP03_UNDER', 'Grog_Smash'), ('CAMP03_UNDER', 'Varis_Wind'), 
('CAMP03_UNDER', 'Zephyr_Storm'), ('CAMP03_UNDER', 'Kaelen_Dark'), ('CAMP03_UNDER', 'Bryn_Swift');
-- Campagna 4
INSERT INTO Membro (codice_campagna, utente) VALUES 
('CAMP04_SHADOW', 'Sylas_Grey'), ('CAMP04_SHADOW', 'Morgana_Le'), 
('CAMP04_SHADOW', 'Ulfric_Storm'), ('CAMP04_SHADOW', 'Valerie_Val'), ('CAMP04_SHADOW', 'Cedric_Bold');
-- Campagna 5
INSERT INTO Membro (codice_campagna, utente) VALUES 
('CAMP05_EBERR', 'Kira_Nerys'), ('CAMP05_EBERR', 'Talon_V'), 
('CAMP05_EBERR', 'Doran_D'), ('CAMP05_EBERR', 'Sariel_Elf'), ('CAMP05_EBERR', 'Brokk_Deep');

-- ============================================================================
-- 4. POPOLAMENTO TABELLA PERSONAGGIO (5 Personaggi per Campagna, Livello iniziale 1)
-- ============================================================================
-- Campagna 1
INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES
('CAMP01_CRIMSON', 'Player_Aragorn', 'Thorin Scudoferro', 'Guerriero', 'Umano', 1, 35, 180, 85, 'Azzurri', 'Chiara', 'Neri', 'Fiero e robusto', 'Alleanza del Nord', 'Cresciuto nelle terre selvagge...', 'Poche monete d''argento', 'Comune, Nanico', 16, 12, 15, 10, 14, 11, 0, 2, 12, '10 MO', '16', 9, '1D10', 12, 'Spada lunga, Scudo, Cotta di maglia', NULL),
('CAMP01_CRIMSON', 'Player_Legolas', 'Aelir Windrunner', 'Ranger', 'Elfo', 1, 120, 185, 70, 'Verdi', 'Pallida', 'Biondi', 'Slanciato ed elegante', 'Guardiani dei Boschi', 'Esiliato dal suo regno natale...', 'Anello d''argento antico', 'Comune, Elfico', 11, 16, 13, 12, 14, 10, 0, 2, 14, '15 MO', '14', 11, '1D10', 11, 'Arco lungo, 20 Frecce, Armatura di cuoio', NULL),
('CAMP01_CRIMSON', 'Player_Gimli', 'Gimli Figlio di Gloin', 'Barbaro', 'Mezzorco', 1, 28, 145, 90, 'Scuri', 'Grigiastra', 'Rossi', 'Tarchiato e furioso', 'Clan Spaccacrani', 'Cacciato dal clan per insubordinazione...', 'Collana d''ossi', 'Comune, Orchesco', 17, 13, 16, 8, 10, 8, 0, 2, 10, '5 MO', '13', 9, '1D12', 15, 'Ascia bipenne, Abiti da viaggio', NULL),
('CAMP01_CRIMSON', 'Player_Boromir', 'Valen il Pio', 'Paladino', 'Umano', 1, 29, 178, 80, 'Marroni', 'Dorata', 'Castani', 'Sguardo risoluto e armatura lucente', 'Ordine del Sole', 'Cavaliere errante in cerca di redenzione...', 'Simbolo sacro d''oro', 'Comune, Celestiale', 15, 10, 14, 10, 12, 15, 0, 2, 11, '25 MO', '18', 9, '1D10', 12, 'Spadone, Armatura a piastre', NULL),
('CAMP01_CRIMSON', 'Player_Frodo', 'Eldrin delle Ombre', 'Ladro', 'Hafling', 1, 22, 110, 35, 'Neri', 'Ambrata', 'Ricci Castani', 'Piccolo e scaltro, difficile da notare', 'Gilda dei Ladri di Crimson', 'Orfano cresciuto nei bassifondi...', 'Pietra fortunata', 'Comune, Furfantesco', 10, 17, 12, 13, 10, 14, 0, 2, 12, '30 MO', '14', 8, '1D8', 9, 'DUE Pugnali, Attrezzi da scassinatore', NULL);

-- Campagna 2
INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES
('CAMP02_DRAGON', 'Xandor_The_Great', 'Malar il Grigio', 'Mago', 'Umano', 1, 60, 175, 68, 'Grigi', 'Rugosa', 'Bianchi', 'Anziano curvo con una lunga barba', 'Accademia degli Arcanisti', 'Ha studiato i tomi proibiti per decenni...', 'Pergamena antica', 'Comune, Draconico, Elfico', 8, 12, 13, 16, 14, 10, 0, 2, 12, '12 MO', '11', 9, '1D6', 7, 'Bastone magico, Libro degli incantesimi', 'Dardo Incantato, Mani Brucianti, Scudo'),
('CAMP02_DRAGON', 'Eldrin_Shadow', 'Keth Varis', 'Bardo', 'Mezzelfo', 1, 24, 176, 70, 'Nocciola', 'Chiara', 'Neri lunghi', 'Fascino magnetico, sorriso beffardo', 'Compagnia del Menestrello', 'Gira le locande raccogliendo segreti...', 'Liuto finemente lavorato', 'Comune, Elfico, Goblin', 10, 14, 12, 12, 10, 16, 0, 2, 12, '20 MO', '13', 9, '1D8', 9, 'Stocco, Liuto, Zaino da diplomatico', 'Parola Guaritrice, Risata Incontrastabile'),
('CAMP02_DRAGON', 'Thorgar_Iron', 'Thorgar Spaccapietre', 'Chierico', 'Gnomo', 1, 75, 105, 42, 'Ambra', 'Rocciosa', 'Grigi', 'Aspetto robusto, indossa paramenti sacri', 'Chiesa della Terra Madre', 'Sacerdote del clan dei minatori...', 'Reliquia di pietra', 'Comune, Gnomesco', 14, 10, 15, 11, 16, 11, 0, 2, 13, '10 MO', '16', 8, '1D8', 10, 'Mazza, Scudo, Cotta di maglia', 'Curare Ferite, Dardo Guidato'),
('CAMP02_DRAGON', 'Lyra_Melody', 'Sariel Luna', 'Druido', 'Elfo', 1, 90, 170, 55, 'Smeraldo', 'Pallidissima', 'Verdi argentei', 'Fusa con la natura circostante', 'Cerchio della Luna', 'Cresciuta tra i lupi delle foreste sacre...', 'Erbe medicinali rari', 'Comune, Elfico, Silvano', 10, 14, 13, 12, 16, 10, 0, 2, 13, '11 MO', '13', 11, '1D8', 9, 'Scimitarra, Scudo di legno', 'Onda Tonante, Intralciare'),
('CAMP02_DRAGON', 'Seraphina_Light', 'Ignis il Corrotto', 'Warlock', 'Tiefling', 1, 19, 172, 63, 'Rossi luminosi', 'Rossa', 'Neri corvini', 'Corna pronunciate, aria misteriosa', 'Patto dell''Abisso', 'Ha venduto l''anima per salvare la propria pelle...', 'Moneta nera incisa', 'Comune, Infernale', 9, 13, 14, 11, 10, 16, 0, 2, 10, '15 MO', '12', 9, '1D8', 10, 'Pugnale, Bastone, Armatura di cuoio', 'Deflagrazione Occulta, Intralcio di Hadar');

-- Campagna 3
INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES
('CAMP03_UNDER', 'Grog_Smash', 'Krush l''Imbattuto', 'Barbaro', 'Mezzorco', 1, 23, 198, 115, 'Gialli', 'Verdastra', 'Nessuno', 'Cicatrice profonda sul volto, mastodontico', 'Gladiatori delle Sabbie', 'Fuggito da un''arena di combattimento...', 'Bracciali di bronzo', 'Comune, Orchesco', 17, 14, 15, 8, 10, 9, 0, 2, 10, '0 MO', '14', 9, '1D12', 14, 'Ascia bipenne, Giavellotti', NULL),
('CAMP03_UNDER', 'Varis_Wind', 'Zephyr Silente', 'Monaco', 'Umano', 1, 25, 175, 65, 'Marroni', 'Abbronzata', 'Rasati neri', 'Calmo, movimenti fluidi e felini', 'Monastero del Vento Calmo', 'Ha passato l''infanzia meditando sulle montagne...', 'Ciotola di legno', 'Comune', 12, 16, 14, 10, 15, 9, 0, 2, 12, '2 MO', '15', 12, '1D8', 10, 'Bastone ferrato, dardi', NULL),
('CAMP03_UNDER', 'Zephyr_Storm', 'Bram Spaccascudi', 'Guerriero', 'Dragonide', 1, 21, 205, 125, 'Dorati', 'Squame Dorate', 'Nessuno', 'Imponente e fiero, testa leonina draconica', 'Esercito del Re di Ferro', 'Soldato mercenario in cerca di contratti...', 'Medaglia al valore', 'Comune, Draconico', 16, 11, 14, 10, 12, 12, 0, 2, 11, '10 MO', '16', 9, '1D10', 12, 'Alabarda, Cotta di maglia', NULL),
('CAMP03_UNDER', 'Kaelen_Dark', 'Norin l''Astuto', 'Ladro', 'Gnomo', 1, 45, 95, 30, 'Azzurri', 'Chiara', 'Bianchi sprizzanti', 'Sguardo vispo, mani agilissime', 'Nessuna', 'Falsario di professione, in fuga dai creditori...', 'Dadi truccati', 'Comune, Gnomesco, Furfantesco', 8, 16, 12, 14, 11, 13, 0, 2, 11, '45 MO', '14', 8, '1D8', 9, 'Stocco, Arco corto, Attrezzi da ladro', NULL),
('CAMP03_UNDER', 'Bryn_Swift', 'Relia dei Boschi', 'Chierico', 'Elfo', 1, 110, 168, 52, 'Verdi', 'Chiara', 'Biondi argentei', 'Sguardo sereno, veste candida', 'Santuario della Luce Nuova', 'Mandata in missione per curare la piaga...', 'Acqua santa', 'Comune, Elfico', 12, 12, 13, 10, 16, 12, 0, 2, 13, '15 MO', '15', 9, '1D8', 9, 'Mazza ferrata, Armatura di scaglie', 'Dardo Guidato, Santuario');

-- Campagna 4
INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES
('CAMP04_SHADOW', 'Sylas_Grey', 'Lord Corvus', 'Warlock', 'Umano', 1, 32, 182, 74, 'Neri', 'Estremamente pallida', 'Neri corti', 'Elegante ma inquietante, indossa un mantello scuro', 'Culto dell''Ombra', 'Nobilsangue decaduto che ha stretto un patto...', 'Sigillo nobiliare', 'Comune, Abissale', 10, 13, 12, 14, 10, 16, 0, 2, 10, '100 MO', '12', 9, '1D8', 9, 'Pugnale, Tomo d''Ombra, Abiti pregiati', 'Deflagrazione Occulta, Braccia di Hadar'),
('CAMP04_SHADOW', 'Morgana_Le', 'Morgana la Rossa', 'Mago', 'Tiefling', 1, 26, 165, 58, 'Viola', 'Porpora', 'Rossi accesi', 'Sguardo penetrante, piccoli tatuaggi runici', 'Torre dell''Alta Stregoneria', 'Fuggita dall''accademia dopo un esperimento fallito...', 'Cristallo arcano', 'Comune, Infernale', 8, 14, 12, 16, 11, 13, 0, 2, 11, '20 MO', '12', 9, '1D6', 7, 'Pugnale, Focus Arcano, Borsa dei componenti', 'Mani Brucianti, Disco Fluttuante'),
('CAMP04_SHADOW', 'Ulfric_Storm', 'Ulfric il Duro', 'Barbaro', 'Umano', 1, 40, 188, 98, 'Grigi', 'Rudere dal sole', 'Castani lunghi e incolti', 'Massiccio, pieno di cicatrici di vecchie battaglie', 'Tribu del Lupo Grigio', 'Ultimo sopravvissuto della sua tribù sterminata...', 'Dente di lupo gigante', 'Comune', 16, 13, 15, 9, 12, 8, 0, 2, 11, '5 MO', '13', 9, '1D12', 14, 'Spadone, 4 Assi da lancio', NULL),
('CAMP04_SHADOW', 'Valerie_Val', 'Enya Cantoferro', 'Bardo', 'Mezzelfo', 1, 21, 162, 50, 'Nocciola', 'Chiara', 'Castani ramati', 'Dolce e aggraziata, sempre con lo strumento in mano', 'Gilda dei Giullari di Corte', 'Cacciata dalla corte reale dopo una ballata satirica...', 'Flauto d''avorio', 'Comune, Elfico', 10, 15, 12, 11, 10, 16, 0, 2, 12, '15 MO', '13', 9, '1D8', 9, 'Stocco, Flauto, Zaino da intrattenitore', 'Luminescenza, Parola Guaritrice'),
('CAMP04_SHADOW', 'Cedric_Bold', 'Cedric il Coraggioso', 'Paladino', 'Umano', 1, 25, 183, 84, 'Azzurri', 'Chiara', 'Biondi corti', 'Fisico atletico, portamento militare fiero', 'Ordine dei Cavalieri d''Argento', 'Giurato a difendere i deboli dal male...', 'Scudo inciso con un leone', 'Comune', 15, 10, 14, 10, 12, 14, 0, 2, 11, '10 MO', '18', 9, '1D10', 12, 'Spada lunga, Scudo, Cotta di maglia', 'Punizione Divina');

-- Campagna 5
INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES
('CAMP05_EBERR', 'Kira_Nerys', 'Vondal Deepdelve', 'Guerriero', 'Dragonide', 1, 52, 138, 72, 'Neri', 'Squame Nere', 'Nessuno', 'Sguardo truce, fuma sempre la pipa', 'Consorzio dei Minatori', 'Soldato di ventura con un passato oscuro...', 'Pipa d''oro', 'Comune, Nanico', 16, 10, 16, 11, 12, 10, 0, 2, 11, '50 MO', '17', 8, '1D10', 13, 'Ascia da guerra, Scudo, Cotta di maglia', NULL),
('CAMP05_EBERR', 'Talon_V', 'Talon Hawk', 'Ranger', 'Umano', 1, 27, 180, 76, 'Gialli', 'Abbronzata', 'Falco neri', 'Sguardo vigile da predatore, vestito di pelli', 'Scout del Confine', 'Cacciatore di taglie specializzato in mostri...', 'Piuma di grifone', 'Comune, Orchesco', 12, 16, 13, 10, 14, 10, 0, 2, 14, '15 MO', '14', 9, '1D10', 11, 'Arco lungo, 2 Spade corte', NULL),
('CAMP05_EBERR', 'Doran_D', 'Doran Fireforge', 'Sacerdote', 'Umano', 1, 33, 179, 82, 'Castani', 'Chiara', 'Rossi corti', 'Aspetto sereno e rassicurante, grandi mani calde', 'Chiesa della Luce Interiore', 'Inviato in città per fondare un nuovo ospizio...', 'Rosario di legno sacro', 'Comune, Elfico', 13, 10, 14, 11, 16, 12, 0, 2, 13, '20 MO', '16', 9, '1D8', 10, 'Mazza ferrata, Armatura a scaglie', 'Cura Ferite, Luce'),
('CAMP05_EBERR', 'Sariel_Elf', 'Sariel Whisper', 'Monaco', 'Elfo', 1, 84, 172, 53, 'Grigi', 'Pallida', 'Bianchi lunghi', 'Estremamente agile, quasi eterea', 'Ordine dell''Ombra Danzante', 'Cresciuta in clausura per padroneggiare il Ki...', 'Cintura ricamata', 'Comune, Elfico', 11, 16, 12, 12, 15, 11, 0, 2, 12, '8 MO', '15', 11, '1D8', 9, 'Dardi, Abiti da monaco', NULL),
('CAMP05_EBERR', 'Brokk_Deep', 'Jax il Furtivo', 'Ladro', 'Tiefling', 1, 23, 174, 68, 'Oro completi', 'Bluastra', 'Neri lunghi legati', 'Sguardo sornione, dita lunghe e affusolate', 'Sindacato criminale Boromar', 'Cresciuto rubando sui tetti di Sharn...', 'Moneta contraffatta', 'Comune, Infernale, Furfantesco', 10, 16, 12, 13, 10, 14, 0, 2, 12, '35 MO', '14', 9, '1D8', 9, 'Due stocchi, Attrezzi da scassinatore', NULL);

-- ============================================================================
-- 5. POPOLAMENTO TABELLA SESSIONE (Da 1 a 3 Sessioni per Campagna)
-- ============================================================================
-- Campagna 1 (3 Sessioni)
INSERT INTO Sessione (id, codice_campagna, data, descrizione) VALUES
(1, 'CAMP01_CRIMSON', '2026-05-01 20:30:00', 'Sessione 1: Il ritrovo nella Locanda del Cinghiale Alato e l''inizio del viaggio.'),
(2, 'CAMP01_CRIMSON', '2026-05-08 20:30:00', 'Sessione 2: L''imboscata dei Goblin nella foresta e il ritrovamento della mappa.'),
(3, 'CAMP01_CRIMSON', '2026-05-15 20:30:00', 'Sessione 3: L''ingresso nelle Rovine del Tempio Crimson e la sconfitta del capo goblin.');

-- Campagna 2 (2 Sessioni)
INSERT INTO Sessione (id, codice_campagna, data, descrizione) VALUES
(4, 'CAMP02_DRAGON', '2026-05-03 21:00:00', 'Sessione 1: Attacco dei cultisti del drago al villaggio di Greenest.'),
(5, 'CAMP02_DRAGON', '2026-05-10 21:00:00', 'Sessione 2: Infiltrazione nell''accampamento dei cultisti per salvare l''ostaggio.');

-- Campagna 3 (2 Sessioni)
INSERT INTO Sessione (id, codice_campagna, data, descrizione) VALUES
(6, 'CAMP03_UNDER', '2026-05-02 15:00:00', 'Sessione 1: Il crollo della miniera e la caduta involontaria nel Sottosuolo.'),
(7, 'CAMP03_UNDER', '2026-05-16 15:00:00', 'Sessione 2: Il primo incontro con una pattuglia Drow e la fuga nei cunicoli.');

-- Campagna 4 (3 Sessioni)
INSERT INTO Sessione (id, codice_campagna, data, descrizione) VALUES
(8, 'CAMP04_SHADOW', '2026-05-04 20:00:00', 'Sessione 1: Furto della reliquia sacra dal museo cittadino da parte di ignoti.'),
(9, 'CAMP04_SHADOW', '2026-05-11 20:00:00', 'Sessione 2: Investigazione nei bassifondi e inseguimento sui tetti della città.'),
(10, 'CAMP04_SHADOW', '2026-05-18 20:00:00', 'Sessione 3: Assalto al covo sotterraneo del culto macabro.');

-- Campagna 5 (2 Sessioni)
INSERT INTO Sessione (id, codice_campagna, data, descrizione) VALUES
(11, 'CAMP05_EBERR', '2026-05-05 21:15:00', 'Sessione 1: Omicidio sul treno fulmine in arrivo a Sharn.'),
(12, 'CAMP05_EBERR', '2026-05-19 21:15:00', 'Sessione 2: Interrogatorio dei sospettati nei quartieri alti della città.');

-- ============================================================================
-- 6. POPOLAMENTO TABELLA PROGRESSI
-- (Solo dalle sessioni successive alla prima di ogni campagna. Qualche assente simulato)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- CAMPAGNA 1 - SESSIONE 2 (ID: 2) -> Tutti presenti, salgono a Livello 2
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP01_CRIMSON', 'Player_Aragorn', 2, 'Ottenuto equipaggiamento dai goblin. Maggiore robustezza.', 2, 10, 'Spada lunga, Scudo, Cotta di maglia, Mantello Goblin', NULL, '16', 9, '2D10', 22, 16, 12, 15, 10, 14, 11, 0, 2, 12, '25 MO'),
('CAMP01_CRIMSON', 'Player_Legolas', 2, 'Raccolte frecce perfette. Migliorata percezione boschiva.', 2, 5, 'Arco lungo, 30 Frecce, Armatura di cuoio', NULL, '14', 11, '2D10', 20, 11, 16, 13, 12, 14, 10, 1, 2, 14, '28 MO'),
('CAMP01_CRIMSON', 'Player_Gimli', 2, 'La furia aumenta nei combattimenti ravvicinati.', 2, 2, 'Ascia bipenne, Abiti da viaggio', NULL, '13', 9, '2D12', 28, 17, 13, 16, 8, 10, 8, 0, 2, 10, '12 MO'),
('CAMP01_CRIMSON', 'Player_Boromir', 2, 'Benedizione divina ricevuta per aver protetto il gruppo.', 2, 15, 'Spadone, Armatura a piastre, Collana solare', NULL, '18', 9, '2D10', 22, 15, 10, 14, 10, 12, 15, 0, 2, 11, '45 MO'),
('CAMP01_CRIMSON', 'Player_Frodo', 2, 'Trovato un set di grimaldelli di ottima fattura.', 2, 20, 'Due Pugnali, Attrezzi da scassinatore avanzati', NULL, '14', 8, '2D8', 16, 10, 17, 12, 13, 10, 14, 0, 2, 12, '65 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 1 - SESSIONE 3 (ID: 3) -> RARA ASSENZA: Player_Frodo è assente
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP01_CRIMSON', 'Player_Aragorn', 3, 'Sconfitto il capo goblin, presa la sua spada corta.', 2, 50, 'Spada lunga, Scudo, Cotta di maglia, Spada corta +1', NULL, '16', 9, '2D10', 22, 16, 12, 15, 10, 14, 11, 1, 2, 12, '75 MO'),
('CAMP01_CRIMSON', 'Player_Legolas', 3, 'Trovato amuleto elfico tra i tesori saccheggiati.', 2, 50, 'Arco lungo, 25 Frecce, Armatura di cuoio, Amuleto Antico', NULL, '14', 11, '2D10', 20, 11, 16, 13, 12, 14, 10, 0, 2, 15, '78 MO'),
('CAMP01_CRIMSON', 'Player_Gimli', 3, 'Ha preso un elmo pesante dalle rovine del tempio.', 2, 30, 'Ascia bipenne, Elmo pesante del tempio', NULL, '14', 9, '2D12', 28, 17, 13, 16, 8, 10, 8, 0, 2, 10, '42 MO'),
('CAMP01_CRIMSON', 'Player_Boromir', 3, 'Donati fondi al tempio distrutto, lo spirito è saldo.', 2, 0, 'Spadone, Armatura a piastre', NULL, '18', 9, '2D10', 22, 15, 10, 14, 10, 12, 15, 1, 2, 11, '15 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 2 - SESSIONE 2 (ID: 5) -> Tutti presenti, salgono a Livello 2
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP02_DRAGON', 'Xandor_The_Great', 5, 'Apprese nuove formule mistiche dai tomi dei cultisti.', 2, 15, 'Bastone magico, Libro degli incantesimi espanso', 'Dardo Incantato, Mani Brucianti, Scudo, Immagine Speculare', '11', 9, '2D6', 13, 8, 12, 13, 16, 14, 10, 0, 2, 12, '27 MO'),
('CAMP02_DRAGON', 'Eldrin_Shadow', 5, 'Ha composto una canzone sulla ritirata dei nemici.', 2, 25, 'Stocco, Liuto d''argento, Zaino da diplomatico', 'Parola Guaritrice, Risata Incontrastabile, Suggestione', '13', 9, '2D8', 16, 10, 14, 12, 12, 10, 16, 1, 2, 12, '45 MO'),
('CAMP02_DRAGON', 'Thorgar_Iron', 5, 'Consacrato il terreno profanato del villaggio.', 2, 10, 'Mazza, Scudo, Cotta di maglia, Reliquia restaurata', 'Curare Ferite, Dardo Guidato, Arma Spirituale', '16', 8, '2D8', 17, 14, 10, 15, 11, 16, 11, 0, 2, 13, '20 MO'),
('CAMP02_DRAGON', 'Lyra_Melody', 5, 'Sintonizzata con gli spiriti degli animali della foresta.', 2, 5, 'Scimitarra, Scudo di legno foderato', 'Onda Tonante, Intralciare, Crescita di Spine', '13', 11, '2D8', 16, 10, 14, 13, 12, 16, 10, 0, 2, 13, '16 MO'),
('CAMP02_DRAGON', 'Seraphina_Light', 5, 'Il Patrono richiede più sacrifici ma concede più potere.', 2, 35, 'Pugnale, Bastone vitreo, Armatura di cuoio borchiata', 'Deflagrazione Occulta, Intralcio di Hadar, Passo Passo', '13', 9, '2D8', 18, 9, 13, 14, 11, 10, 16, 0, 2, 10, '50 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 3 - SESSIONE 2 (ID: 7) -> RARA ASSENZA: Grog_Smash è assente
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP03_UNDER', 'Varis_Wind', 7, 'Padronanza dei movimenti acrobatici nell''oscurità.', 1, 5, 'Bastone ferrato, dardi, bende da combattimento', NULL, '15', 13, '1D8', 10, 12, 16, 14, 10, 15, 9, 1, 2, 12, '7 MO'),
('CAMP03_UNDER', 'Zephyr_Storm', 7, 'Recuperate scorte di metallo elfico scuro.', 1, 20, 'Alabarda, Cotta di maglia graffiata', NULL, '16', 9, '1D10', 12, 16, 11, 14, 10, 12, 12, 0, 2, 11, '30 MO'),
('CAMP03_UNDER', 'Kaelen_Dark', 7, 'Rubata una fiala di veleno drow durante lo scontro.', 1, 40, 'Stocco, Arco corto, Attrezzi da ladro, Veleno Drow', NULL, '14', 8, '1D8', 9, 8, 16, 12, 14, 11, 13, 0, 2, 11, '85 MO'),
('CAMP03_UNDER', 'Bryn_Swift', 7, 'Preghiere sussurrate nel buio mantengono alta la fede.', 1, 10, 'Mazza ferrata, Armatura di scaglie, Simbolo luminoso', 'Dardo Guidato, Santuario', '16', 9, '1D8', 9, 12, 12, 13, 10, 16, 12, 0, 2, 13, '25 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 4 - SESSIONE 2 (ID: 9) -> Tutti presenti, livello base 1 avanzato
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP04_SHADOW', 'Sylas_Grey', 9, 'Ottenuti indizi importanti sul ladro di reliquie.', 1, 10, 'Pugnale, Tomo d''Ombra, Abiti pregiati', 'Deflagrazione Occulta, Braccia di Hadar', '12', 9, '1D8', 9, 10, 13, 12, 14, 10, 16, 0, 2, 10, '110 MO'),
('CAMP04_SHADOW', 'Morgana_Le', 9, 'Raccolto un reagente alchemico volatile.', 1, 5, 'Pugnale, Focus Arcano, Reagenti rari', 'Mani Brucianti, Disco Fluttuante', '12', 9, '1D6', 7, 8, 14, 12, 16, 11, 13, 0, 2, 11, '25 MO'),
('CAMP04_SHADOW', 'Ulfric_Storm', 9, 'Ha placcato un sospettato rompendogli un braccio.', 1, 2, 'Spadone, 3 Assi da lancio', NULL, '13', 9, '1D12', 14, 16, 13, 15, 9, 12, 8, 1, 2, 11, '7 MO'),
('CAMP04_SHADOW', 'Valerie_Val', 9, 'Ha affascinato la guardia cittadina per estorcere informazioni.', 1, 15, 'Stocco, Flauto, Mantello della Gilda', 'Luminescenza, Parola Guaritrice', '13', 9, '1D8', 9, 10, 15, 12, 11, 10, 16, 0, 2, 12, '30 MO'),
('CAMP04_SHADOW', 'Cedric_Bold', 9, 'Garantito il salvataggio di un civile innocente durante l''inseguimento.', 1, 0, 'Spada lunga, Scudo, Cotta di maglia', 'Punizione Divina', '18', 9, '1D10', 12, 15, 10, 14, 10, 12, 14, 0, 2, 11, '10 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 4 - SESSIONE 3 (ID: 10) -> Tutti presenti, avanzamento a Livello 2
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP04_SHADOW', 'Sylas_Grey', 10, 'Assorbita energia oscura dal covo del culto.', 2, 100, 'Pugnale, Tomo d''Ombra, Pergamena Nera', 'Deflagrazione Occulta, Braccia di Hadar, Corona di Follia', '12', 9, '2D8', 16, 10, 13, 12, 14, 10, 16, 0, 2, 10, '210 MO'),
('CAMP04_SHADOW', 'Morgana_Le', 10, 'Trovato un grimorio parzialmente bruciato.', 2, 80, 'Pugnale, Focus Arcano, Libro del Culto', 'Mani Brucianti, Disco Fluttuante, Raggio Rovente', '12', 9, '2D6', 13, 8, 14, 12, 16, 11, 13, 0, 2, 11, '105 MO'),
('CAMP04_SHADOW', 'Ulfric_Storm', 10, 'Invaso dalla furia ha abbattuto la porta sbarrata.', 2, 20, 'Spadone, Collana dei cultisti', NULL, '13', 9, '2D12', 26, 16, 13, 15, 9, 12, 8, 0, 2, 11, '27 MO'),
('CAMP04_SHADOW', 'Valerie_Val', 10, 'Canto di battaglia ispiratore intonato nelle catacombe.', 2, 40, 'Stocco, Flauto, Liuto leggero extra', 'Luminescenza, Parola Guaritrice, Frastornamento', '13', 9, '2D8', 16, 10, 15, 12, 11, 10, 16, 1, 2, 12, '70 MO'),
('CAMP04_SHADOW', 'Cedric_Bold', 10, 'Epurato il leader del culto in nome della giustizia.', 2, 50, 'Spada lunga d''argento, Scudo, Cotta di maglia', 'Punizione Divina, Cura Ferite', '18', 9, '2D10', 22, 15, 10, 14, 10, 12, 14, 0, 2, 11, '60 MO');

-- ----------------------------------------------------------------------------
-- CAMPAGNA 5 - SESSIONE 2 (ID: 12) -> Tutti presenti, livello 1 avanzato
-- ----------------------------------------------------------------------------
INSERT INTO Progressi (codice_campagna, utente, sessione, descrizione, livello, tesoro, equipaggiamento, incantesimi, classe_armatura, velocita, dadi_vita, max_punti_ferita, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta) VALUES
('CAMP05_EBERR', 'Kira_Nerys', 12, 'Scoperti dettagli sul mandante dell''omicidio.', 1, 40, 'Ascia da guerra, Scudo, Cotta di maglia', NULL, '17', 8, '1D10', 13, 16, 10, 16, 11, 12, 10, 1, 2, 11, '90 MO'),
('CAMP05_EBERR', 'Talon_V', 12, 'Trovate tracce di stivali sul soffitto del treno.', 1, 20, 'Arco lungo, 2 Spade corte, Rampino', NULL, '14', 9, '1D10', 11, 12, 16, 13, 10, 14, 10, 0, 2, 14, '35 MO'),
('CAMP05_EBERR', 'Doran_D', 12, 'Offerta assistenza medica ai testimoni sconvolti.', 1, 10, 'Mazza ferrata, Armatura a scaglie, Kit medico', 'Cura Ferite, Luce', '16', 9, '1D8', 10, 13, 10, 14, 11, 16, 12, 0, 2, 13, '30 MO'),
('CAMP05_EBERR', 'Sariel_Elf', 12, 'Pedinato un sospetto elfo senza farsi notare.', 1, 5, 'Dardi, Abiti da monaco scuri', NULL, '15', 11, '1D8', 9, 11, 16, 12, 12, 15, 11, 0, 2, 12, '13 MO'),
('CAMP05_EBERR', 'Brokk_Deep', 12, 'Borseggiato un testimone chiave recuperando un diario.', 1, 150, 'Due stocchi, Attrezzi da ladro, Diario segreto', NULL, '14', 9, '1D8', 9, 10, 16, 12, 13, 10, 14, 0, 2, 12, '185 MO');