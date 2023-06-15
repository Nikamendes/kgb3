<?php
// fichier qui contient la configuration de la base de données
require_once './back/config.php';
require_once './back/gestion.php';

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des agents depuis la base de données
    $query = "SELECT * FROM kgb_agents";
    $stmt = $conn->query($query);
    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style1.css">
    <title>Liste des agents</title>
</head>

<body>
   
<h1>Liste des agents</h1>
<a href="ajouter_agent.php">Ajouter un agent</a>

<table>

    <thead>
      <tr>
         <th>Nom</th>
         <th>Prénom</th>
         <th>Date Naissance</th>
         <th>Code Identification</th>
         <th>Nationalite</th>
         <th>Competences</th>
         <th>Actions</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($agents as $agent): ?>
      <tr>
        <td><?php echo htmlspecialchars($agent['nom']); ?></td>
        <td><?php echo htmlspecialchars($agent['prenom']); ?></td>
        <td><?php echo htmlspecialchars($agent['date_naissance']); ?></td>
        <td><?php echo htmlspecialchars($agent['code_identification']); ?></td>
        <td><?php echo htmlspecialchars($agent['nationalite']); ?></td>
        <td><?php echo htmlspecialchars($agent['competences']); ?></td>
        <td>
        <a href="modifier_agent.php?id=<?php echo $agent['id']; ?>">Modifier</a>
<a href="supprimer_agent.php?id=<?php echo $agent['id']; ?>">Supprimer</a>
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