<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si l'ID de la planque est passé en paramètre
if (isset($_GET['id'])) {
    $planqueId = $_GET['id'];

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'];
        $pays = implode(',', $_POST['pays']);
        $adresse = $_POST['adresse'];
        $type = $_POST['type'];

        try {
            // Mettre à jour la planque dans la base de données
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "UPDATE kgb_planques SET nom_de_code = :nom_de_code, pays = :pays, adresse = :adresse, type = :type WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nom_de_code', $nom);
            $stmt->bindParam(':pays', $pays);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':id', $planqueId);
            $stmt->execute();

            // Rediriger vers la liste des planques avec un message de succès
            session_start();
            $_SESSION['success_message'] = "La planque a été modifiée avec succès.";
            header("Location: planques.php");
            exit();
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    try {
        // Récupérer les informations de la planque à modifier depuis la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM kgb_planques WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $planqueId);
        $stmt->execute();

        $planque = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si la planque existe
        if (!$planque) {
            // Rediriger vers la liste des planques avec un message d'erreur
            session_start();
            $_SESSION['error_message'] = "La planque sélectionnée n'existe pas.";
            header("Location: planques.php");
            exit();
        }
    } catch(PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
} else {
    // Rediriger vers la liste des planques si l'ID n'est pas spécifié
    header("Location: planques.php");
    exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style3.css" rel="stylesheet">
    
    <title>Modifier une planque</title>
</head>
<body>
    <div class="container">
        <h1>Modifier une planque</h1>

        <form action="modifier_planque.php?id=<?php echo $planqueId; ?>" method="POST">
            <?php if ($planque): ?>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de Code</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($planque['nom_de_code']); ?>" required>
            </div>
            <div class="mb-3">
                <div class="form-group"> 
                <label for="pays">Pays de Mission :</label>
                <select name="pays[]" id="pays" multiple required>
                    <!-- Les options seront générées dynamiquement par le script -->
                </select>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse</label>
                <textarea class="form-control" id="adresse" name="adresse" rows="3" required><?php echo htmlspecialchars($planque['adresse']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($planque['type']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <?php endif; ?>
        </form>
    </div>

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
