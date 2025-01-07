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

  <form action="login.php" method="post" class="container">
    <h1>Se connecter</h1>

    <label for="email">Email :</label>
    <input type="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>

    <button type="submit" class="registerbtn">Se connecter</button>

    <div class="signin">
      <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
  </form>

  <?php
  session_start();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "KOO2FOURCHETTE";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT idMembre, prenom, password FROM membres WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['idMembre'];
        $_SESSION['prenom'] = $user['prenom'];
        header("Location: index.php");
      } else {
        echo "<p style='color: red'>Mot de passe incorrect.</p>";
      }
    } else {
      echo "<p style='color: red'>Email non trouvé.</p>";
    }

    $stmt->close();
    $conn->close();
  }
  ?>
</body>

</html>