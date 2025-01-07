<?php
session_start();
?>

<link rel="stylesheet" href="header.css" />
<div class="resaux">
  <a href=""><img src="./images/facebook.png" alt="" /></a>
  <a href=""><img src="./images/twitter.png" alt="" /></a>
  <a href=""><img src="./images/google.png" alt="" /></a>
  <a href=""><img src="./images/youtube.png" alt="" /></a>
</div>

<div class="top-bar">
  <div class="top-bar-left-part">
    <div class="logo_recherche">
      <a href="/index.php">
        <img src="./images/koo_2_fourchette.png" alt="" />
      </a>
      <div class="recherche">
        <input type="search" name="recherche" placeholder="Rechercher une recette" id="search-recette" />
        <a href="rechercher.php" id="search-button">OK</a>
      </div>
    </div>
    <div class="slogan">miam miam, gloup gloup, laps laps</div>
  </div>

  <div class="top-buttons">
    <?php if (isset($_SESSION['user_id'])): ?>
      <div id="login-buttons">
        <span style="color: #333; margin-right: 10px;">Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></span>
        <a href="logout.php" id="login-button" class="button">Se déconnecter</a>
      </div>
      <a href="deposer-recette.php" id="deposer-recette-button" class="button">
        Déposer une recette
      </a>
    <?php else: ?>
      <div id="login-buttons">
        <a href="login.php" id="login-button" class="button">Se connecter</a>
        <a href="register.php" id="register-button" class="button">Créer un compte</a>
      </div>
      <a href="login.php" id="deposer-recette-button" class="button">
        Déposer une recette
      </a>
    <?php endif; ?>
  </div>
</div>

<nav>
  <ul class="navbar">
    <li class="navlink"><a href="recettes.php">recettes</a></li>
    <li class="navlink"><a href="#">menus</a></li>
    <li class="navlink"><a href="#">desserts</a></li>
    <li class="navlink"><a href="#">minceur</a></li>
    <li class="navlink"><a href="#">atelier</a></li>
    <li class="navlink"><a href="#">contact</a></li>
  </ul>
</nav>