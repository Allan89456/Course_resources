<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-success bg-opacity-10">

<div class="container py-4 px-3">

<?php
    require_once 'classe/requete.php';
    $req = new Requetes();

    session_start();
    session_write_close();

    if (!$req->isAdmin()) {
        header('Location: login.php');
        exit;
    }

    ?>

        <nav class="d-flex flex-column flex-lg-row justify-content-lg-between mb-4">
            <a href="index.php" class="btn btn-sm btn-outline-success border border-1 rounded border-success link-underline link-underline-opacity-0 p-1 fs-3">← Accueil</a>
            <hr>
            <?php $req->afficherHeaderUtilisateur(); ?>
        </nav>
        <hr>

    <?php

    if(isset($_POST['nom'], $_POST['prenom'], $_POST['password1'], $_POST['password2'], $_FILES['avatar'], $_POST['matricule'])
        && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['password1']) && !empty($_POST['password2'])) {
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        if($password1 === $password2) {
            $nom       = $_POST['nom'];
            $prenom    = $_POST['prenom'];
            $avatar    = $_FILES['avatar'];
            $matricule = $_POST['matricule'];
            $hashPwd = password_hash($password1, PASSWORD_DEFAULT);

            if(!$req->isMatriculeExist($matricule)){
                if(empty($avatar) || empty($avatar['name'])){
                    if($req->addUser($nom, $prenom, $hashPwd, NULL, $matricule)){
                        echo 'L\'utilisateur a bien été ajouté';
                    } else {
                        echo 'Un problème est survenu';
                    }
                }else{
                    $type = $avatar['type'];
                    if(strstr($type, 'jpeg') || strstr($type, 'png')){
                        if($avatar['size'] <= 500000){
                            $tempName = $avatar['tmp_name'];
                            $dir = 'avatar/';
                            if(file_exists($dir.$avatar['name'])){
                                $i = 1;
                                while (file_exists($dir.$i.'-'.$avatar['name'])){
                                    $i++;
                                }
                                $dir = $dir.$i.'-';
                            }
                            if(move_uploaded_file($tempName, $dir.$avatar['name'])){
                                if($req->addUser($nom, $prenom, $hashPwd, $dir.$avatar['name'], $matricule)){
                                    ?> <p class="text-success">L'utilisateur a bien été ajouté</p><?php

                                }else{
                                    echo 'Un problème est survenu';
                                }
                            }else{
                                echo 'Erreur lors de l\'ajout de l\'avatar';
                            }
                        }else{
                            echo 'Taille du fichier trop importante (max 500KB)';
                        }
                    }else{
                        echo 'Format du fichier invalide';
                    }
                }
            }else{
                echo 'Ce matricule est déjà utilisé';
            }
                
        } else {
            echo 'Les mots de passe ne sont pas identiques';
        }
    }
?>

    <div class="card border-success shadow-sm">
        <div class="card-header bg-success text-white text-center">
            <strong>Nouvel utilisateur</strong>
        </div>

        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" required class="form-control form-control-lg">
                </div>

                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" required class="form-control form-control-lg">
                </div>

                <div class="mb-3">
                    <label class="form-label">Avatar (optionnel)</label>
                    <input type="file" name="avatar" class="form-control">
                   
                </div>

                <div class="mb-3">
                    <label class="form-label">Matricule</label>
                    <input type="text" name="matricule" required class="form-control form-control-lg">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password1" required class="form-control form-control-lg">
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password2" required class="form-control form-control-lg">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg"> Ajouter </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
