<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Résultats des Requêtes SQL</title>
    <style>
        h2 { color: #f3f9f7; }
        .btn { padding: 10px 20px; background-color: #172520; color: white; border: none; cursor: pointer; margin: 5px; font-size: 16px; }
        .btn:hover { background-color: #8c9e95; }
        .search-container { margin: 20px 0; }
        .search-input { padding: 8px; font-size: 16px; width: 80%; }
        .search-btn { padding: 8px; font-size: 16px; }
    </style>
</head>
<body>

<header>
    <div class="menu">
        <a href="admin.php">Accueil</a>
        <a href="gestion_livres_admin.php">Gestion des Livres</a>
        <a href="emprunts_admin.php">Emprunts</a>
        <a href="requetes.php">Requetes</a>
        <a href="utilisateurs_admin.php">Utilisateurs</a>
        <a href="index.php">Déconnexion</a>
    </div>
</header>

<h2>Requêtes SQL</h2>

<!-- Boutons pour exécuter les requêtes -->
<form method="POST">
    <button type="submit" name="query1" class="btn">Livres non achetés directement à l'éditeur</button>
    <button type="submit" name="query2" class="btn">Couples (emprunteur, titre du livre)</button>
    <button type="submit" name="query3" class="btn">Nombre d'emprunts par utilisateur et par titre</button>
    <button type="submit" name="query4" class="btn">Livres non disponibles et date de retour</button>
    <button type="submit" name="query5" class="btn">État des emprunts par utilisateur</button>
</form>

<!-- Barre de recherche pour la 6ᵉ requête -->
<div class="search-container">
    <form method="POST">
        <input type="text" name="searchTerm" class="search-input" placeholder="Rechercher un livre par titre...">
        <button type="submit" name="query6" class="search-btn btn">Rechercher</button>
    </form>
</div>

<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'bibliotheque3';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}




// Fonction pour exécuter une requête SQL
function executeQuery($query) {
    global $pdo;
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Gestion des requêtes
$resultsData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['query1'])) {
        $query = "SELECT * FROM livre WHERE Nom_Librairie != Nom_Maison_Edition";


    } elseif (isset($_POST['query2'])) {
        $query = "SELECT DISTINCT utilisateur.Nom, livre.Titre
                  FROM emprunt
                  INNER JOIN utilisateur ON emprunt.Nom = utilisateur.Nom
                  INNER JOIN livre ON emprunt.Titre = livre.Titre";



    } elseif (isset($_POST['query3'])) {
        $query = "SELECT utilisateur.Nom, livre.Titre, COUNT(*) AS Nombre_Emprunts
                  FROM emprunt
                  INNER JOIN utilisateur ON emprunt.Nom = utilisateur.Nom
                  INNER JOIN livre ON emprunt.Titre = livre.Titre
                  GROUP BY utilisateur.Nom, livre.Titre";


    } elseif (isset($_POST['query4'])) {
        $query = "SELECT livre.Titre, emprunt.Date_Retour, utilisateur.Nom
                  FROM livre
                  INNER JOIN emprunt ON livre.Titre = emprunt.Titre
                  INNER JOIN utilisateur ON emprunt.Nom = utilisateur.Nom
                  WHERE livre.Disponible = '0'
                  ORDER BY emprunt.Date_Retour ASC";



    } elseif (isset($_POST['query5'])) {
        $query = "SELECT utilisateur.Nom, livre.Titre, emprunt.Date_Retour, 
                  CASE WHEN emprunt.Date_Retour < NOW() THEN Amende ELSE 0 END AS Penalite
                  FROM emprunt
                  INNER JOIN utilisateur ON emprunt.Nom = utilisateur.Nom
                  INNER JOIN livre ON emprunt.Titre = livre.Titre";









    } elseif (isset($_POST['query6'])) {

        $searchTerm = htmlspecialchars($_POST['searchTerm'] ?? '');

        $query = "SELECT * FROM livre
                  WHERE Titre LIKE '%$searchTerm%'
                  AND Disponible = 1
                  AND ID_Livre NOT IN (
                      SELECT ID_Livre FROM emprunt WHERE Date_Retour > NOW()
                  )";
    }

    $resultsData = executeQuery($query);
}

// Affichage des résultats
if ($resultsData): ?>
    <h3>Résultats</h3>
    <table>
        <tr>
            <?php foreach (array_keys($resultsData[0]) as $header): ?>
                <th><?= htmlspecialchars($header) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($resultsData as $row): ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
