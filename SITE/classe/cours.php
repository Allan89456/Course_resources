<?php 
    class Cours{
        private $IdCours, $Titre, $Bloc, $Section;

        public function __construct($id=null, $t=null,$b=null,$s=null){
            if($id!=NULL) $this->IdCours = $id;
            if ($t!=NULL) $this->Titre = $t;
            if ($b!=NULL) $this->Bloc = $b;
            if ($s!=NULL) $this->Section = $s;
        }

        public function getIdCours(){
            return $this->IdCours;
        }

        public function getTitre(){
            return $this->Titre;
        }

        public function getBloc(){
            return $this->Bloc;
        }

        public function getSection(){
            return $this->Section;
        }
    }

?>