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

  <form action="register.php" method="post" class="container">
    <h1>Créer un compte</h1>

    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" required>

    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <label for="password_confirm">Confirmer le mot de passe :</label>
    <input type="password" name="password_confirm" required>

    <button type="submit" class="registerbtn">S'inscrire</button>

    <div class="signin">
      <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
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

    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
      echo "<p style='color: red'>Les mots de passe ne correspondent pas.</p>";
      exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $gravatar = "default.jpg"; // Image par défaut
  
    $stmt = $conn->prepare("INSERT INTO membres (prenom, email, password, gravatar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $prenom, $email, $password_hash, $gravatar);

    if ($stmt->execute()) {
      echo "<p style='color: green'>Compte créé avec succès !</p>";
      header("Location: login.php");
    } else {
      echo "<p style='color: red'>Erreur lors de la création du compte.</p>";
    }

    $stmt->close();
    $conn->close();
  }
  ?>
</body>

</html>