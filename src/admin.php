<?php
// Connexion à la base de données:
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bibliotheque3", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}



// Vérification du statut pour autoriser uniquement l'accès aux administrateurs
if (!isset($_GET["statut"]) || $_GET["statut"] != "admin" || !isset($_GET["nom"])) {
    header("Location: index.php"); 
// Rediriger vers la page de connexion si non autorisé
    exit();
}




$nom = htmlspecialchars($_GET["nom"]);
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
    <title>Accueil-Administrateur - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
 <style>
        .welcome {
            text-align: center;
            margin-top: 50px;
        }

        .welcome h1 {
            font-size: 36px;
        }

        .welcome p {
            font-size: 18px;
        }

        .menu1 {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            gap: 20px;
        }

        .menu1 a {
            display: block;
            padding: 15px 25px;
            font-size: 18px;
            text-align: center;
            background-color: #586f64;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .menu1 a:hover {
            background-color: #3e5148;
        }

        .ads-container {
            margin: 50px auto;
            text-align: center;
        }

        .ads-container marquee img {
            width: 200px;
            height: auto;
            margin: 0 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .titre{
            color: white;
        }

    </style>
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

<div class="welcome">
    <h1>Bienvenue, Administrateur <?php echo $nom; ?> !</h1>
    <p>
        <?php
        if (isset($_SESSION['nom'])) {
            echo "Bonjour, " . htmlspecialchars($_SESSION['nom']) . "! Explorez les livres, gérez les emprunts, et bien plus encore.";
        } else {
            echo "Connectez-vous pour accéder aux fonctionnalités de la bibliothèque.";
        }
        ?>
    </p>
</div>

<div class="menu1">
    <a href="admin.php">Accueil</a>
    <a href="gestion_livres_admin.php">Explorer les Livres</a>
    <a href="emprunts_admin.php">Voir les Emprunts</a>
    <a href="utilisateurs_admin.php">Gestion des Utilisateurs</a>
</div>


</body>



</html>


