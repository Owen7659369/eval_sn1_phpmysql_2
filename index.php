<?php
require_once 'config.php';
$solution = random_int(1, 10);
include 'main.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Just Prix</title>
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<form method="post">
			<input type="number" name="value">
			<button type="submit" name="check-res">submit</button>
		</form>
	</body>
</html>
