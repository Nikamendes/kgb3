<?php
require_once './back/config.php';
require_once './back/gestion.php';

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des contacts depuis la base de données
    $query = "SELECT * FROM kgb_contacts";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des contacts</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <div id="title-container">
        <h1 id="title">Liste des contacts</h1>
    </div>
    <div class="container">
        <a href="ajouter_contact.php" class="btn btn-primary mb-3">Ajouter un contact</a>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date Naissance</th>
                    <th>Pays</th>
                    <th>Adresse</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['nom']); ?></td>
                        <td><?php echo htmlspecialchars($contact['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($contact['date_naissance']); ?></td>
                        <td><?php echo htmlspecialchars($contact['pays']); ?></td>
                        <td><?php echo htmlspecialchars($contact['adresse']); ?></td>
                        <td>
                            <a href="modifier_contacts.php?id=<?php echo htmlspecialchars($contact['id']); ?>" class="btn btn-primary btn-sm">Modifier</a>
                            <a href="supprimer_contacts.php?id=<?php echo htmlspecialchars($contact['id']); ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>