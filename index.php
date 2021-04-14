<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Blog</title>
</head>
<body>
    <h1>Mon super blog</h1>
    <p>Derniers billets du blog</p>
    <?php
        // Pour se conneceter à la base de données
        try 
        {
            $bdd = new PDO('mysql:host=localhost;dbname=tpBlog;charset=utf8', 'tpBlog', 'tpBlog');
        }
        catch(Exception $error)
        {
            die('Erreur : ' .$error->getMessage());
        }

        // Si la conexion se passe bien, on continue

        // Je stocke la requête SQL dans la variable $query
        $query = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5';
        // que je passe en paramètre de la méthode query de PDO pour qu'elle aille chercher tous les billets de la table
        $request = $bdd->query($query);
        
        // fetch = va chercher
        // ça nous permet d'accèder à chaque entrée d'une table ligne à ligne
        while ($donnees = $request->fetch())
        {
        ?>
            <h2><?php echo htmlspecialchars($donnees['titre']); ?></h2>
            <p><?php echo htmlspecialchars($donnees['contenu'])?></p>

            <a href="commentaires.php?billet=<?php echo $donnees['id']; ?>">Commentaires</a>
        <?php
        }

        $request->closeCursor();

        ?>   
</body>
</html>

