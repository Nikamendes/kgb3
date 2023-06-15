<?php
require_once './back/config.php';
require_once './back/gestion.php';

// Vérifier si l'ID du contact est passé en paramètre
if (!isset($_GET['id'])) {
    header("Location: contact.php");
    exit();
}

// Récupérer l'ID du contact
$contactId = $_GET['id'];

// Supprimer le contact de la base de données
try {
    $query = "DELETE FROM kgb_contacts WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $contactId);
    $stmt->execute();

    // Rediriger vers la page des contacts
    header("Location: contact.php");
    exit();
} catch(PDOException $e) {
    echo "Erreur de suppression du contact : " . $e->getMessage();
}
?>