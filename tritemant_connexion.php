<?php
require_once './back/config.php';
require_once './back/gestion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire et les valider
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $motDePasse = $_POST['mot_de_passe'];

    if ($email && $motDePasse) {
        // Rechercher l'utilisateur dans la base de données en utilisant une requête préparée
        $sql = "SELECT id, nom, mot_de_passe FROM kgb_utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            // Mettre à jour la date de dernière connexion en utilisant une requête préparée
            $sql = "UPDATE utilisateurs SET date_derniere_connexion = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user['id']]);

            // Créer une session pour l'utilisateur en utilisant des noms de clés de session plus génériques
            session_start();
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['utilisateur_nom'] = $user['nom'];

            // Rediriger vers la page protégée
            header("Location: accueil.php");
            exit();
        } else {
            // Identifiants invalides, afficher un message d'erreur
            echo "Identifiants invalides";
        }
    } else {
        // Données d'entrée invalides, afficher un message d'erreur
        echo "Données d'entrée invalides";
    }
}
?>