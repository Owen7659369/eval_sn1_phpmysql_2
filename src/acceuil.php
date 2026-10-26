<?php
require_once 'config.php';
include 'main.php';

$data = get_difficulties($dbh);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Just Prix</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<header class="topbar">
			<a href="acceuil.php" class="brand">± Plus ou Moins</a>
			<nav class="nav">
				<a href="acceuil.php">Jouer</a>
				<a href="leaderboard.php">Classement</a>
				<a href="statistics.php">Mes stats</a>
				<span class="nav-user"><?= htmlspecialchars($_SESSION["pseudo"]) ?> | <a href="deconnection.php">Se déconnecter</a></span>
			</nav>
		</header>
		<main class="container">
			<h2 class="page-title">Choisissez votre niveau</h2>
			<section class="niveau-grid">
				<?php build_menu($data) ?>
			</section>
		</main>
	</body>
</html>
