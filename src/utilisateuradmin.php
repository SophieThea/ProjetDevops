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
 

<?php
// Début de session pour afficher le nom de l'utilisateur connecté, si disponible
session_start();
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <link rel="stylesheet" href="style.css">
    <style type="text/css">

.form {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 350px;
    margin-bottom: 20px;
}

h2 {
    text-align: center;
    color: #333;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    width: 100%;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 16px;
}



table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    
}


    </style>

<body>

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
        <a href="admin.php">Accueil</a>
        <a href="gestion_livres_admin.php">Gestion des Livres</a>
        <a href="emprunts_admin.php">Emprunts</a>
        <a href="requetes.php">Requetes</a>
        <a href="utilisateurs_admin.php">Utilisateurs</a>

        
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
