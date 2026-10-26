<?php

try {
	$dbh = new PDO(
    	'mysql:host=localhost;dbname=jp',
    	'root',
    	'root'
	);
} catch (PDOException $e) {
	die($e->getMessage());
}

session_start();
?>
