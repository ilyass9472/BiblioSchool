<?php
require 'connexion/Database.php';
require 'models/Livre.php';

$database = new Database();
$pdo = $database->getConnection();

$livre = new Livre($pdo);

$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$tag = isset($_GET['tag']) ? $_GET['tag'] : null;

if ($categorie && $tag) {
    
    $livres = $livre->getLivresByCategorieAndTag($categorie, $tag);
} elseif ($categorie) {
    
    $livres = $livre->getLivresByCategorie($categorie);
} elseif ($tag) {
    
    $livres = $livre->getLivresByTag($tag);
} else {
    
    $livres = $livre->getAllLivres();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Livres</title>
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
    <h1 style="text-align: center;">Liste des Livres</h1>
    
    <form method="get" action="">
        <label for="categorie">Catégorie:</label>
        <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($categorie) ?>">
        
        <label for="tag">Tag:</label>
        <input type="text" id="tag" name="tag" value="<?= htmlspecialchars($tag) ?>">
        
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
            <?php foreach ($livres as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['id']); ?></td>
                    <td><?= htmlspecialchars($book['titre']); ?></td>
                    <td><?= htmlspecialchars($book['auteur']); ?></td>
                    <td><?= htmlspecialchars($book['categorie']); ?></td>
                    <td><?= isset($book['tag']) ? htmlspecialchars($book['tag']) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
