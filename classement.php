
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement - Plus ou Moins</title>
</head>
<body>

    <h1>Classement - Plus ou Moins</h1>

    <h2>Choisis un niveau</h2>

    <nav>
        <a href="classement.php?niveau=1">Facile</a>
        <a href="classement.php?niveau=2">Normal</a>
        <a href="classement.php?niveau=3">Difficile</a>
        <a href="classement.php?niveau=4">Expert</a>
    </nav>

    <h2>Les meilleures parties</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Joueur</th>
                <th>Essais</th>
                <th>Durée</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="5">Le classement sera affiché ici.</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
