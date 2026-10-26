<?php

function get_difficulties($dbh) {
	$query = $dbh->prepare("SELECT * FROM niveaus;");
	$query->execute();
	return $query->fetchAll(PDO::FETCH_ASSOC);
}

function build_menu($data) {
	foreach ($data as $item) {
		$slug = strtolower(preg_replace('/[^a-z0-9]/i', '', $item["nom"]));
		echo "<a href='play.php?niveau=" . (int)$item["id"] . "' class='niveau-card niveau-" . $slug . "'>
			<h3 class='niveau-nom'>" . htmlspecialchars($item["nom"]) . "</h3>
			<h1 class='niveau-plage'>" . (int)$item["min_nombre"] . " à " . (int)$item["max_nombre"] . "</h1>
			<p class='niveau-label'>nombre secret</p>
			<h3 class='niveau-essais'>" . (int)$item["nb_essais"] . " essais</h3>
		</a>";
	}
}

if (!isset($_SESSION["pseudo"])) {
	header("Location: connection.php");
}

?>
