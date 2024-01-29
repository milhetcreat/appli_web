<?php
/** 
* définition de la classe categorie
*/
class Categorie {
        private int $_num_cat;
        private string $_intitule;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
			if (isset($donnees['num_cat'])) { $this->_num_cat = $donnees['num_cat']; }
        }       

        // GETTERS //
		public function intitule() { return $this->_intitule;}
		public function num_cat() { return $this->_num_cat;}
		
		// SETTERS //
        public function setintitule(string $intitule) { $this->_intitule= $intitule; }
		public function setIdCat(int $numcat) { $this->_num_cat = $numcat; }

    }

?>