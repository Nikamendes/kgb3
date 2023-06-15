<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'ID de l'agent à modifier
    $agentId = $_POST['agent_id'];

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateNaissance = $_POST['date_naissance'];
    $codeIdentification = $_POST['code_identification'];
    $nationalite = $_POST['nationalite'];
    $selectedCompetences = isset($_POST['competences']) ? $_POST['competences'] : [];

    try {
        // Mettre à jour les informations de l'agent dans la base de données
        $query = "UPDATE kgb_agents SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, code_identification = :code_identification, nationalite = :nationalite, competences = :competences WHERE id = :agent_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $dateNaissance);
        $stmt->bindParam(':code_identification', $codeIdentification);
        $stmt->bindParam(':nationalite', $nationalite);
        $nationalite = implode(',', $_POST['nationalite']);
        $stmt->bindParam(':competences', implode(',', $selectedCompetences));
        $stmt->bindParam(':agent_id', $agentId);
        $stmt->execute();

        $_SESSION['success_message'] = "L'agent a été modifié avec succès.";
        header("Location: agents.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Une erreur s'est produite lors de la modification de l'agent : " . $e->getMessage();
    }
} else {
    // Vérifier si l'ID de l'agent est passé en paramètre d'URL
    if (isset($_GET['id'])) {
        // Récupérer l'ID de l'agent
        $agentId = $_GET['id'];

        try {
            // Récupérer les informations de l'agent depuis la base de données
            $query = "SELECT * FROM kgb_agents WHERE id = :agent_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':agent_id', $agentId);
            $stmt->execute();

            $agent = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$agent) {
                $_SESSION['error_message'] = "L'agent demandé n'existe pas.";
                header("Location: agents.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Une erreur s'est produite lors de la récupération de l'agent : " . $e->getMessage();
            header("Location: agents.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "L'ID de l'agent n'a pas été spécifié.";
        header("Location: agents.php");
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
    </style>

    <title>Modifier un agent</title>
    <script>
        // Fonction pour récupérer les nationalités à partir de l'API "REST Countries"
        function fetchNationalities() {
            const apiUrl = "https://restcountries.com/v3.1/all"; // Lien de l'API

            return fetch(apiUrl) // On va passer le code en json
                .then(response => response.json())
                .then(data => {
                    const nationalities = Object.values(data).map(country => country.name.common); // On va récupérer les nationalités uniquement
                    return nationalities.sort(); // Tri des nationalités par ordre alphabétique et envoi des résultats
                });
        }

        // Fonction pour générer la liste déroulante des nationalités
        function generateNationalitiesDropdown() {
            fetchNationalities().then(nationalities => {
                const nationaliteDropdown = document.getElementById("nationalite"); // On va chercher là où on va afficher la liste déroulante
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
    <!-- On inclut le menu en appelant la fonction renderMenu qui est dans fonctions.php -->

    <div class="container">
        <div id="agent-container">
            <div id="agent-info" class="agent-section">
                <h2>Modifier un agent</h2>
                <form method="POST" action="" id="agent-form">
                    <input type="hidden" name="agent_id" value="<?php echo $agent['id']; ?>">

                    <div class="form-group">
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" id="nom" value="<?php echo $agent['nom']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo $agent['prenom']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" name="date_naissance" id="date_naissance"
                            value="<?php echo $agent['date_naissance']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="code_identification">Code d'identification :</label>
                        <input type="text" name="code_identification" id="code_identification"
                            value="<?php echo $agent['code_identification']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nationalite">Nationalité :</label>
                        <select name="nationalite[]" id="nationalite" multiple required>
                            <!-- Les options de nationalité seront générées dynamiquement ici -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="competences">Compétences :</label>
                        <select name="competences[]" id="competences" multiple required>
                            <option value="Discrétion 1"
                                <?php if (in_array('Discrétion', explode(',', $agent['competences']))) echo 'selected'; ?>>
                                1 Discrétion
                            </option>
                            <option value="Arts Martiaux 2"
                                <?php if (in_array('', explode('Arts Matiaux,', $agent['competences']))) echo 'selected'; ?>>
                                2 Arts Martiaux
                            </option>
                            <option value="Vitesse 3"
                                <?php if (in_array('Vitesse', explode(',', $agent['competences']))) echo 'selected'; ?>>
                                3 Vitesse
                            </option>
                            <option value="Déguisement 3"
                                <?php if (in_array('Déguisement', explode(',', $agent['competences']))) echo 'selected'; ?>>
                                3 Déguisement
                            </option>
                            <!-- Ajoutez d'autres options de compétences ici -->
                        </select>
                    </div>

                    <button type="submit">Mettre à jour</button>
                </form>
            </div>

            <a href="agents.php">Returne Agents</a>
        </div>
    </div>

    <!-- Ici on va inclure tous les scripts qu'on veut utiliser, comme JQuery, Bootstrap etc -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>