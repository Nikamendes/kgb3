<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

require_once './back/gestion.php';

// Vérifier si le formulaire d'ajout a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $pays = $_POST['pays'];
    $adresse = $_POST['adresse'];
    $type = $_POST['type'];

    try {
        // Insérer la nouvelle planque dans la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO kgb_planques (nom_de_code, pays, adresse, type) VALUES (:nom_de_code, :pays, :adresse, :type)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom_de_code', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':pays', $pays, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();

        // Rediriger vers la liste des planques avec un message de succès
        session_start();
        $_SESSION['success_message'] = "La planque a été ajoutée avec succès.";
        header("Location: planques.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}

// Vérifier si l'ID de la planque est passé en paramètre pour la suppression
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $planqueId = $_GET['id'];

    try {
        // Supprimer la planque de la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM kgb_planques WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $planqueId, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la liste des planques avec un message de succès
        session_start();
        $_SESSION['success_message'] = "La planque a été supprimée avec succès.";
        header("Location: planques.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}

// Récupérer la liste des planques depuis la base de données
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM kgb_planques";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $planques = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="style1.css" rel="stylesheet">
    
    <title>Liste des planques</title>
</head>
<body>
    <div class="container">
        <h1>Liste des planques</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <a href="ajouter_planque.php">Ajouter une planque</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Nom de Code</th>
                    <th>Pays</th>
                    <th>Adresse</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($planques as $planque): ?>
                <tr>
                    <td><?php echo htmlspecialchars($planque['nom_de_code']); ?></td>
                    <td><?php echo htmlspecialchars($planque['pays']); ?></td>
                    <td><?php echo htmlspecialchars($planque['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($planque['type']); ?></td>
                    <td>
                        <a href="modifier_planque.php?id=<?php echo $planque['id']; ?>">Modifier</a>
                        <a href="planques.php?action=supprimer&id=<?php echo $planque['id']; ?>">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>