<?php
/** 
* définition de la classe cours
*/
class Cours {
        private int $_id_cours;
        private string $_intitule;
        private string $_semestre;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
			if (isset($donnees['semestre'])) { $this->_semestre = $donnees['semestre']; }
			if (isset($donnees['id_cours'])) { $this->_id_cours = $donnees['id_cours']; }
        }       

        // GETTERS //
		public function intitule() { return $this->_intitule;}
		public function semestre() { return $this->_semestre;}
		public function id_cours() { return $this->_id_cours;}
		
		// SETTERS //
        public function setintitule(string $intitule) { $this->_intitule= $intitule; }
		public function setsemestre(string $semestre) { $this->_semestre = $semestre; }	
		public function setIdCours(int $idcours) { $this->_id_cours = $idcours; }

    }

?>