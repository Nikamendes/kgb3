<?php
// fichier qui contient la configuration de la base de données
require_once './back/config.php';
require_once './back/gestion.php';

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des missions depuis la base de données
    $query = "SELECT * FROM kgb_missions";
    $stmt = $conn->query($query);
    $missions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="style1.css">
   
    <title>Liste des missions</title>
</head>

<body>
   

<div class="container">
        <h1>Liste des Missions</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

<a href="ajouter_mission.php" class="btn btn-primary mb-3">Ajouter une mission</a>

<table>

    <thead>
      <tr>
         <th>Titre</th>
         <th>Description</th>
         <th>Nom de Code</th>
         <th>Pays</th>
         <th>Statut</th>
         <th>Début de mission</th>
         <th>Fin de mission</th>
         <th>Actions</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($missions as $mission): ?>
      <tr>
        <td><?php echo $mission['titre']; ?></td>
        <td><?php echo $mission['description']; ?></td>
        <td><?php echo $mission['nom_de_code']; ?></td>
        <td><?php echo $mission['pays']; ?></td>
        <td><?php echo $mission['statut']; ?></td>
        <td><?php echo $mission['debut_mission']; ?></td>
        <td><?php echo $mission['fin_mission']; ?></td>
        <td>
            <a href="modifier_mission.php?id=<?php echo $mission['id']; ?>" class="btn btn-primary btn-sm">Modifier</a>
            <a href="supprimer_mission.php?id=<?php echo $mission['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>

</table>

<footer>SKG site &copy; 2023</footer>
  
<!-- Ici on va inclure tous les scripts qu'on veut utiliser, comme JQuery, Bootstrap, etc -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>