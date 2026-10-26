<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo      = trim($_POST['pseudo']);
    $email= trim($_POST['email']);
    $mdp         = $_POST['mot_de_passe'];

    if ($pseudo == '' || $email == '' || $mdp == '') {
        $message = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $dbh->prepare('SELECT id FROM joueurs WHERE pseudo = ?');
        $stmt->execute([$pseudo]);

        if ($stmt->fetch()) {
            $message = 'Ce pseudo existe déjà.';
        } else {
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt = $dbh->prepare('INSERT INTO joueurs (pseudo, email, mot_de_passe) VALUES (?, ?, ?)');
            $stmt->execute([$pseudo, $email, $hash]);

            header('Location: connection.php');
            exit;
        }
    }
}
?>
<h1>Inscription</h1>
<?php if ($message) echo "<p>" . htmlspecialchars($message) . "</p>"; ?>
<form method="post">
    <input type="text" name="pseudo" placeholder="Pseudo" required>
    <input type="email" name="email" placeholder="e-mail" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
<p><a href="connection.php">Déjà un compte ? Se connecter</a></p>
