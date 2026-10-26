<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$stmt = $dbh->prepare('SELECT * FROM joueurs WHERE pseudo = ?');
	$stmt->execute([$_POST['pseudo']]);
	$joueur = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($joueur && password_verify($_POST['mot_de_passe'], $joueur['mot_de_passe'])) {
		$_SESSION['joueur_id'] = $joueur['id'];
		$_SESSION['pseudo'] = $joueur['pseudo'];
		header('Location: acceuil.php');
		exit;
	}
	$erreur = 'Pseudo ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Just Prix — Connexion</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<header class="topbar">
			<a href="connection.php" class="brand">± Plus ou Moins</a>
			<nav class="nav">
				<a href="#">Jouer</a>
				<a href="#">Classement</a>
				<a href="connection.php">Connexion</a>
				<a href="inscription.php">Créer un compte</a>
			</nav>
		</header>
		<main class="container auth-wrap">
			<section class="auth-card">
				<h1 class="auth-title">Se connecter</h1>
				<form class="auth-form" method="post">
					<?php if (isset($erreur)) echo "<p class='form-error'>" . htmlspecialchars($erreur) . "</p>"; ?>
					<div class="form-field">
						<label class="form-label" for="pseudo">Pseudo</label>
						<input class="form-input" id="pseudo" type="text" name="pseudo" placeholder="Pseudo" required>
					</div>
					<div class="form-field">
						<label class="form-label" for="mot_de_passe">Mot de passe</label>
						<input class="form-input" id="mot_de_passe" type="password" name="mot_de_passe" placeholder="Mot de passe" required>
					</div>
					<button class="btn btn-primary btn-block" type="submit">Se connecter</button>
				</form>
			</section>
		</main>
	</body>
</html>
