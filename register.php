<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>Inscription</title>
</head>

<body>
  <?php include 'header.php'; ?>

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

    // Vérifier si le login existe déjà
    $stmt = $conn->prepare("SELECT idMembre FROM membres WHERE login = ?");
    $stmt->bind_param("s", $_POST['login']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $error = "Ce login est déjà utilisé";
    } else {
      // Traitement de l'image de profil
      $gravatar = "default.png"; // Image par défaut
      if (isset($_FILES['gravatar']) && $_FILES['gravatar']['error'] == 0) {
        $target_dir = "photos/gravatars/";
        $imageFileType = strtolower(pathinfo($_FILES["gravatar"]["name"], PATHINFO_EXTENSION));
        $gravatar = uniqid() . "." . $imageFileType;
        move_uploaded_file($_FILES["gravatar"]["tmp_name"], $target_dir . $gravatar);
      }

      $stmt = $conn->prepare("INSERT INTO membres (gravatar, login, password, statut, prenom, nom, dateCrea) VALUES (?, ?, ?, 'membre', ?, ?, NOW())");
      $hashedPassword = sha1($_POST['password']);
      $stmt->bind_param("sssss", $gravatar, $_POST['login'], $hashedPassword, $_POST['prenom'], $_POST['nom']);

      if ($stmt->execute()) {
        header("Location: login.php");
        exit;
      } else {
        $error = "Erreur lors de l'inscription";
      }
    }
    $conn->close();
  }
  ?>

  <form action="register.php" method="post" enctype="multipart/form-data" class="container">
    <h1>Inscription</h1>

    <?php if (isset($error))
      echo "<p style='color: red'>$error</p>"; ?>

    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" required>

    <label for="nom">Nom :</label>
    <input type="text" name="nom" required>

    <label for="login">Login :</label>
    <input type="text" name="login" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <label for="gravatar">Photo de profil :</label>
    <input type="file" name="gravatar" accept="image/*">

    <button type="submit" class="registerbtn">S'inscrire</button>

    <div class="signin">
      <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
  </form>
</body>

</html>