<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-success bg-opacity-10">
<div class="container d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-sm w-100" style="max-width: 400px;">
        <div class="card-header bg-success text-white text-center">
            <strong>Connexion</strong>
        </div>
        <div class="card-body">
            <?php
            session_start();
            require_once 'classe/requete.php';
            $req = new Requetes();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $matricule = $_POST['matricule'];
                $mdp       = $_POST['mdp'];

                $userSession = $req->loginUser($matricule, $mdp);

                if ($userSession == null) {
                    echo '<div class="alert alert-danger text-center">Matricule ou mot de passe incorrect</div>';
                } else {
                    $_SESSION['user'] = $userSession;
                    header('Location: index.php');
                    exit;
                }
            }
            ?>

            <form method="post" action="login.php">
                <div class="mb-3">
                    <label class="form-label">Matricule :</label>
                    <input type="text" name="matricule" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe :</label>
                    <input type="password" name="mdp" required class="form-control">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Se connecter</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
