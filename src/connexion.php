<?php
$host = 'localhost';  // ou '127.0.0.1'
$user = 'root';
$password = 'ton_mdp';
$dbname = 'nom_de_ta_base';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
echo "Connecté à la base de données avec succès !";

?>
