<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ressources</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-success bg-opacity-10 p-2 pt-0 text-center mt-3 fs-3">
    <div class="container py-4 px-3">
    <?php
        session_start();
        session_write_close();

        require_once 'classe/requete.php';   
        $req = new Requetes();
    ?>

    <nav class="d-flex flex-column flex-lg-row justify-content-lg-between">
        <a href="index.php" class="btn btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">← Accueil</a>
        <hr>
        <?php $req->afficherHeaderUtilisateur(); ?>
    </nav>
    <hr>


    <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idRessource'], $_POST['nouvelEtat'])){
            $idRessource = (int) $_POST['idRessource'];
            $nouvelEtat  = (int) $_POST['nouvelEtat'];

            $req->lu($idRessource, $nouvelEtat);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            $ressources = $req->getRessourcesByCoursId($id);

            if(empty($ressources)){
                echo "Aucune ressource pour ce cours.";
            }else{
                ?>

                    <div class="bg-light rounded-4 p-2 container-lg p-">
                        
                <?php
                foreach($ressources as $ressource){
                    ?>

                    <?php
                    if(isset($_SESSION['user'])){
                        ?>

                            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center text-lg-start">
                                <a target="_blank" href="<?php echo $ressource->getType(); ?>" class="text-decoration-none w-100">
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

                            </div></a><br>
                            <form method="POST">
                            <input type="hidden" name="idRessource" value="<?php echo $ressource->getIdRessource(); ?>">
                        
                        <?php
                        $lu = $req->aEteLu($ressource->getIdRessource());
                        if($lu){
                            ?>
                                
                                <input type="hidden" name="nouvelEtat" value="0">
                                <button type="submit" class="w-100 btn btn-success fs-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="35">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    Lu
                                </button>
                            
                            <?php
                        }else{
                            ?>

                            <input type="hidden" name="nouvelEtat" value="1">
                            <button type="submit" class="w-100 btn btn-outline-dark fs-3"> Marquer comme lu </button>
                            
                            <?php
                        }
                        echo '</form></div>';
                    }
                    echo '<hr>';
                }
                echo '</div>';
            }
            if(isset($_SESSION['user'])){
                ?>
                    <br>
                    <a href="soumettreRessource.php?id=<?php echo $id; ?>" class="btn btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">Soumettre une ressource </a>

                <?php
            }
        }
    ?>
    </div>
</body>
</html>