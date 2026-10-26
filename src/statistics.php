<?php
require_once 'config.php';

if (!isset($_SESSION['joueur_id'])) {
	header('Location: connection.php');
	exit;
}

$joueur_id = (int)$_SESSION['joueur_id'];

$stmt = $dbh->prepare("
	SELECT
		COUNT(*) AS total,
		SUM(CASE WHEN resultat = 'gagnee' THEN 1 ELSE 0 END) AS wins
	FROM parties
	WHERE joueur_id = :joueur_id
");
$stmt->execute(['joueur_id' => $joueur_id]);
$totals = $stmt->fetch(PDO::FETCH_ASSOC);
$total = (int)$totals['total'];
$wins  = (int)$totals['wins'];
$rate  = $total > 0 ? round(($wins / $total) * 100) : 0;

$stmt = $dbh->prepare("
	SELECT
		n.id,
		n.nom,
		COUNT(p.id) AS parties,
		SUM(CASE WHEN p.resultat = 'gagnee' THEN 1 ELSE 0 END) AS wins,
		ROUND(AVG(CASE WHEN p.resultat = 'gagnee' THEN p.essais_utilises END), 1) AS moy_essais,
		MIN(CASE WHEN p.resultat = 'gagnee' THEN p.essais_utilises END) AS best
	FROM niveaus n
	LEFT JOIN parties p ON p.niveau_id = n.id AND p.joueur_id = :joueur_id
	GROUP BY n.id, n.nom
	ORDER BY n.id
");
$stmt->execute(['joueur_id' => $joueur_id]);
$by_level = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $dbh->prepare("
	SELECT p.debut_partie, n.nom AS niveau, p.essais_utilises, p.resultat
	FROM parties p
	JOIN niveaus n ON n.id = p.niveau_id
	WHERE p.joueur_id = :joueur_id
	ORDER BY p.debut_partie DESC
	LIMIT 10
");
$stmt->execute(['joueur_id' => $joueur_id]);
$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Mes statistiques - Plus ou Moins</title>
		<link href="style.css" rel="stylesheet">
	</head>
	<body class="app">
		<header class="topbar">
			<a href="acceuil.php" class="brand">± Plus ou Moins</a>
			<nav class="nav">
				<a href="acceuil.php">Jouer</a>
				<a href="leaderboard.php">Classement</a>
				<a href="statistics.php">Mes stats</a>
				<span class="nav-user"><?= htmlspecialchars($_SESSION['pseudo']) ?> | <a href="deconnection.php">Se déconnecter</a></span>
			</nav>
		</header>
		<main class="container">
			<h2 class="page-title">Mes statistiques</h2>

			<section class="stats-tiles">
				<div class="stat-tile">
					<div class="stat-value"><?= $total ?></div>
					<div class="stat-label">parties jouées</div>
				</div>
				<div class="stat-tile">
					<div class="stat-value"><?= $wins ?></div>
					<div class="stat-label">victoires</div>
				</div>
				<div class="stat-tile">
					<div class="stat-value"><?= $rate ?> %</div>
					<div class="stat-label">de réussite</div>
				</div>
			</section>

			<section class="leaderboard-card">
				<table class="leaderboard-table">
					<thead>
						<tr>
							<th>Niveau</th>
							<th>Parties</th>
							<th>Victoires</th>
							<th>Moy. essais</th>
							<th>Meilleur</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($by_level as $row): ?>
							<tr>
								<td><?= htmlspecialchars($row['nom']) ?></td>
								<td><?= (int)$row['parties'] ?></td>
								<td><?= (int)$row['wins'] ?></td>
								<td><?= $row['moy_essais'] !== null ? number_format((float)$row['moy_essais'], 1, ',', ' ') : '—' ?></td>
								<td><?= $row['best'] !== null ? (int)$row['best'] : '—' ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</section>

			<h3 class="stats-subtitle">Dernières parties</h3>
			<section class="leaderboard-card">
				<table class="leaderboard-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Niveau</th>
							<th>Essais</th>
							<th>Résultat</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!$recent): ?>
							<tr><td colspan="4" class="leaderboard-empty">Aucune partie jouée pour l'instant.</td></tr>
						<?php else: ?>
							<?php foreach ($recent as $row): ?>
								<tr>
									<td><?= date('d/m H:i', strtotime($row['debut_partie'])) ?></td>
									<td><?= htmlspecialchars($row['niveau']) ?></td>
									<td><?= (int)$row['essais_utilises'] ?></td>
									<td>
										<?php if ($row['resultat'] === 'gagnee'): ?>
											<span class="result-pill result-win">Gagnée</span>
										<?php else: ?>
											<span class="result-pill result-loss">Perdue</span>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</section>
		</main>
	</body>
</html>
