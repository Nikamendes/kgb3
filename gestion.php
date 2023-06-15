<?php
$menu = '
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      
        <li class="nav-item">
                <a href="deconnexion.php">Déconnexion</a>
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="agents.php">Agents</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="missions.php">Missions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cibles.php">Cibles</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contacts</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="planques.php">Planques</a>
                </li>
            </ul>
        </div>
    </div>
</nav>';

echo $menu;

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="gestion.css">
<?php
# Fonction pour afficher TOUS les kgb
function requete1($conn) {
    $sql = "SELECT * FROM k35gck9e_projets";
    $result = $conn->prepare($sql);
    $result->execute();

    # Si aucun résultat n'est trouvé, on affiche un message
    if ($result->rowCount() == 0) {
        return "Aucune donnée à afficher pour le moment";
    }

    return $result;
}

?>


