<?php
require_once 'cours.php';
require_once 'ressource.php';

class Requetes {
    private $connexion;

    public function __construct() {
        try {
            $this->connexion = new PDO(
                'mysql:host=localhost;dbname=roottest',
                'rootTest',
                'root123'
            );
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        } catch(PDOException $e) {
            echo 'Erreur connexion : '.$e->getMessage();
        }
    }

    public function getAllCours(): array {
        $sql = "SELECT * FROM Cours c WHERE EXISTS (SELECT 1 FROM Ressource r WHERE r.IdCours = c.IdCours and Etat = 1) ORDER BY (SELECT MAX(r2.DateAjout) FROM Ressource r2 WHERE r2.IdCours = c.IdCours AND r2.Etat = 1) DESC";
        $stmt = $this->connexion->query($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cours');
        return $stmt->fetchAll();
    }

    public function getOneCours($IdCours): Cours {
        $sql = "SELECT * FROM Cours WHERE IdCours = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id', $IdCours, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Cours');
        return $stmt->fetch();
    }

    public function getRessourcesByUserId(int $idUser): array {
        $sql = "SELECT * FROM Ressource WHERE UserSource = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id', $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Ressource');
        return $stmt->fetchAll();
    }

    public function getRessourcesByCoursId(int $idCours): array {
        $sql = "SELECT * FROM Ressource WHERE IdCours = :idCours and Etat = 1";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':idCours', $idCours, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Ressource');
        return $stmt->fetchAll();
    }

    public function getNomPrenomByID(int $idUser): void {
        $sql  = "SELECT Nom, Prenom FROM utilisateur WHERE IdUser = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id', $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo '<p class="m-0 text-dark">'.htmlspecialchars($user['Nom']) . ' ' .htmlspecialchars($user['Prenom']) . '</p>' ;
        } else {
            echo 'Utilisateur inconnu<br>';
        }
    }

    public function changePassword(string $matricule, string $newPassword): void {
        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);
        $prepQuery = $this->connexion->prepare('update utilisateur set mdp = :pass where matricule = :mat ;');
        $prepQuery->bindValue(':pass', $hashedPass, PDO::PARAM_STR);
        $prepQuery->bindValue(':mat', $matricule, PDO::PARAM_STR);
        $prepQuery->execute();
    }

    public function loginUser(string $matricule, string $mdpSaisi): ?array {
        $sql  = "SELECT * FROM utilisateur WHERE Matricule = :matricule";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':matricule', $matricule, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        if($mdpSaisi == $user['Mdp']){
            $this->changePassword($matricule, $mdpSaisi);
        }else if(!password_verify($mdpSaisi,$user['Mdp'])) {
            return null;
        }

        return [
            'id'        => $user['IdUser'],
            'nom'       => $user['Nom'],
            'prenom'    => $user['Prenom'],
            'avatar'    => $user['Avatar'],
            'matricule' => $user['Matricule'],
            'isAdmin'   => $user['isAdmin']
        ];
    }

    public function avoirNomCoursByID(int $id): string{
        $sql = "SELECT Titre from cours where IdCours = :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['Titre'] : '';
    }

    public function ajouterRessourceCours(int $idCours, int $idUserSource, string $titre, string $lien): bool {
        $sql = "INSERT INTO ressource (IdCours, Titre, DateAjout, Type, UserSource, etat)
                VALUES (:cours, :titre, CURDATE(), :type, :userSource, 0)";
        
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':cours', $idCours, PDO::PARAM_INT);
        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':type', $lien, PDO::PARAM_STR);
        $stmt->bindValue(':userSource', $idUserSource, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function afficherHeaderUtilisateur(): void {
        if (!isset($_SESSION['user'])) {
            ?>
                <a href="login.php" class="btn btn-success btn-sm rounded-pill shadow-sm px-3 py-2 d-flex align-items-center">Se connecter</a>
            <?php
        }else{
            if($_SESSION['user']['avatar'] == NULL){ 
                $avatarSrc = 'avatar/buste.jpg';
            }else{
                $avatarSrc = $_SESSION['user']['avatar'];
            }
            ?>
            <div class="d-none d-lg-flex justify-content-end">
                <div class="bg-success text-white rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2">
                    <img src="<?php echo $avatarSrc; ?>" alt="Avatar" width="28" height="28" class="rounded-circle border border-2 border-white">
                    <span class="small fw-semibold"><?php echo $_SESSION['user']['nom'].' '.$_SESSION['user']['prenom']; ?></span>
                    <a class="btn btn-outline-light btn-sm" href="logout.php">Déconnexion</a>
                </div>
            </div>

            <nav class="navbar bg-success navbar-dark rounded-3 px-3 py-2 d-lg-none">
                <div class="container-fluid p-0 d-flex align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?php echo $avatarSrc; ?>" alt="Avatar" width="44" height="44" class="rounded-circle border border-2 border-white">
                        <div class="lh-sm">
                            <div class="fw-semibold text-white"><?php echo $_SESSION['user']['nom']; ?></div>
                            <div class="text-white-50 small"><?php echo $_SESSION['user']['prenom']; ?></div>
                        </div>
                    </div>
                    <a class="btn btn-outline-light btn-sm ms-2 mt-2" href="logout.php">Déconnexion</a>
                </div>
            </nav>
            <?php
        }
    }

    public function isAdmin(): bool {
        return isset($_SESSION['user'])
            && isset($_SESSION['user']['isAdmin'])
            && (int)$_SESSION['user']['isAdmin'] === 1;
    }

    public function isMatriculeExist(string $matricule): bool {
        $sql = "SELECT * FROM utilisateur WHERE matricule = :mat";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':mat', $matricule, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($user['IdUser']);
    }

    public function aEteLu(int $idRessource): bool {
        $sql = "SELECT lu FROM ressource_user WHERE idRessource = :id AND idUser = :idU";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id', $idRessource, PDO::PARAM_INT);
        $stmt->bindValue(':idU', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || $result['lu'] == 0) {
            return false;
        }

        return true;
    }

    public function lu(int $idRessource, int $valeur): void {
        $sql = "INSERT INTO ressource_user (idUser, idRessource, lu) 
                VALUES (:idU, :idR, :luInsert)
                ON DUPLICATE KEY UPDATE lu = :luUpdate";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':idU', $_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->bindValue(':idR', $idRessource, PDO::PARAM_INT);
        $stmt->bindValue(':luInsert', $valeur, PDO::PARAM_INT);
        $stmt->bindValue(':luUpdate', $valeur, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function addUser($nom, $prenom, $hashPwd, $avatar, $matricule){
        $sqlInsert = "INSERT INTO utilisateur (Nom, Prenom, Mdp, Avatar, Matricule, isAdmin)
                          VALUES (:n, :p, :m, :a, :ma, 0)";
        $prepQuery = $this->connexion->prepare($sqlInsert);
        $prepQuery->bindValue('n',  $nom,       PDO::PARAM_STR);
        $prepQuery->bindValue('p',  $prenom,    PDO::PARAM_STR);
        $prepQuery->bindValue('m',  $hashPwd,   PDO::PARAM_STR);
        $prepQuery->bindValue('a',  $avatar,    PDO::PARAM_STR);
        $prepQuery->bindValue('ma', $matricule, PDO::PARAM_STR);
        return $prepQuery->execute();
    }

    public function getRessourcesEnAttente(): array{
        $sql = "SELECT * FROM Ressource WHERE Etat = 0 AND UserSource <> :id";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':id',$_SESSION['user']['id'], PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Ressource');
        return $stmt->fetchAll();
    }

    public function modifierEtatRessources(int $idRessource, int $newEtat){
        $sql = "UPDATE ressource SET Etat = :newEtat WHERE IdRessource = :idRessource";
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue(':newEtat',$newEtat, PDO::PARAM_INT);
        $stmt->bindValue(':idRessource',$idRessource, PDO::PARAM_INT);
        $stmt->execute();
    }
}

?>