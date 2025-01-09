<?php
require 'connexion/Database.php';
require 'models/Livre.php';

$database = new Database();
$pdo = $database->getConnection();
$livre = new Livre($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $auteur = $_POST['auteur'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $tags = $_POST['tags'] ?? '';

    if (!empty($titre) && !empty($auteur) && !empty($categorie)) {
        $categorieId = $livre->addCategorie($categorie);
        $livreId = $livre->addLivre($titre, $auteur, $categorieId);

        if (!empty($tags)) {
            $tagsArray = explode(',', $tags);
            foreach ($tagsArray as $tag) {
                $tagId = $livre->addTag(trim($tag));
                $livre->associateTagWithLivre($livreId, $tagId);
            }
        }
        echo "Livre ajouté avec succès !";
    } else {
        echo "Tous les champs (titre, auteur, catégorie) sont obligatoires.";
    }
}

$categorie = isset($_GET['categorie']) ? trim($_GET['categorie']) : null;
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : null;

if ($categorie && $tag) {
    $livres = $livre->getLivresByCategorieAndTag($categorie, $tag);
} elseif ($categorie) {
    $livres = $livre->getLivresByCategorie($categorie);
} elseif ($tag) {
    $livres = $livre->getLivresByTag($tag);
} else {
    $livres = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Gestion des Livres</h1>

    <h2>Ajouter un Livre</h2>
    <form method="post" action="">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required>
        <br>
        <label for="auteur">Auteur :</label>
        <input type="text" id="auteur" name="auteur" required>
        <br>
        <label for="categorie">Catégorie :</label>
        <input type="text" id="categorie" name="categorie" required>
        <br>
        <label for="tags">Tags (séparés par des virgules) :</label>
        <input type="text" id="tags" name="tags">
        <br>
        <button type="submit">Ajouter</button>
    </form>

    <h2>Liste des Livres</h2>
    <form method="get" action="">
        <label for="categorie">Catégorie :</label>
        <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($categorie ?? '') ?>">
        <label for="tag">Tag :</label>
        <input type="text" id="tag" name="tag" value="<?= htmlspecialchars($tag ?? '') ?>">
        <button type="submit">Filtrer</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Tag</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($livres)): ?>
                <?php foreach ($livres as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id']); ?></td>
                        <td><?= htmlspecialchars($book['titre']); ?></td>
                        <td><?= htmlspecialchars($book['auteur']); ?></td>
                        <td><?= htmlspecialchars($book['categorie']); ?></td>
                        <td><?= isset($book['tag']) ? htmlspecialchars($book['tag']) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Aucun livre trouvé</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
