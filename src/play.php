<?php
require_once 'config.php';
include 'play_logic.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php if ($game_over): ?>
			<meta http-equiv="refresh" content="4; url=acceuil.php">
		<?php endif; ?>
		<title>Just Prix - Play</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<main class="container play-wrap">
			<section class="play-card">
				<?php if (!$game_over): ?>
					<?php $remaining = (int)get_total_attemps($dbh) - (int)get_attempts($dbh); ?>
					<div class="play-header">
						<span class="play-attempts">Essais restants : <?= $remaining ?> / <?= (int)get_total_attemps($dbh) ?></span>
					</div>
				<?php endif; ?>

				<?php if ($feedback): ?>
					<div class="play-feedback"><?= htmlspecialchars($feedback) ?></div>
				<?php endif; ?>

				<?php if ($error): ?>
					<div class="form-error"><?= htmlspecialchars($error) ?></div>
				<?php endif; ?>

				<?php if ($game_over): ?>
					<div class="play-endgame <?= $game_over["won"] ? "won" : "lost" ?>">
						<h2 class="endgame-title"><?= $game_over["won"] ? "Gagné !" : "Perdu..." ?></h2>
						<p class="endgame-message"><?= htmlspecialchars($game_over["message"]) ?></p>
						<p class="endgame-hint">Retour à l'accueil…</p>
					</div>
				<?php else: ?>
					<form class="play-form" method="post">
						<input class="form-input" type="number" name="guess" placeholder="Votre nombre" required>
						<button class="btn btn-primary" type="submit" name="submit-guess">Proposer</button>
					</form>
				<?php endif; ?>
			</section>
		</main>
	</body>
</html>
