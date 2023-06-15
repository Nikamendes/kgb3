<?php
require_once './back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire et les nettoyer
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $pays = implode(',', array_map('htmlspecialchars', $_POST['pays'])); // Convertir le tableau en chaîne de caractères
    $adresse = htmlspecialchars($_POST['adresse']);

    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparer la requête d'insertion avec des paramètres sécurisés
        $query = "INSERT INTO kgb_contacts (nom, prenom, pays, adresse) VALUES (:nom, :prenom, :pays, :adresse)";
        $stmt = $conn->prepare($query);

        // Binder les valeurs des paramètres
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':pays', $pays);
        $stmt->bindParam(':adresse', $adresse);

        // Exécuter la requête
        $stmt->execute();

        // Rediriger vers la liste des contacts après l'ajout
        header("Location: contact.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un contact</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    <script>
        // Fonction pour récupérer les nationalités à partir de l'API "REST Countries"
        function fetchCountries() {
            const apiUrl = "https://restcountries.com/v3.1/all"; // lien de l'api

            return fetch(apiUrl) // On va passer le code en json
                .then(response => response.json())
                .then(data => {
                    const nationalities = Object.values(data).map(country => country.name.common); // On va récupérer les nationalités uniquement
                    return nationalities.sort(); // Tri des nationalités par ordre alphabétique et envoi des résultats
                });
        }

        // Fonction pour générer la liste déroulante des nationalités
        function generateNationalitiesDropdown() {
            fetchCountries().then(nationalities => {
                const nationaliteDropdown = document.getElementById("pays"); // On va chercher là où on va afficher la liste déroulante
                nationalities.forEach(nationality => {
                    const option = document.createElement("option");
                    option.text = nationality;
                    option.value = nationality;
                    nationaliteDropdown.appendChild(option);
                });
            });
        }

        // Appeler la fonction lors du chargement de la page
        window.onload = generateNationalitiesDropdown;
    </script>
</head>
<body>
    <div id="title-container">
        <h1 id="title">Ajouter un contact</h1>
    </div>
    <div class="container">
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required>
            </div>
            <div class="form-group">
                <label for="pays">Pays :</label>
                <select name="pays[]" id="pays" multiple required>
                    <option value="Sélectionnez une nationalité">Sélectionnez une nationalité</option> <!-- Option par défaut -->
                </select>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" name="adresse" id="adresse" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <a href="contact.php">Retour aux contacts</a>
</body>
</html>