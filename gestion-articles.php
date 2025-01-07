<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php

  $prixUnitaire = rand(1, 150);
  $quantite = rand(1, 5);
  $tauxTVA = 20;
  $poidsTotal = rand(1, 20);

  $totalHT = $prixUnitaire * $quantite;

  $remise = 0;
  if ($totalHT >= 1000) {
    $remise = 0.15;
  } elseif ($totalHT >= 500) {
    $remise = 0.10;
  } elseif ($totalHT >= 100) {
    $remise = 0.05;
  } else {
    $remise = 0;
  }

  $montantRemise = $totalHT * $remise;
  $totalHTApresRemise = $totalHT - $montantRemise;


  $fraisPort = 0;
  if ($totalHT > 500) {
    $fraisPort = 0;
  } elseif ($poidsTotal < 5) {
    $fraisPort = 10;
  } elseif ($poidsTotal <= 20) {
    $fraisPort = 25;
  } else {
    $fraisPort = 50;
  }

  $montantTVA = $tauxTVA / 100 * ($totalHTApresRemise + $fraisPort);
  $totalTTC = $totalHTApresRemise + $fraisPort + $montantTVA;

  echo "<p>Prix unitaire : $prixUnitaire €</p>";
  echo "<p>Quantité : $quantite</p>";
  echo "<p>Total HT avant remise : $totalHT €</p>";
  echo "<hr />";
  echo "<p>Remise appliquée : " . ($remise * 100) . "%</p>";
  echo "<p>Montant de la remise : $montantRemise €</p>";
  echo "<p>Total HT après remise : $totalHTApresRemise €</p>";
  echo "<p>Frais de port : $fraisPort €</p>";
  echo "<p>Montant de la TVA : $montantTVA €</p>";
  echo "<p>Total TTC : $totalTTC €</p>";

  ?>

</body>

</html>