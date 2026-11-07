<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proposer une ressource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-success bg-opacity-10 p-2 text-center mt-3 fs-3">
    <div class="container py-4 px-3">
    <?php
        require_once 'classe/requete.php';
        $req = new Requetes();
        session_start();
        session_write_close();

        if (!isset($_SESSION['user'])) {
            header('Location: login.php');
            exit;
        }

        if (!isset($_GET['id'])) {
            echo "Aucun cours sélectionné.";
            exit;
        }
        
        $idCours  = (int) $_GET['id'];
        ?>

            <nav class="d-flex flex-column flex-lg-row justify-content-lg-between mb-4">
                <a href="ressources.php?id=<?php echo $idCours; ?>" class="btn btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">← Cours</a>
                <hr>
                <?php $req->afficherHeaderUtilisateur(); ?>
            </nav>
            <hr>

        <?php
        
        $nomCours = $req->avoirNomCoursByID($idCours);
        $message = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['titre']) && !empty($_POST['titre'])){
                $titre = $_POST['titre'];
                $idUserSource =(int) $_SESSION['user']['id']; 
                if(isset($_FILES['typeFichier']) && !empty($_FILES['typeFichier']) && !empty($_FILES['typeFichier']['name'])){
                    $fichier = $_FILES['typeFichier'];
                    $type = $fichier['type'];
                    if(strstr($type, 'jpeg') || strstr($type, 'png') || strstr($type, 'pdf')){
                        if($fichier['size'] <= 500000){
                            $tempName = $fichier['tmp_name'];
                            $dir = 'type_ressource/';
                            if(file_exists($dir.$fichier['name'])){
                                $i = 1;
                                while (file_exists($dir.$i.'-'.$fichier['name'])){
                                    $i++;
                                }
                                $dir = $dir.$i.'-';
                            }
                            if(move_uploaded_file($tempName, $dir.$fichier['name'])){
                                if($req->ajouterRessourceCours($idCours, $idUserSource, $titre, $dir.$fichier['name'])) {
                                    $message = '<div style="color:green;">Ressource proposée !</div>';
                                }else{
                                    $message = '<div style="color:red;">Erreur lors de l\'ajout</div>';
                                }
                            }else{
                                $message = '<div style="color:red;">Erreur lors de l\'ajout</div>';
                            }
                        }else{
                            $message = '<div style="color:red;">Taille du fichier trop importante (max 500KB)</div>';
                        }
                    }else{
                        $message = '<div style="color:red;">Format du fichier invalide</div>';
                    }
                }else if(isset($_POST['typeUrl']) && !empty($_POST['typeUrl'])){
                    $typeUrl  = $_POST['typeUrl'];
                    
                    if($req->ajouterRessourceCours($idCours, $idUserSource, $titre, $typeUrl)) {
                        $message = '<div style="color:green;">Ressource proposée !</div>';
                    }else{
                        $message = '<div style="color:red;">Erreur lors de l\'ajout</div>';
                    }
                }else{
                    $message = '<div style="color:red;">Veuillez entrer un fichier ou un url</div>';
                }
            }
        }
        echo $message;
    ?>

    <div class="card border-success shadow-sm mt-3 container-lg p-0">
        <div class="card-header bg-success text-white text-center">
            <h2 class="bg-success text-light m-0 p-2">Proposer une ressource pour<br> «<strong> <?php echo htmlspecialchars($nomCours); ?> </strong>»</h2>
        </div>

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" id="titre" name="titre" required maxlength="255" class="form-control form-control-lg">
                </div>

                <div class="mb-3">
                    <label for="typeFichier" class="form-label">Fichier (jpg, png ou pdf) ...</label>
                    <input type="file" id="typeFichier" name="typeFichier" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="typeUrl" class="form-label">... ou lien URL</label>
                    <input type="url" id="typeUrl" name="typeUrl" class="form-control form-control-lg">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg"> Soumettre </button>
                </div>

            </form>
        </div>
    </div>
    </div>
</body>
</html>
