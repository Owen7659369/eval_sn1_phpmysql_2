<?php

function get_difficulties($dbh) {
	$query = $dbh->prepare("SELECT * FROM niveaus WHERE id = :id;");
	$query->execute(["id" => $_GET["niveau"]]);
	return $query->fetchAll(PDO::FETCH_ASSOC)[0];
}

function get_userid($dbh) {
	$query = $dbh->prepare("SELECT id FROM joueurs WHERE pseudo = :username;");
	$query->execute([
		"username" => $_SESSION["pseudo"]
	]);
	return $query->fetchAll(PDO::FETCH_ASSOC)[0];
}

function create_game($dbh) {
	$difficulties = get_difficulties($dbh);
	$query = $dbh->prepare("INSERT INTO parties VALUES (:joueur_id, :niveau_id, ?, ?, ?);");
	$query->execute([
		get_userid($dbh),
		$_GET["niveau"],
		random_int($difficulties["min_nombre"], $difficulties["max_nombre"]),
		0,
		null
	]);

	$query = $dbh->prepare("SELECT id FROM parties WHERE joueur_id = ? ORDER BY debut_partie");
	$query->execute([get]);
}

if (!isset($_GET["game_id"])) {
	create_game($dbh);
}


?>
