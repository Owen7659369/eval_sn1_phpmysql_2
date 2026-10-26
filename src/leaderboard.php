<?php
require_once 'config.php';

$niveau_id = isset($_GET['niveau']) ? (int)$_GET['niveau'] : 1;
if ($niveau_id < 1 || $niveau_id > 4) {
	$niveau_id = 1;
}

$stmt = $dbh->prepare("SELECT nom FROM niveaus WHERE id = ?");
$stmt->execute([$niveau_id]);
$niveau_nom = $stmt->fetch(PDO::FETCH_ASSOC)['nom'] ?? 'Niveau';

$stmt = $dbh->prepare("
	SELECT j.id AS joueur_id, j.pseudo, p.essais_utilises, p.debut_partie,
	       TIMESTAMPDIFF(SECOND, p.debut_partie, p.fin_partie) AS duree
	FROM parties p
	JOIN joueurs j ON j.id = p.joueur_id
	WHERE p.niveau_id = :niveau_id AND p.resultat = 'gagnee'
	ORDER BY p.essais_utilises ASC, (duree IS NULL), duree ASC, p.debut_partie ASC
	LIMIT 10
");
$stmt->execute(['niveau_id' => $niveau_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$me = $_SESSION['joueur_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Classement - Plus ou Moins</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<header class="topbar">
			<a href="acceuil.php" class="brand">± Plus ou Moins</a>
			<nav class="nav">
				<a href="acceuil.php">Jouer</a>
				<a href="leaderboard.php">Classement</a>
				<?php if (isset($_SESSION['pseudo'])): ?>
					<a href="statistics.php">Mes stats</a>
					<span class="nav-user"><?= htmlspecialchars($_SESSION['pseudo']) ?> | <a href="deconnection.php">Se déconnecter</a></span>
				<?php else: ?>
					<a href="connection.php">Connexion</a>
					<a href="inscription.php">Créer un compte</a>
				<?php endif; ?>
			</nav>
		</header>
		<main class="container">
			<h2 class="page-title">Classement — <?= htmlspecialchars($niveau_nom) ?></h2>

			<nav class="niveau-tabs">
				<?php foreach ([1 => 'Facile', 2 => 'Normal', 3 => 'Difficile', 4 => 'Expert'] as $id => $nom): ?>
					<a href="leaderboard.php?niveau=<?= $id ?>"
					   class="niveau-tab<?= $id === $niveau_id ? ' active' : '' ?>">
					   <?= $nom ?>
					</a>
				<?php endforeach; ?>
			</nav>

			<section class="leaderboard-card">
				<table class="leaderboard-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Joueur</th>
							<th>Essais</th>
							<th>Durée</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!$rows): ?>
							<tr><td colspan="5" class="leaderboard-empty">Aucune partie gagnée pour ce niveau.</td></tr>
						<?php else: ?>
							<?php foreach ($rows as $i => $row): ?>
								<tr class="<?= ($me !== null && (int)$row['joueur_id'] === (int)$me) ? 'me' : '' ?>">
									<td><?= $i + 1 ?></td>
									<td><?= htmlspecialchars($row['pseudo']) ?></td>
									<td><?= (int)$row['essais_utilises'] ?></td>
									<td><?= $row['duree'] !== null ? sprintf('%d:%02d', intdiv((int)$row['duree'], 60), (int)$row['duree'] % 60) : '—' ?></td>
									<td><?= date('d/m H:i', strtotime($row['debut_partie'])) ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</section>
		</main>
	</body>
</html>
