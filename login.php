<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>Connexion</title>
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

    $login = $_POST['login'];
    $password = sha1($_POST['password']); // Utilisation de SHA1 comme dans votre base
  
    $stmt = $conn->prepare("SELECT idMembre, prenom FROM membres WHERE login = ? AND password = ?");
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      session_start();
      $_SESSION['user_id'] = $user['idMembre'];
      $_SESSION['prenom'] = $user['prenom'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Login ou mot de passe incorrect";
    }
    $conn->close();
  }
  ?>

  <form action="login.php" method="post" class="container">
    <h1>Connexion</h1>

    <?php if (isset($error))
      echo "<p style='color: red'>$error</p>"; ?>

    <label for="login">Login :</label>
    <input type="text" name="login" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit" class="registerbtn">Se connecter</button>

    <div class="signin">
      <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
    </div>
  </form>
</body>

</html>