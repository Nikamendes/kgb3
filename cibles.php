<?php
// On appelle le fichier qui contient la configuration de la base de données
require_once 'back/config.php';

require_once './back/gestion.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM kgb_cibles"; // Requête pour récupérer toutes les cibles
    $stmt = $conn->prepare($query);
    $stmt->execute();

    ?>
    <!doctype html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="style1.css" rel="stylesheet">
        
        <title>Cibles</title>
    </head>
    <body>
        <h1>Liste des Cibles</h1>
        <div class="message-db">
            <div class="message-db-text">
                <?php
                if (isset($_SESSION['success_message'])) {
                    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
                    unset($_SESSION['success_message']);
                }

                if (isset($_SESSION['error_message'])) {
                    echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
                    unset($_SESSION['error_message']);
                }
                ?>
            </div>
        </div>

        <div class="container">    
            <div>
                <a href="ajouter_cibles.php" class="button">
                    <i class="fas fa-plus-circle"></i> Ajouter une cible
                </a>
            </div>

            <table id="cibles-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date de naissance</th>
                        <th>Pays</th>
                        <th>Nom de code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_naissance']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['pays']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['nom_de_code']) . '</td>';
                    echo '<td>';
                    echo '<a href="modifier_cibles.php?id=' . htmlspecialchars($row['id']) . '">Modifier</a> | ';
                    echo '<a href="supprimer_cible.php?id=' . htmlspecialchars($row['id']) . '">Supprimer</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>
    </html>

    <?php
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>