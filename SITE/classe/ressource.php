<?php
    class Ressource {
        private $IdRessource, $IdCours, $Titre, $DateAjout,$Type, $UserSource, $Etat;

        public function __construct($idR=null,$id=null, $t=null,$da=null,$ty=null,$us=null,$et=null){
            if($idR!=NULL) $this->IdRessource = $idR;
            if($id!=NULL) $this->IdCours = $id;
            if ($t!=NULL) $this->Titre = $t;
            if ($da!=NULL) $this->DateAjout = $da;
            if ($ty!=NULL) $this->Type = $ty;
            if ($us!=NULL) $this->UserSource = $us;
            if ($et!=NULL) $this->Etat = $et;
        }

        public function getIdRessource(){
            return $this->IdRessource;
        }
        
        public function getIdCours(){
            return $this->IdCours;
        }

        public function getTitre(){
            return $this->Titre;
        }

        public function getDateAjout(){
            return $this->DateAjout;
        }

        public function getType(){
            return $this->Type;
        }

        public function getUserSource(){
            return $this->UserSource;
        }

        public function getEtat(){
            return $this->Etat;
        }
    }
?>