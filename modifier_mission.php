<?php
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID de la mission à modifier
    $missionId = $_POST['mission_id'];

    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $nom_de_code = $_POST['nom_de_code'];
    $description = $_POST['description'];
    $pays = implode(',', $_POST['pays']);
    $statut = $_POST['statut'];
    $debut_mission = $_POST['debut_mission'];
    $fin_mission = $_POST['fin_mission'];

    try {
        // Mettre à jour les informations de la mission dans la base de données
        $query = "UPDATE kgb_missions SET titre = :titre, nom_de_code = :nom_de_code, description = :description, pays = :pays, statut = :statut, debut_mission = :debut_mission, fin_mission = :fin_mission WHERE id = :mission_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':nom_de_code', $nom_de_code);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':pays', $pays);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':debut_mission', $debut_mission);
        $stmt->bindParam(':fin_mission', $fin_mission);
        $stmt->bindParam(':mission_id', $missionId);
        $stmt->execute();

        $_SESSION['success_message'] = "La mission a été modifiée avec succès.";
        header("Location: missions.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de la modification de la mission : " . $e->getMessage();
        header("Location: missions.php");
        exit();
    }
} else {
    // Vérifier si l'ID de la mission est passé en paramètre d'URL
    if (isset($_GET['id'])) {
        // Récupérer l'ID de la mission
        $missionId = $_GET['id'];

        try {
            // Récupérer les informations de la mission depuis la base de données
            $query = "SELECT * FROM kgb_missions WHERE id = :mission_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':mission_id', $missionId);
            $stmt->execute();

            $mission = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mission) {
                $_SESSION['error_message'] = "La mission demandée n'existe pas.";
                header("Location: missions.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Une erreur s'est produite lors de la récupération de la mission : " . $e->getMessage();
            header("Location: missions.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "L'ID de la mission n'a pas été spécifié.";
        header("Location: missions.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une mission</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="container">
        <h1>Modifier une mission</h1>
        <form method="POST" action="">
            <input type="hidden" name="mission_id" value="<?php echo $mission['id']; ?>">

            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="nom" value="<?php echo $mission['titre']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nom_de_code">Nom de Code :</label>
                <input type="text" name="nom_de_code" id="nom_de_code" value="<?php echo $mission['nom_de_code']; ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description de la mission :</label>
                <textarea name="description" id="description" rows="5" required><?php echo $mission['description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="pays">Pays de Mission :</label>
                <select name="pays[]" id="pays" multiple required>
                    <!-- Les options seront générées dynamiquement par le script -->
                </select>
            </div>

            <div class="form-group">
                <label for="statut">Statut de Mission :</label>
                <select name="statut" id="statut" required>
                    <option value="Préparation" <?php if ($mission['statut'] == 'Préparation') echo 'selected'; ?>>Préparation</option>
                    <option value="En cours" <?php if ($mission['statut'] == 'En cours') echo 'selected'; ?>>En cours</option>
                    <option value="Terminée" <?php if ($mission['statut'] == 'Terminée') echo 'selected'; ?>>Terminée</option>
                </select>
            </div>

            <div class="form-group">
                <label for="debut_mission">Début de mission:</label>
                <input type="date" name="debut_mission" id="debut_mission" value="<?php echo $mission['debut_mission']; ?>" required>
            </div>

           <div class="form-group">
              <label for="fin_mission">Fin de Mission :</label>
               <input type="date" name="fin_mission" id="fin_mission" value="<?php echo $mission['fin_mission']; ?>" required>
            </div>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <a href="missions.php">Retourner aux missions</a>

    <script>
        // Fonction pour récupérer les pays à partir de l'API "REST Countries"
        function fetchCountries() {
            const apiUrl = "https://restcountries.com/v3.1/all";

            return fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const countries = Object.values(data).map(country => country.name.common);
                    return countries.sort();
                });
        }

        // Fonction pour générer la liste déroulante des pays
        function generateCountriesDropdown() {
            fetchCountries().then(countries => {
                const paysDropdown = document.getElementById("pays");
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