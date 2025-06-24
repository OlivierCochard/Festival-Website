<!doctype html>
<html lang="fr">
<head>
  <title>Agenda</title>
  <link rel="stylesheet" href="formulaire.css"  type="text/css" >
</head>
<body>
  <h1>Agenda</h1>
  <hr>
  <table class="tabM">
  <tr>
    <td class="tdM"><?php  echo $zonePrincipale; ?>  </td>
    <td style="background-color:dimgray;">
      <p>
        <a href="index.php?action=insert">Ajouter un festival</a><br>
        <a href="index.php?action=liste&filtre=id">Liste des festivals</a><br>
        <a href="index.php?action=liste&filtre=nom">Trier par Nom</a><br>
        <a href="index.php?action=liste&filtre=style">Trier par Style</a><br>
        <a href="index.php?action=liste&filtre=capacite">Trier par capacité</a><br>
      </p>
    </td>
  </tr>
  </table>
  <hr>
</body>
</html>
