<?php
// Connexion à la base de données

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bibliotheque3", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e)  {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}







if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Titre_Livre'], $_POST['Nom_Utilisateur'])) {
    $Titre_Livre = $_POST['Titre_Livre'];
    $Nom_Utilisateur = $_POST['Nom_Utilisateur'];







/*
    // Insérer l'emprunt dans une table temporaire ou de gestion des emprunts
    $stmt = $pdo->prepare("
        INSERT INTO emprunt (Titre, Nom, Date_Emprunt, Date_Retour)
        VALUES (?, ?, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY))
    ");
    $stmt->execute([$Titre_Livre, $Nom_Utilisateur]);
*/











// Vérifiez si l'utilisateur existe
$stmtCheckUser = $pdo->prepare("SELECT Nom FROM utilisateur WHERE Nom = ?");
$stmtCheckUser->execute([$Nom_Utilisateur]);
$userExists = $stmtCheckUser->fetch();

if (!$userExists) {
    // Insérez l'utilisateur s'il n'existe pas
    $stmtInsertUser = $pdo->prepare("INSERT INTO utilisateur (Nom) VALUES (?)");
    $stmtInsertUser->execute([$Nom_Utilisateur]);
}




// Insérez l'emprunt
$stmt = $pdo->prepare("
    INSERT INTO emprunt (Titre, Nom, Date_Emprunt, Date_Retour)
    VALUES (?, ?, CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY))
");
$stmt->execute([$Titre_Livre, $Nom_Utilisateur]);
$emprunts = $stmt->fetchAll();





    // Mettre à jour le statut de disponibilité du livre
    $pdo->prepare("UPDATE Livre SET Disponible = 0 WHERE Titre = ?")->execute([$Titre_Livre]);

    echo "Le livre '$Titre_Livre' a été emprunté par '$Nom_Utilisateur'.";
} 









/* Ancien:
// Gestion de l'emprunt via formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID_Livre'], $_POST['ID_Utilisateur'])) {

    $ID_Livre = $_POST['ID_Livre'];
    $ID_Utilisateur = $_POST['ID_Utilisateur'];

}

    // Récupérer le titre du livre à partir de la table Livre
    $stmtTitle = $pdo->prepare("SELECT Titre FROM livre WHERE ID_Livre = ?");
    $stmtTitle->execute([$ID_Livre]);
    $livre = $stmtTitle->fetch();
    $Titre = $livre['Titre'];



    // Récupérer le nom de l'utilisateur depuis la table Utilisateurs
    $stmtUser = $pdo->prepare("SELECT Nom FROM utilisateur WHERE ID_Utilisateur = ?");
    $stmtUser->execute([$ID_Utilisateur]);
    $user = $stmtUser->fetch();
    $Nom = $user['Nom'];

echo $Titre;
echo $Nom;
*/



// Récupération des livres pour l'affichage
$stmt = $pdo->query("SELECT ID_Emprunt, Date_Emprunt, Date_Retour, Nom, Titre FROM emprunt");
$emprunts = $stmt->fetchAll();


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <link rel="stylesheet" href="style.css">
    
</head>
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
    <h1>Emprunts</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Nom</th>
                <th>Date_Emprunt</th>
                <th>Date_Retour</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($emprunts as $emprunt): ?>
            <tr>
                <td><?= htmlspecialchars($emprunt['ID_Emprunt']) ?></td>
                <td><?= htmlspecialchars($emprunt['Titre']) ?></td>
                <td><?= htmlspecialchars($emprunt['Nom']) ?></td>
                <td><?= htmlspecialchars($emprunt['Date_Emprunt']) ?></td>
                <td><?= htmlspecialchars($emprunt['Date_Retour'] ?? 'Non retourné') ?></td>
            <?php endforeach; ?>







            </tr>
        
        </tbody>
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
