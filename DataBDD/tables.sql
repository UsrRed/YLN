USE nathiotime;

CREATE TABLE Utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(25) NOT NULL,
    mot_de_passe VARCHAR(50) NOT NULL
);


CREATE TABLE Historique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    comparaison1 VARCHAR(50) NOT NULL,
    comparaison2 VARCHAR(50) NOT NULL,
    date DATETIME,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id)
);

CREATE TABLE Favoris (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    historique_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateur(id),
    FOREIGN KEY (historique_id) REFERENCES Historique(id)
);

