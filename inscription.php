<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=plus_ou_moins;charset=utf8mb4', 'root', '');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo      = trim($_POST['pseudo']);
    $utilisateur = trim($_POST['utilisateur']);
    $mdp         = $_POST['mot_de_passe'];

    if ($pseudo == '' || $utilisateur == '' || $mdp == '') {
        $message = 'Veuillez remplir tous les champs.';
    } else {
        // Le pseudo est-il déjà pris ?
        $stmt = $pdo->prepare('SELECT id FROM joueurs WHERE pseudo = ?');
        $stmt->execute([$pseudo]);

        if ($stmt->fetch()) {
            $message = 'Ce pseudo existe déjà.';
        } else {
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO joueurs (pseudo, utilisateur, mot_de_passe) VALUES (?, ?, ?)');
            $stmt->execute([$pseudo, $utilisateur, $hash]);

            header('Location: connexion.php');
            exit;
        }
    }
}
?>
<h1>Inscription</h1>
<?php if ($message) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>
<form method="post">
    <input type="text" name="pseudo" placeholder="Pseudo" required>
    <input type="text" name="utilisateur" placeholder="Utilisateur" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
<p><a href="connexion.php">Déjà un compte ? Se connecter</a></p>