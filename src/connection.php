<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=plus_ou_moins;charset=utf8mb4', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('SELECT * FROM joueurs WHERE pseudo = ?');
    $stmt->execute([$_POST['pseudo']]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($joueur && password_verify($_POST['mot_de_passe'], $joueur['mot_de_passe'])) {
        $_SESSION['joueur_id'] = $joueur['id'];
        $_SESSION['pseudo'] = $joueur['pseudo'];
        header('Location: niveau.php');
        exit;
    }
    $erreur = 'Pseudo ou mot de passe incorrect.';
}
?>
<form method="post">
    <?php if (isset($erreur)) echo "<p>$erreur</p>"; ?>
    <input type="text" name="pseudo" placeholder="Pseudo" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>