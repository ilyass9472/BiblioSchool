<?php
// Inclusion des fichiers
require_once 'connexion/Database.php';
require_once 'models/Livre.php';

// Création d'une instance de la classe Database
$database = new Database();
$pdo = $database->getConnection();

// Création d'une instance de la classe Livre
$livre = new Livre($pdo);

// Récupérer tous les livres
$livres = $livre->getAllLivres();
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
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($livres as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['id']); ?></td>
                    <td><?= htmlspecialchars($book['nom']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
