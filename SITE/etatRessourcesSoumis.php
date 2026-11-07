<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ressources soumises</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-success bg-opacity-10 p-2 pt-0 text-center mt-3 fs-3">

<div class="container py-4 px-3">
    <?php
        session_start();
        session_write_close();

        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit;
        }

        require_once 'classe/requete.php';
        $req = new Requetes();

        ?>

            <nav class="d-flex flex-column flex-lg-row justify-content-lg-between mb-4">
                <a href="index.php" class="btn btn-sm btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">← Accueil</a>
                <hr>
                <?php $req->afficherHeaderUtilisateur(); ?>
            </nav>
            <hr>

        <?php

        $ressources = $req->getRessourcesByUserId($_SESSION['user']['id']);
        if(empty($ressources)){
            echo "Aucune ressource soumise.";
        }else{
        
        ?>

            <div class="bg-light rounded-4 p-2 container-lg p-1">
                    
            <?php
            foreach($ressources as $ressource){
                $idCours = $ressource->getIdCours();
                ?>
                    <a href="ressources.php?id=<?php echo $idCours; ?>" class="text-decoration-none text-success text-opacity-75">
                <?php
                $cours = $req->getOneCours($idCours);
                echo 'Cours de '.$cours->getTitre();
                echo ' - Bloc '.$cours->getBloc();
                echo ' - Section '.$cours->getSection();
                ?>
                    </a>
                    <a target="_blank" href="<?php echo $ressource->getType(); ?>" class="text-decoration-none d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center p-3">
                        <div class="text-lg-start">
                            <h1 class="m-0 text-success"><?php echo $ressource->getTitre(); ?></h1>
                            <p class="m-0 text-dark "><?php echo $ressource->getDateAjout(); ?></p>

                <?php
                    
                if (isset($_SESSION['user'])) {
                    ?>

                        </div>  
                        <br>

                    <?php
                    $etat =  $ressource->getEtat();
                    if($etat == 0){
                        ?><p class="text-warning text-lg-end m-0">En attente de validation</p><?php
                    }else if ($etat == 1){
                        ?><p class="text-success text-lg-end m-0">Acceptée</p><?php

                    }else{
                        ?><p class="text-danger text-lg-end m-0">Refusée</p><?php
                    }
                    echo '</a>';
                }
                echo '<hr>';
            }
            echo '</div>';
        }
    ?>
</div>
</body>
</html>