<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page des commentaires</title>
</head>
<body>
    <h1>Mon super blog</h1>
    <a href="index.php">Retour à la liste des billets</a>

    <?php
        // On doit toujours faire la connexion avec la base de données
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=tpBlog;charset=utf8', 'tpBlog', 'tpBlog');
        }
        catch (Exception $error)
        {
            die('Erreur : ' .$error->getMessage());
        }

        // On doit d'abord récupérer le billet concerné par le commentaire, comme pour cette requête on dépend d'un paramètre envoyé en méthode GET, on utilise la méthode prepare au lieu de query (plus sécurisée)
        $query = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id = ?';
        $req = $bdd->prepare($query);
        // Une fois la requête préparé on l'exécute en appelant la variable billet passée en paramètre
        $req->execute(array($_GET['billet']));
        // Ici on a pas besoin de faire une boucle parce qu'on ne reçoit que le billet de l'id passé en paramètre
        $donnees = $req->fetch();
    ?>

    <div class="news">
        <h3>
            <?php echo htmlspecialchars($donnees['titre']); ?>
            <em>le <?php echo $donnees['date_creation_fr']; ?></em>
        </h3>

        <p>
            <?php echo nl2br(htmlspecialchars($donnees['contenu'])); ?>
        </p>
    </div>

    <h2>Commentaires</h2>
    <!-- On passe à la requête pour les commentaires -->

    <?php
        // On commence par liberer le curseur pour la prochaine requête
        $req->closeCursor();

        // On fait la requête pour récupérer les commenatires liés à ce billet
        $query = 'SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire';
        $req = $bdd->prepare($query);
        $req->execute(array($_GET['billet']));

        while ($donnees = $req->fetch())
        {
        // Ici on ferme la balise php parce qu'on va écrire du html
        ?>
        <p><strong><?php echo htmlspecialchars($donnees['auteur']); ?></strong> le <?php echo $donnees['date_commentaire_fr']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($donnees['commentaire'])); ?></p>
        <?php
        } // Fin de la boucle des commentaires
        $req->closeCursor();
        ?>    
</body>
</html>