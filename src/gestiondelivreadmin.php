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



// Vérification du statut pour autoriser uniquement l'accès aux administrateurs:


/*
if (isset($_GET["Statut"])) {
    $statut = htmlspecialchars($_GET["Statut"]);
    $_SESSION['statut'] = $statut; // Stocker le statut dans la session
}

// Vérifiez si $_SESSION['statut'] est défini
if (!isset($_SESSION['statut'])) {
    die("Erreur : Statut de l'utilisateur non défini.");
}*/


/*
// Récupération des informations de session:

if (!isset($_SESSION['nom']) || !isset($_SESSION['statut'])) {
    die("Erreur : Vous devez être connecté pour accéder à cette page.");
}
$nom = $_SESSION['nom'];
$statut = $_SESSION['statut'];
*/


/*if (isset($_GET["Statut"])) {
    $statut = htmlspecialchars($_GET["Statut"]);
    $_SESSION['statut'] = $statut; // Stockez le statut dans la session
} elseif (!isset($_SESSION['statut'])) {
    die("Erreur : Vous n'avez pas accès à cette page. Veuillez vous connecter.");
}*/


// Vérifier et mettre à jour la disponibilité des livres
$pdo->exec("UPDATE Livre SET Disponible = 0 WHERE Exemplaires = 0");
$pdo->exec("UPDATE Livre SET Disponible = 1 WHERE Exemplaires > 0");
 


 
// Requête pour récupérer les livres
$stmt = $pdo->query("SELECT ID_Livre, Titre, Auteurs, Nom_Maison_Edition, Nombre_Pages, Prix, Nom_Librairie, Image, Disponible, Exemplaires FROM Livre");
$livres = $stmt->fetchAll();


// Requête pour récupérer les utilisateurs:
$stmt = $pdo->query("SELECT ID_Utilisateur, Nom FROM Utilisateur");
$utilisateurs = $stmt->fetchAll();







// Ajouter un livre si le formulaire a été soumis,le script PHP vérifie que la méthode HTTP est POST et que le champ caché add_book est défini
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $Titre = $_POST['Titre'];
    $Auteurs = $_POST['Auteurs'];
    $Nom_Maison_Edition = $_POST['Nom_Maison_Edition'];
    $Nombre_Pages = $_POST['Nombre_Pages'];
    $Prix = $_POST['Prix'];
    $Nom_Librairie = $_POST['Nom_Librairie'];
    $Image = $_POST['Image'];
    $Exemplaires = $_POST['Exemplaires'];




    // Insertion dans la base de données
    $stmt = $pdo->prepare("
        INSERT INTO Livre (Titre, Auteurs, Nom_Maison_Edition, Nombre_Pages, Prix, Nom_Librairie, Image, Exemplaires, Disponible)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");



//les valeurs correspondant
    $stmt->execute([
        $Titre,
        $Auteurs,
        $Nom_Maison_Edition,
        $Nombre_Pages,
        $Prix,
        $Nom_Librairie,
        $Image,
        $Exemplaires,
        $Exemplaires > 0 ? 1 : 0, // Disponible si Exemplaires > 0
    ]);

    echo "<p style='color: green;'>Livre ajouté avec succès !</p>";
}



// Récupération des livres pour l'affichage
$stmt = $pdo->query("SELECT ID_Livre, Titre, Auteurs, Nom_Maison_Edition, Nombre_Pages, Prix, Nom_Librairie, Image, Disponible, Exemplaires FROM Livre");
$livres = $stmt->fetchAll();






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
}

h2 {
    text-align: center;
    color: white;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
    color: black;
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
    width: 80%;
    background: white;
    color: black;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 16px;
}

button:hover {
    background:seagreen;
}


    </style>
    
</head>
<body>

<header>
    <div class="menu">

        <?php
        /* Ancien code pour afficher l'accueil:

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

        /* Ancien code pour afficher la requete:

        // Vérifiez si l'utilisateur est un admin
        if (isset($_SESSION['statut']) && $_SESSION['statut'] === 'admin') {
            echo '<a href="requetes.php">Requêtes</a>';
        }*/
        ?>
        
        <a href="index.php">Déconnexion</a>
    </div>
</header>




<main>
    <h1>Gestion des Livres</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Titre</th>
                <th>Auteur(s)</th>
                <th>Maison d'Édition</th>
                <th>Nombre de Pages</th>
                <th>Prix</th>
                <th>Provenance</th>
                <th>Disponible</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
           <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= htmlspecialchars($livre['ID_Livre']) ?></td>

                    <td>
                        <?php if ($livre['Image']): ?>
                            
                            <img src="<?= htmlspecialchars($livre['Image']) ?>" alt="<?= htmlspecialchars($livre['Titre']) ?>" class="book-image">
                        <?php else: ?>
                            <span>Aucune image</span>
                        <?php endif; ?>
                    </td>
                    
                    <td ><?= htmlspecialchars($livre['Titre']) ?></td>
                    <td><?= htmlspecialchars($livre['Auteurs']) ?></td>
                    <td><?= htmlspecialchars($livre['Nom_Maison_Edition']) ?></td>
                    <td><?= htmlspecialchars($livre['Nombre_Pages']) ?></td>

                    <td style="color:red;"><?= htmlspecialchars(number_format($livre['Prix'], 2)) ?> FCFA</td>

                    <td><?= htmlspecialchars($livre['Nom_Librairie']) ?></td>
                    <td><?= $livre['Disponible'] ? 'Oui' : 'Non' ?></td>


                          <td>
    <?php if ($livre['Disponible']): ?>
        <form action="emprunts_admin.php" method="POST">
            <input type="hidden" name="ID_Livre" value="<?= htmlspecialchars($livre['ID_Livre']) ?>">

            <input type="hidden" name="Titre_Livre" value="<?= htmlspecialchars($livre['Titre']) ?>">

           <?php foreach ($utilisateurs as $utilisateur): ?>
            <input type="hidden" name="Nom_Utilisateur" value="<?= htmlspecialchars($utilisateur['Nom'])  ?>">
            <?php endforeach; ?>


            <select name="Nom_Utilisateur" required>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <option value="<?= htmlspecialchars($utilisateur['Nom']) ?>">
                        <?= htmlspecialchars($utilisateur['Nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>








            <button type="submit" >Emprunter</button>
        </form>
    <?php else: ?>
        <span>Non disponible</span>
    <?php endif; ?>
</td>
</tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>




<!-- Formulaire pour ajouter un livre -->
    <h2>Ajouter un livre</h2>
    <center>
    <form method="POST" action="" class="form">
        <input type="hidden" name="add_book" value="1">
        <label for="Titre">Titre :</label>
        <input type="text" name="Titre" id="Titre" required><br>

        <label for="Auteurs">Auteur(s) :</label>
        <input type="text" name="Auteurs" id="Auteurs" required><br>

        <label for="Nom_Maison_Edition">Maison d'Édition :</label>
        <input type="text" name="Nom_Maison_Edition" id="Nom_Maison_Edition" required><br>

        <label for="Nombre_Pages">Nombre de Pages :</label>
        <input type="number" name="Nombre_Pages" id="Nombre_Pages" required><br>

        <label for="Prix">Prix :</label>
        <input type="number" step="0.01" name="Prix" id="Prix" required><br>

        <label for="Nom_Librairie">Provenance (Librairie) :</label>
        <input type="text" name="Nom_Librairie" id="Nom_Librairie" required> <br>

        <label for="Image">Lien de l'image :</label>
        <input type="text" name="Image" id="Image"><br>

        <label for="Exemplaires">Nombre d'exemplaires :</label>
        <input type="number" name="Exemplaires" id="Exemplaires" required><br>

        <button type="submit">Ajouter le livre</button>
    </form>
    </center>

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
