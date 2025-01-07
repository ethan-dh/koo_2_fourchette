<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="recettes.css?<?= time(); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recettes</title>
</head>

<body>

  <?php

  include 'header.php';

  ?>


  <div class="cards">
    <?php

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "KOO2FOURCHETTE";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Build the query with filters and sorting
    $query = "SELECT recettes.idRecette, recettes.titre, recettes.chapo, recettes.img, membres.prenom, membres.gravatar, recettes.couleur 
          FROM recettes 
          JOIN membres ON recettes.membre = membres.idMembre";


    // Execute the query
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
      $titre = mb_convert_encoding($row["titre"], 'ISO-8859-1', 'UTF-8');
      $chapo = mb_convert_encoding($row["chapo"], 'ISO-8859-1', 'UTF-8');

      echo '
    <div class="card" id="color-' . $row['couleur'] . '">  
        <img class="cover-image" src="./photos/recettes/' . $row['img'] . '" alt="' . $titre . '" />
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
    $conn->close();
    ?>
  </div>
</body>

</html>