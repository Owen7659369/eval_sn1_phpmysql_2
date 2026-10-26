<?php
// statistiques.php : page « Mes statistiques » (réservée au joueur connecté)
session_start();
require_once 'config.php'; // doit définir $dbh (objet PDO)

// 1) Accès réservé au joueur connecté
// ADAPTE le nom de la variable de session à celui utilisé dans ta page de connexion
if (!isset($_SESSION['joueur_id'])) {
    header('Location: connexion.php');
    exit;
}
$joueurId = (int) $_SESSION['joueur_id'];
$pseudo   = $_SESSION['pseudo'] ?? 'Joueur';

// 2) Chiffres globaux
$sql = "SELECT COUNT(*) AS parties,
               COALESCE(SUM(resultat = 'gagnee'), 0) AS victoires
        FROM partie
        WHERE joueur_id = :id";
$stmt = $dbh->prepare($sql);
$stmt->execute([':id' => $joueurId]);
$global = $stmt->fetch(PDO::FETCH_ASSOC);

$nbParties   = (int) $global['parties'];
$nbVictoires = (int) $global['victoires'];
$tauxReussite = $nbParties > 0 ? round($nbVictoires * 100 / $nbParties) : 0;

// 3) Détail par niveau (tous les niveaux, même ceux jamais joués)
// Moyenne et meilleur score calculés sur les parties gagnées uniquement
$sql = "SELECT n.nom,
               COUNT(p.id) AS parties,
               COALESCE(SUM(p.resultat = 'gagnee'), 0) AS victoires,
               AVG(CASE WHEN p.resultat = 'gagnee' THEN p.essais_utilises END) AS moy_essais,
               MIN(CASE WHEN p.resultat = 'gagnee' THEN p.essais_utilises END) AS meilleur
        FROM niveau n
        LEFT JOIN partie p ON p.niveau_id = n.id AND p.joueur_id = :id
        GROUP BY n.id, n.nom
        ORDER BY n.id";
$stmt = $dbh->prepare($sql);
$stmt->execute([':id' => $joueurId]);
$niveaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4) Historique des 10 dernières parties
$sql = "SELECT p.debut_partie, n.nom AS niveau, p.essais_utilises, p.resultat
        FROM partie p
        JOIN niveau n ON n.id = p.niveau_id
        WHERE p.joueur_id = :id
        ORDER BY p.debut_partie DESC
        LIMIT 10";
$stmt = $dbh->prepare($sql);
$stmt->execute([':id' => $joueurId]);
$historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mes statistiques - Plus ou Moins</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #fff; color: #222; }
        nav { background: #232850; color: #fff; padding: 14px 24px; display: flex; justify-content: space-between; }
        nav a { color: #fff; text-decoration: none; margin-left: 16px; font-size: 14px; }
        main { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .cartes { display: flex; gap: 16px; margin-bottom: 24px; }
        .carte { flex: 1; text-align: center; background: #f4f4f9; border: 1px solid #dcdce8; border-radius: 8px; padding: 16px; }
        .carte .valeur { font-size: 32px; font-weight: bold; color: #232850; }
        .carte .label { font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 14px; }
        th { background: #232850; color: #fff; text-align: left; padding: 6px 8px; }
        td { padding: 6px 8px; border: 1px solid #dcdce8; }
        tr:nth-child(even) td { background: #f4f4f9; }
    </style>
</head>
<body>
    <nav>
        <strong>± Plus ou Moins</strong>
        <div>
            <a href="jeu.php">Jouer</a>
            <a href="classement.php">Classement</a>
            <a href="statistiques.php">Mes stats</a>
            <a href="deconnexion.php"><?= htmlspecialchars($pseudo) ?> | Se déconnecter</a>
        </div>
    </nav>

    <main>
        <h1>Mes statistiques</h1>

        <div class="cartes">
            <div class="carte"><div class="valeur"><?= $nbParties ?></div><div class="label">parties jouées</div></div>
            <div class="carte"><div class="valeur"><?= $nbVictoires ?></div><div class="label">victoires</div></div>
            <div class="carte"><div class="valeur"><?= $tauxReussite ?> %</div><div class="label">de réussite</div></div>
        </div>

        <h2>Détail par niveau</h2>
        <table>
            <tr><th>Niveau</th><th>Parties</th><th>Victoires</th><th>Moy. essais</th><th>Meilleur</th></tr>
            <?php foreach ($niveaux as $n): ?>
                <tr>
                    <td><?= htmlspecialchars($n['nom']) ?></td>
                    <td><?= $n['parties'] ?></td>
                    <td><?= $n['victoires'] ?></td>
                    <td><?= $n['moy_essais'] !== null ? number_format($n['moy_essais'], 1, ',', '') : '-' ?></td>
                    <td><?= $n['meilleur'] !== null ? $n['meilleur'] : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Dernières parties</h2>
        <?php if (count($historique) === 0): ?>
            <p>Tu n'as pas encore joué de partie.</p>
        <?php else: ?>
            <table>
                <tr><th>Date</th><th>Niveau</th><th>Essais</th><th>Résultat</th></tr>
                <?php foreach ($historique as $h): ?>
                    <tr>
                        <td><?= date('d/m H:i', strtotime($h['debut_partie'])) ?></td>
                        <td><?= htmlspecialchars($h['niveau']) ?></td>
                        <td><?= $h['essais_utilises'] ?></td>
                        <td><?= $h['resultat'] === 'gagnee' ? 'Gagnée' : 'Perdue' ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>