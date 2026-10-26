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
	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["id"];
}

function create_game($dbh) {
	$difficulties = get_difficulties($dbh);
	$query = $dbh->prepare("INSERT INTO parties (joueur_id, niveau_id, nombre_secret, essais_utilises, resultat, debut_partie, fin_partie) VALUES (:joueur_id, :niveau_id, :nb_secret, :nb_attempts, :result, :start, :end);");
	$query->execute([
		"joueur_id" => get_userid($dbh),
		"niveau_id" => $_GET["niveau"],
		"nb_secret" => random_int($difficulties["min_nombre"], $difficulties["max_nombre"]),
		"nb_attempts" => 0,
		"result" => "perdue",
		"start" => null,
		"end" => null,
	]);

	$query = $dbh->prepare("SELECT id FROM parties WHERE joueur_id = ? ORDER BY debut_partie");
	$query->execute([get_userid($dbh)]);
	$game_id = $query->fetchAll(PDO::FETCH_ASSOC);
	$_SESSION["game_id"] = $game_id;

}

function get_secret($dbh) {
	$query = $dbh->prepare("SELECT nombre_secret FROM parties");
	$query->execute();
	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["nombre_secret"];
}

function inc_guess($dbh) {
	$query = $dbh->prepare("SELECT essais_utilises FROM parties WHERE id = :game_id");
	$query->execute(["game_id" => $_SESSION["game_id"]]);
	
	$nb_attempts = intval($query->fetchAll(PDO::FETCH_ASSOC)[0]["essais_utilises"]);
	$nb_attempts++;

	$query = $dbh->prepare("INSERT INTO parties (essais_utilises) VALUES (:nb_attempts)");
	$query->execute(["essais_utilises" => nb_attempts]);

}

if (!isset($_SESSION["game_id"])) {
	create_game($dbh);
}

if (isset($_POST["submit-guess"])) {
	$secret = get_secret($dbh);
	inc_guess($dbh);
}

?>
