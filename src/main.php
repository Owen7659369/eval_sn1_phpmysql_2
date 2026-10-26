<?php

if (!isset($_SESSION["to-guess"])) {
	$_SESSION["to-guess"] = randint();
}

if (isset($_POST["check-res"])) {
    if ($_POST["value"] == $solution) {
		echo "good";
    } else {
		echo "not good. got: " . $_POST["value"]  . "exp: " . $solution;
    }
}

?>
