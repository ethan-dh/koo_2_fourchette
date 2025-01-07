<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>Déposer une recette</title>
  <style>
    textarea {
      width: 100%;
      min-height: 100px;
      margin: 5px 0 22px 0;
      padding: 15px;
    }

    select {
      width: 100%;
      padding: 15px;
      margin: 5px 0 22px 0;
      display: inline-block;
      border: none;
      background: #f1f1f1;
    }
  </style>
</head>

<body>
  <?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }
  include 'header.php';

  $servername = "localhost";
  $username = "root";
  $password = "root";
  $dbname = "KOO2FOURCHETTE";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "photos/recettes/";
    $img = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
      $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
      $img = uniqid() . "." . $imageFileType;
      move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $img);
    }

    // Couleurs disponibles
    $couleurs = ['fushia', 'bleuClair', 'vertClair'];
    $couleur = $couleurs[array_rand($couleurs)];

    $stmt = $conn->prepare("INSERT INTO recettes (titre, chapo, img, preparation, ingredient, membre, couleur, categorie, tempsCuisson, tempsPreparation, difficulte, prix) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
      "sssssissssss",
      $_POST['titre'],
      $_POST['chapo'],
      $img,
      $_POST['preparation'],
      $_POST['ingredients'],
      $_SESSION['user_id'],
      $couleur,
      $_POST['categorie'],
      $_POST['tempsCuisson'],
      $_POST['tempsPreparation'],
      $_POST['difficulte'],
      $_POST['prix']
    );

    if ($stmt->execute()) {
      header("Location: recettes.php");
      exit;
    } else {
      $error = "Erreur lors de l'ajout de la recette : " . $stmt->error;
    }
  }

  $categories = $conn->query("SELECT * FROM categories");
  ?>

  <form action="deposer-recette.php" method="post" enctype="multipart/form-data" class="container">
    <h1>Déposer une recette</h1>

    <?php if (isset($error))
      echo "<p style='color: red'>$error</p>"; ?>

    <label for="titre">Titre de la recette :</label>
    <input type="text" name="titre" required>

    <label for="chapo">Description courte :</label>
    <textarea name="chapo" required></textarea>

    <label for="ingredients">Ingrédients (format liste HTML) :</label>
    <textarea name="ingredients" placeholder="<ul>
<li>ingrédient 1</li>
<li>ingrédient 2</li>
</ul>" required></textarea>

    <label for="preparation">Préparation (format liste HTML) :</label>
    <textarea name="preparation" placeholder="<ol>
<li>étape 1</li>
<li>étape 2</li>
</ol>" required></textarea>

    <label for="categorie">Catégorie :</label>
    <select name="categorie" required>
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['idCategorie'] ?>"><?= $cat['nom'] ?></option>
      <?php endwhile; ?>
    </select>

    <label for="tempsCuisson">Temps de cuisson :</label>
    <input type="text" name="tempsCuisson" placeholder="30 min" required>

    <label for="tempsPreparation">Temps de préparation :</label>
    <input type="text" name="tempsPreparation" placeholder="15 min" required>

    <label for="difficulte">Difficulté :</label>
    <select name="difficulte" required>
      <option value="Facile">Facile</option>
      <option value="Moyen">Moyen</option>
      <option value="Difficile">Difficile</option>
    </select>

    <label for="prix">Prix :</label>
    <select name="prix" required>
      <option value="Pas cher">Pas cher</option>
      <option value="Abordable">Abordable</option>
      <option value="Cher">Cher</option>
    </select>

    <label for="image">Photo de la recette :</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit" class="registerbtn">Publier la recette</button>
  </form>
</body>

</html>