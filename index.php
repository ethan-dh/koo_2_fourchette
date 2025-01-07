<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css?<?= time() ?>" />
  <link rel="icon" href="./images/favicon.ico" />
  <title>Koo2Fourchette</title>
</head>

<body>
  <?php include 'header.php'; ?>
  <div class="header-images">
    <img src="./photos/slides/creme-petits-poids.jpg" alt="" />
    <div class="block" id="block1">
      <p>block1 140/300</p>
    </div>
    <div class="block" id="block2">
      <p>block1 140/300</p>
    </div>
  </div>

  <div class="recettes-section">
    <div class="recettes-title">
      <h3>Recettes du jour</h3>
    </div>

    <div class="cards">
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "root";
      $dbname = "KOO2FOURCHETTE";

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
        die("Erreur de connexion: " . $conn->connect_error);
      }

      $result = $conn->query("SELECT recettes.idRecette, recettes.titre, recettes.chapo, recettes.img, membres.prenom, membres.gravatar, recettes.couleur
FROM recettes
JOIN membres ON recettes.membre = membres.idMembre
LIMIT 3");

      $i = 0;

      while ($row = $result->fetch_assoc()) {
        $titre = mb_convert_encoding($row["titre"], "ISO-8859-1", "UTF-8");
        $chapo = mb_convert_encoding($row["chapo"], "ISO-8859-1", "UTF-8");

        echo '
    <div class="card" id="color-' .
          $row["couleur"] .
          '">
        <img
          class="cover-image"
          src="./photos/recettes/' .
          $row["img"] .
          '"
          alt="' .
          $titre .
          '"
        />
        <div class="description">
          <h4><a href="/recette.php?id=' .
          $row["idRecette"] .
          '">' .
          $titre .
          '</a></h4>
          <p>' .
          $chapo .
          '</p>
        </div>
        <div class="author">
          <img src="./photos/gravatars/' .
          $row["gravatar"] .
          '" />
          <p>Proposée par ' .
          $row["prenom"] .
          '</p>
        </div>
    </div>';
      }

      $conn->close();
      ?>

    </div>
    <div class="barre-noire">

    </div>
  </div>

</body>

</html>