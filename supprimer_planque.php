<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

require_once './back/gestion.php';

// Vérifier si l'ID de la planque est passé en paramètre
if (isset($_GET['id'])) {
    $planqueId = $_GET['id'];

    try {
        // Supprimer la planque de la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM kgb_planques WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $planqueId);
        $stmt->execute();

        // Rediriger vers la liste des planques avec un message de succès
        session_start();
        $_SESSION['success_message'] = "La planque a été supprimée avec succès.";
        header("Location: planques.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
} else {
    // Rediriger vers la liste des planques si l'ID n'est pas spécifié
    header("Location: planques.php");
    exit();
}
?>