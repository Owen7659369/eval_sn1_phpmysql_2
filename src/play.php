<?php
require_once 'config.php';
include 'play_logic.php';
$niveau = get_difficulties($dbh);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Just Prix - Play</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<main class="container play-wrap">
			<section class="play-card">
				<div class="play-header">
					<span class="play-attempts">Essais restants : <?= (int)$niveau['nb_essais'] ?> / <?= (int)$niveau['nb_essais'] ?></span>
				</div>
				<form class="play-form" method="post">
					<input class="form-input" type="number" name="guess" placeholder="Votre nombre" required>
					<button class="btn btn-primary" type="submit" name="submit-guess">Proposer</button>
				</form>
			</section>
		</main>
	</body>
</html>
