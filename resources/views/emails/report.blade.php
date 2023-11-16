<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2 >Un contenu a été signalé par un utilisateur</h2>
    <p>Réception d'un signalement avec les éléments suivants :</p>
    <ul>
      <li><strong>ID du post</strong> : {{ $report['ID'] }}</li>
      <li><strong>Titre du post</strong> : {{ $report['title'] }}</li>
      <li><strong>ID de l'auteur du post</strong> : {{ $report['authorID'] }}</li>
      <li><strong>Raison du signalement</strong> : {{ $report['result'] }}</li>
      <li><strong>Contenu du post</strong> : {{ $report['content'] }}</li>
    </ul>
  </body>
</html>
