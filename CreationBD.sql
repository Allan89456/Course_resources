CREATE TABLE cours (
    IdCours INT(11) NOT NULL AUTO_INCREMENT,
    Titre   VARCHAR(25) NOT NULL,
    Bloc    TINYINT(1) NOT NULL,
    Section VARCHAR(25) NOT NULL,
    PRIMARY KEY (IdCours)
);

CREATE TABLE utilisateur (
    IdUser    INT(11) NOT NULL AUTO_INCREMENT,
    Nom       VARCHAR(25)  NOT NULL,
    Prenom    VARCHAR(25)  NOT NULL,
    Mdp       VARCHAR(250) NOT NULL,
    Avatar    VARCHAR(500),
    Matricule VARCHAR(50)  NOT NULL,
    isAdmin   TINYINT(1)   NOT NULL,

    PRIMARY KEY (IdUser),
    UNIQUE KEY Matricule (Matricule)
);

CREATE TABLE Ressource (
    IdRessource INT AUTO_INCREMENT PRIMARY KEY,
    IdCours     INT NOT NULL,
    Titre       VARCHAR(255) NOT NULL,
    DateAjout   DATE NOT NULL,
    Type        VARCHAR(50) NOT NULL,
    UserSource  INT NOT NULL,
    Etat 	TINYINT(1),
    CONSTRAINT fk_ressource_cours
        FOREIGN KEY (IdCours) REFERENCES cours(IdCours),
    CONSTRAINT fk_ressource_user
        FOREIGN KEY (UserSource) REFERENCES utilisateur(IdUser)
);

CREATE TABLE ressource_user (
    idUser INT NOT NULL,
    idRessource INT NOT NULL,
    lu TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (idUser, idRessource),
    FOREIGN KEY (idUser) REFERENCES utilisateur(IdUser),
    FOREIGN KEY (idRessource) REFERENCES ressource(IdRessource)
);

ALTER TABLE ressource 
MODIFY DateAjout DATE NOT NULL DEFAULT (CURRENT_DATE);

INSERT INTO cours VALUES (NULL, 'C', '2', 'Informatique');
INSERT INTO cours VALUES (NULL, 'JAVA', '2', 'Informatique');
INSERT INTO cours VALUES (NULL, 'SQL', '2', 'Informatique');

INSERT INTO utilisateur VALUES (NULL, 'Dupont', 'Johnny', 'mdp', NULL, 'A0001', 1);
INSERT INTO utilisateur VALUES (NULL, 'Brisefer', 'Benoit', 'mdp', NULL, 'A0002', 1);

INSERT INTO ressource VALUES (NULL, '1', 'Chapitre 1 - Introduction au C', '2025-12-18', 'type_ressource/Logo_C.png', '1', '1');
INSERT INTO ressource VALUES (NULL, '2', 'Chapitre 1 - Introduction à Java', '2025-12-18', 'type_ressource/Logo_Java.png', '2', '1');
INSERT INTO ressource VALUES (NULL, '2', 'Doc', '2025-12-18', 'https://docs.oracle.com/javase/8/docs/api/', '2', '0');
INSERT INTO ressource VALUES (NULL, '3', 'Chapitre 1 - Introduction à SQL', '2025-12-18', 'type_ressource/Intro_SQL.pdf', '2', '1');