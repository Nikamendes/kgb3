<?php
require_once './back/gestion.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Protégée</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="title-container">
        <h1 id="title">Bienvenue,!</h1>
    </div>
    <div class="container">
        <h2>Contenu protégé</h2>
        <p>Vous êtes connecté en tant qu'utilisateur </p>
       
        <a href="deconnexion.php">Déconnexion</a>
    </div>
</body>
</html>
