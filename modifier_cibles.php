<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID de la cible à modifier
    $cibleId = $_POST['cible_id'];

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['date_naissance'];
    $pays = $_POST['pays'];
    $nomDeCode = $_POST['nom_de_code'];

    try {
        // Mettre à jour les informations de la cible dans la base de données
        $query = "UPDATE kgb_cibles SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, pays = :pays, nom_de_code = :nom_de_code WHERE id = :cible_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $dateNaissance);
        $paysString = implode(',', $pays);
        $stmt->bindParam(':pays', $paysString);
        $stmt->bindParam(':nom_de_code', $nomDeCode);
        $stmt->bindParam(':cible_id', $cibleId);
        $stmt->execute();

        $_SESSION['success_message'] = "La cible a été modifiée avec succès.";
        header("Location: cibles.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de la modification de la cible : " . $e->getMessage();
    }
} else {
    // Vérifier si l'ID de la cible est passé en paramètre d'URL
    if (isset($_GET['id'])) {
        // Récupérer l'ID de la cible
        $cibleId = $_GET['id'];

        try {
            // Récupérer les informations de la cible depuis la base de données
            $query = "SELECT * FROM kgb_cibles WHERE id = :cible_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':cible_id', $cibleId);
            $stmt->execute();

            $cible = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cible) {
                $_SESSION['error_message'] = "La cible demandée n'existe pas.";
                header("Location: cibles.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Une erreur s'est produite lors de la récupération des informations de la cible : " . $e->getMessage();
            header("Location: cibles.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Aucun ID de cible n'a été spécifié.";
        header("Location: cibles.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style3.css" rel="stylesheet">
    <style>
        /* Votre code CSS personnalisé ici */
    </style>
    <title>Modifier une cible</title>
</head>

<body>
    <div class="container">
        <div id="cible-container">
            <div id="cible-info" class="cible-section">
                <h2>Modifier une cible</h2>
                <form method="POST" action="" id="cible-form">
                    <input type="hidden" name="cible_id" value="<?php echo $cible['id']; ?>">
                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" id="nom" value="<?php echo $cible['nom']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $cible['prenom']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" name="date_naissance" id="date_naissance"
                            value="<?php echo $cible['date_naissance']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pays">Pays :</label>
                        <select name="pays[]" id="pays" multiple required>
                            <!-- Les options seront générées dynamiquement par le script -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
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