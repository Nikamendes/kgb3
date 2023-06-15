<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['date_naissance'];
    $codeIdentification = $_POST['code_identification'];
    $nationalite = implode(',', $_POST['nationalite']);
    $selectedCompetences = isset($_POST['competences']) ? $_POST['competences'] : [];

    try {
        // Insérer les informations de l'agent dans la base de données
        $query = "INSERT INTO kgb_agents (nom, prenom, date_naissance, code_identification, nationalite, competences) VALUES (:nom, :prenom, :date_naissance, :code_identification, :nationalite, :competences)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $dateNaissance);
        $stmt->bindParam(':code_identification', $codeIdentification);
        $stmt->bindParam(':nationalite', $nationalite);
        $stmt->bindParam(':competences', implode(',', $selectedCompetences)); // Convertir le tableau en une chaîne séparée par des virgules
        $stmt->execute();

        // Récupérer l'ID de l'agent nouvellement inséré
        $agentId = $conn->lastInsertId();

        $_SESSION['success_message'] = "L'agent a été ajouté avec succès.";
        header("Location: agents.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de l'ajout de l'agent : " . $e->getMessage();
    }
}
?>

<!-- Maintenant on commence le HTML -->

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style2.css" rel="stylesheet">

    <style>

    </style>

    <title>Agents</title>
</head>
<body>
    <div class="container">
        <div id="agent-container">
            <div id="agent-info" class="agent-section">
                <h2>Ajouter un agent</h2>
                <form method="POST" action="" id="agent-form">
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
                        <label for="code_identification">Code d'identification :</label>
                        <input type="text" name="code_identification" id="code_identification" required>
                    </div>

                    <div class="form-group">
                        <label for="nationalite">Nationalité :</label>
                        <select name="nationalite[]" id="nationalite" multiple required>
                            <option value="" disabled selected>Sélectionnez une nationalité</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="competences">Compétences :</label>
                        <select name="competences[]" id="competences" multiple required>
                            <option value="Discrétion">Discrétion</option>
                            <option value="Arts Martiaux">Arts Martiaux</option>
                            <option value="Vitesse">Vitesse</option>
                            <option value="Déguisement">Déguisement</option>
                        </select>
                    </div>

                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <a href="agents.php" class="nav-items">Retour aux Agents</a>

    <!-- Ici on va inclure tous les scripts qu'on veut utiliser, comme JQuery, Bootstrap, etc -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Fonction pour récupérer les nationalités à partir de l'API "REST Countries"
        function fetchCountries() {
          const apiUrl = "https://restcountries.com/v3.1/all";	// lien de l'api

          return fetch(apiUrl)	// On va passer le code en json
            .then(response => response.json())
            .then(data => {
              const nationalities = Object.values(data).map(country => country.name.common);	// On va récupérer les nationalités uniquement
              return nationalities.sort(); // Tri des nationalités par ordre alphabétique et envoi des résultats
            });
        }

        // Fonction pour générer la liste déroulante des nationalités
        function generateNationalitiesDropdown() {
            fetchCountries().then(nationalities => {
                const nationaliteDropdown = document.getElementById("nationalite");	// On va chercher là où on va afficher la liste déroulante
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
</body>
</html>