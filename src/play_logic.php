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
	$query = $dbh->prepare("INSERT INTO parties (joueur_id, niveau_id, nombre_secret, essais_utilises, resultat) VALUES (:joueur_id, :niveau_id, :nb_secret, 0, 'perdue')");
	$query->execute([
		"joueur_id" => get_userid($dbh),
		"niveau_id" => $_GET["niveau"],
		"nb_secret" => random_int($difficulties["min_nombre"], $difficulties["max_nombre"]),
	]);
	$_SESSION["game_id"] = (int)$dbh->lastInsertId();
}

function get_secret($dbh) {
	$query = $dbh->prepare("SELECT nombre_secret FROM parties WHERE id = :game_id");
	$query->execute(["game_id" => $_SESSION["game_id"]]);
	return (int)$query->fetch(PDO::FETCH_ASSOC)["nombre_secret"];
}

function inc_guess($dbh) {
	$query = $dbh->prepare("SELECT essais_utilises FROM parties WHERE id = :game_id");
	$query->execute(["game_id" => $_SESSION["game_id"]]);

	$nb_attempts = (int)($query->fetchAll(PDO::FETCH_ASSOC)[0]["essais_utilises"]);
	$nb_attempts++;

	$query = $dbh->prepare("UPDATE parties SET essais_utilises = :nb_attempts WHERE id=:game_id");
	$query->execute(["nb_attempts" => $nb_attempts, "game_id" => $_SESSION["game_id"]]);

}

function get_attempts($dbh) {
	$query = $dbh->prepare("SELECT essais_utilises FROM parties WHERE id = :game_id");
	$query->execute(["game_id" => $_SESSION["game_id"]]);

	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["essais_utilises"];
}

function get_total_attemps($dbh) {
	$query = $dbh->prepare("SELECT nb_essais FROM niveaus WHERE id=:id");
	$query->execute(["id" => $_GET["niveau"]]);
	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["nb_essais"];
}

function get_min($dbh) {
	$query = $dbh->prepare("SELECT min_nombre FROM niveaus WHERE id=:id");
	$query->execute(["id" => $_GET["niveau"]]);
	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["min_nombre"];
}

function get_max($dbh) {
	$query = $dbh->prepare("SELECT max_nombre FROM niveaus WHERE id=:id");
	$query->execute(["id" => $_GET["niveau"]]);
	return $query->fetchAll(PDO::FETCH_ASSOC)[0]["max_nombre"];
}

function end_game($dbh, $resultat) {
	$query = $dbh->prepare("UPDATE parties SET resultat = :resultat, fin_partie = NOW() WHERE id = :game_id");
	$query->execute(["resultat" => $resultat, "game_id" => $_SESSION["game_id"]]);
	unset($_SESSION["game_id"]);
}

if (!isset($_SESSION["game_id"])) {
	create_game($dbh);
}

$feedback  = null;
$error     = null;
$game_over = null;

if (isset($_POST["submit-guess"]) && isset($_POST["guess"])) {
	$guess  = (int)$_POST["guess"];
	$min    = (int)get_min($dbh);
	$max    = (int)get_max($dbh);
	$secret = get_secret($dbh);

	if ($guess < $min || $guess > $max) {
		$error = "Le nombre doit être entre $min et $max.";
	} else {
		inc_guess($dbh);

		if ($guess === $secret) {
			$used = (int)get_attempts($dbh);
			end_game($dbh, "gagnee");
			$game_over = [
				"won"     => true,
				"message" => "Gagné ! Le nombre était $secret. Trouvé en $used essais.",
			];
		} else {
			$feedback = ($guess < $secret) ? "C'est plus !" : "C'est moins !";

			if ((int)get_attempts($dbh) >= (int)get_total_attemps($dbh)) {
				end_game($dbh, "perdue");
				$game_over = [
					"won"     => false,
					"message" => "Perdu... Plus d'essais disponibles. Le nombre secret était $secret.",
				];
			}
		}
	}
}

?>
