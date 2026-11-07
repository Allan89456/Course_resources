<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ressources en attente d'évaluation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-success bg-opacity-10 p-2 pt-0 text-center mt-3 fs-3">
    <div class="container py-4 px-3">
    <?php
        session_start();
        session_write_close();

        require_once 'classe/requete.php';
        $req = new Requetes();

        if (!$req->isAdmin()) {
            header('Location: login.php');
            exit;
        }

        ?>

            <nav class="d-flex flex-column flex-lg-row justify-content-lg-between mb-4">
                <a href="index.php" class="btn btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">← Accueil</a>
                <hr>
                <?php $req->afficherHeaderUtilisateur(); ?>
            </nav>
            <hr>

        <?php

        $ressources = $req->getRessourcesEnAttente();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idRessource'], $_POST['nouvelEtat'])) {
            $idRessource = (int) $_POST['idRessource'];
            $nouvelEtat  = (int) $_POST['nouvelEtat'];

            $req->modifierEtatRessources($idRessource, $nouvelEtat);

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        if(empty($ressources)){
            echo "Aucune ressource en attente de validation.";
        }else{
            ?>

                <div class="bg-light rounded-4 p-2 container-lg p-">
                    
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

                <?php
                if(isset($_SESSION['user'])){
                    ?>

                        <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center text-lg-start">
                            <a target="_blank" href="<?php echo $ressource->getType(); ?>" class="text-decoration-none">
                                <div>

                    <?php
                }
                ?>

                    <h1 class="m-0 text-success"><?php echo $ressource->getTitre(); ?></h1>
                    <?php $req->getNomPrenomByID($ressource->getUserSource()); ?>
                    <p class="m-0 text-dark"><?php echo $ressource->getDateAjout(); ?></p>

                <?php
                    
                if (isset($_SESSION['user'])) {
                    ?>

                        </div>
                        </a>
                        <br>
                        <div class="d-flex justify-content-center flex-lg-column">
                            <form method="POST" class="p-1">
                                <input type="hidden" name="idRessource" value="<?php echo $ressource->getIdRessource(); ?>">
                                <input type="hidden" name="nouvelEtat" value="1">
                                <button type="submit" class="w-100 btn btn-success fs-3"> Accepter </button>
                            </form>

                            <form method="POST" class="p-1">
                                <input type="hidden" name="idRessource" value="<?php echo $ressource->getIdRessource(); ?>">
                                <input type="hidden" name="nouvelEtat" value="2">
                                <button type="submit" class="w-100 btn btn-outline-danger fs-3"> Refuser </button>
                            </form>
                        </div>
                        
                    <?php

                    echo '</form></div>';
                }
                echo '<hr>';
            }
            echo '</div>';
        }
    ?>
    </div>
</body>
</html>