<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['date_naissance'];
    $pays = $_POST['pays'];
    $nomDeCode = $_POST['nom_de_code'];

    try {
        // Insérer les informations de la cible dans la base de données
        $query = "INSERT INTO kgb_cibles (nom, prenom, date_naissance, pays, nom_de_code) VALUES (:nom, :prenom, :date_naissance, :pays, :nom_de_code)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':date_naissance', $dateNaissance, PDO::PARAM_STR);
        $paysString = implode(',', $pays);
        $stmt->bindParam(':pays', $paysString, PDO::PARAM_STR);
        $stmt->bindParam(':nom_de_code', $nomDeCode, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['success_message'] = "La cible a été ajoutée avec succès.";
        header("Location: cibles.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de l'ajout de la cible : " . $e->getMessage();
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

    <title>Cibles</title>
</head>
<body>
    <div class="container">
        <div id="cible-container">
            <div id="cible-info" class="cible-section">
                <h2>Ajouter une cible</h2>
                <form method="POST" action="" id="cible-form">
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" id="nom" required>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" required>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" name="date_naissance" id="date_naissance" required>
                    </div>

                    <div class="form-group">
                        <label for="pays">Pays :</label>
                        <select name="pays[]" id="pays" multiple required>
                            <option value="" disabled selected>Sélectionnez un pays</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nom_de_code">Nom de code :</label>
                        <input type="text" name="nom_de_code" id="nom_de_code" required>
                    </div>

                    <button type="submit">Ajouter</button>
                </form>
            </div>

            <a href="cibles.php">Retour aux Cibles</a>
        </div>
    </div>

    <!-- Ici on va inclure tous les scripts qu'on veut utiliser, comme JQuery, Bootstrap etc -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Fonction pour récupérer les pays depuis l'API "REST Countries"
        function fetchCountries() {
            const apiUrl = "https://restcountries.com/v3.1/all"; // L'URL de l'API

            return fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const countries = data.map(country => country.name.common); // On récupère les noms des pays uniquement
                    return countries.sort(); // Tri des pays par ordre alphabétique et envoi des résultats
                });
        }

        // Fonction pour générer la liste déroulante des pays
        function generateCountriesDropdown() {
            fetchCountries().then(countries => {
                const paysDropdown = document.getElementById("pays"); // On va chercher l'élément où on va afficher la liste déroulante
                countries.forEach(country => {
                    const option = document.createElement("option");
                    option.text = country;
                    option.value = country;
                    paysDropdown.appendChild(option);
                });
            });
        }

        // Appeler la fonction lors du chargement de la page
        window.onload = generateCountriesDropdown;
    </script>
</body>
</html>