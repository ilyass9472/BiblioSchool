

CREATE database bibloschool
use database bibloschool
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);


CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    categorie_id INT,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE livre_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livre_id INT NOT NULL,
    tag_id INT NOT NULL,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

INSERT INTO categories (nom) VALUES
('Roman'),
('Science-Fiction'),
('Historique');

INSERT INTO tags (nom) VALUES
('Aventure'),
('Drame'),
('Fantasy'),
('Technologie');

INSERT INTO livres (titre, auteur, categorie_id) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 1),
('1984', 'George Orwell', 2),
('Les Misérables', 'Victor Hugo', 3);

INSERT INTO livre_tags (livre_id, tag_id) VALUES
(1, 1),
(2, 4),
(3, 2);