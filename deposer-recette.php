<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>Déposer une recette</title>
</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }
  include 'header.php';
  ?>

  <form action="deposer-recette.php" method="post" enctype="multipart/form-data" class="container">
    <h1>Déposer une recette</h1>

    <label for="titre">Titre :</label>
    <input type="text" name="titre" required>

    <label for="chapo">Description courte :</label>
    <textarea name="chapo" required></textarea>

    <label for="ingredients">Ingrédients :</label>
    <textarea name="ingredients" required></textarea>

    <label for="preparation">Préparation :</label>
    <textarea name="preparation" required></textarea>

    <label for="image">Image :</label>
    <input type="file" name="image" accept="image/*" required>

    <label for="couleur">Couleur :</label>
    <select name="couleur" required>
      <option value="vertClair">Vert clair</option>
      <option value="bleuClair">Bleu clair</option>
      <option value="fushia">Fushia</option>
    </select>

    <button type="submit" class="registerbtn">Publier la recette</button>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "KOO2FOURCHETTE";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $titre = $_POST['titre'];
    $chapo = $_POST['chapo'];
    $ingredients = $_POST['ingredients'];
    $preparation = $_POST['preparation'];
    $couleur = $_POST['couleur'];
    $membre = $_SESSION['user_id'];

    // Gestion de l'upload d'image
    $target_dir = "photos/recettes/";
    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $image_name = uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $stmt = $conn->prepare("INSERT INTO recettes (titre, chapo, preparation, ingredient, img, membre, couleur) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $titre, $chapo, $preparation, $ingredients, $image_name, $membre, $couleur);

      if ($stmt->execute()) {
        echo "<p style='color: green'>Recette publiée avec succès !</p>";
        header("Location: recettes.php");
      } else {
        echo "<p style='color: red'>Erreur lors de la publication de la recette.</p>";
      }
      $stmt->close();
    } else {
      echo "<p style='color: red'>Erreur lors de l'upload de l'image.</p>";
    }

    $conn->close();
  }
  ?>
</body>

</html>