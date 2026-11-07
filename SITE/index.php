<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-success bg-opacity-10">

<?php
    session_start();
    session_write_close();
    require_once 'classe/requete.php';
    $req = new Requetes();
?>

<div class="bg-success text-white py-3">
    <div class="container-md px-3">

        <div class="d-flex flex-column gap-2">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Plateforme de ressources</h5>
                <?php $req->afficherHeaderUtilisateur(); ?>
            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <hr>

                <div class="d-flex flex-column gap-2 mt-0">

                    <a href="etatRessourcesSoumis.php"
                       class="btn btn-outline-light btn-sm w-100">
                        Ressources soumises
                    </a>

                    <?php if ($req->isAdmin()): ?>
                        <a href="voirRessourcesEnAttente.php"
                           class="btn btn-outline-warning btn-sm w-100">
                            Ressources en attente
                        </a>

                        <a href="addUser.php"
                           class="btn btn-outline-light btn-sm w-100">
                            Ajouter un utilisateur
                        </a>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>

    </div>
</div>

<div class="container-md px-3 py-4">

<?php
    $ligneSet = $req->getAllCours();
?>

<h4 class="mb-3">Liste des cours</h4>

<?php if (empty($ligneSet)): ?>
    <div class="alert alert-info">
        Aucun cours disponible pour le moment.
    </div>
<?php else: ?>
    <div class="row gy-3">
        <?php foreach ($ligneSet as $cours): ?>
            <div class="col-12">
                <a href="ressources.php?id=<?= $cours->getIdCours(); ?>" class="text-decoration-none">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-1 text-success">
                                <strong><?= htmlspecialchars($cours->getTitre()); ?></strong>
                            </h5>
                            <p class="mb-1 text-muted small">
                                Bloc : <?= $cours->getBloc(); ?> · Section : <?= $cours->getSection(); ?>
                            </p>
                            <span class="badge bg-success">
                                <?= count($req->getRessourcesByCoursId($cours->getIdCours())); ?> ressource(s)
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</div>

</body>
</html>
