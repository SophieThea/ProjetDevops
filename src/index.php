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

// Début de session :Il démarre une session PHP pour stocker et récupérer des variables utilisateur.
session_start();






if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $pass = $_POST["pass"];
    $statut = $_POST["statut"];






    // Hachage du mot de passe pour plus de sécurité
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);



    // Insertion des données dans la base de données
    $sql = "INSERT INTO utilisateur (nom, mot_de_pass, statut) VALUES (:nom, :mot_de_pass, :statut)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':nom' => $nom,
            ':mot_de_pass' => $hashed_pass,
            ':statut' => $statut,
        ]);

        // Stockage des données dans la session
        $_SESSION['nom'] = $nom;
        $_SESSION['statut'] = $statut;

        // Redirection après validation
        //Si l'utilisateur est un admin, il est redirigé vers admin.php avec les paramètres nom et statut=admin
        if ($statut == "admin") {
            header("Location: admin.php?nom=" . urlencode($nom) . "&statut=admin");
        } else {
            header("Location: client.php?nom=" . urlencode($nom) . "&statut=client");
        }
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion des données : " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque - Connexion</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="livre.png" width="60px" height="60px">
        <h1 style="color:white">Bibliothèque</h1>
    </div>
</header>
<div class="head1">
    <div class="login-container">
        <h1>Connexion</h1>
        <form method="POST" action="">
            <label for="nom">Nom d'utilisateur :  </label>
            <input type="text" id="nom" name="nom" required>

            <label for="pass">Mot de passe :</label>
            <input type="password" id="pass" name="pass" required>

            <label for="statut">Statut :</label>
            <select id="statut" name="statut">
                <option value="client">Client</option>
                <option value="admin">Administrateur</option>
            </select>

            <button class="btn" name="connect" type="submit">Se connecter</button>
        </form>
    </div>
</div>
</body>
</html>
