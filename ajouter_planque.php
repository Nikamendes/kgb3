<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire et nettoyer les entrées
    $nom = htmlspecialchars($_POST['nom']);
    $pays = $_POST['pays'];
    $adresse = htmlspecialchars($_POST['adresse']);
    $type = htmlspecialchars($_POST['type']);

    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête d'insertion avec des paramètres
        $query = "INSERT INTO kgb_planques (nom_de_code, pays, adresse, type) VALUES (:nom_de_code, :pays, :adresse, :type)";
        $stmt = $conn->prepare($query);

        // Liaison des valeurs des paramètres
        $stmt->bindParam(':nom_de_code', $nom);
        $stmt->bindParam(':pays', $pays);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':type', $type);

        // Exécution de la requête d'insertion
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
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style2.css" rel="stylesheet">
    
    <title>Ajouter une planque</title>
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
    <div class="container">
        <h1>Ajouter une planque</h1>

        <form action="ajouter_planque.php" method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de Code</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="pays">Pays de Mission :</label>
                <select name="pays[]" id="pays" multiple required>
                    <option value="Sélectionnez un pays">Sélectionnez un pays</option> <!-- Option par défaut -->
                </select>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <textarea class="form-control" id="type" name="type" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    <a href="planques.php" class="nav-items">Retourner aux Planques</a>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>