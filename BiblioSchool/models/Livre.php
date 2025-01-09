<?php
class Livre {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getLivresByCategorieAndTag($categorie, $tag) {
        $sql = "SELECT l.id, l.titre, l.auteur, c.nom AS categorie, t.nom AS tag
                FROM livres l
                JOIN categories c ON l.categorie_id = c.id
                JOIN livre_tags lt ON l.id = lt.livre_id
                JOIN tags t ON lt.tag_id = t.id
                WHERE c.nom = :categorie AND t.nom = :tag";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['categorie' => $categorie, 'tag' => $tag]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLivresByCategorie($categorie) {
        $sql = "SELECT l.id, l.titre, l.auteur, c.nom AS categorie
                FROM livres l
                JOIN categories c ON l.categorie_id = c.id
                WHERE c.nom = :categorie";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['categorie' => $categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLivresByTag($tag) {
        $sql = "SELECT l.id, l.titre, l.auteur, t.nom AS tag
                FROM livres l
                JOIN livre_tags lt ON l.id = lt.livre_id
                JOIN tags t ON lt.tag_id = t.id
                WHERE t.nom = :tag";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tag' => $tag]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addCategorie($nom) {
        $query = "INSERT INTO categories (nom) VALUES (:nom) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $nom]);
        return $this->pdo->lastInsertId();
    }
    
    public function addTag($nom) {
        $query = "INSERT INTO tags (nom) VALUES (:nom) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $nom]);
        return $this->pdo->lastInsertId();
    }
    
    public function addLivre($titre, $auteur, $categorieId) {
        $query = "INSERT INTO livres (titre, auteur, categorie_id) VALUES (:titre, :auteur, :categorie_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'titre' => $titre,
            'auteur' => $auteur,
            'categorie_id' => $categorieId,
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function associateTagWithLivre($livreId, $tagId) {
        $query = "INSERT INTO livre_tags (livre_id, tag_id) VALUES (:livre_id, :tag_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'livre_id' => $livreId,
            'tag_id' => $tagId,
        ]);
    }
    
}
?>
