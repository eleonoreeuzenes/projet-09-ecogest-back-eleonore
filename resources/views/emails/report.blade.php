<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2 >Un post a été signalé par un utilisateur</h2>
    <p>Réception d'un signalement avec les éléments suivants :</p>
    <ul>
      <li><strong>ID du post</strong> : {{ $report['postID'] }}</li>
      <li><strong>Titre du post</strong> : {{ $report['postTitle'] }}</li>
      <li><strong>ID de l'auteur du post</strong> : {{ $report['authorID'] }}</li>
      <li><strong>Nom de l'utilisateur</strong> : {{ $report['authorName'] }}</li>
      <li><strong>Raison du signalement</strong> : {{ $report['result'] }}</li>
      <li><strong>Contenu du post</strong> : {{ $report['postContent'] }}</li>
    </ul>
  </body>
</html>
