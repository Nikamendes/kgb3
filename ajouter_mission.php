<?php
// fichier qui contient la configuration de la base de données
require_once './back/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire et nettoyage
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $nomDeCode = htmlspecialchars($_POST['nom_de_code']);
    $pays = $_POST['pays'];
    $statut = htmlspecialchars($_POST['statut']);
    $debutMission = htmlspecialchars($_POST['debut_mission']);
    $finMission = htmlspecialchars($_POST['fin_mission']);

    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparation de la requête d'insertion avec des paramètres
        $query = "INSERT INTO kgb_missions (titre, description, nom_de_code, pays, statut, debut_mission, fin_mission) VALUES (:titre, :description, :nom_de_code, :pays, :statut, :debut_mission, :fin_mission)";
        $stmt = $conn->prepare($query);

        // Liaison des valeurs des paramètres
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':nom_de_code', $nomDeCode);

        // Conversion du tableau de pays en une chaîne séparée par des virgules
        $paysStr = implode(',', $pays);
        $stmt->bindParam(':pays', $paysStr);

        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':debut_mission', $debutMission);
        $stmt->bindParam(':fin_mission', $finMission);

        // Exécution de la requête d'insertion
        $stmt->execute();

        // Redirection vers la page de liste des missions après l'ajout
        header("Location: missions.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}

// Récupération des pays déjà sélectionnés pour la mission
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données de la mission
    $query = "SELECT pays FROM kgb_missions WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id); // Remplacez $id par l'identifiant de la mission actuelle
    $stmt->execute();
    $mission = $stmt->fetch();

    $pays = explode(',', $mission['pays']); // Conversion de la chaîne de pays en un tableau
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
    <link href="style4.css" rel="stylesheet">
    <title>Ajouter une mission</title>
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
   
<h1>Ajouter une mission</h1>

<form method="POST" action="">
    <div class="form-group">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Description :</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="nom_de_code">Nom de Code :</label>
        <input type="text" name="nom_de_code" id="nom_de_code" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="pays">Pays de Mission :</label>
        <select name="pays[]" id="pays" multiple required>
            <option value="Sélectionnez un pays">Sélectionnez un pays</option> <!-- Option par défaut -->
        </select>
    </div>
    <div class="form-group">
        <label for="statut">Statut :</label>
        <select name="statut" id="statut" class="form-control" required>
            <option value="En cours">En cours</option>
            <option value="Terminée">Terminée</option>
            <option value="Annulée">Annulée</option>
        </select>
    </div>
    <div class="form-group">
        <label for="debut_mission">Début de mission :</label>
        <input type="date" name="debut_mission" id="debut_mission" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="fin_mission">Fin de mission :</label>
        <input type="date" name="fin_mission" id="fin_mission" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
</form>
<a href="missions.php">Retour aux Missions</a>

<footer>SKG site &copy; 2023</footer>
  
<!-- Ici on va inclure tous les scripts qu'on veut utiliser, comme JQuery, Bootstrap, etc -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>






