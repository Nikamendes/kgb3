<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './back/config.php';

// Vérifier les informations d'identification de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['password'];

    if (verifyCredentials($email, $mot_de_passe)) {
        // Les informations d'identification sont correctes, rediriger vers une page accueil.php
        header("Location: accueil.php");
        exit();
    } else {
        // Les informations d'identification sont incorrectes, afficher un message d'erreur
        $errorMessage = "Identifiants incorrects. Veuillez réessayer.";
    }
}

// Fonction pour vérifier les informations d'identification de l'utilisateur
function verifyCredentials($email, $mot_de_passe) {
    global $conn;

    // Préparez la requête avec des paramètres pour éviter les injections SQL
    $query = "SELECT * FROM kgb_utilisateurs WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt ->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_pass'])) {
        return true; // Les informations d'identification sont valides
    }

    return false; // Les informations d'identification sont invalides
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Interface de Gestion</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id="title-container">
        <h1 id="title">Interface de Gestion</h1>
    </div>
    <div class="container">
        <form method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <?php if (isset($errorMessage)): ?>
                <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <div id="login-area">
                <a id="forgot-password" href="#">Mot de passe oublié ?</a>
                <button id="login-button" type="submit">Connexion</button>
            </div>
        </form>
    </div>
</body>
</html>