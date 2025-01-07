<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="rechercher.css">
  <title>Rechercher une recette</title>
</head>

<body>
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "KOO2FOURCHETTE";

  // Connexion à la base de données
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Récupérer la requête de recherche
  $query = isset($_GET['query']) ? $_GET['query'] : '';

  if (!empty($query)) {
    // Préparer et exécuter la requête pour chercher dans le titre ou le chapo
    $stmt = $conn->prepare("SELECT recettes.idRecette, recettes.titre, recettes.chapo, recettes.img, membres.prenom, membres.gravatar, recettes.couleur
                            FROM recettes
                            JOIN membres ON recettes.membre = membres.idMembre
                            WHERE recettes.titre LIKE ? OR recettes.chapo LIKE ?");
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo '<h3>Résultats de recherche pour "' . htmlspecialchars($query) . '"</h3>';
      echo '<div class="cards">';
      while ($row = $result->fetch_assoc()) {
        $titre = mb_convert_encoding($row["titre"], 'ISO-8859-1', 'UTF-8');
        $chapo = mb_convert_encoding($row["chapo"], 'ISO-8859-1', 'UTF-8');
        echo '
            <div class="card" id="color-' . $row['couleur'] . '">  
                <img
                  class="cover-image"
                  src="./photos/recettes/' . $row['img'] . '"
                  alt="' . $titre . '"
                />
                <div class="description">
                  <h4><a href="/recette.php?id=' . $row["idRecette"] . '">' . $titre . '</a></h4>
                  <p>' . $chapo . '</p>
                </div>
                <div class="author">
                  <img src="./photos/gravatars/' . $row['gravatar'] . '" />
                  <p>Proposée par ' . $row["prenom"] . '</p>
                </div>
            </div>';
      }
      echo '</div>';
    } else {
      echo '<p>Aucune recette trouvée pour "' . htmlspecialchars($query) . '"</p>';
    }
  } else {
    echo '<p>Veuillez entrer un terme de recherche.</p>';
  }

  $conn->close();
  ?>

</body>

</html>