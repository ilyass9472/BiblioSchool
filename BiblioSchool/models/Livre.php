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
    
    
    public function addLivre($titre, $auteur, $annee_publication, $categorie, $tags) {
        
        $this->pdo->beginTransaction();
        
        try {
            
            $sql = "INSERT INTO livres (titre, auteur, annee_publication, categorie_id) VALUES (:titre, :auteur, :annee_publication, :categorie_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'titre' => $titre,
                'auteur' => $auteur,
                'annee_publication' => $annee_publication,
                'categorie_id' => $categorie
            ]);

            
            $livreId = $this->pdo->lastInsertId();

           
            foreach ($tags as $tag) {
                
                $tagSql = "SELECT id FROM tags WHERE nom = :tag";
                $tagStmt = $this->pdo->prepare($tagSql);
                $tagStmt->execute(['tag' => $tag]);
                $tagId = $tagStmt->fetchColumn();

                
                if (!$tagId) {
                    $tagSql = "INSERT INTO tags (nom) VALUES (:tag)";
                    $tagStmt = $this->pdo->prepare($tagSql);
                    $tagStmt->execute(['tag' => $tag]);
                    $tagId = $this->pdo->lastInsertId();
                }

                
                $livreTagSql = "INSERT INTO livre_tags (livre_id, tag_id) VALUES (:livre_id, :tag_id)";
                $livreTagStmt = $this->pdo->prepare($livreTagSql);
                $livreTagStmt->execute(['livre_id' => $livreId, 'tag_id' => $tagId]);
            }
            
            
            $this->pdo->commit();
            echo "Livre ajouté avec succès!";
        } catch (Exception $e) {
            
            $this->pdo->rollBack();
            echo "Erreur: " . $e->getMessage();
        }
    }

    public function getLivresByCategorie($categorie) {
        $sql = "SELECT l.id, l.titre, l.auteur, l.annee_publication, c.nom AS categorie 
                FROM livres l
                JOIN categories c ON l.categorie_id = c.id
                WHERE c.nom = :categorie";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['categorie' => $categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLivresByTag($tag) {
        $sql = "SELECT l.id, l.titre, l.auteur, l.annee_publication, t.nom AS tag 
                FROM livres l
                JOIN livre_tags lt ON l.id = lt.livre_id
                JOIN tags t ON lt.tag_id = t.id
                WHERE t.nom = :tag";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tag' => $tag]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
