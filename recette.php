<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="recette.css?<?= time(); ?>">
  <title>Recette</title>
</head>

<body>
  <?php include 'header.php'; ?>


  <div class="container">
    <?php


    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "KOO2FOURCHETTE";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Récupérer l'ID de la recette depuis l'URL
    $idRecette = $_GET['id'];

    if ($idRecette > 0) {
      // Requête pour récupérer les détails de la recette
      $sql = "SELECT recettes.titre, recettes.chapo, recettes.preparation, recettes.ingredient, recettes.img, membres.prenom, membres.gravatar
          FROM recettes
          JOIN membres ON recettes.membre = membres.idMembre
          WHERE recettes.idRecette = ?";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $idRecette);
      $stmt->execute();
      $result = $stmt->get_result();


      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titre = mb_convert_encoding($row["titre"], 'ISO-8859-1', 'UTF-8');
        $chapo = mb_convert_encoding($row["chapo"], 'ISO-8859-1', 'UTF-8');
        $preparation = mb_convert_encoding($row["preparation"], 'ISO-8859-1', 'UTF-8');
        $ingredients = mb_convert_encoding($row["ingredient"], 'ISO-8859-1', 'UTF-8');


        echo "<title>$titre</title>";

        echo "<div class='titre-container'><h1>$titre</h1><div class='auteur-container'><p>Proposée par " . $row['prenom'] . "</p><img src='./photos/gravatars/" . $row['gravatar'] . "' alt='Photo de " . $row['prenom'] . "' /></div></div>";
        echo "<p>$chapo</p>";
        echo "<img class='image' src='./photos/recettes/" . $row['img'] . "' alt='$titre' />";
        echo "<div class='icone-titre'><img class='image' src='./images/fourchette.png' /><h2><strong>Ingrédients:</strong></h2></div> $ingredients";
        echo "<div class='icone-titre'><img class='image' src='./images/cuisson.png' /><h2><strong>preparation:</strong></h2></div> $preparation";
      } else {
        echo "<script>
    window.location.replace('/index.php');
  </script>";
      }

      $stmt->close();
    } else {
      echo "<script>
    window.location.replace('/index.php');
  </script>";
    }

    $conn->close();
    ?>

  </div>

</body>

</html>