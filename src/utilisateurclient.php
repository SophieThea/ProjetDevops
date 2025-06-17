<?php
// Connexion à la base de données

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bibliotheque3", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}





// Requête pour récupérer les utilisateurs:
$stmt = $pdo->query("SELECT ID_Utilisateur, Nom FROM Utilisateur");
$utilisateurs = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <link rel="stylesheet" href="style.css">
    
</head>

<header>
    <div class="menu">

        <?php
        /*
        // Vérifiez si l'utilisateur est un admin
        if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'admin') {
            echo '<a href="admin.php">Accueil</a>';
        }else if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'client') {
            echo '<a href="client.php">Accueil</a>';}*/
        ?>
        <a href="client.php">Accueil</a>
        <a href="gestion_livres_client.php">Gestion des Livres</a>
        <a href="emprunts_client.php">Emprunts</a>
        <a href="utilisateurs_client.php">Utilisateurs</a>

        
        <?php
        /*
        // Vérifiez si l'utilisateur est un admin
        if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'admin') {
            echo '<a href="requetes.php">Requêtes</a>';
        }*/

        ?>
        

        <a href="index.php">Déconnexion</a>
    </div>
</header>


<main>
    <h1>Utilisateurs:</h1>
    <table>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
            </tr>
       
            <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
            <td><?= htmlspecialchars($utilisateur['ID_Utilisateur']) ?></td>
            <td><?= htmlspecialchars($utilisateur['Nom']) ?></td>
            </tr>
            <?php endforeach; ?>
           

    </table>
</main>

</body>




<footer class="footer">
        <div class="footer-content">
            <nav class="footer-nav">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </nav>
            <p>&copy; 2024 MonSite.com. Tous droits réservés.</p>
        </div>
</footer>

</html>
